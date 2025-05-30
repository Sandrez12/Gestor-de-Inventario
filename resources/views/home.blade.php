{{-- resources/views/home.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bienvenido, {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Gestión de módulos</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li><a href="{{ route('productos.index') }}" class="text-blue-600 hover:underline">Productos</a></li>
                    <li><a href="{{ route('clientes.index') }}" class="text-blue-600 hover:underline">Clientes</a></li>
                    <li><a href="{{ route('proveedores.index') }}" class="text-blue-600 hover:underline">Proveedores</a></li>
                    <li><a href="{{ route('entradas.index') }}" class="text-blue-600 hover:underline">Entradas</a></li>
                    <li><a href="{{ route('salidas.index') }}" class="text-blue-600 hover:underline">Salidas</a></li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
