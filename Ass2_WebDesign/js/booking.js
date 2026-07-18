document.addEventListener("DOMContentLoaded", function() {    
    const checkRateBtn = document.getElementById("checkRateBtn");
    const nightsInput = document.getElementById("nights");
    const resultDisplay = document.getElementById("resultDisplay");
    const linkContainer = document.getElementById("bookingLinkContainer");

    checkRateBtn.addEventListener("click", function() {
        
        const nightsValue = nightsInput.value;
        const nights = Number(nightsValue);
        
        resultDisplay.textContent = "";
        linkContainer.innerHTML = "";

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

    function displayBookingLink() {
        const proceedLink = document.createElement("a");
        proceedLink.href = "reservation.html";
        proceedLink.textContent = "Proceed with Booking";
        proceedLink.className = "proceed-btn"; 
        linkContainer.appendChild(proceedLink);
    }
});