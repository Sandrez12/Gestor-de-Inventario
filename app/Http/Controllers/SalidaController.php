<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class SalidaController extends Controller
{
    public function index()
    {
        $salidas = Salida::with(['producto', 'cliente'])->get();
        return view('salidas.index', compact('salidas'));
    }

    public function create()
    {
        $productos = Producto::all();
        $clientes = Cliente::all();
        return view('salidas.create', compact('productos', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if ($request->cantidad > $producto->stock) {
            return redirect()->back()->withErrors(['cantidad' => 'No hay suficiente stock disponible.']);
        }

        // Registrar salida
        $salida = Salida::create($request->all());

        // Disminuir stock
        $producto->stock -= $request->cantidad;
        $producto->save();

        return redirect()->route('salidas.index')->with('success', 'Salida registrada y stock actualizado.');
    }

    public function edit(Salida $salida)
    {
        $productos = Producto::all();
        $clientes = Cliente::all();
        return view('salidas.edit', compact('salida', 'productos', 'clientes'));
    }

    public function update(Request $request, Salida $salida)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Revertir stock anterior
        $productoAnterior = Producto::findOrFail($salida->producto_id);
        $productoAnterior->stock += $salida->cantidad;
        $productoAnterior->save();

        // Validar nuevo stock disponible
        $productoNuevo = Producto::findOrFail($request->producto_id);
        if ($request->cantidad > $productoNuevo->stock) {
            return redirect()->back()->withErrors(['cantidad' => 'No hay suficiente stock disponible.']);
        }

        // Actualizar salida
        $salida->update($request->all());

        // Disminuir nuevo stock
        $productoNuevo->stock -= $request->cantidad;
        $productoNuevo->save();

        return redirect()->route('salidas.index')->with('success', 'Salida actualizada y stock ajustado.');
    }

    public function destroy(Salida $salida)
    {
        // Reponer stock
        $producto = Producto::findOrFail($salida->producto_id);
        $producto->stock += $salida->cantidad;
        $producto->save();

        $salida->delete();

        return redirect()->route('salidas.index')->with('success', 'Salida eliminada y stock restaurado.');
    }

    public function eliminados()
    {
        $salidas = Salida::onlyTrashed()->with(['producto', 'cliente'])->get();
        return view('salidas.eliminados', compact('salidas'));
    }

    public function restore($id)
    {
        $salida = Salida::onlyTrashed()->findOrFail($id);

        // Validar stock para restaurar
        $producto = Producto::findOrFail($salida->producto_id);
        if ($salida->cantidad > $producto->stock) {
            return redirect()->back()->withErrors(['restore' => 'No hay suficiente stock para restaurar la salida.']);
        }

        // Restaurar salida y ajustar stock
        $salida->restore();
        $producto->stock -= $salida->cantidad;
        $producto->save();

        return redirect()->route('salidas.index')->with('success', 'Salida restaurada y stock ajustado.');
    }

    public function forceDelete($id)
    {
        $salida = Salida::onlyTrashed()->findOrFail($id);
        $salida->forceDelete();

        return redirect()->route('salidas.eliminados')->with('success', 'Salida eliminada permanentemente.');
    }
}
