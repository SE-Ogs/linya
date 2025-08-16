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
    const passwordInputSignup = document.getElementById("password-signup");
    const togglePasswordSignup = document.getElementById(
        "togglePassword-signup",
    );
    const eyeClosedSignup = document.getElementById("eyeClosed-signup");
    const eyeOpenSignup = document.getElementById("eyeOpen-signup");

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
    // NOTE: Alpine.js is recommended for modal transitions, but if using vanilla JS, add Tailwind classes for animation.
    if (contactBtn && contactModal) {
        contactBtn.addEventListener("click", function (e) {
            // Add Tailwind transition classes for modal animation
            contactModal.classList.remove("hidden");
            contactModal.classList.add("flex");
            // Ensure base transition classes are present
            contactModal.classList.add(
                "transition",
                "duration-300",
                "ease-out",
                "transform",
                "scale-95",
                "opacity-0",
            );
            // Force reflow to enable transition
            void contactModal.offsetWidth;
            // Animate to visible
            contactModal.classList.remove("scale-95", "opacity-0");
            contactModal.classList.add("scale-100", "opacity-100");
        });

        contactModal.addEventListener("click", function (e) {
            if (e.target === contactModal) {
                // Animate to hidden
                contactModal.classList.remove("scale-100", "opacity-100");
                contactModal.classList.add("scale-95", "opacity-0");
                // Wait for transition before hiding
                setTimeout(() => {
                    contactModal.classList.add("hidden");
                    contactModal.classList.remove("flex");
                }, 300);
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
    } // signup password toggle
    if (
        togglePasswordSignup &&
        passwordInputSignup &&
        eyeClosedSignup &&
        eyeOpenSignup
    ) {
        togglePasswordSignup.addEventListener("click", function () {
            const isPassword = passwordInputSignup.type === "password";
            passwordInputSignup.type = isPassword ? "text" : "password";
            eyeClosedSignup.classList.toggle("hidden", isPassword);
            eyeOpenSignup.classList.toggle("hidden", !isPassword);
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
            if (e.key === "Enter") {
                e.preventDefault();
                saveSearch(searchBar.value);
                search(searchBar.value);
            }
        });

        searchBar.addEventListener("input", () => {
            const query = searchBar.value.trim();
            console.log("Input listener fired. Query:", query);

            if (query.length === 0) {
                loadRecentSearches("");
                clearSearch();
                return;
            }

            const resultsContainer = document.getElementById(
                "search-results-container",
            );
            const content = document.getElementById("content");

            if (resultsContainer && content) {
                resultsContainer.classList.remove("hidden");
                content.classList.add("hidden");
            }

            console.log("Calling search()");
            search(query);
            loadRecentSearches(searchBar.value);
        });

        searchBar.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                saveSearch(searchBar.value);
            }
        });

        function search(query) {
            const resultsContainer = document.getElementById(
                "search-results-container",
            );
            const resultsGrid = document.getElementById("search-results");
            const loadingText = document.getElementById("search-loading");
            const content = document.getElementById("content");

            if (!resultsContainer || !resultsGrid || !content || !loadingText)
                return;

            resultsContainer.classList.remove("hidden");
            loadingText.classList.remove("hidden");
            content.classList.add("hidden");

            fetch(`/home-search?q=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((data) => {
                    resultsGrid.innerHTML = "";
                    resultsGrid.classList.remove("hidden");

                    if (data.length === 0) {
                        resultsGrid.innerHTML = `<p class="text-gray-500 col-span-full">No results found.</p>`;
                    } else {
                        data.forEach((article) => {
                            const card = document.createElement("a");
                            card.href = `/articles/${article.id}`;
                            card.className =
                                "bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm p-6 hover:shadow-md transition";

                            card.innerHTML = `<div class="relative h-96 cursor-pointer overflow-hidden rounded-lg bg-gradient-to-br from-indigo-50 to-pink-50">
                                <div class="gradient-overlay absolute inset-0">
                                <img src="${article.image_url || "/images/placeholder.jpg"}"
                                    alt="${article.title}"
                                    class="h-full w-full object-cover object-center">
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                <div class="relative z-10 flex h-full flex-col justify-end p-8 text-white">
                                <div>
                                    <div class="mb-4 flex flex-wrap gap-2">
                                    ${(article.tags || [])
                                        .map(
                                            (tag) => `
                                        <div class="bg-indigo-600 rounded-full px-4 py-1 text-xs font-semibold">
                                        ${tag.abbreviated_name || tag.name}
                                        </div>
                                    `,
                                        )
                                        .join("")}
                                    </div>
                                    <h1 class="mb-4 text-3xl font-bold leading-tight lg:text-4xl">${article.title}</h1>
                                    <p class="text-lg leading-relaxed text-gray-200">${article.summary}</p>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-300 mt-4">
                                    <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    <span>${article.author_name || "Unknown Author"}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <span>${new Date(
                                        article.created_at,
                                    ).toLocaleDateString(undefined, {
                                        year: "numeric",
                                        month: "long",
                                        day: "numeric",
                                    })}</span>
                                    </div>
                                </div>
                                </div>
                            </div>`;

                            resultsGrid.appendChild(card);
                        });
                    }

                    loadingText.classList.add("hidden");
                })
                .catch((err) => {
                    console.error("Search error: ", err);
                    loadingText.textContent = "Something went wrong.";
                });
        }

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
                            saveSearch(item.query); // optional, if you're updating history
                            search(item.query); // <-- THIS is the important addition
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

    document.addEventListener("DOMContentLoaded", () => {
        const sidebarToggle = document.getElementById("sidebarToggle");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("mainContent");

        if (sidebarToggle) {
            sidebarToggle.addEventListener("click", () => {
                sidebar.classList.toggle("-translate-x-full");
                mainContent.classList.toggle("ml-64");
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        // Add event listener to all edit buttons
        document.querySelectorAll(".edit-btn").forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                const articleId = btn.getAttribute("data-id"); // Get the article ID
                confirmEdit(articleId); // Pass the article ID to the confirmEdit function
            });
        });
    });

    function confirmEdit(articleId) {
        // Redirect to the edit page for the specific article
        window.location.href = `/admin/articles/${articleId}/edit`;
    }
});

function clearSearch() {
    console.log("clearSearch started");

    const searchResults = document.getElementById("search-results");
    const loadingText = document.getElementById("search-loading");
    const resultsContainer = document.getElementById(
        "search-results-container",
    );
    const dashboard = document.getElementById("content");
    const searchInput = document.getElementById("searchBar");

    if (searchResults) {
        searchResults.innerHTML = ""; // only clear inner results, not the whole container
        searchResults.classList.add("hidden");
    }

    if (loadingText) {
        loadingText.classList.add("hidden");
    }

    if (resultsContainer) {
        resultsContainer.classList.add("hidden");
    }

    if (dashboard) {
        dashboard.classList.remove("hidden");
    }

    if (searchInput) {
        searchInput.value = "";
    }

    console.log("clearSearch completed");
}

window.clearSearch = clearSearch;

document.addEventListener("DOMContentLoaded", () => {
    const replyButtons = document.querySelectorAll(".reply-button");

    replyButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault();

            const commentId = button.dataset.commentId;
            const replyFormContainer = document.getElementById(
                `reply-form-${commentId}`,
            );

            if (replyFormContainer) {
                document
                    .querySelectorAll(".reply-form-container")
                    .forEach((form) => {
                        if (form.id !== `reply-form-${commentId}`) {
                            form.style.display = "none";
                        }
                    });

                if (
                    replyFormContainer.style.display === "none" ||
                    replyFormContainer.style.display === ""
                ) {
                    replyFormContainer.style.display = "block";
                    const inputField =
                        replyFormContainer.querySelector('input[type="text"]');
                    if (inputField) {
                        inputField.focus();
                    }
                } else {
                    replyFormContainer.style.display = "none";
                }
            }
        });
    });

    const toggleRepliesButtons = document.querySelectorAll(
        ".toggle-replies-btn",
    );

    toggleRepliesButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const commentId = button.dataset.commentId;
            const repliesCount = button.dataset.repliesCount;
            const repliesContainer = document.getElementById(
                `replies-container-${commentId}`,
            );

            if (repliesContainer) {
                if (repliesContainer.classList.contains("hidden")) {
                    repliesContainer.classList.remove("hidden");
                    button.textContent = "Hide Replies";
                } else {
                    repliesContainer.classList.add("hidden");
                    button.textContent = `See Replies (${repliesCount})`;
                }
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    // Use the id if present, otherwise fallback to name selector
    let textarea = document.getElementById("comment-box");
    if (!textarea) {
        textarea = document.querySelector('textarea[name="comment_text"]');
    }
    const counter = document.getElementById("char-count");

    if (textarea && counter) {
        const updateCounter = () => {
            counter.textContent = `${textarea.value.length}/500`;
        };

        textarea.addEventListener("input", updateCounter);

        // Submit form on Enter (without Shift)
        // textarea.addEventListener("keydown", function (e) {
        //     if (e.key === "Enter" && !e.shiftKey) {
        //         e.preventDefault();
        //         this.closest("form").submit(); // Submit the form on Enter
        //     }
        // });

        // Keyboard shortcut formatting: Ctrl+B, Ctrl+I, Ctrl+U
        textarea.addEventListener("keydown", function (e) {
            if ((e.ctrlKey || e.metaKey) && !e.shiftKey) {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                const selectedText = this.value.substring(start, end);

                if (e.key === "b") {
                    e.preventDefault();
                    this.setRangeText(`**${selectedText}**`, start, end, "end");
                } else if (e.key === "i") {
                    e.preventDefault();
                    this.setRangeText(`*${selectedText}*`, start, end, "end");
                } else if (e.key === "u") {
                    e.preventDefault();
                    this.setRangeText(
                        `<u>${selectedText}</u>`,
                        start,
                        end,
                        "end",
                    );
                }
            }
        });

        // Set initial value
        updateCounter();
    }

    // Profanity filter for comment textarea
    if (textarea) {
        const profanityMap = {
            badword: "🐸",
            ugly: "🌸",
            stupid: "🍭",
            hate: "💖",
            fuck: "ദ്ദി(˵ •̀ ᴗ - ˵ ) ✧",
            shit: "˙ . ꒷ 🍰 . 𖦹˙—",
            asshole: "ᕙ(  •̀ ᗜ •́  )ᕗ",
            ass: "(⸝⸝๑﹏๑⸝⸝)",
            kys: "🎀🪞🩰🦢🕯️",
            faggot: "🫧",
            retarded: "▶︎ •၊၊||၊|။|||||||• 0:10",
            "kill your self": "🌸˚˖⋆",
            bitch: "˙ . ꒷ 🍰 . 𖦹˙—",
            dick: "Ϟ(๑⚈ ․̫ ⚈๑)⋆",
            betch: "꧁ᬊᬁᴀɴɢᴇʟᬊ᭄꧂",
            nigga: "˙✧˖🌅📸 ༘ ⋆｡˚", //spyke ga type
            nigger: "˙⋆｡ﾟ☁︎｡⋆｡ ﾟ☾ ﾟ｡⋆",
            nazi: "𓆉𓆝 𓆟 𓆞 𓆝 𓆟𓇼",
            wtf: "* ੈ ♡ ⸝⸝🪐 ༘ ⋆",
            atay: "⁺˚⋆｡°✩₊✩°｡⋆˚⁺",
            bobo: "≽^•⩊•^≼",
            tanga: "₊ ⊹🪻 ✿˚. ᵎᵎ 🫐 ༘ ⋆｡˚",
            tangina: " *‧.₊˚*੭*ˊᵕˋ੭.*",
            putangina: "˙⋆.˚🦋༘⋆",
            sybau: "𖡼𖤣𖥧𖡼𓋼𖤣𖥧𓋼𓍊",
            syet: "⋆｡‧˚ʚ🍓ɞ˚‧｡⋆",
            puta: "₍^. .^₎⟆",
            sex: "˚.🎀༘⋆",
            kill: "༘⋆₊ ⊹★🔭๋࣭ ⭑⋆｡˚",
            "tang ina": "⊹ ࣪ ﹏𓊝﹏𓂁﹏⊹ ࣪ ˖",
            kayata: "˗ˏˋ(ˊ•͈ω•͈ˋ)ˎˊ˗",
            kayasa: "✩₊˚.⋆☾𓃦☽⋆⁺₊✧",
            piss: "🪼⋆｡𖦹°🫧⋆.ೃ࿔*:･",
            "pak you": "꧁⎝ 𓆩༺✧༻𓆪 ⎠꧂",
            pakyu: "꧁⎝ 𓆩༺✧༻𓆪 ⎠꧂",
            retard: "ᯓ★",
        };

        function filterProfanity(text) {
            // Normalization for spaced-out or symbol-separated profanity
            const normalized = text.toLowerCase().replace(/[^a-zA-Z0-9]/g, "");
            Object.keys(profanityMap).forEach((badWord) => {
                const compactBadWord = badWord
                    .replace(/\s+/g, "")
                    .toLowerCase();
                if (normalized.includes(compactBadWord)) {
                    // e.g. "b a d w o r d" or "b-a-d-w-o-r-d" or "b_a_d_w_o_r_d"
                    const regex = new RegExp(
                        badWord.split("").join("[^a-zA-Z0-9]*"),
                        "gi",
                    );
                    text = text.replace(regex, profanityMap[badWord]);
                }
            });
            // Standard word-boundary replacement for direct matches
            let result = text;
            for (const word in profanityMap) {
                const regex = new RegExp(`\\b${word}\\b`, "gi");
                result = result.replace(regex, profanityMap[word]);
            }
            return result;
        }

        // Track if profanity was previously detected
        let profanityWasPresent = false;

        // if (textarea) {
        //     const adjustHeight = () => {
        //         textarea.style.height = "auto"; // reset
        //         textarea.style.height = textarea.scrollHeight + "px"; // expand
        //     };

        //     textarea.addEventListener("input", () => {
        //         adjustHeight();
        //     });

        //     // Initialize on page load
        //     adjustHeight();
        // }

        textarea.addEventListener("input", () => {
            const originalValue = textarea.value;
            let newValue = filterProfanity(originalValue);
            let profanityDetected = newValue !== originalValue;
            if (profanityDetected) {
                textarea.value = newValue;
                spookyAutoType();
                textarea.setSelectionRange(newValue.length, newValue.length);
                profanityWasPresent = true;
            } else if (profanityWasPresent && !profanityDetected) {
                // User erased the bad word, reverse the spooky message
                spookyAutoType(true);
                profanityWasPresent = false;
            }
            if (document.getElementById("char-count")) {
                document.getElementById("char-count").textContent =
                    `${newValue.length}/500`;
            }
        });

        // Spooky typing effect function with reverse animation
        function spookyAutoType(reverse = false) {
            let warning = document.querySelector(".spooky-warning");
            const message =
                "Not very punk rock of you to use that language, is it?";

            // Create if it doesn't exist
            if (!warning) {
                warning = document.createElement("span");
                warning.className =
                    "text-xs text-red-500 spooky-warning ml-2 block mt-1";
                document
                    .getElementById("below-textarea")
                    .insertBefore(
                        warning,
                        document.getElementById("char-count"),
                    );
            }

            let index = reverse ? message.length : 0;

            // Clear any existing interval
            if (warning.spookyInterval) {
                clearInterval(warning.spookyInterval);
            }

            // If reverse, start with full message
            if (reverse) {
                warning.textContent = message;
            } else {
                warning.textContent = "";
            }

            warning.spookyInterval = setInterval(() => {
                if (!reverse && index < message.length) {
                    warning.textContent += message[index++];
                } else if (reverse && index >= 0) {
                    warning.textContent = message.substring(0, index--);
                } else {
                    clearInterval(warning.spookyInterval);
                }
            }, 60);
        }
    }
});

import "./comments.js";
