<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Media Manager') }}
            </h2>
            <div class="flex space-x-2">
                <a href="#upload-modal" class="btn btn-primary btn-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload File
                </a>
                <a href="#folder-modal" class="btn btn-secondary btn-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    New Folder
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success mb-6 bg-emerald-50 border-emerald-200 text-emerald-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error mb-6 bg-rose-50 border-rose-200 text-rose-800 p-4 rounded-lg flex items-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Navigation / Breadcrumbs -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                <div class="text-sm breadcrumbs py-0 text-gray-600">
                    <ul class="flex items-center space-x-2">
                        <li>
                            <a href="{{ route('admin.media.index') }}" class="hover:text-blue-600 font-medium">Root</a>
                        </li>
                        @if($currentFolder)
                            <li class="flex items-center space-x-2">
                                <span class="text-gray-400">/</span>
                                <span class="font-semibold text-gray-800">{{ $currentFolder->name }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
                @if($currentFolder)
                    <a href="{{ route('admin.media.index', ['folder_id' => $parentFolderId]) }}" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50">
                        ➔ Back to Parent
                    </a>
                @endif
            </div>

            <!-- Folders Section -->
            @if($folders->isNotEmpty())
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Folders</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 mb-8">
                    @foreach($folders as $folder)
                        <div class="group relative bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition duration-200 flex flex-col items-center text-center">
                            <a href="{{ route('admin.media.index', ['folder_id' => $folder->id]) }}" class="w-full flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-amber-400 group-hover:text-amber-500 transition" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                                <span class="mt-2 text-sm font-medium text-gray-700 truncate w-full px-1">{{ $folder->name }}</span>
                            </a>
                            
                            <!-- Delete Folder Action -->
                            <form action="{{ route('admin.media.folder.destroy', $folder->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-150">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete folder and all its contents?')" class="p-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-md transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Media Section -->
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Files</h3>
            @if($media->isEmpty())
                <div class="bg-white p-12 rounded-xl text-center border border-dashed border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium">No files uploaded yet.</p>
                    <p class="text-xs text-gray-400 mt-1">Upload images or documents to populate this folder.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-6">
                    @foreach($media as $item)
                        @php
                            $isImage = str_starts_with($item->mime_type, 'image/');
                            $service = app(\App\Modules\Media\Contracts\MediaServiceInterface::class);
                            $fileUrl = $service->getUrl($item->id);
                            $thumbUrl = $isImage ? $service->getUrl($item->id, 'thumbnail') : null;
                        @endphp
                        <div class="group relative bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition duration-200 flex flex-col justify-between">
                            <!-- Preview Area -->
                            <div class="aspect-square bg-gray-50 flex items-center justify-center relative overflow-hidden">
                                @if($isImage)
                                    <img src="{{ $thumbUrl }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
                                @else
                                    <!-- Document Icons -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                @endif
                                
                                <!-- Hover Actions Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 transition duration-200 flex items-center justify-center space-x-2">
                                    <a href="{{ $fileUrl }}" target="_blank" class="p-2 bg-white text-gray-700 hover:bg-gray-100 rounded-lg shadow transition" title="View/Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this file?')" class="p-2 bg-white text-rose-600 hover:bg-rose-50 rounded-lg shadow transition" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- File Meta Info -->
                            <div class="p-3 bg-white border-t border-gray-50">
                                <p class="text-xs font-semibold text-gray-700 truncate" title="{{ $item->original_name }}">
                                    {{ $item->original_name }}
                                </p>
                                <div class="flex items-center justify-between mt-1 text-[10px] text-gray-400">
                                    <span>{{ $item->extension }}</span>
                                    <span>{{ number_format($item->size / 1024, 1) }} KB</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="upload-modal" class="modal bg-black bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none target:opacity-100 target:pointer-events-auto transition-opacity duration-200">
        <div class="modal-box bg-white p-6 rounded-xl shadow-xl max-w-md w-full relative">
            <h3 class="font-bold text-lg text-gray-800 mb-4">Upload File</h3>
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($currentFolder)
                    <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
                @endif
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Choose File</label>
                    <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-md p-1" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Caption</label>
                    <input type="text" name="caption" class="w-full text-sm border border-gray-200 rounded-md p-2 focus:ring focus:ring-blue-100 focus:border-blue-500" placeholder="Optional description">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alt Text</label>
                    <input type="text" name="alt_text" class="w-full text-sm border border-gray-200 rounded-md p-2 focus:ring focus:ring-blue-100 focus:border-blue-500" placeholder="Alt description for accessibility">
                </div>
                <div class="flex justify-end space-x-2">
                    <a href="#" class="btn btn-ghost btn-sm text-gray-500 hover:bg-gray-100">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Folder Modal -->
    <div id="folder-modal" class="modal bg-black bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none target:opacity-100 target:pointer-events-auto transition-opacity duration-200">
        <div class="modal-box bg-white p-6 rounded-xl shadow-xl max-w-md w-full relative">
            <h3 class="font-bold text-lg text-gray-800 mb-4">Create New Folder</h3>
            <form action="{{ route('admin.media.folder.create') }}" method="POST">
                @csrf
                @if($currentFolder)
                    <input type="hidden" name="parent_id" value="{{ $currentFolder->id }}">
                @endif
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Folder Name</label>
                    <input type="text" name="name" class="w-full text-sm border border-gray-200 rounded-md p-2 focus:ring focus:ring-blue-100 focus:border-blue-500" placeholder="Folder name" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <a href="#" class="btn btn-ghost btn-sm text-gray-500 hover:bg-gray-100">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">Create</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
