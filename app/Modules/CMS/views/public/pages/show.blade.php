<x-public-layout>
    @section('title', $page->seo_title ?: $page->title . ' - ' . config('app.name', 'Sekolah Hub'))

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <header class="mb-8 border-b border-gray-100 pb-4">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">
                {{ $page->title }}
            </h1>
        </header>

        <!-- Featured Image -->
        @if($page->featured_image_url)
            <div class="mb-10 aspect-video rounded-2xl overflow-hidden bg-gray-100 border border-gray-100 shadow-sm">
                <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover" />
            </div>
        @endif

        <!-- Page Content -->
        <div class="prose max-w-none text-gray-800 leading-relaxed text-base">
            {!! $page->content !!}
        </div>
    </div>
</x-public-layout>
