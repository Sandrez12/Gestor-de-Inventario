<x-ui.page title="Editar cliente">
    <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        @include('clientes._form', ['cliente' => $cliente])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Guardar cambios</x-ui.button>
            <x-ui.button :href="route('clientes.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
