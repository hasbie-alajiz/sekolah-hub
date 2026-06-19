@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, outline, ghost
    'size' => 'md', // sm, md, lg
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none rounded-full text-[13px] gap-1.5';
    
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 border border-transparent shadow-sm',
        'secondary' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 shadow-sm focus:ring-blue-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 border border-transparent shadow-sm',
        'outline' => 'bg-transparent border border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
        'ghost' => 'bg-transparent text-gray-600 hover:bg-gray-100 focus:ring-gray-500',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-5 py-2',
        'lg' => 'px-6 py-2.5 text-sm',
    ];
    
    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
