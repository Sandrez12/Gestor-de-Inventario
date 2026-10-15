<x-ui.page title="Registrar entrada" subtitle="El stock del producto aumentará.">
    <form method="POST" action="{{ route('entradas.store') }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @include('entradas._form', ['entrada' => null])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Registrar</x-ui.button>
            <x-ui.button :href="route('entradas.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
