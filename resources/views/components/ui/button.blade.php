@props(['href' => null, 'variant' => 'primary', 'type' => 'button'])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';
    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus-visible:ring-indigo-600',
        'secondary' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus-visible:ring-gray-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:ring-red-600',
        'ghost' => 'text-indigo-700 hover:bg-indigo-50 focus-visible:ring-indigo-600',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus-visible:ring-emerald-600',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
