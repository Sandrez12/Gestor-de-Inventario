<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'direccion', 'telefono', 'email'];

    protected $table = 'proveedores';

    public function getRouteKeyName()
    {
        return 'id';
    }

    // Relación con entradas
    public function entradas()
    {
        return $this->hasMany(Entrada::class);
    }
}
