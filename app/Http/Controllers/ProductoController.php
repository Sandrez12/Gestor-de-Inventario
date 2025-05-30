<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);
    
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'stock' => $request->stock,
        ]);
    
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }
    
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock' => 'required|integer',
        ]);
    
        $producto->update($request->all());
    
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }
      
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }

    public function eliminados()
    {
        $productos = Producto::onlyTrashed()->get();
        return view('productos.eliminados', compact('productos'));
    }

    public function restore($id)
    {
        $producto = Producto::onlyTrashed()->findOrFail($id);
        $producto->restore();
        return redirect()->route('productos.index')->with('success', 'Producto restaurado.');
        
    }

    public function forceDelete($id)
    {
        $producto = Producto::onlyTrashed()->findOrFail($id);
        $producto->forceDelete();
        return redirect()->route('productos.eliminados')->with('success', 'Producto eliminado permanentemente.');
    }
}
