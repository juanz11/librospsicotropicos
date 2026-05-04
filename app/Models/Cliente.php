<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre', 'rif', 'direccion', 'telefono',
        'email', 'permiso_sanitario', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function despachos(): HasMany
    {
        return $this->hasMany(Despacho::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
