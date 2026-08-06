document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            if (!username || !password) {
                alert('Please enter both username and password.');
                event.preventDefault();
            }
        });
    }

    if (signupForm) {
        signupForm.addEventListener('submit', function (event) {
            const fullName = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!fullName || !email || !username || !password || !confirmPassword) {
                alert('Please fill in all signup fields.');
                event.preventDefault();
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                event.preventDefault();
            }
        });
    }

    const expenseForm = document.getElementById('expenseForm');
    if (expenseForm) {
        expenseForm.addEventListener('submit', function (event) {
            const customerFullname = document.getElementById('customer_fullname').value.trim();
            const customerPhone = document.getElementById('customer_phone').value.trim();
            const expenseDate = document.getElementById('expense_date').value;
            const description = document.getElementById('description').value.trim();
            const amount = document.getElementById('amount').value;
            const paymentMethod = document.getElementById('payment_method').value;

            if (!customerFullname || !customerPhone || !expenseDate || !description || !amount || !paymentMethod) {
                alert('Please fill in all expense fields.');
                event.preventDefault();
                return;
            }

            if (!/^[0-9]{7,15}$/.test(customerPhone)) {
                alert('Phone number must contain only digits and be 7 to 15 digits long.');
                event.preventDefault();
                return;
            }

            if (Number(amount) <= 0) {
                alert('Amount must be greater than zero.');
                event.preventDefault();
            }
        });
    }
});
