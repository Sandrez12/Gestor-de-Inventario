<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Proveedor</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>
        
        <form method="POST" action="{{ route('proveedores.store') }}" class="bg-white p-6 rounded shadow-md">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700">Nombre</label>
                <input type="text" name="nombre" required class="w-full mt-1 p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Teléfono</label>
                <input type="text" name="telefono" required class="w-full mt-1 p-2 border rounded">
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Guardar</button>
            <a href="{{ route('proveedores.index') }}" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
        </form>
    </div>
</x-app-layout>
