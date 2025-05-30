<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SalidaController;

Route::get('/', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/home', function () {
    return view('home');
})->name('home');

Route::middleware(['auth'])->group(function () {
    // Productos
    Route::resource('productos', ProductoController::class)->except(['show']);
    Route::get('productos/eliminados', [ProductoController::class, 'eliminados'])->name('productos.eliminados');
    Route::post('productos/{id}/restore', [ProductoController::class, 'restore'])->name('productos.restore');
    Route::put('productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('productos/{id}/force', [ProductoController::class, 'forceDelete'])->name('productos.forceDelete');

    // Clientes
    Route::resource('clientes', ClienteController::class)->except(['show']);
    Route::get('clientes/eliminados', [ClienteController::class, 'eliminados'])->name('clientes.eliminados');
    Route::post('clientes/{id}/restore', [ClienteController::class, 'restore'])->name('clientes.restore');
    Route::delete('clientes/{id}/force', [ClienteController::class, 'forceDelete'])->name('clientes.forceDelete');

    // Proveedores
    Route::resource('proveedores', ProveedorController::class)->except(['show'])->parameters([
        'proveedores' => 'proveedor'
    ]);
    Route::get('proveedores/eliminados', [ProveedorController::class, 'eliminados'])->name('proveedores.eliminados');
    Route::post('proveedores/{id}/restore', [ProveedorController::class, 'restore'])->name('proveedores.restore');
    Route::delete('proveedores/{id}/force', [ProveedorController::class, 'forceDelete'])->name('proveedores.forceDelete');

    // Entradas y salidas (si los necesitas)
    Route::resource('entradas', EntradaController::class)->except(['show']);
    Route::get('entradas/eliminados', [EntradaController::class, 'eliminados'])->name('entradas.eliminados');
    Route::post('entradas/{id}/restore', [EntradaController::class, 'restore'])->name('entradas.restore');
    Route::delete('entradas/{id}/force', [EntradaController::class, 'forceDelete'])->name('entradas.forceDelete');

    Route::resource('salidas', SalidaController::class)->except(['show']);
    Route::get('salidas/eliminados', [SalidaController::class, 'eliminados'])->name('salidas.eliminados');
    Route::post('salidas/{id}/restaurar', [SalidaController::class, 'restaurar'])->name('salidas.restaurar');
    Route::delete('salidas/{id}/force', [SalidaController::class, 'forceDelete'])->name('salidas.forceDelete');
});

// require __DIR__.'/auth.php';
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Tus rutas protegidas aquí
});
