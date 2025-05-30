<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salida extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['producto_id', 'cliente_id', 'cantidad'];

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
