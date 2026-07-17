// Ensure the HTML document is fully loaded before running the script
document.addEventListener("DOMContentLoaded", function() {
    
    // Select the necessary HTML elements
    const checkRateBtn = document.getElementById("checkRateBtn");
    const nightsInput = document.getElementById("nights");
    const resultDisplay = document.getElementById("resultDisplay");
    const linkContainer = document.getElementById("bookingLinkContainer");

    // Add a click event listener to the "Check Rate" button
    checkRateBtn.addEventListener("click", function() {
        

        const nightsValue = nightsInput.value;
        const nights = Number(nightsValue);
        
        // Clear any previous results and the booking link
        resultDisplay.textContent = "";
        linkContainer.innerHTML = "";

        // Apply if...else if...else conditional statements as required
        if (isNaN(nights) || nights < 1) {
            resultDisplay.textContent = "Please enter a valid number of nights.";
        } else if (nights >= 1 && nights <= 2) {
            resultDisplay.textContent = "Standard rate: $120/night.";
            displayBookingLink();
        } else if (nights >= 3 && nights <= 6) {
            resultDisplay.textContent = "Weekly discount rate: $100/night.";
            displayBookingLink();
        } else if (nights > 6) {
            resultDisplay.textContent = "Extended stay rate: $80/night.";
            displayBookingLink();
        }
    });

    // Function to dynamically create and display the "Proceed with Booking" link
    function displayBookingLink() {
        const proceedLink = document.createElement("a");
        proceedLink.href = "reservation.html";
        proceedLink.textContent = "Proceed with Booking";
        proceedLink.className = "proceed-btn"; // Adding a class so we can style it like a button later
        
        // Append the new link to the container div without reloading the page
        linkContainer.appendChild(proceedLink);
    }
});