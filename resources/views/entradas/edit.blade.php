<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Entrada</h2>
    </x-slot>

    <div class="py-4 px-6">
        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            ← Volver al inicio
        </a>
        
        <form action="{{ route('entradas.update', $entrada) }}" method="POST" class="bg-white p-6 rounded shadow-md">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Producto</label>
                <select name="producto_id" required class="w-full mt-1 p-2 border rounded">
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}" @selected($producto->id == $entrada->producto_id)>{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Proveedor</label>
                <select name="proveedor_id" required class="w-full mt-1 p-2 border rounded">
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}" @selected($proveedor->id == $entrada->proveedor_id)>{{ $proveedor->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Cantidad</label>
                <input type="number" name="cantidad" value="{{ $entrada->cantidad }}" required class="w-full mt-1 p-2 border rounded">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Actualizar</button>
            <a href="{{ route('entradas.index') }}" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
        </form>
    </div>
</x-app-layout>
