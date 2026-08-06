<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'], $_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$userId = $_SESSION['user_id'];

$error = '';
$message = '';
$record = null;
$customer_fullname = '';
$customer_phone = '';
$expense_date = '';
$description = '';
$amount = '';
$payment_method = '';
$expense_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $customer_fullname = trim($_POST['customer_fullname'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $expense_date = trim($_POST['expense_date'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';
    $expense_id = $_POST['expense_id'] ?? '';

    if ($action === 'save' || $action === 'update') {
        if ($customer_fullname === '' || $customer_phone === '' || $expense_date === '' || $description === '' || $amount === '' || $payment_method === '') {
            $error = 'Please fill in all required fields before saving.';
        } elseif (!preg_match('/^[0-9]{7,15}$/', $customer_phone)) {
            $error = 'Phone number must contain only digits and be 7 to 15 digits long.';
        } elseif (!is_numeric($amount) || floatval($amount) <= 0) {
            $error = 'Amount must be a number greater than zero.';
        } else {
            if ($action === 'save') {
                $stmt = $conn->prepare('INSERT INTO expenses (user_id, customer_fullname, customer_phone, expense_date, description, amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('isssdss', $userId, $customer_fullname, $customer_phone, $expense_date, $description, $amount, $payment_method);
                if ($stmt->execute()) {
                    $message = 'Expense record saved successfully.';
                    $expense_id = '';
                } else {
                    $error = 'Unable to save expense record. Please try again.';
                }
                $stmt->close();
            } else {
                if ($expense_id === '') {
                    $error = 'Please search a record first before updating.';
                } else {
                    $stmt = $conn->prepare('UPDATE expenses SET customer_fullname = ?, customer_phone = ?, expense_date = ?, description = ?, amount = ?, payment_method = ? WHERE expense_id = ? AND user_id = ?');
                    $stmt->bind_param('sssdssii', $customer_fullname, $customer_phone, $expense_date, $description, $amount, $payment_method, $expense_id, $userId);
                    if ($stmt->execute()) {
                        $message = 'Expense record updated successfully.';
                        $stmt->close();

                        $stmt = $conn->prepare('SELECT expense_id, customer_fullname, customer_phone, expense_date, description, amount, payment_method FROM expenses WHERE expense_id = ? AND user_id = ? LIMIT 1');
                        $stmt->bind_param('ii', $expense_id, $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result && $result->num_rows > 0) {
                            $record = $result->fetch_assoc();
                            $customer_fullname = $record['customer_fullname'];
                            $customer_phone = $record['customer_phone'];
                            $expense_date = $record['expense_date'];
                            $description = $record['description'];
                            $amount = $record['amount'];
                            $payment_method = $record['payment_method'];
                        }
                    } else {
                        $error = 'Unable to update expense record. Please try again.';
                        $stmt->close();
                    }
                }
            }
        }
    }

    if ($action === 'search') {
        if ($customer_phone === '') {
            $error = 'Please enter a phone number to search.';
        } elseif (!preg_match('/^[0-9]{7,15}$/', $customer_phone)) {
            $error = 'Phone number must contain only digits and be 7 to 15 digits long.';
        } else {
            $stmt = $conn->prepare('SELECT expense_id, customer_fullname, customer_phone, expense_date, description, amount, payment_method FROM expenses WHERE customer_phone = ? AND user_id = ? ORDER BY expense_date DESC LIMIT 1');
            $stmt->bind_param('si', $customer_phone, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $record = $result->fetch_assoc();
                $expense_id = $record['expense_id'];
                $customer_fullname = $record['customer_fullname'];
                $customer_phone = $record['customer_phone'];
                $expense_date = $record['expense_date'];
                $description = $record['description'];
                $amount = $record['amount'];
                $payment_method = $record['payment_method'];
                $message = 'Record found and loaded below.';
            } else {
                $error = 'No expense records found for that phone number.';
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Daily Expense</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="card">
        <h2><i class="fa-solid fa-file-invoice-dollar"></i> Customer Expense Entry</h2>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="management.php" id="expenseForm">
            <input type="hidden" name="expense_id" value="<?= htmlspecialchars($expense_id) ?>">
            <div class="form-group">
                <label for="customer_fullname">Customer Full Name</label>
                <input type="text" id="customer_fullname" name="customer_fullname" value="<?= htmlspecialchars($customer_fullname) ?>" placeholder="Enter customer full name">
            </div>
            <div class="form-group">
                <label for="customer_phone">Customer Phone Number</label>
                <input type="text" id="customer_phone" name="customer_phone" value="<?= htmlspecialchars($customer_phone) ?>" placeholder="Enter phone digits only">
            </div>
            <div class="form-group">
                <label for="expense_date">Expense Date</label>
                <input type="date" id="expense_date" name="expense_date" value="<?= htmlspecialchars($expense_date) ?>">
            </div>
            <div class="form-group">
                <label for="description">Expense Description</label>
                <textarea id="description" name="description" placeholder="Enter expense description"><?= htmlspecialchars($description) ?></textarea>
            </div>
            <div class="form-group">
                <label for="amount">Amount Spent</label>
                <input type="number" step="0.01" id="amount" name="amount" value="<?= htmlspecialchars($amount) ?>" placeholder="Enter amount spent">
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method">
                    <option value="">Select payment method</option>
                    <option value="cash" <?= $payment_method === 'cash' ? 'selected' : '' ?>>Cash</option>
                    <option value="mobile money" <?= $payment_method === 'mobile money' ? 'selected' : '' ?>>Mobile Money</option>
                    <option value="bank" <?= $payment_method === 'bank' ? 'selected' : '' ?>>Bank</option>
                </select>
            </div>
            <div class="action-row">
                <button type="submit" name="action" value="save" class="btn">Save</button>
                <button type="submit" name="action" value="search" class="btn secondary">Search</button>
                <button type="submit" name="action" value="update" class="btn secondary">Update</button>
                <button type="button" onclick="window.print();" class="btn secondary">Print</button>
                <button type="reset" class="btn secondary">Reset</button>
            </div>
        </form>

        <?php if ($record): ?>
            <div class="search-result">
                <h3>Search Result</h3>
                <p><strong>Customer:</strong> <?= htmlspecialchars($record['customer_fullname']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($record['customer_phone']) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($record['expense_date']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($record['description']) ?></p>
                <p><strong>Amount:</strong> <?= htmlspecialchars($record['amount']) ?></p>
                <p><strong>Payment:</strong> <?= htmlspecialchars($record['payment_method']) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>