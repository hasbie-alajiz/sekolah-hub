<header class="sticky top-0 z-20 h-14 bg-white border-b border-border-light flex items-center justify-between px-4 shrink-0 shadow-sm">
    <!-- Left Section: Toggle Buttons & App Context -->
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Open Toggle -->
        <button 
            @click="mobileOpen = !mobileOpen" 
            class="md:hidden p-1.5 rounded-lg text-body-dark hover:bg-gray-100 transition focus:outline-none"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Desktop Sidebar Toggle Trigger (When collapsed, show icon button to expand/collapse) -->
        <button 
            @click="toggleSidebar()" 
            class="hidden md:flex p-1.5 rounded-lg text-body-dark hover:bg-gray-100 transition focus:outline-none"
        >
            <svg xmlns="http://www.w3.org/2000/svg" :class="sidebarCollapsed ? 'rotate-180' : ''" class="h-5 w-5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>

        <!-- App/School Brand Title for Context -->
        <div class="hidden sm:flex items-center gap-2">
            <span class="text-xs font-semibold px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md border border-blue-100">
                V1
            </span>
            <span class="text-xs text-muted-text font-medium">
                Admin Panel
            </span>
        </div>
    </div>

    <!-- Right Section: Direct Site Link & User Dropdown -->
    <div class="flex items-center gap-4">
        <!-- Direct Link to Public Website -->
        <a 
            href="/" 
            target="_blank" 
            class="hidden sm:flex items-center gap-1.5 text-[13px] font-medium text-blue-600 hover:text-blue-700 transition"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            <span>Lihat Website</span>
        </a>

        <!-- User Profile Dropdown -->
        <div class="dropdown dropdown-end">
            <button tabindex="0" class="flex items-center gap-2 p-1 rounded-lg hover:bg-gray-50 transition focus:outline-none">
                <div class="h-8 w-8 rounded-full bg-blue-500 text-white font-bold flex items-center justify-center text-xs uppercase shadow-sm">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <span class="hidden md:inline text-[13px] font-semibold text-heading-dark">
                    {{ Auth::user()->name }}
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-body-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <ul tabindex="0" class="dropdown-content menu p-1.5 shadow-md bg-white rounded-xl w-52 border border-border-light z-30 mt-2">
                <li>
                    <a href="{{ route('profile.edit') }}" class="text-[13px] text-body-dark hover:bg-gray-50 rounded-lg py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-body-dark/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Edit Profil</span>
                    </a>
                </li>
                <li class="border-t border-gray-100 my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left text-[13px] text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg py-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>