<x-ui.page title="Proveedores" subtitle="Gestiona tus proveedores.">
    <x-slot name="actions">
        <x-ui.button :href="route('proveedores.eliminados')" variant="secondary">Papelera</x-ui.button>
        <x-ui.button :href="route('proveedores.create')">Nuevo proveedor</x-ui.button>
    </x-slot>

    <x-ui.search :action="route('proveedores.index')" :q="$q ?? null" />

    <x-ui.table>
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th scope="col" class="px-4 py-3 ">Proveedor</th>
                    <th scope="col" class="px-4 py-3 ">Contacto</th>
                    <th scope="col" class="px-4 py-3 ">Dirección</th>
                    <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($registros as $r)
                <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 "><div class="font-semibold text-gray-900">{{ $r->nombre }}</div></td>
                        <td class="px-4 py-3 ">@if ($r->email)<div>{{ $r->email }}</div>@endif
                            @if ($r->telefono)<div class="text-xs text-gray-500">{{ $r->telefono }}</div>@endif</td>
                        <td class="px-4 py-3 text-gray-600">{{ $r->direccion ?: "—" }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <x-ui.button :href="route('proveedores.edit', $r)" variant="secondary" class="!px-3 !py-1.5">Editar</x-ui.button>
                                <form method="POST" action="{{ route('proveedores.destroy', $r) }}" onsubmit="return confirm('¿Enviar este proveedor a la papelera?');">
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
