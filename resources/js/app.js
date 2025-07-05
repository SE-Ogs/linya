import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSideBar");
    const contactBtn = document.getElementById("contactUs");
    const contactModal = document.getElementById("contactModal");

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

    contactBtn.addEventListener("click", function (e) {
        contactModal.classList.remove("hidden");
        contactModal.classList.add("flex");
    });

    contactModal.addEventListener("click", function (e) {
        if (e.target === contactModal) {
            contactModal.classList.add("hidden");
        }
    });
});
