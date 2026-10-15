@props(['tone' => 'gray'])

@php
    $tones = [
        'gray' => 'bg-gray-100 text-gray-700',
        'green' => 'bg-emerald-100 text-emerald-800',
        'red' => 'bg-red-100 text-red-800',
        'amber' => 'bg-amber-100 text-amber-800',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold '.($tones[$tone] ?? $tones['gray'])]) }}>{{ $slot }}</span>
