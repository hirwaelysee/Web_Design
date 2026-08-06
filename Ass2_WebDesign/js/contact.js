document.addEventListener("DOMContentLoaded", function () {
    
    const contactForm = document.getElementById("contactForm");

    contactForm.addEventListener("submit", function (event) {
        
        event.preventDefault();

       
        const name = document.getElementById("contactName").value.trim();
        const email = document.getElementById("contactEmail").value.trim();
        const phone = document.getElementById("contactPhone").value.trim();
        const city = document.getElementById("contactCity").value.trim();
        const country = document.getElementById("contactCountry").value.trim();
        const ageValue = document.getElementById("contactAge").value;
        const age = parseInt(ageValue, 10);

        
        const errorSpans = document.querySelectorAll(".error-message");
        for (let i = 0; i < errorSpans.length; i++) {
            errorSpans[i].textContent = "";
        }

        const inputs = document.querySelectorAll("input");
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].classList.remove("input-error");
        }

        
        document.getElementById("formSuccessMessage").textContent = "";

        
        let isValid = true;

        
        if (name === "") {
            document.getElementById("nameError").textContent = "Full name is required.";
            document.getElementById("contactName").classList.add("input-error");
            isValid = false;
        }

        
        if (email === "") {
            document.getElementById("emailError").textContent = "Email address is required.";
            document.getElementById("contactEmail").classList.add("input-error");
            isValid = false;
        } else if (email.includes("@") === false || email.includes(".") === false) {
            document.getElementById("emailError").textContent = "Please enter a valid email address.";
            document.getElementById("contactEmail").classList.add("input-error");
            isValid = false;
        }

        
        if (phone === "") {
            document.getElementById("phoneError").textContent = "Phone number is required.";
            document.getElementById("contactPhone").classList.add("input-error");
            isValid = false;
        } else {
            let hasOnlyNumbers = true;

            
            for (let i = 0; i < phone.length; i++) {
                const char = phone.charAt(i);
                if (char < "0" || char > "9") {
                    hasOnlyNumbers = false;
                    break;
                }
            }

            
            if (!hasOnlyNumbers || phone.length < 4) {
                document.getElementById("phoneError").textContent = "Please enter a valid phone number (exactly 10 digits).";
                document.getElementById("contactPhone").classList.add("input-error");
                isValid = false;
            }
        }

        
        if (city === "") {
            document.getElementById("cityError").textContent = "City is required.";
            document.getElementById("contactCity").classList.add("input-error");
            isValid = false;
        }

        
        if (country === "") {
            document.getElementById("countryError").textContent = "Country is required.";
            document.getElementById("contactCountry").classList.add("input-error");
            isValid = false;
        }

        
        if (ageValue === "" || isNaN(age)) {
            document.getElementById("ageError").textContent = "Age is required.";
            document.getElementById("contactAge").classList.add("input-error");
            isValid = false;
        } else if (age <= 18) {
            document.getElementById("ageError").textContent = "You must be over 18 years old to submit this form.";
            document.getElementById("contactAge").classList.add("input-error");
            isValid = false;
        }

        if (isValid) {
            document.getElementById("formSuccessMessage").textContent = "Thank you! Your inquiry has been submitted successfully.";
            contactForm.reset();
        }
    });
});