# Panduan Migrasi UI/UX Sekolah Hub ke Laravel

Dokumen ini berisi panduan lengkap untuk menduplikasi seluruh aspek UI/UX dari project React + Tailwind CSS 4 **Sekolah Hub** ke dalam project Laravel Anda (baik menggunakan Blade, Livewire, atau Inertia.js).

---

## 1. Fondasi & Token Desain (Design Tokens)

Sekolah Hub menggunakan font modern **Plus Jakarta Sans** dengan palet warna bersih berbasis netral abu-abu/biru gelap (`slate/gray`) dan warna aksen biru terang (`blue-500`).

### 1.1. Menambahkan Font & CSS Variables
Tambahkan baris berikut di file CSS utama Laravel Anda (misalnya `resources/css/app.css`):

```css
/* Import Font Plus Jakarta Sans */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
  --font-size: 16px;
  --background: #ffffff;
  --foreground: #111827;
  --card: #ffffff;
  --card-foreground: #111827;
  --popover: #ffffff;
  --popover-foreground: #111827;
  --primary: #3B82F6; /* Biru Utama */
  --primary-foreground: #ffffff;
  --secondary: #F8F9FA;
  --secondary-foreground: #111827;
  --muted: #F3F4F6;
  --muted-foreground: #6B7280;
  --accent: #EFF6FF;
  --accent-foreground: #3B82F6;
  --destructive: #DC2626;
  --destructive-foreground: #ffffff;
  --border: #E9ECEF;
  --input: transparent;
  --input-background: #F9FAFB;
  --switch-background: #cbced4;
  --radius: 0.625rem; /* 10px - Rounded border standar */
  
  /* Sidebar Colors (Dark Slate) */
  --sidebar: #1E2A3B;
  --sidebar-foreground: #CBD5E1;
  --sidebar-primary: #3B82F6;
  --sidebar-primary-foreground: #ffffff;
  --sidebar-accent: #253347;
  --sidebar-accent-foreground: #ffffff;
  --sidebar-border: rgba(255, 255, 255, 0.08);
}
```

### 1.2. Konfigurasi Tailwind CSS di Laravel

#### Jika Menggunakan Tailwind CSS v4 (Rekomendasi Baru)
Tambahkan konfigurasi langsung di file `resources/css/app.css` Anda:

```css
@import 'tailwindcss';

@theme {
  --font-sans: 'Plus Jakarta Sans', sans-serif;
  
  --color-background: var(--background);
  --color-foreground: var(--foreground);
  --color-primary: var(--primary);
  --color-primary-foreground: var(--primary-foreground);
  --color-secondary: var(--secondary);
  --color-secondary-foreground: var(--secondary-foreground);
  --color-[#1E2A3B]: #1E2A3B;
  --color-[#253347]: #253347;
  --color-[#E9ECEF]: #E9ECEF;
  --color-[#F8F9FA]: #F8F9FA;
  
  --radius-lg: var(--radius);
  --radius-md: calc(var(--radius) - 2px);
  --radius-sm: calc(var(--radius) - 4px);
}

@layer base {
  body {
    background-color: var(--background);
    color: var(--foreground);
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
}
```

#### Jika Menggunakan Tailwind CSS v3
Sesuaikan file `tailwind.config.js` di project Laravel Anda:

```javascript
const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/View/Component*.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.tsx',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        border: 'var(--border)',
        background: 'var(--background)',
        foreground: 'var(--foreground)',
        primary: {
          DEFAULT: 'var(--primary)',
          foreground: 'var(--primary-foreground)',
        },
        sidebar: {
          DEFAULT: 'var(--sidebar)',
          foreground: 'var(--sidebar-foreground)',
          accent: 'var(--sidebar-accent)',
        }
      },
      borderRadius: {
        lg: 'var(--radius)',
        md: 'calc(var(--radius) - 2px)',
        sm: 'calc(var(--radius) - 4px)',
      }
    },
  },
  plugins: [],
};
```

---

## 2. Struktur Layout Admin (Sidebar + Topbar)

Layout admin menggunakan struktur layar penuh (`h-screen`) yang terbagi menjadi Sidebar (sisi kiri) dan Konten Utama (sisi kanan) yang memiliki Topbar di bagian atas.

### 2.1. Template Layout Blade (`resources/views/layouts/admin.blade.php`)
Berikut adalah struktur dasar HTML & Tailwind untuk layout admin:

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sekolah Hub Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- AlpineJS untuk interaktivitas sidebar & dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F8F9FA] font-sans antialiased text-sm">
    <div class="flex h-screen w-full overflow-hidden" x-data="{ sidebarCollapsed: false }">
        
        <!-- SIDEBAR -->
        <aside 
            :class="sidebarCollapsed ? 'w-[64px]' : 'w-[240px]'"
            class="relative flex flex-col bg-[#1E2A3B] transition-all duration-300 ease-in-out flex-shrink-0 border-r border-[#1E2A3B] z-20"
        >
            <!-- Toggle Button -->
            <button 
                @click="sidebarCollapsed = !sidebarCollapsed"
                class="absolute -right-3 top-5 flex h-6 w-6 items-center justify-center rounded-full bg-white text-[#1E2A3B] shadow-sm border border-gray-200 z-30 hover:bg-gray-50 focus:outline-none"
            >
                <template x-if="sidebarCollapsed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </template>
                <template x-if="!sidebarCollapsed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </template>
            </button>

            <!-- Brand Header -->
            <div class="flex h-[56px] items-center px-4 overflow-hidden flex-shrink-0">
                <!-- Icon Cap Toga -->
                <svg class="text-white flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/><path d="M21.5 12v6"/></svg>
                <span x-show="!sidebarCollapsed" class="ml-3 text-white text-[16px] font-medium whitespace-nowrap">Sekolah Hub</span>
            </div>

            <!-- Navigation Menu (Scrollable) -->
            <div class="flex-1 overflow-y-auto py-4 overflow-x-hidden [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-[#475569] [&::-webkit-scrollbar-thumb]:rounded-full">
                <!-- Grup Konten -->
                <div class="mb-6">
                    <div x-show="!sidebarCollapsed" class="px-4 mb-2 text-[10px] font-medium text-[#475569] tracking-widest uppercase">
                        KONTEN
                    </div>
                    <div class="flex flex-col space-y-1">
                        <!-- Menu Berita (Aktif) -->
                        <a href="{{ route('admin.berita.index') }}" 
                           class="flex items-center h-[40px] px-4 transition-colors whitespace-nowrap border-l-[3px] {{ request()->routeIs('admin.berita.*') ? 'bg-[#253347] border-[#3B82F6] text-white' : 'border-transparent text-[#94A3B8] hover:bg-[#253347] hover:text-[#CBD5E1]' }}">
                            <!-- Icon Newspaper -->
                            <svg class="flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                            <span x-show="!sidebarCollapsed" class="ml-3 text-[13px] font-medium">Berita</span>
                        </a>

                        <!-- Halaman -->
                        <a href="#" class="flex items-center h-[40px] px-4 transition-colors text-[#94A3B8] hover:bg-[#253347] hover:text-[#CBD5E1] border-l-[3px] border-transparent">
                            <!-- Icon File -->
                            <svg class="flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                            <span x-show="!sidebarCollapsed" class="ml-3 text-[13px] font-medium">Halaman</span>
                        </a>
                    </div>
                </div>

                <!-- Tambahkan Grup Menu Lain Sesuai Kebutuhan (PPDB, Sistem, dll.) -->
            </div>

            <!-- Profile Info Footer -->
            <div class="p-4 flex-shrink-0 border-t border-gray-700/50">
                <div class="flex items-center overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1560250097-0b93528c311a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=100" 
                        alt="User" 
                        class="h-8 w-8 rounded-full object-cover flex-shrink-0 ring-2 ring-[#253347]"
                    />
                    <div x-show="!sidebarCollapsed" class="ml-3 flex flex-col min-w-0">
                        <span class="text-white text-[13px] font-medium truncate">Ahmad Dahlan</span>
                        <span class="text-[#94A3B8] text-[11px] truncate">Super Admin</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <div class="flex flex-col flex-1 min-w-0">
            
            <!-- TOPBAR -->
            <header class="h-[56px] bg-white border-b border-[#E9ECEF] flex items-center justify-between px-6 flex-shrink-0 sticky top-0 z-10">
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-[#1E2A3B] rounded flex items-center justify-center text-white mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/><path d="M21.5 12v6"/></svg>
                    </div>
                    <span class="font-semibold text-[#1E2A3B] text-lg">Sekolah Hub</span>
                </div>

                <!-- Dropdown Profil Kanan -->
                <div class="flex items-center" x-data="{ open: false }">
                    <div @click="open = !open" class="flex items-center cursor-pointer hover:bg-gray-50 p-1.5 rounded-md transition-colors relative">
                        <img 
                            src="https://images.unsplash.com/photo-1560250097-0b93528c311a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=100" 
                            alt="User" 
                            class="h-8 w-8 rounded-full object-cover ring-1 ring-gray-200"
                        />
                        <span class="ml-2 text-[13px] font-medium text-gray-700 hidden sm:block">Ahmad Dahlan</span>
                        <svg class="ml-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        
                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open" 
                            @click.away="open = false" 
                            class="absolute right-0 top-full mt-1 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50"
                            x-transition
                        >
                            <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Profil Saya</a>
                            <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">Pengaturan</a>
                            <hr class="border-gray-200 my-1">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ISI HALAMAN -->
            <main class="flex-1 overflow-auto p-6">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>
```

---

## 3. Komponen Dashboard (Tabel & Filter Data)

Gaya visual card menggunakan border minimalis (`border-[#E9ECEF]`), bayangan halus (`shadow-sm`), dan radius 12px (`rounded-[12px]`).

### 3.1. Halaman Berita (`resources/views/admin/berita/index.blade.php`)

```html
<x-admin-layout>
    <div class="max-w-6xl mx-auto w-full pb-8">
        
        <!-- Breadcrumb -->
        <div class="flex items-center text-[14px] text-[#9CA3AF] mb-4">
            <span>Dashboard</span>
            <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span class="text-[#6B7280]">Berita</span>
        </div>

        <!-- Flash Message (Success Alert) -->
        @if(session('success'))
        <div class="mb-6 flex items-center justify-between bg-[#ECFDF5] border border-[#6EE7B7] rounded-[8px] p-3 shadow-sm" x-data="{ show: true }" x-show="show">
            <div class="flex items-center gap-2 text-[#065F46] text-[13px] font-medium">
                <svg class="text-[#10B981]" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-[#065F46] hover:bg-[#D1FAE5] p-1 rounded-md transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        @endif

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-[20px] font-semibold text-[#111827]">Kelola Berita</h1>
            <a href="{{ route('admin.berita.create') }}" class="h-[36px] bg-[#3B82F6] hover:bg-blue-600 transition-colors text-white px-4 rounded-[8px] flex items-center gap-2 text-[13px] font-medium shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah Berita
            </a>
        </div>

        <!-- Filter Bar Card -->
        <div class="bg-white border border-[#E9ECEF] rounded-[12px] p-4 mb-4 flex flex-wrap sm:flex-nowrap items-center gap-4 shadow-sm">
            <!-- Search -->
            <div class="relative flex-shrink-0">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input 
                    type="text" 
                    placeholder="Cari berita..." 
                    class="w-[260px] h-[36px] pl-9 pr-3 border border-gray-200 rounded-lg text-[13px] outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder:text-gray-400 text-gray-700"
                />
            </div>
            
            <!-- Category dropdown -->
            <select class="h-[36px] border border-gray-200 rounded-lg px-3 text-[13px] text-gray-600 bg-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 min-w-[140px] cursor-pointer">
                <option value="">Semua Kategori</option>
                <option value="pengumuman">Pengumuman</option>
                <option value="kegiatan">Kegiatan</option>
                <option value="akademik">Akademik</option>
            </select>

            <!-- Filter Button -->
            <button class="h-[36px] px-4 flex items-center gap-2 text-[13px] font-medium text-gray-600 hover:bg-gray-50 border border-transparent hover:border-gray-200 rounded-lg transition-all ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filter
            </button>
        </div>

        <!-- Main Table Card -->
        <div class="bg-white border border-[#E9ECEF] rounded-[12px] p-6 shadow-sm flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse">
                    <thead>
                        <tr class="border-b border-[#E9ECEF]">
                            <th class="pb-3 text-left text-[12px] font-medium text-[#4B5563] w-[35%]">Judul Berita</th>
                            <th class="pb-3 text-left text-[12px] font-medium text-[#4B5563] w-[15%]">Penulis</th>
                            <th class="pb-3 text-left text-[12px] font-medium text-[#4B5563] w-[15%]">Kategori</th>
                            <th class="pb-3 text-left text-[12px] font-medium text-[#4B5563] w-[10%]">Status</th>
                            <th class="pb-3 text-left text-[12px] font-medium text-[#4B5563] w-[15%]">Tanggal</th>
                            <th class="pb-3 text-right text-[12px] font-medium text-[#4B5563] w-[10%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contoh Row 1 -->
                        <tr class="border-b border-[#E9ECEF] hover:bg-[#F9FAFB] transition-colors">
                            <td class="py-4 pr-4">
                                <div class="text-[13px] font-medium text-[#111827] line-clamp-1">Penerimaan Siswa Baru 2026/2027</div>
                            </td>
                            <td class="py-4 text-[13px] text-gray-600">Admin</td>
                            <td class="py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border border-blue-200 bg-blue-50 text-blue-600">
                                    Pengumuman
                                </span>
                            </td>
                            <td class="py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-green-100 text-green-700">
                                    Diterbitkan
                                </span>
                            </td>
                            <td class="py-4 text-[13px] text-gray-500">18 Jun 2026</td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="#" class="flex items-center justify-center text-blue-600 hover:bg-blue-50 p-1.5 rounded-md transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <button class="flex items-center justify-center text-red-600 hover:bg-red-50 p-1.5 rounded-md transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Desain Custom) -->
            <div class="mt-6 pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-[13px] text-gray-500">
                    Menampilkan <span class="font-medium text-gray-900">1-10</span> dari <span class="font-medium text-gray-900">24</span> data
                </div>
                <div class="flex items-center gap-1">
                    <button class="px-3 py-1.5 text-[13px] font-medium text-gray-400 cursor-not-allowed rounded-md">
                        Sebelumnya
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center text-[13px] font-medium bg-blue-50 text-blue-600 rounded-md">
                        1
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center text-[13px] font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-colors">
                        2
                    </button>
                    <span class="text-gray-400 mx-1">...</span>
                    <button class="px-3 py-1.5 text-[13px] font-medium text-gray-600 hover:bg-gray-50 rounded-md transition-colors">
                        Selanjutnya
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
```

---

## 4. Komponen Form (Tambah/Edit Berita)

Menggunakan layout grid responsive 3 kolom (`grid-cols-1 lg:grid-cols-3`):
- **Kolom Kiri (2/3)**: Input Judul, Slug, Excerpt, dan Editor Area.
- **Kolom Kanan (1/3)**: Card aksi Publikasi, Checklist Kategori, dan Upload Gambar Unggulan.

### 4.1. Halaman Tambah Berita (`resources/views/admin/berita/create.blade.php`)

```html
<x-admin-layout>
    <div class="max-w-6xl mx-auto w-full pb-8">
        
        <!-- Breadcrumb -->
        <div class="flex items-center text-[14px] text-[#9CA3AF] mb-4">
            <span>Dashboard</span>
            <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span>Berita</span>
            <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            <span class="text-[#6B7280]">Tambah Berita</span>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-[20px] font-semibold text-[#111827]">Tambah Berita Baru</h1>
            <a href="{{ route('admin.berita.index') }}" class="h-[36px] hover:bg-gray-100 transition-colors text-gray-600 px-4 rounded-[8px] flex items-center gap-2 text-[13px] font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- KOLOM UTAMA (KIRI - 2/3) -->
                <div class="lg:col-span-2 flex flex-col gap-6">
                    
                    <!-- Card 1: Isi Berita -->
                    <div class="bg-white border border-[#E9ECEF] rounded-[12px] p-6 shadow-sm flex flex-col gap-5">
                        <!-- Judul -->
                        <div>
                            <label class="block text-[12px] font-semibold text-[#4B5563] uppercase tracking-wide mb-2">Judul Berita</label>
                            <input 
                                type="text" 
                                name="title" 
                                placeholder="Ketik judul berita di sini..."
                                class="w-full h-[40px] px-3 border border-[#E9ECEF] rounded-[8px] text-[14px] outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-gray-900"
                                required
                            />
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-[12px] font-semibold text-[#4B5563] uppercase tracking-wide mb-2">Slug</label>
                            <input 
                                type="text" 
                                name="slug" 
                                placeholder="slug-berita-otomatis"
                                class="w-full h-[40px] px-3 border border-[#E9ECEF] rounded-[8px] text-[14px] outline-none bg-gray-50 text-gray-600"
                                readonly
                            />
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label class="block text-[12px] font-semibold text-[#4B5563] uppercase tracking-wide mb-2">Kutipan Singkat (Excerpt)</label>
                            <textarea 
                                name="excerpt" 
                                rows="3"
                                placeholder="Tulis ringkasan singkat berita..."
                                class="w-full p-3 border border-[#E9ECEF] rounded-[8px] text-[14px] outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-gray-900 resize-none"
                            ></textarea>
                        </div>

                        <!-- Content Editor -->
                        <div>
                            <label class="block text-[12px] font-semibold text-[#4B5563] uppercase tracking-wide mb-2">Konten Berita</label>
                            <div class="border border-[#E9ECEF] rounded-[8px] overflow-hidden">
                                <!-- Mock Toolbar (atau ganti dengan CKEditor / Trix Editor) -->
                                <div class="flex items-center gap-1 bg-gray-50 border-b border-[#E9ECEF] p-2">
                                    <button type="button" class="p-1.5 text-gray-600 hover:bg-gray-200 rounded-md transition-colors"><strong class="text-xs">B</strong></button>
                                    <button type="button" class="p-1.5 text-gray-600 hover:bg-gray-200 rounded-md transition-colors"><span class="italic text-xs">I</span></button>
                                    <div class="w-[1px] h-4 bg-gray-300 mx-1"></div>
                                    <button type="button" class="p-1.5 text-gray-600 hover:bg-gray-200 rounded-md transition-colors">Link</button>
                                </div>
                                <textarea 
                                    name="content" 
                                    class="w-full h-[320px] p-4 text-[14px] outline-none resize-none text-gray-900"
                                    placeholder="Mulai menulis konten berita di sini..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: SEO -->
                    <div class="bg-white border border-[#E9ECEF] rounded-[12px] p-6 shadow-sm flex flex-col gap-5">
                        <h2 class="text-[16px] font-semibold text-gray-900 pb-4 border-b border-[#E9ECEF]">Pengaturan SEO</h2>
                        <div>
                            <label class="block text-[12px] font-semibold text-[#4B5563] uppercase tracking-wide mb-2">SEO Title</label>
                            <input 
                                type="text" 
                                name="seo_title" 
                                placeholder="Judul untuk mesin pencari..."
                                class="w-full h-[40px] px-3 border border-[#E9ECEF] rounded-[8px] text-[14px] outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-gray-900"
                            />
                        </div>
                        <div>
                            <label class="block text-[12px] font-semibold text-[#4B5563] uppercase tracking-wide mb-2">SEO Description</label>
                            <textarea 
                                name="seo_description" 
                                rows="3"
                                placeholder="Deskripsi singkat untuk snippet hasil pencarian..."
                                class="w-full p-3 border border-[#E9ECEF] rounded-[8px] text-[14px] outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-gray-900 resize-none"
                            ></textarea>
                        </div>
                    </div>

                </div>

                <!-- KOLOM SIDEBAR (KANAN - 1/3) -->
                <div class="lg:col-span-1 flex flex-col gap-6">
                    
                    <!-- Card 1: Publikasi -->
                    <div class="bg-white border border-[#E9ECEF] rounded-[12px] p-6 shadow-sm flex flex-col gap-5">
                        <h2 class="text-[16px] font-semibold text-gray-900">Publikasi</h2>
                        <div>
                            <label class="block text-[12px] font-semibold text-[#4B5563] uppercase tracking-wide mb-2">Status</label>
                            <div class="relative">
                                <select name="status" class="w-full h-[40px] px-3 appearance-none border border-[#E9ECEF] rounded-[8px] text-[13px] outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white text-gray-900 cursor-pointer">
                                    <option value="published">Diterbitkan</option>
                                    <option value="draft">Draft</option>
                                    <option value="scheduled">Dijadwalkan</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 mt-2">
                            <button type="submit" class="w-full h-[40px] bg-[#3B82F6] hover:bg-blue-600 transition-colors text-white rounded-[8px] text-[13px] font-medium shadow-sm">
                                Simpan Berita
                            </button>
                            <a href="{{ route('admin.berita.index') }}" class="w-full h-[40px] flex items-center justify-center bg-transparent hover:bg-gray-50 transition-colors text-gray-600 rounded-[8px] text-[13px] font-medium border border-transparent">
                                Batal
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Kategori -->
                    <div class="bg-white border border-[#E9ECEF] rounded-[12px] p-6 shadow-sm flex flex-col gap-4">
                        <h2 class="text-[16px] font-semibold text-gray-900">Kategori Berita</h2>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="categories[]" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                <span class="text-[13px] text-gray-700 group-hover:text-gray-900 transition-colors">Pengumuman</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="categories[]" value="2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                <span class="text-[13px] text-gray-700 group-hover:text-gray-900 transition-colors">Kegiatan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Card 3: Gambar Unggulan -->
                    <div class="bg-white border border-[#E9ECEF] rounded-[12px] p-6 shadow-sm flex flex-col gap-4" x-data="{ imgPreview: null }">
                        <h2 class="text-[16px] font-semibold text-gray-900">Gambar Unggulan</h2>
                        
                        <!-- Upload Box / Preview -->
                        <div class="w-full aspect-video bg-[#F3F4F6] border-2 border-dashed border-gray-300 rounded-[8px] flex flex-col items-center justify-center gap-2 overflow-hidden relative">
                            <template x-if="!imgPreview">
                                <div class="flex flex-col items-center gap-1">
                                    <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    <span class="text-[12px] text-gray-500 font-medium">Belum ada gambar terpilih</span>
                                </div>
                            </template>
                            <template x-if="imgPreview">
                                <img :src="imgPreview" class="w-full h-full object-cover" />
                            </template>
                            <!-- Hidden Input File -->
                            <input 
                                type="file" 
                                id="featured_image" 
                                name="image" 
                                accept="image/*"
                                class="hidden" 
                                @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imgPreview = e.target.result; }; reader.readAsDataURL(file); }"
                            />
                        </div>

                        <!-- Button Trigger -->
                        <button 
                            type="button"
                            onclick="document.getElementById('featured_image').click()" 
                            class="w-full h-[36px] border border-[#3B82F6] text-[#3B82F6] hover:bg-blue-50 transition-colors rounded-[8px] text-[13px] font-medium mt-2"
                        >
                            Pilih Gambar
                        </button>
                    </div>

                </div>

            </div>
        </form>
    </div>
</x-admin-layout>
```

---

## 5. UI/UX Polishing & Pola Premium

Untuk memberikan kesan premium dan sangat interaktif, terapkan beberapa mikro-interaksi berikut:

### 5.1. Tombol Pill Asimetris (Asymmetric Pill Button)
Tombol ini memiliki sudut rounded-full yang lebar dengan sebuah tombol ikon berbentuk lingkaran di dalam kanan tombol yang memberikan kedalaman visual:

```html
<a href="#ppdb" class="inline-flex items-center bg-[#3B82F6] text-white font-semibold text-[15px] rounded-full h-[52px] pl-6 pr-2 gap-3 hover:bg-[#2563EB] transition-colors group">
    Daftar PPDB Online
    <span class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center group-hover:bg-white/25 transition-colors">
        <svg class="text-white" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </span>
</a>
```

### 5.2. Desain Double-Bezel (Pillow Effect)
Teknik ini membuat container terasa berlapis dan premium dengan cara menaruh card putih di dalam card background berwarna abu-abu tipis (`#F3F4F6`), dengan selisih padding dan border radius:

```html
<!-- Container Luar (Double-Bezel) -->
<div class="bg-[#F3F4F6] rounded-[32px] p-2 border border-[#E5E7EB]">
    <!-- Container Dalam -->
    <div class="bg-white rounded-[24px] p-8 shadow-sm">
        <!-- Isi Form / Konten Anda -->
    </div>
</div>
```

### 5.3. Efek Zoom pada Hover Gambar Grid
Terapkan CSS class `group` pada parent element dan `group-hover:scale-105 transition-transform duration-500` pada gambar untuk memberikan transisi halus yang modern saat kursor melintas:

```html
<div class="bg-white rounded-[20px] overflow-hidden hover:shadow-[0_8px_32px_rgba(0,0,0,0.08)] transition-shadow duration-300 group cursor-pointer border border-[#E9ECEF]">
    <div class="aspect-[16/9] overflow-hidden bg-[#F3F4F6]">
        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=640&h=360&fit=crop" 
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
    </div>
    <div class="p-5">
        <!-- Konten Card -->
    </div>
</div>
```
