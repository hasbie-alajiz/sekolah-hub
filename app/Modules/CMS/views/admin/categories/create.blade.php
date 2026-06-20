<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kategori Baru') }}
            </h2>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="card bg-white shadow-sm border border-gray-100 p-6">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="form-control w-full">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Pengumuman" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('name') input-error @enderror" required />
                        @error('name')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Slug (Opsional)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="slug-kategori-otomatis" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                        @error('slug')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Parent Kategori</label>
                        <select name="parent_id" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                            <option value="">Tanpa Parent (Kategori Utama)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Deskripsi singkat kategori ini..." class="textarea textarea-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="btn btn-primary flex-1 rounded-lg text-sm font-bold active:scale-[0.98]">Simpan Kategori</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost flex-1 rounded-lg text-sm font-bold active:scale-[0.98] border border-gray-200">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
