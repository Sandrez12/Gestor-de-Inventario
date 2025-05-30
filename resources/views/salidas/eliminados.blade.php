<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Salidas Eliminadas</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('salidas.index') }}" class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
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
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>Cantidad</th>
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
                            <td class="flex justify-center gap-2 py-2">
                                <form action="{{ route('salidas.restore', $salida->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Restaurar</button>
                                </form>

                                <form action="{{ route('salidas.forceDelete', $salida->id) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($salidas->isEmpty())
                        <tr>
                            <td colspan="5" class="py-4">No hay salidas eliminadas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
