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
    const searchOpen = document.getElementById("search-popup");
    const searchBar = document.getElementById("searchBar");
    const logoutModal = document.getElementById("logoutModal");
    const logoutButton = document.getElementById("logoutButton");

    const adminSidebar = document.getElementById("admin_sidebar");
    const toggleAdminBtn = document.getElementById("toggleAdminSidebar");
    const mainContent = document.getElementById("main-content");
    const adminHeader = document.getElementById("admin_header");

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

    // Toggle search popup if search bar is focused
    if (searchBar && searchOpen) {
        const searchList = document.getElementById("recent-search-list");
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const clearBtn = document.getElementById("clear-recent-searches");

        if (clearBtn) {
            clearBtn.addEventListener("click", () => {
                fetch("/recent-searches", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                    },
                }).then((res) =>
                    res.json().then((data) => {
                        console.log(data);
                        loadRecentSearches("");
                    }),
                );
            });
        }

        searchBar.addEventListener("focus", () => {
            searchOpen.classList.remove(
                "opacity-0",
                "scale-95",
                "pointer-events-none",
            );
            searchOpen.classList.add("opacity-100", "scale-100");
            loadRecentSearches(searchBar.value);
        });

        searchBar.addEventListener("blur", () => {
            setTimeout(() => {
                searchOpen.classList.add(
                    "opacity-0",
                    "scale-95",
                    "pointer-events-none",
                );
                searchOpen.classList.remove("opacity-100", "scale-100");
            }, 150);
        });

        searchOpen.addEventListener("mousedown", (e) => {
            e.preventDefault(); // keeps input focused when clicking inside
        });

        searchBar.addEventListener("input", () => {
            loadRecentSearches(searchBar.value);
        });

        searchBar.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                saveSearch(searchBar.value);
            }
        });

        function loadRecentSearches(query) {
            if (!searchList) return;
            fetch(`/recent-searches?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((data) => {
                    searchList.innerHTML = "";
                    data.forEach((item) => {
                        const li = document.createElement("li");
                        li.className =
                            "flex cursor-pointer items-center space-x-2 rounded-2xl p-2 transition duration-300 hover:bg-blue-100";
                        li.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>${item.query}</span>
                        `;
                        li.addEventListener("click", () => {
                            searchBar.value = item.query;
                            saveSearch(item.query);
                        });

                        searchList.appendChild(li);
                    });
                    if (data.length === 0) {
                        const li = document.createElement("li");
                        li.className = "text-gray-400 p-2";
                        li.textContent = "No results found";
                        searchList.appendChild(li);
                    }
                });
        }

        function saveSearch(query) {
            if (!query) return;
            fetch("/recent-searches", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                },
                body: JSON.stringify({ query }),
            })
                .then((res) => res.json())
                .then((data) => {
                    console.log(data);
                    loadRecentSearches("");
                });
        }
    } else {
        console.log("Search elements not found");
    }

    if (logoutButton) {
        logoutButton.addEventListener("click", toggleLogoutModal);
    }

    function toggleLogoutModal() {
        logoutModal.classList.remove("hidden");
        logoutModal.classList.add("flex");
    }

    // 👑 Admin sidebar toggle with content adjustment

    function adjustContentLayout() {
        if (adminSidebar && mainContent && adminHeader) {
            const isSidebarHidden =
                adminSidebar.classList.contains("-translate-x-full");

            if (isSidebarHidden) {
                // Sidebar is hidden - make content full width
                mainContent.classList.remove("ml-64");
                mainContent.classList.add("ml-0");
                adminHeader.classList.remove("ml-64");
                adminHeader.classList.add("ml-0");
            } else {
                // Sidebar is visible - add left margin
                mainContent.classList.remove("ml-0");
                mainContent.classList.add("ml-64");
                adminHeader.classList.remove("ml-0");
                adminHeader.classList.add("ml-64");
            }
        }
    }

    if (toggleAdminBtn && adminSidebar) {
        toggleAdminBtn.addEventListener("click", function (e) {
            adminSidebar.classList.toggle("-translate-x-full");
            // Adjust content layout after sidebar toggle
            setTimeout(adjustContentLayout, 50); // Small delay to ensure sidebar animation starts
            e.stopPropagation();
        });

        adminSidebar.addEventListener("click", function (e) {
            e.stopPropagation();
        });

        document.addEventListener("click", function (e) {
            if (
                !adminSidebar.contains(e.target) &&
                !toggleAdminBtn.contains(e.target)
            ) {
                adminSidebar.classList.add("-translate-x-full");
                setTimeout(adjustContentLayout, 50);
            }
        });

        // Initialize layout on page load
        adjustContentLayout();
    }

    // 🏷️ Tag toggle functionality
    function toggleTag(label) {
        const checkbox = label.querySelector('input[type="checkbox"]');
        const tagDisplay = label.querySelector(".tag-display");
        const plusIcon = label.querySelector(".plus-icon");
        const closeIcon = label.querySelector(".close-icon");

        checkbox.checked = !checkbox.checked;

        if (checkbox.checked) {
            // Selected state (dark like your image)
            tagDisplay.classList.remove(
                "bg-gray-100",
                "text-gray-700",
                "border-gray-200",
                "hover:bg-gray-200",
            );
            tagDisplay.classList.add(
                "bg-gray-800",
                "text-white",
                "border-gray-800",
            );
            plusIcon.classList.add("hidden");
            closeIcon.classList.remove("hidden");
        } else {
            // Unselected state (light)
            tagDisplay.classList.remove(
                "bg-gray-800",
                "text-white",
                "border-gray-800",
            );
            tagDisplay.classList.add(
                "bg-gray-100",
                "text-gray-700",
                "border-gray-200",
                "hover:bg-gray-200",
            );
            plusIcon.classList.remove("hidden");
            closeIcon.classList.add("hidden");
        }
    }

    // Add click handlers to tag checkboxes
    document.querySelectorAll(".tag-checkbox").forEach((label) => {
        label.addEventListener("click", function (e) {
            e.preventDefault();
            toggleTag(this);
        });

        // Initialize checked tags
        const checkbox = label.querySelector('input[type="checkbox"]');
        if (checkbox.checked) {
            toggleTag(label);
        }
    });
});
