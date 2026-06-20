<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Menu Baru') }}
            </h2>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="card bg-white shadow-sm border border-gray-100 p-6">
                <form action="{{ route('admin.menus.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="form-control w-full">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nama Menu</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Menu Utama" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('name') input-error @enderror" required />
                        @error('name')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Slug (Opsional)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="slug-menu-otomatis" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                        @error('slug')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Lokasi Tampilan (Opsional)</label>
                        <select name="location" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                            <option value="">Jangan Kaitkan ke Lokasi</option>
                            <option value="header-menu" {{ old('location') === 'header-menu' ? 'selected' : '' }}>Header Menu (Navigasi Atas)</option>
                            <option value="footer-menu" {{ old('location') === 'footer-menu' ? 'selected' : '' }}>Footer Menu (Navigasi Bawah)</option>
                        </select>
                        <span class="text-xs text-gray-400 mt-1">Satu lokasi hanya bisa dipasangkan ke satu menu. Mengaitkan menu baru ke lokasi yang sudah digunakan akan memutus menu sebelumnya dari lokasi tersebut.</span>
                        @error('location')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="btn btn-primary flex-1 rounded-lg text-sm font-bold active:scale-[0.98]">Simpan Menu</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-ghost flex-1 rounded-lg text-sm font-bold active:scale-[0.98] border border-gray-200">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
