<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
    public function index()
    {
        $entradas = Entrada::with(['producto', 'proveedor'])->get();
        return view('entradas.index', compact('entradas'));
    }

    public function create()
    {
        $productos = Producto::all();
        $proveedores = Proveedor::all();
        return view('entradas.create', compact('productos', 'proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $entrada = Entrada::create($request->all());

        // Aumentar el stock del producto
        $producto = Producto::findOrFail($request->producto_id);
        $producto->stock += $request->cantidad;
        $producto->save();

        return redirect()->route('entradas.index')->with('success', 'Entrada registrada y stock actualizado.');
    }

    public function edit(Entrada $entrada)
    {
        $productos = Producto::all();
        $proveedores = Proveedor::all();
        return view('entradas.edit', compact('entrada', 'productos', 'proveedores'));
    }

    public function update(Request $request, Entrada $entrada)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Revertir stock anterior
        $productoAnterior = Producto::findOrFail($entrada->producto_id);
        $productoAnterior->stock -= $entrada->cantidad;
        $productoAnterior->save();

        // Actualizar entrada
        $entrada->update($request->all());

        // Aplicar nuevo stock
        $productoNuevo = Producto::findOrFail($request->producto_id);
        $productoNuevo->stock += $request->cantidad;
        $productoNuevo->save();

        return redirect()->route('entradas.index')->with('success', 'Entrada actualizada y stock ajustado.');
    }

    public function destroy(Entrada $entrada)
    {
        // Restar stock antes de eliminar
        $producto = Producto::findOrFail($entrada->producto_id);
        $producto->stock -= $entrada->cantidad;
        $producto->save();

        $entrada->delete();

        return redirect()->route('entradas.index')->with('success', 'Entrada eliminada y stock ajustado.');
    }

    public function eliminados()
    {
        $entradas = Entrada::onlyTrashed()->with(['producto', 'proveedor'])->get();
        return view('entradas.eliminados', compact('entradas'));
    }

    public function restore($id)
    {
        $entrada = Entrada::onlyTrashed()->findOrFail($id);
        $entrada->restore();
        // Volver a sumar stock
        $producto = Producto::findOrFail($entrada->producto_id);
        $producto->stock += $entrada->cantidad;
        $producto->save();

        return redirect()->route('entradas.index')->with('success', 'Entrada restaurada y stock ajustado.');
    }

    public function forceDelete($id)
    {
        $entrada = Entrada::onlyTrashed()->get();
        $entrada->forceDelete();

        return redirect()->route('entradas.eliminados')->with('success', 'Entrada eliminada permanentemente.');
    }
}
