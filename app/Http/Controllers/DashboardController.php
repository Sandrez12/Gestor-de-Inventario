<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Salida;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $inicioMes = now()->startOfMonth();

        $movimientos = Entrada::with(['producto', 'proveedor'])->latest()->limit(6)->get()
            ->map(fn (Entrada $e) => ['tipo' => 'entrada', 'registro' => $e, 'tercero' => $e->proveedor?->nombre])
            ->concat(
                Salida::with(['producto', 'cliente'])->latest()->limit(6)->get()
                    ->map(fn (Salida $s) => ['tipo' => 'salida', 'registro' => $s, 'tercero' => $s->cliente?->nombre])
            )
            ->sortByDesc(fn (array $m) => $m['registro']->created_at)
            ->take(6)
            ->values();

        return view('dashboard', [
            'totalProductos' => Producto::count(),
            'unidades' => (int) Producto::sum('stock'),
            'stockBajo' => Producto::stockBajo()->orderBy('stock')->limit(6)->get(),
            'totalStockBajo' => Producto::stockBajo()->count(),
            'entradasMes' => (int) Entrada::where('created_at', '>=', $inicioMes)->sum('cantidad'),
            'salidasMes' => (int) Salida::where('created_at', '>=', $inicioMes)->sum('cantidad'),
            'movimientos' => $movimientos,
        ]);
    }
}
