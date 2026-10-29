document.addEventListener("DOMContentLoaded", function () {
    console.log("script.js loaded successfully");

    const form = document.getElementById("contactForm");

    if (!form) {
        console.error("Form not found!");
        return;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        console.log("Form submission event triggered");

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const message = document.getElementById("message").value.trim();

        if (!name || !email || !phone || !message) {
            console.error("Missing form fields!");
            alert("Please fill out all fields.");
            return;
        } else if (!validateEmail(email)) {
            console.error("Invalid email format!");
            alert("Please enter a valid email address.");
            return;
        }

        submitForm();
    });

    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
