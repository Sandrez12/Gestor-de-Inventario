<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoLoginController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SalidaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

if (config('app.demo')) {
    Route::get('/demo', DemoLoginController::class)->name('demo');
}

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::redirect('/home', '/dashboard')->name('home');

    $papelera = function (string $recurso, string $controlador, ?string $parametro = null) {
        $resource = Route::resource($recurso, $controlador)->except(['show']);
        if ($parametro) {
            $resource->parameters([$recurso => $parametro]);
        }
        Route::get("$recurso/eliminados", [$controlador, 'eliminados'])->name("$recurso.eliminados");
        Route::post("$recurso/{id}/restore", [$controlador, 'restore'])->whereNumber('id')->name("$recurso.restore");
        Route::delete("$recurso/{id}/force", [$controlador, 'forceDelete'])->whereNumber('id')->name("$recurso.forceDelete");
    };

    // Cada módulo: CRUD + papelera (soft delete) con restauración y borrado definitivo.
    $papelera('productos', ProductoController::class);
    $papelera('clientes', ClienteController::class);
    $papelera('proveedores', ProveedorController::class, 'proveedor');
    $papelera('entradas', EntradaController::class);
    $papelera('salidas', SalidaController::class);
});
