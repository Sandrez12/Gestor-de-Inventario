<x-ui.page title="Editar producto">
    <form method="POST" action="{{ route('productos.update', $producto) }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        @include('productos._form', ['producto' => $producto])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Guardar cambios</x-ui.button>
            <x-ui.button :href="route('productos.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
