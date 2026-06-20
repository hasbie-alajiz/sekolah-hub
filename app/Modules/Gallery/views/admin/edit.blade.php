<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Album Galeri') }}
            </h2>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="galleryEditor()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.gallery.update', $album->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @csrf
                @method('PUT')

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Album Details Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <div class="form-control w-full mb-4">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Nama/Judul Album</label>
                            <input type="text" name="title" value="{{ old('title', $album->title) }}" placeholder="Ketik nama album galeri..." class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1 @error('title') input-error @enderror" required />
                            @error('title')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $album->slug) }}" placeholder="url-album-otomatis-jika-kosong" class="input input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                            @error('slug')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-gray-700 font-semibold mb-1.5 text-[13px]">Deskripsi Album</label>
                            <textarea name="description" rows="3" placeholder="Keterangan singkat mengenai album kegiatan..." class="textarea textarea-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">{{ old('description', $album->description) }}</textarea>
                            @error('description')
                                <span class="text-xs text-rose-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Manage Photos Card -->
                    <div class="card bg-white shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <div>
                                <h3 class="font-semibold text-gray-800 text-lg">Foto di dalam Album</h3>
                                <p class="text-xs text-gray-400">Pilih foto, berikan keterangan, dan tentukan urutan tampilnya.</p>
                            </div>
                            <button type="button" @click="openPhotosModal()" class="btn btn-sm btn-outline btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Tambah Foto
                            </button>
                        </div>

                        <!-- Hidden Inputs for Form Submit -->
                        <template x-for="(item, index) in items" :key="item.media_id">
                            <div>
                                <input type="hidden" :name="'items[' + index + '][media_id]'" :value="item.media_id" />
                                <input type="hidden" :name="'items[' + index + '][caption]'" :value="item.caption" />
                                <input type="hidden" :name="'items[' + index + '][sort_order]'" :value="item.sort_order" />
                            </div>
                        </template>

                        <!-- Selected Photos Grid -->
                        <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                            <template x-if="items.length === 0">
                                <div class="border-2 border-dashed border-gray-100 rounded-lg py-12 text-center text-gray-400 text-sm">
                                    Belum ada foto di album ini. Klik tombol "Tambah Foto" di atas untuk menambahkan.
                                </div>
                            </template>

                            <template x-for="(item, index) in items" :key="item.media_id">
                                <div class="flex flex-col sm:flex-row gap-4 p-4 border border-gray-100 rounded-lg hover:shadow-sm transition bg-gray-50/30">
                                    <!-- Photo Preview -->
                                    <div class="w-full sm:w-32 aspect-video sm:aspect-square bg-gray-100 rounded overflow-hidden border border-gray-200 shrink-0">
                                        <img :src="item.url" class="w-full h-full object-cover" />
                                    </div>

                                    <!-- Fields -->
                                    <div class="flex-grow space-y-3">
                                        <div class="form-control w-full">
                                            <label class="block text-gray-700 font-semibold mb-1 text-[11px]">Keterangan Foto (Caption)</label>
                                            <input type="text" x-model="item.caption" placeholder="Tulis deskripsi pendek untuk foto ini..." class="input input-sm input-bordered w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                                        </div>

                                        <div class="flex items-center gap-4">
                                            <div class="form-control w-28">
                                                <label class="block text-gray-700 font-semibold mb-1 text-[11px]">Urutan (Sort)</label>
                                                <input type="number" x-model.number="item.sort_order" class="input input-sm input-bordered w-full rounded-lg text-center text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" min="0" />
                                            </div>
                                            <div class="text-xs text-gray-400 mt-5">ID Media: <span x-text="item.media_id"></span></div>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="sm:self-center">
                                        <button type="button" @click="removeItem(item.media_id)" class="btn btn-sm btn-circle btn-ghost text-rose-500 hover:bg-rose-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
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
                                                <option value="draft" {{ old('status', $album->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', $album->status) === 'published' ? 'selected' : '' }}>Diterbitkan</option>
                                            </select>
                                        </div>

                                        <div class="flex gap-2">
                                            <button type="submit" class="btn btn-primary flex-1 rounded-lg text-sm font-bold active:scale-[0.98]">Simpan Perubahan</button>
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
                                        <button type="button" @click="clearCover()" class="btn btn-circle btn-sm btn-error">
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
                            Ubah Sampul
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
                                 @click="selectCover('{{ $media->id }}', '{{ $imageUrl }}')">
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

        <!-- Multi Photo Picker Modal -->
        <dialog id="photos_picker_modal" class="modal">
            <div class="modal-box max-w-4xl bg-white flex flex-col h-[80vh]">
                <div class="flex items-center justify-between border-b pb-3 mb-4">
                    <h3 class="font-bold text-lg text-gray-800">Tambahkan Foto ke Album</h3>
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                    </form>
                </div>
                
                <!-- Media List Scrollable -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 overflow-y-auto p-1 flex-grow">
                    @forelse($mediaList as $media)
                        @php
                            $imageUrl = '';
                            $thumbnailUrl = '';
                            try {
                                $imageUrl = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->getUrl($media->id);
                                $thumbnailUrl = app(\App\Modules\Media\Contracts\MediaServiceInterface::class)->getUrl($media->id, 'thumbnail');
                            } catch (\Exception $e) {
                                $imageUrl = '';
                            }
                        @endphp
                        @if($imageUrl)
                            <div class="border rounded-lg overflow-hidden cursor-pointer transition relative aspect-square"
                                 :class="isMediaSelected({{ $media->id }}) ? 'border-primary ring-2 ring-primary/20' : 'hover:border-primary/55 border-gray-200'"
                                 @click="toggleMediaSelection({{ $media->id }}, '{{ $thumbnailUrl ?: $imageUrl }}')">
                                <img src="{{ $thumbnailUrl ?: $imageUrl }}" class="w-full h-full object-cover" />
                                
                                <!-- Selection overlay badge -->
                                <div x-show="isMediaSelected({{ $media->id }})" class="absolute top-2 right-2 w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold shadow">
                                    ✓
                                </div>

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

                <!-- Footer / Confirm selection -->
                <div class="flex justify-end gap-2 border-t pt-4 mt-4">
                    <button type="button" @click="closePhotosModal()" class="btn btn-ghost btn-sm">Batal</button>
                    <button type="button" @click="confirmPhotosAddition()" class="btn btn-primary btn-sm" :disabled="selectedMedia.length === 0">
                        Tambahkan Terpilih (<span x-text="selectedMedia.length"></span>)
                    </button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>

    <script>
        function galleryEditor() {
            return {
                coverMediaId: '{{ old('cover_media_id', $album->cover_media_id) }}',
                coverMediaUrl: '{{ $album->cover_image_url ?? "" }}',
                
                // Existing album items
                items: [
                    @foreach($album->items as $item)
                    {
                        media_id: {{ $item->media_id }},
                        url: '{{ $item->thumbnail_url ?: $item->url }}',
                        caption: '{{ addslashes($item->caption ?? "") }}',
                        sort_order: {{ $item->sort_order }}
                    },
                    @endforeach
                ],

                // Temporary multi selection array in modal
                selectedMedia: [],

                init() {
                    // Sort initial items
                    this.items.sort((a, b) => a.sort_order - b.sort_order);
                },

                // Cover methods
                selectCover(id, url) {
                    this.coverMediaId = id;
                    this.coverMediaUrl = url;
                    document.getElementById('cover_media_modal').close();
                },
                clearCover() {
                    this.coverMediaId = '';
                    this.coverMediaUrl = '';
                },

                // Multi photos selector methods
                openPhotosModal() {
                    // Reset modal selections
                    this.selectedMedia = [];
                    document.getElementById('photos_picker_modal').showModal();
                },
                closePhotosModal() {
                    document.getElementById('photos_picker_modal').close();
                },
                toggleMediaSelection(id, url) {
                    const index = this.selectedMedia.findIndex(m => m.id === id);
                    if (index > -1) {
                        this.selectedMedia.splice(index, 1);
                    } else {
                        this.selectedMedia.push({ id, url });
                    }
                },
                isMediaSelected(id) {
                    return this.selectedMedia.some(m => m.id === id);
                },
                confirmPhotosAddition() {
                    this.selectedMedia.forEach(media => {
                        // Avoid duplicates
                        if (!this.items.some(item => item.media_id === media.id)) {
                            // Find highest sort order
                            const maxSort = this.items.reduce((max, item) => item.sort_order > max ? item.sort_order : max, -1);
                            
                            this.items.push({
                                media_id: media.id,
                                url: media.url,
                                caption: '',
                                sort_order: maxSort + 1
                            });
                        }
                    });
                    this.closePhotosModal();
                },
                removeItem(mediaId) {
                    this.items = this.items.filter(item => item.media_id !== mediaId);
                    // Re-index sort orders to keep it clean
                    this.items.forEach((item, idx) => {
                        item.sort_order = idx;
                    });
                }
            };
        }
    </script>
</x-app-layout>
