<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DespachoItem extends Model
{
    protected $fillable = [
        'despacho_id', 'producto_id', 'lote', 'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'integer',
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
