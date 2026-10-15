<x-ui.page title="Papelera de productos" subtitle="Los registros eliminados se pueden restaurar o borrar de forma definitiva.">
    <x-slot name="actions">
        <x-ui.button :href="route('productos.index')" variant="secondary">← Volver al listado</x-ui.button>
    </x-slot>

    <x-ui.table>
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th scope="col" class="px-4 py-3 ">Producto</th>
                    <th scope="col" class="px-4 py-3 ">Estado</th>
                    <th scope="col" class="px-4 py-3 text-right">Stock</th>
                    <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($registros as $r)
                <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $r->nombre }}</td>
                        <td class="px-4 py-3 text-gray-500">Eliminado {{ $r->deleted_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right tabular-nums">{{ $r->stock }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('productos.restore', $r->id) }}">
                                    @csrf
                                    <x-ui.button type="submit" variant="success" class="!px-3 !py-1.5">Restaurar</x-ui.button>
                                </form>
                                <form method="POST" action="{{ route('productos.forceDelete', $r->id) }}" onsubmit="return confirm('Esto no se puede deshacer. ¿Eliminar definitivamente?');">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" class="!px-3 !py-1.5">Borrar definitivo</x-ui.button>
                                </form>
                            </div>
                        </td>
                </tr>
            @empty
                <x-ui.empty :cols="4" text="La papelera está vacía." />
            @endforelse
        </tbody>
    </x-ui.table>

    {{ $registros->links() }}
</x-ui.page>
