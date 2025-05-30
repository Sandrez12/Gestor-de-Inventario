<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Producto</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>        

        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')    
            <div class="mb-4">
                <label class="block text-gray-700">Nombre</label>
                <input type="text" name="nombre" value="{{ $producto->nombre }}" required class="w-full mt-1 p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Descripción</label>
                <textarea name="descripcion" rows="3" required class="w-full mt-1 p-2 border rounded">{{ $producto->descripcion }}</textarea>
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-gray-700 font-bold mb-2">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $producto->stock) }}"
                    class="w-full px-3 py-2 border rounded">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Actualizar
            </button>
            <a href="{{ route('productos.index') }}" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
        </form>
    </div>
</x-app-layout>
