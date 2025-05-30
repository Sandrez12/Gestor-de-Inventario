<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Producto</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <div>
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" required>
            </div>
        
            <div>
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" required></textarea>
            </div>
        
            <div>
                <label for="stock">Stock</label>
                <input type="number" name="stock" id="stock" required>
            </div>
        
            <button type="submit">Guardar</button>
        </form>        
    </div>
</x-app-layout>
