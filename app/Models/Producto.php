<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'nombre', 'concentracion', 'presentacion',
        'principio_activo', 'laboratorio', 'registro_sanitario', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function despachoItems(): HasMany
    {
        return $this->hasMany(DespachoItem::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
