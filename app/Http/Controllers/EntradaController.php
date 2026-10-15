<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\MovimientoRequest;
use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Services\StockService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EntradaController extends Controller
{
    public function __construct(private readonly StockService $stock) {}

    public function index(): View
    {
        $registros = Entrada::with(['producto', 'proveedor'])->latest()->paginate(10);

        return view('entradas.index', compact('registros'));
    }

    public function create(): View
    {
        return view('entradas.create', $this->opciones());
    }

    public function store(MovimientoRequest $request): RedirectResponse
    {
        $this->stock->registrarEntrada($request->validated());

        return redirect()->route('entradas.index')->with('success', 'Entrada registrada y stock actualizado.');
    }

    public function edit(Entrada $entrada): View
    {
        return view('entradas.edit', $this->opciones() + ['entrada' => $entrada]);
    }

    public function update(MovimientoRequest $request, Entrada $entrada): RedirectResponse
    {
        try {
            $this->stock->actualizarEntrada($entrada, $request->validated());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->withErrors(['cantidad' => $e->getMessage()]);
        }

        return redirect()->route('entradas.index')->with('success', 'Entrada actualizada y stock ajustado.');
    }

    public function destroy(Entrada $entrada): RedirectResponse
    {
        try {
            $this->stock->eliminarEntrada($entrada);
        } catch (InsufficientStockException $e) {
            return back()->withErrors(['stock' => $e->getMessage()]);
        }

        return redirect()->route('entradas.index')->with('success', 'Entrada eliminada y stock ajustado.');
    }

    public function eliminados(): View
    {
        $registros = Entrada::onlyTrashed()->with(['producto', 'proveedor'])->latest('deleted_at')->paginate(10);

        return view('entradas.eliminados', compact('registros'));
    }

    public function restore(int $id): RedirectResponse
    {
        $this->stock->restaurarEntrada(Entrada::onlyTrashed()->findOrFail($id));

        return redirect()->route('entradas.index')->with('success', 'Entrada restaurada y stock ajustado.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        Entrada::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('entradas.eliminados')->with('success', 'Entrada eliminada de forma permanente.');
    }

    /** @return array<string, mixed> */
    private function opciones(): array
    {
        return [
            'productos' => Producto::orderBy('nombre')->get(['id', 'nombre']),
            'proveedores' => Proveedor::orderBy('nombre')->get(['id', 'nombre']),
        ];
    }
}
