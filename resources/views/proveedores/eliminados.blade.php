<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proveedores Eliminados</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('proveedores.index') }}" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
            Volver a la lista
        </a>
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>
        

        <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proveedores as $proveedor)
                        <tr class="border-t">
                            <td>{{ $proveedor->id }}</td>
                            <td>{{ $proveedor->nombre }}</td>
                            <td class="flex justify-center gap-2 py-2">
                                <form action="{{ route('proveedores.restore', $proveedor->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Restaurar</button>
                                </form>

                                <form action="{{ route('proveedores.forceDelete', $proveedor->id) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($proveedores->isEmpty())
                        <tr>
                            <td colspan="3" class="py-4">No hay proveedores eliminados.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
