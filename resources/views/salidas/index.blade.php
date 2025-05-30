<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Salidas</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('salidas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            Registrar Salida
        </a>
        <a href="{{ route('salidas.eliminados') }}" class="ml-2 bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
            Ver Eliminadas
        </a>
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>
        
        <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salidas as $salida)
                        <tr class="border-t">
                            <td>{{ $salida->id }}</td>
                            <td>{{ $salida->producto->nombre }}</td>
                            <td>{{ $salida->cliente->nombre }}</td>
                            <td>{{ $salida->cantidad }}</td>
                            <td>{{ $salida->created_at->format('d/m/Y') }}</td>
                            <td class="flex justify-center gap-2 py-2">
                                <a href="{{ route('salidas.edit', $salida) }}" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Editar</a>
                                <form action="{{ route('salidas.destroy', $salida) }}" method="POST" onsubmit="return confirm('¿Eliminar esta salida?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($salidas->isEmpty())
                        <tr>
                            <td colspan="6" class="py-4">No hay salidas registradas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
