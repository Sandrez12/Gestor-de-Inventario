<x-ui.page title="Nuevo producto">
    <form method="POST" action="{{ route('productos.store') }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @include('productos._form', ['producto' => null])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Guardar</x-ui.button>
            <x-ui.button :href="route('productos.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
