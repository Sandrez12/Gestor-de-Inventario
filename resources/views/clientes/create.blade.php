<x-ui.page title="Nuevo cliente">
    <form method="POST" action="{{ route('clientes.store') }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @include('clientes._form', ['cliente' => null])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Guardar</x-ui.button>
            <x-ui.button :href="route('clientes.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
