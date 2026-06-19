@php
    $segments = Request::segments();
    $currentUrl = '';
    
    // Mappings for URL segments to friendly Indonesian / Standard titles
    $mappings = [
        'admin' => 'Dashboard',
        'posts' => 'Berita',
        'pages' => 'Halaman',
        'categories' => 'Kategori',
        'menus' => 'Menu',
        'media' => 'Media',
        'gallery' => 'Galeri',
        'ppdb' => 'PPDB',
        'registrations' => 'Pendaftaran',
        'tracks' => 'Jalur Pendaftaran',
        'academic-years' => 'Tahun Ajaran',
        'form-fields' => 'Form Pendaftaran',
        'contacts' => 'Pesan Masuk',
        'users' => 'Pengguna',
        'audit-logs' => 'Log Audit',
        'themes' => 'Tema',
        'settings' => 'Pengaturan',
        'create' => 'Tambah',
        'edit' => 'Edit',
    ];
@endphp

@if(count($segments) > 0)
    <nav class="flex mb-5 text-[11px] font-semibold text-gray-400 uppercase tracking-wider select-none" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-1.5">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-400 hover:text-blue-600 transition">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            @foreach($segments as $index => $segment)
                @php
                    $currentUrl .= '/' . $segment;
                    // Skip 'admin' segment in breadcrumbs if it's the first one to avoid duplicate Home/Dashboard link
                    if ($segment === 'admin' && $index === 0) {
                        continue;
                    }
                    
                    // If segment is a number, display 'Detail' or similar context
                    if (is_numeric($segment)) {
                        $title = 'Detail';
                    } else {
                        $title = $mappings[$segment] ?? ucfirst(str_replace('-', ' ', $segment));
                    }
                @endphp
                <li>
                    <div class="flex items-center">
                        <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                        @if($loop->last)
                            <span class="ml-1 md:ml-1.5 text-gray-600 font-bold truncate max-w-[200px]">{{ $title }}</span>
                        @else
                            <a href="{{ url($currentUrl) }}" class="ml-1 md:ml-1.5 text-gray-400 hover:text-blue-600 transition">{{ $title }}</a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ol>
    </nav>
@endif