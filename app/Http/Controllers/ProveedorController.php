<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }

    public function eliminados()
    {
        $proveedores = Proveedor::onlyTrashed()->get();
        return view('proveedores.eliminados', compact('proveedores'));
    }

    public function restore($id)
    {
        Proveedor::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor restaurado.');
    }

    public function forceDelete($id)
    {
        Proveedor::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('proveedores.eliminados')->with('success', 'Proveedor eliminado permanentemente.');
    }
}
