<x-ui.page title="Registrar salida" subtitle="El stock del producto disminuirá; no puede quedar negativo.">
    <form method="POST" action="{{ route('salidas.store') }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @include('salidas._form', ['salida' => null])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Registrar</x-ui.button>
            <x-ui.button :href="route('salidas.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
