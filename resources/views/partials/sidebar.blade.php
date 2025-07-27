<div class="w-95 scrollbar-none h-screen overflow-auto bg-[#23222E] p-3 text-white">
    <div class="relative mx-auto w-full max-w-xs py-5">
        <input type="text"
               id="searchBar"
               placeholder="Search..."
               class="w-full rounded-[30px] border border-black bg-[#F8F6F2] py-2 pl-10 pr-4 text-black focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 transform text-gray-400"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
        </svg>

        @include('partials.search-popup')

    </div>

    <ul class="ml-1 space-y-2">
        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/dashboard"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="h-6 w-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0
          .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125
          1.125-1.125h2.25c.621 0 1.125.504
          1.125 1.125V21h4.125c.621 0 1.125-.504
          1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>
                    <p>Home</p>
                </span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:cursor-pointer hover:bg-[#434158]"
            id="contactUs">
            <a class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>

                <span>
                    <p>Contact Us</p>
                </span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/settings"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                <span>Settings</span>
            </a>
        </li>
    </ul>

    <div class="ml-4 mt-3 text-[12px]">
        <p>Sections</p>
    </div>

    <hr class="mb-2 ml-4 mt-2 w-80">

    <ul class="ml-1 space-y-2">
        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/dashboard/animation"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>

                <span>Animation</span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/dashboard/software-engineering"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                </svg>

                <span>Software Engineering</span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/dashboard/multimedia-arts"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                </svg>

                <span>Multimedia Arts & Design</span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/dashboard/game-development"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.63 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z" />
                </svg>

                <span>Game Development</span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/dashboard/real-estate-management"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                </svg>

                <span>Real Estate Management</span>
            </a>
        </li>
    </ul>

    <div class="ml-4 mt-3 text-[12px]">
        <p>Special Events</p>
    </div>

    <hr class="mb-2 ml-4 mt-2 w-80">

    <ul class="ml-1 space-y-2">
        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/home"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>

                <span>Open House</span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/profile"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                </svg>

                <span>Workshops</span>
            </a>
        </li>

        <li class="rounded-[30px] pb-2 pl-3 pt-2 transition duration-300 hover:bg-[#434158]">
            <a href="/profile"
               class="flex items-center space-x-2 text-[20px] md:text-[18px]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>

                <span>Yearly Festivities</span>
            </a>
        </li>
    </ul>

    <hr class="mb-5 ml-4 mt-3 w-80">

    @auth
        <div class="ml-4 mt-3 flex w-full max-w-xs items-center justify-between rounded-[10px]">
            <div class="flex items-center space-x-2">
                <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 128 128'%3E%3Ccircle cx='64' cy='64' r='64' fill='%23d1d5db'/%3E%3Cpath d='M64 32c-8.8 0-16 7.2-16 16s7.2 16 16 16 16-7.2 16-16-7.2-16-16-16zM64 96c-13.3 0-24-10.7-24-24v-8c0-8.8 7.2-16 16-16h16c8.8 0 16 7.2 16 16v8c0 13.3-10.7 24-24 24z' fill='%23374151'/%3E%3C/svg%3E" }}"
                     class="h-10 w-10 rounded-full object-cover" />
                <span>@auth {{ Auth::user()->name }} @endauth
                </span>
            </div>
            <button type="button"
                    id="logoutButton"
                    class="text-white hover:cursor-pointer hover:opacity-20 active:opacity-10">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24"
                     height="24"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     class="lucide lucide-log-out">
                    <path d="m16 17 5-5-5-5" />
                    <path d="M21 12H9" />
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                </svg>
            </button>
        </div>
    @endauth

    @guest
        <div class="ml-4 mt-3 flex w-full max-w-xs items-center justify-between rounded-[10px]">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-6">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                <span>
                    <p>Guest</p>
                </span>
            </div>

        </div>
    @endguest

</div>
