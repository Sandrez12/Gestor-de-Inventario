<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Productos</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>
        
        <a href="{{ route('productos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            Crear Producto
        </a>
        <a href="{{ route('productos.eliminados') }}" class="ml-2 bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
            Ver Eliminados
        </a>
        
        <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto text-center">
                <thead class="bg-gray-100">
                    @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->descripcion }}</td>
                            <td>{{ $producto->stock }}</td>
                            <td class="flex justify-center gap-2 py-2">
                                <a href="{{ route('productos.edit', $producto) }}" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Editar</a>

                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($productos->isEmpty())
                        <tr>
                            <td colspan="6" class="py-4">No hay productos registrados.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
