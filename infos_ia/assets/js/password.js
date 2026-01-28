document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const toggleBtn = document.getElementById("togglePassword");

    toggleBtn.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleBtn.textContent = "🙈";
        } else {
            passwordInput.type = "password";
            toggleBtn.textContent = "👁";
        }
    });
});
