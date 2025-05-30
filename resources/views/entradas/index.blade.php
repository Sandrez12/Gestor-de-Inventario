<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Entradas</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>    
        <a href="{{ route('entradas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            Registrar Entrada
        </a>
        <a href="{{ route('entradas.eliminados') }}" class="ml-2 bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
            Ver Eliminadas
        </a>
        

        <div class="mt-6 bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entradas as $entrada)
                        <tr class="border-t">
                            <td>{{ $entrada->id }}</td>
                            <td>{{ $entrada->producto->nombre }}</td>
                            <td>{{ $entrada->proveedor->nombre }}</td>
                            <td>{{ $entrada->cantidad }}</td>
                            <td>{{ $entrada->created_at->format('d/m/Y') }}</td>
                            <td class="flex justify-center gap-2 py-2">
                                <a href="{{ route('entradas.edit', $entrada) }}" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Editar</a>
                                <form action="{{ route('entradas.destroy', $entrada) }}" method="POST" onsubmit="return confirm('¿Eliminar esta entrada?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($entradas->isEmpty())
                        <tr>
                            <td colspan="6" class="py-4">No hay entradas registradas.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
