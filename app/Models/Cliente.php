<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre', 'rif', 'direccion', 'telefono',
        'email', 'sicm', 'activo',
        'rif_archivo', 'factura_archivo', 'permiso_instalacion_archivo',
        'cedula_regente_archivo', 'titulo_farmaceutico_archivo',
        'ultima_relacion_psicotropica_archivo', 'carta_solicitud_archivo',
        'cedula_farmaceutico_regente', 'orden_compra_archivo',
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
