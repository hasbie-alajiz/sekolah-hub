<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kategori Berita') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kategori
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

            <!-- Search Panel -->
            <div class="bg-white p-4 mb-6 rounded-lg shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center justify-between">
                <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                    <div class="form-control">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="input input-bordered input-sm w-64 text-sm focus:ring-primary focus:border-primary" />
                    </div>
                    <button type="submit" class="btn btn-ghost btn-sm">Filter</button>
                    @if(request()->filled('search'))
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-link btn-sm text-gray-500">Reset</a>
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
                                    <th>Nama Kategori</th>
                                    <th>Parent</th>
                                    <th>Slug</th>
                                    <th>Deskripsi</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="py-4 font-semibold text-gray-900">
                                            {{ $category->name }}
                                        </td>
                                        <td class="py-4 text-gray-600 text-sm">
                                            {{ $category->parent->name ?? '-' }}
                                        </td>
                                        <td class="py-4 text-gray-500 text-sm">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="py-4 text-gray-500 text-sm max-w-xs truncate">
                                            {{ $category->description ?? '-' }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-ghost btn-xs text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus kategori ini? (Kategori anak akan kehilangan parent-nya)')" class="btn btn-ghost btn-xs text-rose-600 hover:text-rose-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-400 text-sm">
                                            Belum ada kategori yang dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
