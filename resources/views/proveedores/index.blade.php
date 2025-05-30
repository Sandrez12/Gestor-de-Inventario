<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proveedores</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('proveedores.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            Crear Proveedor
        </a>
        <a href="{{ route('proveedores.eliminados') }}" class="ml-2 bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
            Ver Eliminados
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
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proveedores as $proveedor)
                        <tr class="border-t">
                            <td>{{ $proveedor->id }}</td>
                            <td>{{ $proveedor->nombre }}</td>
                            <td>{{ $proveedor->telefono }}</td>
                            <td class="flex justify-center gap-2 py-2">
                                <a href="{{ route('proveedores.edit', $proveedor) }}" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Editar</a>

                                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" onsubmit="return confirm('¿Eliminar este proveedor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($proveedores->isEmpty())
                        <tr>
                            <td colspan="4" class="py-4">No hay proveedores registrados.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
