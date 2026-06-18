<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Berita') }}
            </h2>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Berita
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filter Panel -->
            <div class="bg-white p-4 mb-6 rounded-lg shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center justify-between">
                <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                    <div class="form-control">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="input input-bordered input-sm w-64 text-sm focus:ring-primary focus:border-primary" />
                    </div>
                    <div class="form-control">
                        <select name="category_id" class="select select-bordered select-sm text-sm focus:ring-primary focus:border-primary" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-ghost btn-sm">Filter</button>
                    @if(request()->filled('search') || request()->filled('category_id'))
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-link btn-sm text-gray-500">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-gray-500 text-sm border-b border-gray-200">
                                    <th>Judul Berita</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($posts as $post)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-4">
                                            <div class="font-semibold text-gray-900">{{ $post->title }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Slug: {{ $post->slug }}</div>
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            {{ $post->author->name ?? '-' }}
                                        </td>
                                        <td class="py-4">
                                            @forelse($post->categories as $category)
                                                <span class="badge badge-sm badge-outline badge-primary mr-1">{{ $category->name }}</span>
                                            @empty
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endforelse
                                        </td>
                                        <td class="py-4">
                                            @if($post->status === 'published')
                                                <span class="badge badge-sm bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-0.5 font-medium rounded-full">Diterbitkan</span>
                                            @elseif($post->status === 'draft')
                                                <span class="badge badge-sm bg-amber-50 border border-amber-200 text-amber-700 px-2.5 py-0.5 font-medium rounded-full">Draft</span>
                                            @else
                                                <span class="badge badge-sm bg-gray-100 border border-gray-200 text-gray-600 px-2.5 py-0.5 font-medium rounded-full">Diarsipkan</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-sm text-gray-500">
                                            @if($post->published_at)
                                                {{ $post->published_at->format('d M Y H:i') }}
                                            @else
                                                <span class="italic text-gray-400">Belum publish</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus berita ini?')" class="btn btn-ghost btn-xs text-rose-600 hover:text-rose-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-400 text-sm">
                                            Belum ada berita yang ditulis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
