// Wait for the DOM to fully load before running the script
document.addEventListener("DOMContentLoaded", function() {
    const contactForm = document.getElementById("contactForm");

    contactForm.addEventListener("submit", function(event) {
        // Prevent the default form submission so the page doesn't reload instantly
        event.preventDefault();

        // Clear any previous error messages before re-validating
        clearErrors();

        let isValid = true;

        // Retrieve and trim the values from the input fields
        const name = document.getElementById("contactName").value.trim();
        const email = document.getElementById("contactEmail").value.trim();
        const phone = document.getElementById("contactPhone").value.trim();
        const city = document.getElementById("contactCity").value.trim();
        const country = document.getElementById("contactCountry").value.trim();
        const ageValue = document.getElementById("contactAge").value;
        const age = parseInt(ageValue, 10);

        // Validate Full Name
        if (name === "") {
            showError("contactName", "nameError", "Full name is required.");
            isValid = false;
        }

        // Validate Email using a basic regular expression
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "") {
            showError("contactEmail", "emailError", "Email address is required.");
            isValid = false;
        } else if (!emailPattern.test(email)) {
            showError("contactEmail", "emailError", "Please enter a valid email address.");
            isValid = false;
        }

        // Validate Phone Number (ensuring it contains 10 to 15 digits)
        const phonePattern = /^[0-9]{10,15}$/;
        if (phone === "") {
            showError("contactPhone", "phoneError", "Phone number is required.");
            isValid = false;
        } else if (!phonePattern.test(phone)) {
            showError("contactPhone", "phoneError", "Please enter a valid phone number (10-15 digits).");
            isValid = false;
        }

        // Validate City
        if (city === "") {
            showError("contactCity", "cityError", "City is required.");
            isValid = false;
        }

        // Validate Country
        if (country === "") {
            showError("contactCountry", "countryError", "Country is required.");
            isValid = false;
        }

        // Validate Age (Must be above 18)
        if (ageValue === "" || isNaN(age)) {
            showError("contactAge", "ageError", "Age is required.");
            isValid = false;
        } else if (age <= 18) {
            showError("contactAge", "ageError", "You must be over 18 years old to submit this form.");
            isValid = false;
        }

        // If all fields are completed correctly, show success message
        if (isValid) {
            document.getElementById("formSuccessMessage").textContent = "Thank you! Your inquiry has been submitted successfully.";
            contactForm.reset(); // Clear the form fields after successful submission
        }
    });

    // Helper function to display error messages and apply error CSS styling
    function showError(inputId, errorSpanId, message) {
        const inputElement = document.getElementById(inputId);
        const errorSpan = document.getElementById(errorSpanId);
        
        inputElement.classList.add("input-error");
        errorSpan.textContent = message;
    }

    // Helper function to clear all error messages and remove error CSS classes
    function clearErrors() {
        const errorSpans = document.querySelectorAll(".error-message");
        const inputs = document.querySelectorAll(".input-error");
        
        // Loop through and clear text content for all error spans
        for (let i = 0; i < errorSpans.length; i++) {
            errorSpans[i].textContent = "";
        }
        
        // Loop through and remove the red border class from all inputs
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].classList.remove("input-error");
        }
        
        // Clear the general success message as well
        document.getElementById("formSuccessMessage").textContent = "";
    }
});