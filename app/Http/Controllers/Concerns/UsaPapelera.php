<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Listado de eliminados, restauración y borrado definitivo para catálogos
 * simples (productos, clientes y proveedores).
 *
 * El controlador que lo usa define: modelo(), prefijo() y etiqueta().
 */
trait UsaPapelera
{
    /** @return class-string<\Illuminate\Database\Eloquent\Model> */
    abstract protected function modelo(): string;

    /** Prefijo de rutas y carpeta de vistas, p. ej. "productos". */
    abstract protected function prefijo(): string;

    /** Nombre en singular con artículo, p. ej. "El producto". */
    abstract protected function etiqueta(): string;

    public function eliminados(): View
    {
        $registros = $this->modelo()::onlyTrashed()->latest('deleted_at')->paginate(10);

        return view("{$this->prefijo()}.eliminados", ['registros' => $registros]);
    }

    public function restore(int $id): RedirectResponse
    {
        $this->modelo()::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route("{$this->prefijo()}.index")->with('success', "{$this->etiqueta()} se restauró.");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $this->modelo()::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route("{$this->prefijo()}.eliminados")->with('success', "{$this->etiqueta()} se eliminó de forma permanente.");
    }
}
