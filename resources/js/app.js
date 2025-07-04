import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSideBar");

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
});
