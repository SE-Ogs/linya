import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSideBar");
    const contactBtn = document.getElementById("contactUs");
    const contactModal = document.getElementById("contactModal");

    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeClosed = document.getElementById("eyeClosed");
    const eyeOpen = document.getElementById("eyeOpen");

    // ✅ Sidebar toggle only if elements exist
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", function (e) {
            sidebar.classList.toggle("-translate-x-full");
            e.stopPropagation();
        });

        sidebar.addEventListener("click", function (e) {
            e.stopPropagation();
        });

        document.addEventListener("click", function (e) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.add("-translate-x-full");
            }
        });
    }

    // ✅ Contact modal only if elements exist
    if (contactBtn && contactModal) {
        contactBtn.addEventListener("click", function (e) {
            contactModal.classList.remove("hidden");
            contactModal.classList.add("flex");
        });

        contactModal.addEventListener("click", function (e) {
            if (e.target === contactModal) {
                contactModal.classList.add("hidden");
            }
        });
    }

    // ✅ Password toggle
    if (togglePassword && passwordInput && eyeClosed && eyeOpen) {
        togglePassword.addEventListener("click", function () {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            eyeClosed.classList.toggle("hidden", isPassword);
            eyeOpen.classList.toggle("hidden", !isPassword);
        });
    }
});
