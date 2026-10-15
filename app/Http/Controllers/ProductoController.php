<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UsaPapelera;
use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    use UsaPapelera;

    protected function modelo(): string
    {
        return Producto::class;
    }

    protected function prefijo(): string
    {
        return 'productos';
    }

    protected function etiqueta(): string
    {
        return 'El producto';
    }

    public function index(Request $request): View
    {
        $productos = Producto::buscar($request->query('q'))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('productos.index', ['registros' => $productos, 'q' => $request->query('q')]);
    }

    public function create(): View
    {
        return view('productos.create');
    }

    public function store(ProductoRequest $request): RedirectResponse
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto): View
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(ProductoRequest $request, Producto $producto): RedirectResponse
    {
        $producto->update($request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto enviado a la papelera.');
    }
}
