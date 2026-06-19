<!-- Sidebar Container -->
<aside 
    :class="{
        'w-60': !sidebarCollapsed, 
        'w-16': sidebarCollapsed,
        'translate-x-0': mobileOpen, 
        '-translate-x-full md:translate-x-0': !mobileOpen
    }"
    class="fixed inset-y-0 left-0 z-30 flex flex-col justify-between bg-sidebar-bg border-r border-sidebar-active text-sidebar-muted transition-all duration-300 ease-in-out md:static h-screen overflow-hidden"
>
    <!-- Brand / Header -->
    <div class="flex items-center justify-between px-4 h-14 border-b border-sidebar-active shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-white overflow-hidden">
            <!-- Icon Logo -->
            <div class="p-1.5 bg-blue-500 rounded-lg text-white shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>
                </svg>
            </div>
            <!-- Text Logo -->
            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="text-sm font-semibold tracking-wide truncate whitespace-nowrap">
                Sekolah Hub
            </span>
        </a>

        <!-- Collapse Toggle Button (Desktop Only) -->
        <button @click="toggleSidebar()" class="hidden md:flex items-center justify-center p-1 rounded-md text-sidebar-muted hover:text-white hover:bg-sidebar-active transition">
            <svg xmlns="http://www.w3.org/2000/svg" :class="sidebarCollapsed ? 'rotate-180' : ''" class="h-4 w-4 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="11 17 6 12 11 7"/>
                <polyline points="18 17 13 12 18 7"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <div class="flex-1 overflow-y-auto py-4 space-y-4 select-none">
        
        <!-- Group: Dashboard -->
        <div class="px-3">
            <a 
                href="{{ route('dashboard') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                :data-tip="sidebarCollapsed ? 'Dashboard' : null"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
            </a>
        </div>

        <!-- Group: Konten (CMS) -->
        @can('cms.manage')
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-6 text-[10px] font-bold tracking-wider text-sidebar-muted/50 uppercase select-none">
                    Konten
                </div>
                <div class="px-3 space-y-0.5">
                    <a href="{{ route('admin.posts.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.posts.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Berita' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11h3m-3 4h2" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Berita</span>
                    </a>
                    
                    <a href="{{ route('admin.pages.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.pages.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Halaman' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Halaman</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Kategori' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V9a2 2 0 00-2-2h-1M4 18V5a2 2 0 012-2h2a2 2 0 012 2v3M4 18a2 2 0 002 2h12a2 2 0 002-2V9M4 18v-5a4 4 0 014-4h2.5" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Kategori</span>
                    </a>

                    <a href="{{ route('admin.menus.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.menus.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Menu Navigasi' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Menu Navigasi</span>
                    </a>
                </div>
            </div>
        @endcan

        <!-- Group: Media -->
        <div class="space-y-1">
            <div x-show="!sidebarCollapsed" class="px-6 text-[10px] font-bold tracking-wider text-sidebar-muted/50 uppercase select-none">
                Media
            </div>
            <div class="px-3">
                <a href="{{ route('admin.media.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.media.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                   :data-tip="sidebarCollapsed ? 'Media Manager' : null"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Media Manager</span>
                </a>
            </div>
        </div>

        <!-- Group: Galeri (Gallery) -->
        @can('gallery.manage')
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-6 text-[10px] font-bold tracking-wider text-sidebar-muted/50 uppercase select-none">
                    Galeri
                </div>
                <div class="px-3">
                    <a href="{{ route('admin.gallery.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.gallery.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Album Galeri' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Album Galeri</span>
                    </a>
                </div>
            </div>
        @endcan

        <!-- Group: PPDB -->
        @can('ppdb.manage')
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-6 text-[10px] font-bold tracking-wider text-sidebar-muted/50 uppercase select-none">
                    PPDB
                </div>
                <div class="px-3 space-y-0.5">
                    <a href="{{ route('admin.ppdb.registrations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.ppdb.registrations.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Pendaftaran Masuk' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Pendaftaran Masuk</span>
                    </a>

                    <a href="{{ route('admin.ppdb.tracks.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.ppdb.tracks.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Jalur Pendaftaran' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Jalur Pendaftaran</span>
                    </a>

                    <a href="{{ route('admin.ppdb.academic-years.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.ppdb.academic-years.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Tahun Ajaran' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Tahun Ajaran</span>
                    </a>
                </div>
            </div>
        @endcan

        <!-- Group: Kontak (Contact) -->
        @can('contact.manage')
            <div class="space-y-1">
                <div x-show="!sidebarCollapsed" class="px-6 text-[10px] font-bold tracking-wider text-sidebar-muted/50 uppercase select-none">
                    Kontak
                </div>
                <div class="px-3">
                    <a href="{{ route('admin.contacts.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.contacts.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Pesan Masuk' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 5-8-5M6 18h12M6 21h12" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Pesan Masuk</span>
                    </a>
                </div>
            </div>
        @endcan

        <!-- Group: Sistem -->
        <div class="space-y-1">
            <div x-show="!sidebarCollapsed" class="px-6 text-[10px] font-bold tracking-wider text-sidebar-muted/50 uppercase select-none">
                Sistem
            </div>
            <div class="px-3 space-y-0.5">
                @can('users.manage')
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Manajemen Pengguna' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Pengguna</span>
                    </a>
                @endcan

                @can('audit_logs.view')
                    <a href="{{ route('admin.audit-logs.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Log Audit' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Log Audit</span>
                    </a>
                @endcan

                @can('settings.manage')
                    <a href="{{ route('admin.themes.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.themes.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Tema' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Tema</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-sidebar-active text-white border-l-4 border-blue-500 rounded-l-none' : 'hover:bg-sidebar-active/50 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center tooltip tooltip-right' : ''"
                       :data-tip="sidebarCollapsed ? 'Pengaturan' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Pengaturan</span>
                    </a>
                @endcan
            </div>
        </div>

    </div>

    <!-- Sidebar Footer (User Info & Logout) -->
    <div class="p-3 border-t border-sidebar-active bg-sidebar-bg shrink-0">
        <div class="flex items-center justify-between" :class="sidebarCollapsed ? 'flex-col gap-3' : ''">
            <!-- User Info -->
            <div class="flex items-center gap-2.5 min-w-0" :class="sidebarCollapsed ? 'justify-center' : ''">
                <!-- Avatar Initial Badge -->
                <div class="h-9 w-9 rounded-full bg-blue-500 text-white font-bold flex items-center justify-center text-sm shrink-0 uppercase">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <!-- Text Details -->
                <div x-show="!sidebarCollapsed" class="min-w-0 text-left">
                    <div class="text-[13px] font-semibold text-white truncate">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-[11px] text-sidebar-muted truncate">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Admin' }}
                    </div>
                </div>
            </div>

            <!-- Logout Form / Action -->
            <div class="flex" :class="sidebarCollapsed ? 'justify-center w-full' : ''">
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="p-2 rounded-lg text-sidebar-muted hover:text-red-400 hover:bg-sidebar-active transition"
                        :class="sidebarCollapsed ? 'tooltip tooltip-right' : ''"
                        :data-tip="sidebarCollapsed ? 'Keluar' : null"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>