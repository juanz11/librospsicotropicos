<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DespachoItem extends Model
{
    protected $fillable = [
        'despacho_id', 'producto_id', 'lote', 'cantidad', 'fecha_vencimiento',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'cantidad'          => 'integer',
    ];

    public function despacho(): BelongsTo
    {
        return $this->belongsTo(Despacho::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
