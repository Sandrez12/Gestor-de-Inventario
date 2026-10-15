<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    /** Por debajo (o igual) a esta cantidad se considera stock bajo. */
    public const STOCK_BAJO = 5;

    protected $fillable = ['nombre', 'descripcion', 'stock'];

    protected $casts = ['stock' => 'integer'];

    public function salidas(): HasMany
    {
        return $this->hasMany(Salida::class);
    }

    public function entradas(): HasMany
    {
        return $this->hasMany(Entrada::class);
    }

    public function scopeBuscar(Builder $query, ?string $texto): Builder
    {
        return $query->when($texto, fn (Builder $q) => $q->where('nombre', 'like', "%{$texto}%"));
    }

    public function scopeStockBajo(Builder $query): Builder
    {
        return $query->where('stock', '<=', self::STOCK_BAJO);
    }

    public function getStockBajoAttribute(): bool
    {
        return $this->stock <= self::STOCK_BAJO;
    }
}
