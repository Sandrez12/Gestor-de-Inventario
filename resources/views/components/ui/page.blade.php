@props(['title', 'subtitle' => null])

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-ink-900">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-2">
                {{ $actions ?? '' }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <x-ui.flash />
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
