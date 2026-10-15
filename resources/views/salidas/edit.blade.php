<x-ui.page title="Editar salida" subtitle="Al guardar, el stock se recalcula con los nuevos datos.">
    <form method="POST" action="{{ route('salidas.update', $salida) }}" class="max-w-2xl space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @csrf @method('PUT')
        @include('salidas._form', ['salida' => $salida])

        <div class="flex items-center gap-3 pt-2">
            <x-ui.button type="submit">Guardar cambios</x-ui.button>
            <x-ui.button :href="route('salidas.index')" variant="secondary">Cancelar</x-ui.button>
        </div>
    </form>
</x-ui.page>
