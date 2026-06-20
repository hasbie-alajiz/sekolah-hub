<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Album Baru') }}
            </h2>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        coverMediaId: '{{ old('cover_media_id') }}',
        coverMediaUrl: '',
        selectMedia(id, url) {
            this.coverMediaId = id;
            this.coverMediaUrl = url;
            document.getElementById('cover_media_modal').close();
        },
        clearMedia() {
            this.coverMediaId = '';
            this.coverMediaUrl = '';
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.gallery.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @csrf

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <div class="form-control w-full mb-4">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nama/Judul Album</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Ketik nama album galeri..." class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('title') input-error @enderror" required />
                            @error('title')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Slug (Opsional)</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="url-album-otomatis-jika-kosong" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                            @error('slug')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Deskripsi Album</label>
                            <textarea name="description" rows="5" placeholder="Keterangan singkat mengenai album kegiatan..." class="textarea textarea-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="space-y-6">
                    <!-- Status & Save -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Publikasi</h3>

                        <div class="form-control w-full mb-6">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Status</label>
                            <select name="status" class="select select-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Diterbitkan</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-primary flex-1 rounded-lg text-sm font-bold active:scale-[0.98]">Simpan Album</button>
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-ghost rounded-lg text-sm font-bold active:scale-[0.98] border border-gray-200">Batal</a>
                        </div>
                    </div>

                    <!-- Album Cover Image -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Sampul Album</h3>
                        
                        <input type="hidden" name="cover_media_id" :value="coverMediaId" />
                        
                        <!-- Sampul Preview -->
                        <div class="mb-4">
                            <template x-if="coverMediaUrl">
                                <div class="relative group aspect-video bg-gray-50 border rounded-lg overflow-hidden">
                                    <img :src="coverMediaUrl" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button type="button" @click="clearMedia()" class="btn btn-circle btn-sm btn-error">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!coverMediaUrl">
                                <div class="border-2 border-dashed border-gray-200 rounded-lg aspect-video flex flex-col items-center justify-center text-gray-400 p-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-xs">Pilih sampul album</span>
                                </div>
                            </template>
                        </div>

                        <button type="button" onclick="document.getElementById('cover_media_modal').showModal()" class="btn btn-sm btn-outline btn-primary w-full">
                            Pilih Gambar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Cover Media Picker Modal -->
        <dialog id="cover_media_modal" class="modal">
            <div class="modal-box max-w-4xl bg-white">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-lg text-gray-800 mb-4">Pilih Gambar Sampul</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-h-[400px] overflow-y-auto p-1">
                    @forelse($mediaList as $media)
                        @php
                            $imageUrl = '';
                            try {
                                $imageUrl = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->getUrl($media->id);
                            } catch (\Exception $e) {
                                $imageUrl = '';
                            }
                        @endphp
                        @if($imageUrl)
                            <div class="border rounded-lg overflow-hidden cursor-pointer hover:border-primary transition group relative aspect-square"
                                 @click="selectMedia('{{ $media->id }}', '{{ $imageUrl }}')">
                                <img src="{{ $imageUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition" />
                                <div class="absolute bottom-0 inset-x-0 bg-black/60 p-1 text-[10px] text-white truncate text-center">
                                    {{ $media->filename }}
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-full py-8 text-center text-gray-400 text-sm">
                            Tidak ada media yang ditemukan. Silakan upload media terlebih dahulu di menu Media.
                        </div>
                    @endforelse
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>
</x-app-layout>
