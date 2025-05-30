<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }

    public function eliminados()
    {
        $clientes = Cliente::onlyTrashed()->get();
        return view('clientes.eliminados', compact('clientes'));
    }

    public function restore($id)
    {
        Cliente::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('clientes.index')->with('success', 'Cliente restaurado.');
    }

    public function forceDelete($id)
    {
        Cliente::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('clientes.eliminados')->with('success', 'Cliente eliminado permanentemente.');
    }
}
