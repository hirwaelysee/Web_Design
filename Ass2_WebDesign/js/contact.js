document.addEventListener("DOMContentLoaded", function () {
    // Get the contact form from the HTML page
    const contactForm = document.getElementById("contactForm");

    // Run this code when the form is submitted
    contactForm.addEventListener("submit", function (event) {
        // Prevent the page from reloading
        event.preventDefault();

        // Read the values entered by the user and remove extra spaces
        const name = document.getElementById("contactName").value.trim();
        const email = document.getElementById("contactEmail").value.trim();
        const phone = document.getElementById("contactPhone").value.trim();
        const city = document.getElementById("contactCity").value.trim();
        const country = document.getElementById("contactCountry").value.trim();
        const ageValue = document.getElementById("contactAge").value;
        const age = parseInt(ageValue, 10);

        // Clear old error messages before checking again
        const errorSpans = document.querySelectorAll(".error-message");
        for (let i = 0; i < errorSpans.length; i++) {
            errorSpans[i].textContent = "";
        }

        // Remove the red border from all inputs before re-checking
        const inputs = document.querySelectorAll("input");
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].classList.remove("input-error");
        }

        // Clear any old success message
        document.getElementById("formSuccessMessage").textContent = "";

        // Track whether the form is valid
        let isValid = true;

        // Check the full name field
        if (name === "") {
            document.getElementById("nameError").textContent = "Full name is required.";
            document.getElementById("contactName").classList.add("input-error");
            isValid = false;
        }

        // Check the email field
        if (email === "") {
            document.getElementById("emailError").textContent = "Email address is required.";
            document.getElementById("contactEmail").classList.add("input-error");
            isValid = false;
        } else if (email.includes("@") === false || email.includes(".") === false) {
            document.getElementById("emailError").textContent = "Please enter a valid email address.";
            document.getElementById("contactEmail").classList.add("input-error");
            isValid = false;
        }

        // Check the phone number field
        if (phone === "") {
            document.getElementById("phoneError").textContent = "Phone number is required.";
            document.getElementById("contactPhone").classList.add("input-error");
            isValid = false;
        } else {
            let hasOnlyNumbers = true;

            // Make sure every character is a digit
            for (let i = 0; i < phone.length; i++) {
                const char = phone.charAt(i);
                if (char < "0" || char > "9") {
                    hasOnlyNumbers = false;
                    break;
                }
            }

            // Check that the phone number length is between 10 and 15 digits
            if (!hasOnlyNumbers || phone.length < 10 || phone.length > 15) {
                document.getElementById("phoneError").textContent = "Please enter a valid phone number (10-15 digits).";
                document.getElementById("contactPhone").classList.add("input-error");
                isValid = false;
            }
        }

        // Check the city field
        if (city === "") {
            document.getElementById("cityError").textContent = "City is required.";
            document.getElementById("contactCity").classList.add("input-error");
            isValid = false;
        }

        // Check the country field
        if (country === "") {
            document.getElementById("countryError").textContent = "Country is required.";
            document.getElementById("contactCountry").classList.add("input-error");
            isValid = false;
        }

        // Check the age field
        if (ageValue === "" || isNaN(age)) {
            document.getElementById("ageError").textContent = "Age is required.";
            document.getElementById("contactAge").classList.add("input-error");
            isValid = false;
        } else if (age <= 18) {
            document.getElementById("ageError").textContent = "You must be over 18 years old to submit this form.";
            document.getElementById("contactAge").classList.add("input-error");
            isValid = false;
        }

        // If everything is correct, show a success message and reset the form
        if (isValid) {
            document.getElementById("formSuccessMessage").textContent = "Thank you! Your inquiry has been submitted successfully.";
            contactForm.reset();
        }
    });
});