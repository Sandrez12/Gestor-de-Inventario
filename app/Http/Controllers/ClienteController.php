<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UsaPapelera;
use App\Http\Requests\ContactoRequest;
use App\Models\Cliente;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    use UsaPapelera;

    protected function modelo(): string
    {
        return Cliente::class;
    }

    protected function prefijo(): string
    {
        return 'clientes';
    }

    protected function etiqueta(): string
    {
        return 'El cliente';
    }

    public function index(Request $request): View
    {
        $clientes = Cliente::buscar($request->query('q'))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', ['registros' => $clientes, 'q' => $request->query('q')]);
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(ContactoRequest $request): RedirectResponse
    {
        Cliente::create($request->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ContactoRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente enviado a la papelera.');
    }
}
