<x-ui.page title="Entradas" subtitle="Compras a proveedores: suman al stock.">
    <x-slot name="actions">
        <x-ui.button :href="route('entradas.eliminados')" variant="secondary">Papelera</x-ui.button>
        <x-ui.button :href="route('entradas.create')">Registrar entrada</x-ui.button>
    </x-slot>

    <x-ui.table>
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th scope="col" class="px-4 py-3 ">Fecha</th>
                    <th scope="col" class="px-4 py-3 ">Producto</th>
                    <th scope="col" class="px-4 py-3 ">Proveedor</th>
                    <th scope="col" class="px-4 py-3 text-right">Cantidad</th>
                    <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($registros as $r)
                <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $r->created_at->format("d/m/Y H:i") }}</td>
                        <td class="px-4 py-3 "><span class="font-semibold text-gray-900">{{ $r->producto?->nombre }}</span></td>
                        <td class="px-4 py-3 ">{{ $r->proveedor?->nombre }}</td>
                        <td class="px-4 py-3 text-right"><x-ui.badge tone="green">+{{ $r->cantidad }}</x-ui.badge></td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <x-ui.button :href="route('entradas.edit', $r)" variant="secondary" class="!px-3 !py-1.5">Editar</x-ui.button>
                                <form method="POST" action="{{ route('entradas.destroy', $r) }}" onsubmit="return confirm('¿Eliminar esta entrada? El stock se ajustará.');">
                                    @csrf @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" class="!px-3 !py-1.5">Eliminar</x-ui.button>
                                </form>
                            </div>
                        </td>
                </tr>
            @empty
                <x-ui.empty :cols="5" text="Aún no hay registros. ¡Crea el primero!" />
            @endforelse
        </tbody>
    </x-ui.table>

    {{ $registros->links() }}
</x-ui.page>
