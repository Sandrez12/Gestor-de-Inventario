<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\MovimientoRequest;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Salida;
use App\Services\StockService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SalidaController extends Controller
{
    public function __construct(private readonly StockService $stock) {}

    public function index(): View
    {
        $registros = Salida::with(['producto', 'cliente'])->latest()->paginate(10);

        return view('salidas.index', compact('registros'));
    }

    public function create(): View
    {
        return view('salidas.create', $this->opciones());
    }

    public function store(MovimientoRequest $request): RedirectResponse
    {
        try {
            $this->stock->registrarSalida($request->validated());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->withErrors(['cantidad' => $e->getMessage()]);
        }

        return redirect()->route('salidas.index')->with('success', 'Salida registrada y stock actualizado.');
    }

    public function edit(Salida $salida): View
    {
        return view('salidas.edit', $this->opciones() + ['salida' => $salida]);
    }

    public function update(MovimientoRequest $request, Salida $salida): RedirectResponse
    {
        try {
            $this->stock->actualizarSalida($salida, $request->validated());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->withErrors(['cantidad' => $e->getMessage()]);
        }

        return redirect()->route('salidas.index')->with('success', 'Salida actualizada y stock ajustado.');
    }

    public function destroy(Salida $salida): RedirectResponse
    {
        $this->stock->eliminarSalida($salida);

        return redirect()->route('salidas.index')->with('success', 'Salida eliminada y stock restaurado.');
    }

    public function eliminados(): View
    {
        $registros = Salida::onlyTrashed()->with(['producto', 'cliente'])->latest('deleted_at')->paginate(10);

        return view('salidas.eliminados', compact('registros'));
    }

    public function restore(int $id): RedirectResponse
    {
        try {
            $this->stock->restaurarSalida(Salida::onlyTrashed()->findOrFail($id));
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['restore' => $e->getMessage()]);
        }

        return redirect()->route('salidas.index')->with('success', 'Salida restaurada y stock ajustado.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        Salida::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('salidas.eliminados')->with('success', 'Salida eliminada de forma permanente.');
    }

    /** @return array<string, mixed> */
    private function opciones(): array
    {
        return [
            'productos' => Producto::orderBy('nombre')->get(['id', 'nombre', 'stock']),
            'clientes' => Cliente::orderBy('nombre')->get(['id', 'nombre']),
        ];
    }
}
