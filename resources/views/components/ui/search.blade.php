@props(['action', 'q' => null, 'placeholder' => 'Buscar por nombre…'])

<form method="GET" action="{{ $action }}" role="search" class="flex w-full max-w-md gap-2">
    <label for="q" class="sr-only">Buscar</label>
    <input id="q" name="q" type="search" value="{{ $q }}" placeholder="{{ $placeholder }}"
           class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    <x-ui.button type="submit" variant="secondary">Buscar</x-ui.button>
    @if ($q)
        <x-ui.button :href="$action" variant="ghost">Limpiar</x-ui.button>
    @endif
</form>
