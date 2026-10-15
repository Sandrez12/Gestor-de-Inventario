<?php

namespace App\Exceptions;

use App\Models\Producto;
use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public static function for(Producto $producto, int $requerido): self
    {
        return new self(
            "Stock insuficiente de «{$producto->nombre}»: hay {$producto->stock} y la operación necesita {$requerido}."
        );
    }
}
