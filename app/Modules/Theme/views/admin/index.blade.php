<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Tema') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'themes' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabs -->
            <div class="tabs tabs-lifted mb-6 space-x-2">
                <button @click="activeTab = 'themes'" :class="activeTab === 'themes' ? 'tab-active' : ''" class="tab font-semibold text-sm">Pilih Tema</button>
                <button @click="activeTab = 'sections'" :class="activeTab === 'sections' ? 'tab-active' : ''" class="tab font-semibold text-sm">Susunan Beranda</button>
                <button @click="activeTab = 'config'" :class="activeTab === 'config' ? 'tab-active' : ''" class="tab font-semibold text-sm">Konfigurasi Konten</button>
            </div>

            <!-- Tab Content: Choose Theme -->
            <div x-show="activeTab === 'themes'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($themes as $theme)
                        <div class="card bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                            <!-- Preview Image / Placeholder -->
                            <div class="aspect-video bg-gray-50 flex items-center justify-center border-b border-gray-150 relative">
                                @if($theme['screenshot'])
                                    <img src="{{ $theme['screenshot'] }}" alt="{{ $theme['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-gray-300 flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs">No Preview</span>
                                    </div>
                                @endif
                                @if($theme['directory'] === $activeTheme)
                                    <div class="absolute top-3 right-3 badge badge-success text-white text-xs font-semibold py-1 px-2.5 rounded-full border-0">
                                        Aktif
                                    </div>
                                @endif
                            </div>

                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $theme['name'] }}</h3>
                                    <p class="text-xs text-gray-400 mt-1">Versi {{ $theme['version'] }} &bull; Oleh {{ $theme['author'] ?: 'Sekolah Hub' }}</p>
                                </div>
                                <div class="pt-5">
                                    @if($theme['directory'] === $activeTheme)
                                        <button disabled class="btn btn-neutral btn-sm w-full rounded-lg text-xs font-bold">Tema Sedang Aktif</button>
                                    @else
                                        <form action="{{ route('admin.themes.activate') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="theme" value="{{ $theme['directory'] }}">
                                            <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg text-xs font-bold">Aktifkan Tema</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab Content: Homepage Sections -->
            <div x-show="activeTab === 'sections'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6"
                 x-data="homepageSections({{ json_encode($allSections) }}, {{ json_encode($activeSections) }})">
                <div class="max-w-xl">
                    <p class="text-sm text-gray-500 mb-6">Atur urutan pemuatan section pada halaman depan dengan tombol panah (▲/▼). Centang checkbox untuk menampilkan section di halaman depan.</p>
                    
                    <form action="{{ route('admin.themes.sections.update') }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <template x-for="(section, index) in sections" :key="section">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-150 hover:bg-gray-100/50 transition">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="sections[]" :value="section" class="checkbox checkbox-primary checkbox-sm" :checked="activeSections.includes(section)">
                                        <span class="font-bold text-gray-800 text-sm capitalize" x-text="section === 'ppdb' ? 'PPDB (Admisi)' : (section === 'cta' ? 'CTA Banner' : section)"></span>
                                    </div>
                                    <div class="flex gap-1">
                                        <button type="button" @click="moveUp(index)" class="btn btn-ghost btn-xs font-semibold" :disabled="index === 0">▲</button>
                                        <button type="button" @click="moveDown(index)" class="btn btn-ghost btn-xs font-semibold" :disabled="index === sections.length - 1">▼</button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="btn btn-primary rounded-lg text-sm font-bold active:scale-[0.98]">
                                Simpan Susunan Beranda
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab Content: Theme Settings Config -->
            <div x-show="activeTab === 'config'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <form action="{{ route('admin.themes.settings.update') }}" method="POST" class="max-w-3xl space-y-8 divide-y divide-gray-100">
                    @csrf

                    <!-- HERO Section Config -->
                    <div class="space-y-4">
                        <h3 class="text-md font-bold text-gray-900 uppercase tracking-wider">1. Konfigurasi Bagian Hero</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Judul Utama (Hero Title)</label>
                                <input type="text" name="settings[hero_title]" value="{{ $themeSettings['hero_title'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                            </div>
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Sub-Judul / Deskripsi (Hero Subtitle)</label>
                                <textarea name="settings[hero_subtitle]" class="textarea textarea-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 min-h-[60px]" required>{{ $themeSettings['hero_subtitle'] ?? '' }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Label Tombol Aksi (CTA Button)</label>
                                    <input type="text" name="settings[hero_cta_text]" value="{{ $themeSettings['hero_cta_text'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                </div>
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Link Tombol Aksi</label>
                                    <input type="text" name="settings[hero_cta_url]" value="{{ $themeSettings['hero_cta_url'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                </div>
                            </div>
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Pilih Gambar Latar Belakang (Hero Background)</label>
                                <select name="settings[hero_bg_media_id]" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                    <option value="">-- Gunakan Latar Belakang Default --</option>
                                    @foreach($mediaList as $media)
                                        <option value="{{ $media->id }}" {{ (isset($themeSettings['hero_bg_media_id']) && (int)$themeSettings['hero_bg_media_id'] === $media->id) ? 'selected' : '' }}>
                                            {{ $media->original_name }} ({{ number_format($media->size/1024, 1) }} KB)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- ANNOUNCEMENT Section Config -->
                    <div class="space-y-4 pt-8">
                        <h3 class="text-md font-bold text-gray-900 uppercase tracking-wider">2. Sambutan Kepala Sekolah</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Judul Bagian Sambutan</label>
                                <input type="text" name="settings[announcement_title]" value="{{ $themeSettings['announcement_title'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                            </div>
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Isi Teks Sambutan</label>
                                <textarea name="settings[announcement_content]" class="textarea textarea-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 min-h-[100px]" required>{{ $themeSettings['announcement_content'] ?? '' }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nama Kepala Sekolah</label>
                                    <input type="text" name="settings[announcement_author]" value="{{ $themeSettings['announcement_author'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                </div>
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Jabatan / Peran</label>
                                    <input type="text" name="settings[announcement_author_role]" value="{{ $themeSettings['announcement_author_role'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                </div>
                            </div>
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Pilih Foto Kepala Sekolah</label>
                                <select name="settings[announcement_image_media_id]" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                    <option value="">-- Gunakan Foto Default --</option>
                                    @foreach($mediaList as $media)
                                        <option value="{{ $media->id }}" {{ (isset($themeSettings['announcement_image_media_id']) && (int)$themeSettings['announcement_image_media_id'] === $media->id) ? 'selected' : '' }}>
                                            {{ $media->original_name }} ({{ number_format($media->size/1024, 1) }} KB)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section Config -->
                    <div class="space-y-4 pt-8">
                        <h3 class="text-md font-bold text-gray-900 uppercase tracking-wider">3. Banner Ajakan (CTA Banner)</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Judul Banner</label>
                                <input type="text" name="settings[cta_title]" value="{{ $themeSettings['cta_title'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Label Tombol Banner</label>
                                    <input type="text" name="settings[cta_button_text]" value="{{ $themeSettings['cta_button_text'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                </div>
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Link Tujuan Tombol</label>
                                    <input type="text" name="settings[cta_button_url]" value="{{ $themeSettings['cta_button_url'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CONTACT Section Config -->
                    <div class="space-y-4 pt-8">
                        <h3 class="text-md font-bold text-gray-900 uppercase tracking-wider">4. Hubungi Kami (Detail Kontak)</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Judul Bagian Kontak</label>
                                    <input type="text" name="settings[contact_title]" value="{{ $themeSettings['contact_title'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                </div>
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Deskripsi Singkat</label>
                                    <input type="text" name="settings[contact_subtitle]" value="{{ $themeSettings['contact_subtitle'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                </div>
                            </div>
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Alamat Resmi Sekolah</label>
                                <input type="text" name="settings[contact_address]" value="{{ $themeSettings['contact_address'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nomor Telepon</label>
                                    <input type="text" name="settings[contact_phone]" value="{{ $themeSettings['contact_phone'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                </div>
                                <div class="form-control w-full">
                                    <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Email Resmi</label>
                                    <input type="email" name="settings[contact_email]" value="{{ $themeSettings['contact_email'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" required>
                                </div>
                            </div>
                            <div class="form-control w-full">
                                <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Google Maps Embed URL</label>
                                <input type="text" name="settings[contact_maps_embed]" value="{{ $themeSettings['contact_maps_embed'] ?? '' }}" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" placeholder="https://www.google.com/maps/embed?pb=...">
                                <span class="text-[10px] text-gray-400 mt-1">Salin alamat URL yang ada di dalam tag src `<iframe>` dari Google Maps Share Option.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-8 flex justify-start">
                        <button type="submit" class="btn btn-primary rounded-lg text-sm font-bold active:scale-[0.98]">
                            Simpan Konfigurasi Konten
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('homepageSections', (initialSections, initialActive) => ({
                sections: initialSections,
                activeSections: initialActive,
                moveUp(index) {
                    if (index > 0) {
                        const arr = [...this.sections];
                        const temp = arr[index];
                        arr[index] = arr[index - 1];
                        arr[index - 1] = temp;
                        this.sections = arr;
                    }
                },
                moveDown(index) {
                    if (index < this.sections.length - 1) {
                        const arr = [...this.sections];
                        const temp = arr[index];
                        arr[index] = arr[index + 1];
                        arr[index + 1] = temp;
                        this.sections = arr;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
