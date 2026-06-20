@php
    try {
        $systemService = app(\App\Modules\System\Contracts\SystemServiceInterface::class);
        $primaryColor = $systemService->getSetting('theme.primary_color', '#3B82F6');
    } catch (\Exception $e) {
        $primaryColor = '#3B82F6';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Sekolah Hub'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styling -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary: {{ $primaryColor }};
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50/50 min-h-screen flex flex-col justify-between">
        @php
            $headerMenu = null;
            $footerMenu = null;
            try {
                $cmsService = app(\App\Modules\CMS\Contracts\CMSServiceInterface::class);
                $headerMenu = $cmsService->getMenuByLocation('header-menu');
                $footerMenu = $cmsService->getMenuByLocation('footer-menu');
            } catch (\Exception $e) {
                // Fail-safe fallback when tables do not exist
            }
        @endphp

        <!-- Navbar Header -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
            <style>
                [x-cloak] { display: none !important; }
            </style>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 md:h-20 items-center">
                    <!-- Logo / Brand (Left) -->
                    <div class="shrink-0 flex items-center">
                        <a href="/" class="font-bold text-xl text-primary flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>{{ config('app.name', 'Sekolah Hub') }}</span>
                        </a>
                    </div>

                    <!-- Nav & CTA Group (Right - Desktop) -->
                    <div class="hidden md:flex items-center gap-8">
                        <nav class="flex space-x-2 items-center">
                            @if($headerMenu && $headerMenu->items)
                                @foreach($headerMenu->items as $item)
                                    @if($item->children && $item->children->isNotEmpty())
                                        <!-- Dropdown Menu Item -->
                                        <div class="dropdown dropdown-hover dropdown-end">
                                            <div tabindex="0" role="button" class="px-4 py-2 text-[15px] font-semibold text-gray-600 hover:text-primary transition-colors flex items-center cursor-pointer">
                                                {{ $item->title }}
                                                <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-white rounded-box w-52 border border-gray-100 z-50">
                                                @foreach($item->children as $child)
                                                    <li>
                                                        <a href="{{ $child->url }}" target="{{ $child->target }}" class="text-gray-600 hover:text-primary hover:bg-gray-50 py-2">
                                                            {{ $child->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ $item->url }}" target="{{ $item->target }}" class="px-4 py-2 text-[15px] font-semibold text-gray-600 hover:text-primary transition-colors">
                                            {{ $item->title }}
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <a href="/" class="px-4 py-2 text-[15px] font-semibold text-gray-600 hover:text-primary transition-colors">Beranda</a>
                                <a href="/kontak" class="px-4 py-2 text-[15px] font-semibold text-gray-600 hover:text-primary transition-colors">Hubungi Kami</a>
                            @endif
                        </nav>
                        
                        <!-- CTA Button -->
                        <a href="/ppdb" class="btn btn-primary text-white text-sm font-bold px-6 py-2.5 rounded-full transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                            Daftar PPDB
                        </a>
                    </div>

                    <!-- Mobile Hamburger Button (Right - Mobile Only) -->
                    <div class="flex md:hidden items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition focus:outline-none" aria-label="Toggle menu">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Overlay & Menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden border-b border-gray-100 bg-white"
                 x-cloak>
                <div class="px-4 pt-2 pb-6 space-y-4 shadow-lg">
                    <nav class="flex flex-col space-y-1">
                        @if($headerMenu && $headerMenu->items)
                            @foreach($headerMenu->items as $item)
                                @if($item->children && $item->children->isNotEmpty())
                                    <div x-data="{ open: false }">
                                        <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-sm font-semibold text-gray-600 hover:text-primary rounded-lg hover:bg-gray-50 transition">
                                            <span>{{ $item->title }}</span>
                                            <svg class="h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-cloak class="pl-4 mt-1 space-y-1 border-l border-gray-100">
                                            @foreach($item->children as $child)
                                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="block px-3 py-2 text-xs text-gray-500 hover:text-primary rounded-lg hover:bg-gray-50 transition">
                                                    {{ $child->title }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ $item->url }}" target="{{ $item->target }}" class="block px-3 py-2 text-sm font-semibold text-gray-600 hover:text-primary rounded-lg hover:bg-gray-50 transition">
                                        {{ $item->title }}
                                    </a>
                                @endif
                            @endforeach
                        @else
                            <a href="/" class="block px-3 py-2 text-sm font-semibold text-gray-600 hover:text-primary rounded-lg hover:bg-gray-50 transition">Beranda</a>
                        @endif
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content Slot -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Copyright info -->
                <div class="text-sm text-gray-500 text-center md:text-left">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Sekolah Hub') }}. Hak Cipta Dilindungi.
                </div>

                <!-- Footer Links -->
                <nav class="flex flex-wrap gap-6 justify-center">
                    @if($footerMenu && $footerMenu->items)
                        @foreach($footerMenu->items as $item)
                            <a href="{{ $item->url }}" target="{{ $item->target }}" class="text-sm text-gray-500 hover:text-primary transition-colors">
                                {{ $item->title }}
                            </a>
                        @endforeach
                    @else
                        <a href="/" class="text-sm text-gray-500 hover:text-primary transition-colors">Beranda</a>
                        <a href="/kontak" class="text-sm text-gray-500 hover:text-primary transition-colors">Hubungi Kami</a>
                    @endif
                </nav>
            </div>
        </footer>
    </body>
</html>
