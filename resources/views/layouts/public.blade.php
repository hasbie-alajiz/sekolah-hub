<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50/50 min-h-screen flex flex-col justify-between">
        @php
            $cmsService = app(\App\Modules\CMS\Contracts\CMSServiceInterface::class);
            $headerMenu = $cmsService->getMenuByLocation('header-menu');
            $footerMenu = $cmsService->getMenuByLocation('footer-menu');
        @endphp

        <!-- Navbar Header -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center w-full justify-between">
                        <!-- Logo / Brand -->
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="font-bold text-xl text-primary flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>{{ config('app.name', 'Sekolah Hub') }}</span>
                            </a>
                        </div>

                        <!-- Menu Items -->
                        <nav class="hidden md:flex space-x-1 items-center">
                            @if($headerMenu && $headerMenu->items)
                                @foreach($headerMenu->items as $item)
                                    @if($item->children && $item->children->isNotEmpty())
                                        <!-- Dropdown Menu Item -->
                                        <div class="dropdown dropdown-hover dropdown-end">
                                            <div tabindex="0" role="button" class="btn btn-ghost btn-sm text-gray-600 hover:text-primary font-medium flex items-center">
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
                                        <a href="{{ $item->url }}" target="{{ $item->target }}" class="btn btn-ghost btn-sm text-gray-600 hover:text-primary font-medium">
                                            {{ $item->title }}
                                        </a>
                                    @endif
                                @endforeach
                            @else
                                <a href="/" class="btn btn-ghost btn-sm text-gray-600 hover:text-primary font-medium">Beranda</a>
                            @endif
                        </nav>

                        <!-- Auth Button -->
                        <div class="flex items-center gap-2">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm rounded-lg">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm text-gray-600 font-medium">Masuk</a>
                            @endauth
                        </div>
                    </div>
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
                <nav class="flex flex-wrap gap-4 justify-center">
                    @if($footerMenu && $footerMenu->items)
                        @foreach($footerMenu->items as $item)
                            <a href="{{ $item->url }}" target="{{ $item->target }}" class="text-sm text-gray-500 hover:text-primary">
                                {{ $item->title }}
                            </a>
                        @endforeach
                    @else
                        <span class="text-xs text-gray-400">Website sekolah berbasis Laravel</span>
                    @endif
                </nav>
            </div>
        </footer>
    </body>
</html>
