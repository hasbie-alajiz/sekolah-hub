<x-public-layout>
    @section('title', 'Kategori: ' . $category->name . ' - ' . config('app.name', 'Sekolah Hub'))

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <header class="mb-10 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Kategori: <span class="text-primary">{{ $category->name }}</span></h1>
            @if($category->description)
                <p class="text-gray-500 max-w-2xl">{{ $category->description }}</p>
            @endif
        </header>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @forelse($posts as $post)
                <div class="card bg-white shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <!-- Thumbnail -->
                        @if($post->featured_image_url)
                            <div class="aspect-video bg-gray-50 overflow-hidden border-b border-gray-50">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover" />
                            </div>
                        @else
                            <div class="aspect-video bg-gray-100 flex items-center justify-center border-b border-gray-50 text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif

                        <!-- Card Body -->
                        <div class="p-6">
                            <!-- Category Badge -->
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach($post->categories as $c)
                                    <span class="badge badge-xs badge-primary badge-outline">{{ $c->name }}</span>
                                @endforeach
                            </div>

                            <!-- Title -->
                            <h2 class="text-lg font-bold text-gray-900 hover:text-primary mb-2 line-clamp-2">
                                <a href="{{ route('public.posts.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>

                            <!-- Excerpt -->
                            <p class="text-gray-500 text-sm line-clamp-3 mb-4">
                                {{ $post->excerpt ?: strip_tags($post->content) }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                        <span>Oleh: {{ $post->author->name ?? 'Admin' }}</span>
                        <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400 bg-white border border-dashed rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2v3m2-3V9m0 0a2 2 0 012 2v7a2 2 0 01-2 2h-1m-1 4h.01m-4 0h.01m-3 0h.01m-3 0h.01" /></svg>
                    <p class="text-sm">Belum ada berita yang diterbitkan untuk kategori ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div>
            {{ $posts->links() }}
        </div>
    </div>
</x-public-layout>
