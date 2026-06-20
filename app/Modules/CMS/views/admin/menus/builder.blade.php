<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Struktur Menu:') }} <span class="text-primary">{{ $menu->name }}</span>
            </h2>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-ghost btn-sm">
                Kembali
            </a>
        </div>
    </x-slot>

    @php
        $flatten = function($items, $depth = 0) use (&$flatten) {
            $result = [];
            foreach ($items as $item) {
                $result[] = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => $item->type,
                    'reference_type' => $item->reference_type,
                    'reference_id' => $item->reference_id,
                    'url' => $item->getRawOriginal('url'),
                    'target' => $item->target,
                    'depth' => $depth
                ];
                if ($item->children && $item->children->count() > 0) {
                    $result = array_merge($result, $flatten($item->children, $depth + 1));
                }
            }
            return $result;
        };
        $flatItems = $flatten($menuItems);
    @endphp

    <div class="py-12" x-data="menuBuilder({{ json_encode($flatItems) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Add Menu Items Accordion -->
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                        <h3 class="font-bold text-gray-800 mb-3 text-sm">Tambah Item Navigasi</h3>
                        
                        <!-- Accordion -->
                        <div class="join join-vertical w-full">
                            <!-- Custom Link -->
                            <div class="collapse collapse-arrow join-item border border-base-200">
                                <input type="radio" name="accordion" checked="checked" /> 
                                <div class="collapse-title text-sm font-semibold text-gray-700">Link Kustom</div>
                                <div class="collapse-content space-y-3">
                                    <div class="form-control">
                                        <label class="block text-gray-700 font-semibold mb-1 text-xs">Teks Link</label>
                                        <input type="text" x-model="customLink.title" placeholder="Contoh: Beranda" class="input input-bordered input-sm w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                                    </div>
                                    <div class="form-control">
                                        <label class="block text-gray-700 font-semibold mb-1 text-xs">URL Link</label>
                                        <input type="text" x-model="customLink.url" placeholder="Contoh: /hubungi-kami atau https://google.com" class="input input-bordered input-sm w-full rounded-lg text-sm border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                                    </div>
                                    <button type="button" @click="addCustomLink()" class="btn btn-primary btn-sm w-full rounded-lg text-sm font-bold active:scale-[0.98]">Tambahkan</button>
                                </div>
                            </div>
                            
                            <!-- Static Pages -->
                            <div class="collapse collapse-arrow join-item border border-base-200">
                                <input type="radio" name="accordion" /> 
                                <div class="collapse-title text-sm font-semibold text-gray-700">Halaman Statis</div>
                                <div class="collapse-content max-h-60 overflow-y-auto space-y-2">
                                    @forelse($pages as $page)
                                        <label class="label cursor-pointer justify-start gap-2 py-1">
                                            <input type="checkbox" value="{{ $page->id }}" data-title="{{ $page->title }}" class="checkbox checkbox-primary checkbox-sm page-checkbox" />
                                            <span class="label-text text-sm text-gray-700">{{ $page->title }}</span>
                                        </label>
                                    @empty
                                        <div class="text-xs text-gray-400 italic">Tidak ada halaman publish.</div>
                                    @endforelse
                                    @if($pages->isNotEmpty())
                                        <button type="button" @click="addCheckedItems('.page-checkbox', 'page')" class="btn btn-primary btn-sm w-full mt-2 rounded-lg text-sm font-bold active:scale-[0.98]">Tambahkan</button>
                                    @endif
                                </div>
                            </div>

                            <!-- News Posts -->
                            <div class="collapse collapse-arrow join-item border border-base-200">
                                <input type="radio" name="accordion" /> 
                                <div class="collapse-title text-sm font-semibold text-gray-700">Berita Sekolah</div>
                                <div class="collapse-content max-h-60 overflow-y-auto space-y-2">
                                    @forelse($posts as $post)
                                        <label class="label cursor-pointer justify-start gap-2 py-1">
                                            <input type="checkbox" value="{{ $post->id }}" data-title="{{ $post->title }}" class="checkbox checkbox-primary checkbox-sm post-checkbox" />
                                            <span class="label-text text-sm text-gray-700">{{ $post->title }}</span>
                                        </label>
                                    @empty
                                        <div class="text-xs text-gray-400 italic">Tidak ada berita publish.</div>
                                    @endforelse
                                    @if($posts->isNotEmpty())
                                        <button type="button" @click="addCheckedItems('.post-checkbox', 'post')" class="btn btn-primary btn-sm w-full mt-2 rounded-lg text-sm font-bold active:scale-[0.98]">Tambahkan</button>
                                    @endif
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="collapse collapse-arrow join-item border border-base-200">
                                <input type="radio" name="accordion" /> 
                                <div class="collapse-title text-sm font-semibold text-gray-700">Kategori Berita</div>
                                <div class="collapse-content max-h-60 overflow-y-auto space-y-2">
                                    @forelse($categories as $category)
                                        <label class="label cursor-pointer justify-start gap-2 py-1">
                                            <input type="checkbox" value="{{ $category->id }}" data-title="{{ $category->name }}" class="checkbox checkbox-primary checkbox-sm category-checkbox" />
                                            <span class="label-text text-sm text-gray-700">{{ $category->name }}</span>
                                        </label>
                                    @empty
                                        <div class="text-xs text-gray-400 italic">Tidak ada kategori.</div>
                                    @endforelse
                                    @if($categories->isNotEmpty())
                                        <button type="button" @click="addCheckedItems('.category-checkbox', 'category')" class="btn btn-primary btn-sm w-full mt-2 rounded-lg text-sm font-bold active:scale-[0.98]">Tambahkan</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Visual Layout Structure -->
                <div class="lg:col-span-2">
                    <form action="{{ route('admin.menus.save_structure', $menu->id) }}" method="POST" @submit="submitForm($event)">
                        @csrf
                        <input type="hidden" name="items_json" :value="JSON.stringify(buildTree())" />

                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-base">Struktur Menu</h3>
                                    <p class="text-xs text-gray-400">Atur urutan, navigasi, dan tingkat menu (maksimal 3 tingkat).</p>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm rounded-lg text-xs font-bold active:scale-[0.98]">Simpan Struktur</button>
                            </div>

                            <!-- List of Menu Items -->
                            <div class="space-y-2 max-h-[500px] overflow-y-auto p-1">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="flex flex-col border rounded-lg bg-white shadow-sm transition hover:border-indigo-100"
                                         :class="{
                                             'ml-0': item.depth === 0,
                                             'ml-8 border-l-2 border-l-primary/40': item.depth === 1,
                                             'ml-16 border-l-2 border-l-secondary/40': item.depth === 2
                                         }">
                                        <div class="flex items-center justify-between p-3">
                                            <!-- Drag Info & Title -->
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-semibold badge"
                                                      :class="{
                                                          'badge-primary': item.type === 'page',
                                                          'badge-secondary': item.type === 'post',
                                                          'badge-accent': item.type === 'category',
                                                          'badge-outline': item.type === 'custom'
                                                      }" x-text="item.type.toUpperCase()"></span>
                                                <span class="font-semibold text-gray-800 text-sm" x-text="item.title"></span>
                                                <span class="text-xs text-gray-400" x-show="item.type === 'custom'" x-text="'('+item.url+')'"></span>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center gap-1">
                                                <!-- Indentation Controls -->
                                                <button type="button" @click="outdent(index)" :disabled="item.depth === 0" class="btn btn-ghost btn-circle btn-xs text-gray-500 disabled:opacity-30" title="Geser Keluar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                                                </button>
                                                <button type="button" @click="indent(index)" :disabled="index === 0 || item.depth >= 2 || item.depth > items[index-1].depth" class="btn btn-ghost btn-circle btn-xs text-gray-500 disabled:opacity-30" title="Geser Masuk">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                                </button>

                                                <!-- Reorder Controls -->
                                                <button type="button" @click="moveUp(index)" :disabled="index === 0" class="btn btn-ghost btn-circle btn-xs text-gray-500 disabled:opacity-30">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                                </button>
                                                <button type="button" @click="moveDown(index)" :disabled="index === items.length - 1" class="btn btn-ghost btn-circle btn-xs text-gray-500 disabled:opacity-30">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                </button>

                                                <!-- Toggle Edit Form -->
                                                <button type="button" @click="item.showEdit = !item.showEdit" class="btn btn-ghost btn-circle btn-xs text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                </button>

                                                <!-- Delete -->
                                                <button type="button" @click="removeItem(index)" class="btn btn-ghost btn-circle btn-xs text-rose-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Edit Item Collapse Panel -->
                                        <div x-show="item.showEdit" class="p-3 bg-gray-50/50 border-t border-gray-100 space-y-3">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="form-control">
                                                    <label class="block text-gray-700 font-semibold mb-1 text-[11px]">Label Navigasi</label>
                                                    <input type="text" x-model="item.title" class="input input-bordered input-xs w-full rounded-md text-xs border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                                                </div>
                                                <div class="form-control" x-show="item.type === 'custom'">
                                                    <label class="block text-gray-700 font-semibold mb-1 text-[11px]">URL</label>
                                                    <input type="text" x-model="item.url" class="input input-bordered input-xs w-full rounded-md text-xs border-gray-200 focus:ring-primary focus:border-primary focus:ring-1" />
                                                </div>
                                                <div class="form-control">
                                                    <label class="block text-gray-700 font-semibold mb-1 text-[11px]">Target Link</label>
                                                    <select x-model="item.target" class="select select-bordered select-xs w-full rounded-md text-xs border-gray-200 focus:ring-primary focus:border-primary focus:ring-1">
                                                        <option value="_self">Tab Aktif (_self)</option>
                                                        <option value="_blank">Tab Baru (_blank)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="items.length === 0" class="text-center py-12 text-gray-400 text-sm italic border border-dashed rounded-lg">
                                    Struktur menu kosong. Tambahkan item navigasi dari panel sebelah kiri.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- AlpineJS Code -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('menuBuilder', (initialItems) => ({
                items: initialItems.map(i => ({ ...i, showEdit: false })),
                customLink: { title: '', url: '' },

                addCustomLink() {
                    if (!this.customLink.title) return;
                    this.items.push({
                        id: null,
                        title: this.customLink.title,
                        type: 'custom',
                        reference_type: null,
                        reference_id: null,
                        url: this.customLink.url || '#',
                        target: '_self',
                        depth: 0,
                        showEdit: false
                    });
                    this.customLink.title = '';
                    this.customLink.url = '';
                },

                addCheckedItems(selector, type) {
                    const checkedCheckboxes = document.querySelectorAll(selector + ':checked');
                    checkedCheckboxes.forEach(cb => {
                        const id = cb.value;
                        const title = cb.getAttribute('data-title');
                        
                        this.items.push({
                            id: null,
                            title: title,
                            type: type,
                            reference_type: type === 'page' ? 'App\\Modules\\CMS\\Models\\Page' : (type === 'post' ? 'App\\Modules\\CMS\\Models\\Post' : 'App\\Modules\\CMS\\Models\\Category'),
                            reference_id: id,
                            url: null,
                            target: '_self',
                            depth: 0,
                            showEdit: false
                        });
                        
                        cb.checked = false;
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.normalizeDepths();
                },

                moveUp(index) {
                    if (index === 0) return;
                    let temp = this.items[index];
                    this.items[index] = this.items[index - 1];
                    this.items[index - 1] = temp;
                    this.normalizeDepths();
                },

                moveDown(index) {
                    if (index === this.items.length - 1) return;
                    let temp = this.items[index];
                    this.items[index] = this.items[index + 1];
                    this.items[index + 1] = temp;
                    this.normalizeDepths();
                },

                indent(index) {
                    if (index === 0) return;
                    let prevItem = this.items[index - 1];
                    if (this.items[index].depth <= prevItem.depth && this.items[index].depth < 2) {
                        this.items[index].depth++;
                    }
                },

                outdent(index) {
                    if (this.items[index].depth > 0) {
                        this.items[index].depth--;
                        this.normalizeDepths();
                    }
                },

                normalizeDepths() {
                    let prevDepth = 0;
                    this.items.forEach((item) => {
                        if (item.depth > prevDepth + 1) {
                            item.depth = prevDepth + 1;
                        }
                        prevDepth = item.depth;
                    });
                },

                buildTree() {
                    this.normalizeDepths();
                    let tree = [];
                    let stack = [];

                    for (let i = 0; i < this.items.length; i++) {
                        let item = {
                            id: this.items[i].id,
                            title: this.items[i].title,
                            type: this.items[i].type,
                            reference_type: this.items[i].reference_type,
                            reference_id: this.items[i].reference_id,
                            url: this.items[i].url,
                            target: this.items[i].target,
                            sort_order: i,
                            children: []
                        };

                        let depth = this.items[i].depth;

                        if (depth === 0) {
                            tree.push(item);
                            stack[0] = item;
                        } else {
                            let parent = stack[depth - 1];
                            if (parent) {
                                parent.children.push(item);
                                stack[depth] = item;
                            } else {
                                tree.push(item);
                                stack[0] = item;
                            }
                        }
                    }
                    return tree;
                },

                submitForm(event) {
                    // Letting standard form submit continue after hidden items_json is updated
                }
            }));
        });
    </script>
</x-app-layout>
