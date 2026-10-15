<x-ui.page title="Productos" subtitle="Catálogo y existencias actuales.">
    <x-slot name="actions">
        <x-ui.button :href="route('productos.eliminados')" variant="secondary">Papelera</x-ui.button>
        <x-ui.button :href="route('productos.create')">Nuevo producto</x-ui.button>
    </x-slot>

    <x-ui.search :action="route('productos.index')" :q="$q ?? null" />

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
                        <td class="px-4 py-3 "><div class="font-semibold text-gray-900">{{ $r->nombre }}</div>
                            @if ($r->descripcion)<div class="max-w-md truncate text-xs text-gray-500">{{ $r->descripcion }}</div>@endif</td>
                        <td class="px-4 py-3 ">@if ($r->stock_bajo)
                                <x-ui.badge :tone="$r->stock === 0 ? 'red' : 'amber'">{{ $r->stock === 0 ? 'Agotado' : 'Stock bajo' }}</x-ui.badge>
                            @else
                                <x-ui.badge tone="green">Disponible</x-ui.badge>
                            @endif</td>
                        <td class="px-4 py-3 text-right"><span class="font-semibold tabular-nums">{{ $r->stock }}</span></td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <x-ui.button :href="route('productos.edit', $r)" variant="secondary" class="!px-3 !py-1.5">Editar</x-ui.button>
                                <form method="POST" action="{{ route('productos.destroy', $r) }}" onsubmit="return confirm('¿Enviar este producto a la papelera?');">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" class="!px-3 !py-1.5">Eliminar</x-ui.button>
                                </form>
                            </div>
                        </td>
                </tr>
            @empty
                <x-ui.empty :cols="4" text="Aún no hay registros. ¡Crea el primero!" />
            @endforelse
        </tbody>
    </x-ui.table>

    {{ $registros->links() }}
</x-ui.page>
