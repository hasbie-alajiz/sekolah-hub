<x-public-layout>
    @section('title', $post->seo_title ?: $post->title . ' - ' . config('app.name', 'Sekolah Hub'))

    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Post Header -->
        <header class="mb-8 text-center">
            <div class="flex items-center justify-center gap-2 mb-4">
                @foreach($post->categories as $category)
                    <a href="{{ route('public.categories.show', $category->slug) }}" class="badge badge-primary badge-outline text-xs px-2.5 py-0.5 rounded-full font-semibold">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
            
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight mb-4">
                {{ $post->title }}
            </h1>

            <div class="text-sm text-gray-500 flex items-center justify-center gap-3">
                <span>Oleh: <strong class="text-gray-700">{{ $post->author->name ?? 'Admin' }}</strong></span>
                <span>&bull;</span>
                <span>{{ $post->published_at ? $post->published_at->format('d M Y H:i') : $post->created_at->format('d M Y H:i') }}</span>
            </div>
        </header>

        <!-- Featured Image -->
        @if($post->featured_image_url)
            <div class="mb-10 aspect-video rounded-2xl overflow-hidden bg-gray-100 border border-gray-100 shadow-sm">
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover" />
            </div>
        @endif

        <!-- Post Body Content -->
        <div class="prose max-w-none text-gray-800 leading-relaxed text-base">
            {!! $post->content !!}
        </div>
    </article>
</x-public-layout>
