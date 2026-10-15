<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UsaPapelera;
use App\Http\Requests\ContactoRequest;
use App\Models\Proveedor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    use UsaPapelera;

    protected function modelo(): string
    {
        return Proveedor::class;
    }

    protected function prefijo(): string
    {
        return 'proveedores';
    }

    protected function etiqueta(): string
    {
        return 'El proveedor';
    }

    public function index(Request $request): View
    {
        $proveedores = Proveedor::buscar($request->query('q'))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('proveedores.index', ['registros' => $proveedores, 'q' => $request->query('q')]);
    }

    public function create(): View
    {
        return view('proveedores.create');
    }

    public function store(ContactoRequest $request): RedirectResponse
    {
        Proveedor::create($request->validated());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    public function edit(Proveedor $proveedor): View
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(ContactoRequest $request, Proveedor $proveedor): RedirectResponse
    {
        $proveedor->update($request->validated());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor enviado a la papelera.');
    }
}
