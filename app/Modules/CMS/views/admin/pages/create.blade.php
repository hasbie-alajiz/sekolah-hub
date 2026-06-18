<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Halaman Baru') }}
            </h2>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        featuredMediaId: '{{ old('featured_media_id') }}',
        featuredMediaUrl: '',
        selectMedia(id, url) {
            this.featuredMediaId = id;
            this.featuredMediaUrl = url;
            document.getElementById('media_modal').close();
        },
        clearMedia() {
            this.featuredMediaId = '';
            this.featuredMediaUrl = '';
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.pages.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @csrf

                <!-- Main Content Editor -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <div class="form-control w-full mb-4">
                            <label class="label font-medium text-gray-700">Judul Halaman</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Ketik judul halaman di sini..." class="input input-bordered w-full @error('title') input-error @enderror" required />
                            @error('title')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label font-medium text-gray-700">Slug (Opsional)</label>
                            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="url-halaman-otomatis-jika-kosong" class="input input-bordered w-full text-sm" />
                            @error('slug')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700">Isi Halaman</label>
                            <textarea id="page-content" name="content" class="hidden">{{ old('content') }}</textarea>
                            @error('content')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- SEO Settings Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Pengaturan SEO (Mesin Pencari)</h3>
                        
                        <div class="form-control w-full mb-4">
                            <label class="label font-medium text-gray-700">SEO Title</label>
                            <input type="text" name="seo_title" value="{{ old('seo_title') }}" placeholder="Judul khusus untuk hasil pencarian Google" class="input input-bordered w-full text-sm" />
                        </div>

                        <div class="form-control w-full">
                            <label class="label font-medium text-gray-700">SEO Description</label>
                            <textarea name="seo_description" rows="3" placeholder="Deskripsi khusus untuk Google snippet" class="textarea textarea-bordered w-full text-sm">{{ old('seo_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="space-y-6">
                    <!-- Publish Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Publikasi</h3>

                        <div class="form-control w-full mb-4">
                            <label class="label font-medium text-gray-700">Parent Halaman</label>
                            <select name="parent_id" class="select select-bordered w-full text-sm">
                                <option value="">Tanpa Parent (Halaman Utama)</option>
                                @foreach($parentPages as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full mb-6">
                            <label class="label font-medium text-gray-700">Status</label>
                            <select name="status" class="select select-bordered w-full">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Diterbitkan</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-primary flex-1">Simpan Halaman</button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-ghost">Batal</a>
                        </div>
                    </div>

                    <!-- Featured Image Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-4">Gambar Unggulan</h3>
                        
                        <input type="hidden" name="featured_media_id" :value="featuredMediaId" />
                        
                        <!-- Media Preview -->
                        <div class="mb-4">
                            <template x-if="featuredMediaUrl">
                                <div class="relative group aspect-video bg-gray-50 border rounded-lg overflow-hidden">
                                    <img :src="featuredMediaUrl" class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button type="button" @click="clearMedia()" class="btn btn-circle btn-sm btn-error">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!featuredMediaUrl">
                                <div class="border-2 border-dashed border-gray-200 rounded-lg aspect-video flex flex-col items-center justify-center text-gray-400 p-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-xs">Belum ada gambar terpilih</span>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="document.getElementById('media_modal').showModal()" class="btn btn-sm btn-outline btn-primary w-full">
                            Pilih Gambar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Media Picker Modal -->
        <dialog id="media_modal" class="modal">
            <div class="modal-box max-w-4xl bg-white">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-lg text-gray-800 mb-4">Pilih Gambar Unggulan</h3>
                
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

    <!-- Self-hosted TinyMCE -->
    <script src="/vendor/tinymce/tinymce.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#page-content',
                height: 400,
                menubar: false,
                branding: false,
                promotion: false,
                skin: 'oxide',
                plugins: 'lists link code table help wordcount',
                toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code help',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
        });
    </script>
</x-app-layout>
