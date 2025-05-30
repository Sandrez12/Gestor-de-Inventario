<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'descripcion', 'stock'];

    // Relación con salidas
    public function salidas()
    {
        return $this->hasMany(Salida::class);
    }

    // Relación con entradas
    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }
}
