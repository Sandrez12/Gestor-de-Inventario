<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;

/**
 * Centraliza todos los movimientos de inventario.
 *
 * Cada operación corre en una transacción y bloquea la fila del producto, de modo que
 * el stock nunca queda a medias (si algo falla, se deshace todo) ni se vuelve negativo.
 */
class StockService
{
    // ---------- Entradas (compras a proveedores: suman stock) ----------

    /** @param array{producto_id: int, proveedor_id: int, cantidad: int} $data */
    public function registrarEntrada(array $data): Entrada
    {
        return DB::transaction(function () use ($data) {
            $this->ajustar($data['producto_id'], $data['cantidad']);

            return Entrada::create($data);
        });
    }

    /** @param array{producto_id: int, proveedor_id: int, cantidad: int} $data */
    public function actualizarEntrada(Entrada $entrada, array $data): Entrada
    {
        return DB::transaction(function () use ($entrada, $data) {
            $this->ajustar($entrada->producto_id, -$entrada->cantidad);
            $this->ajustar($data['producto_id'], $data['cantidad']);
            $entrada->update($data);

            return $entrada;
        });
    }

    public function eliminarEntrada(Entrada $entrada): void
    {
        DB::transaction(function () use ($entrada) {
            $this->ajustar($entrada->producto_id, -$entrada->cantidad);
            $entrada->delete();
        });
    }

    public function restaurarEntrada(Entrada $entrada): void
    {
        DB::transaction(function () use ($entrada) {
            $this->ajustar($entrada->producto_id, $entrada->cantidad);
            $entrada->restore();
        });
    }

    // ---------- Salidas (ventas a clientes: restan stock) ----------

    /** @param array{producto_id: int, cliente_id: int, cantidad: int} $data */
    public function registrarSalida(array $data): Salida
    {
        return DB::transaction(function () use ($data) {
            $this->ajustar($data['producto_id'], -$data['cantidad']);

            return Salida::create($data);
        });
    }

    /** @param array{producto_id: int, cliente_id: int, cantidad: int} $data */
    public function actualizarSalida(Salida $salida, array $data): Salida
    {
        return DB::transaction(function () use ($salida, $data) {
            $this->ajustar($salida->producto_id, $salida->cantidad);
            $this->ajustar($data['producto_id'], -$data['cantidad']);
            $salida->update($data);

            return $salida;
        });
    }

    public function eliminarSalida(Salida $salida): void
    {
        DB::transaction(function () use ($salida) {
            $this->ajustar($salida->producto_id, $salida->cantidad);
            $salida->delete();
        });
    }

    public function restaurarSalida(Salida $salida): void
    {
        DB::transaction(function () use ($salida) {
            $this->ajustar($salida->producto_id, -$salida->cantidad);
            $salida->restore();
        });
    }

    // ---------- Interno ----------

    /**
     * Suma (o resta, si es negativo) unidades al producto, bloqueando su fila.
     *
     * @throws InsufficientStockException si el resultado quedaría por debajo de cero
     */
    private function ajustar(int $productoId, int $delta): Producto
    {
        $producto = Producto::withTrashed()->lockForUpdate()->findOrFail($productoId);

        if ($producto->stock + $delta < 0) {
            throw InsufficientStockException::for($producto, abs($delta));
        }

        $producto->increment('stock', $delta);

        return $producto;
    }
}
