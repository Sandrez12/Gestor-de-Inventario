<x-ui.page title="Editar proveedor">
    <form method="POST" action="{{ route('proveedores.update', $proveedor) }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        @include('proveedores._form', ['proveedor' => $proveedor])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Guardar cambios</x-ui.button>
            <x-ui.button :href="route('proveedores.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
