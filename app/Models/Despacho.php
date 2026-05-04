<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Despacho extends Model
{
    protected $fillable = [
        'numero_factura', 'cliente_id', 'fecha',
        'estado', 'observaciones', 'archivo_adjunto', 'user_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DespachoItem::class);
    }

    public function totalUnidades(): int
    {
        return $this->items->sum('cantidad');
    }

    public static function siguienteNumeroFactura(): string
    {
        $year  = now()->format('Y');
        $last  = static::whereYear('created_at', $year)->count() + 1;
        return 'F' . str_pad($last, 3, '0', STR_PAD_LEFT) . '-' . $year;
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'aprobado'   => 'badge-success',
            'despachado' => 'badge-info',
            default      => 'badge-warning',
        };
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'aprobado'   => 'Aprobado',
            'despachado' => 'Despachado',
            default      => 'Pendiente',
        };
    }
}
