@extends('layouts.app')
@section('title', 'Despacho ' . $despacho->numero_factura)

@section('content')
<div class="page-header">
    <h1>Despacho {{ $despacho->numero_factura }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('despachos.exportar-uno', $despacho) }}" class="btn btn-success btn-sm">
            ↓ Exportar Excel
        </a>
        <a href="{{ route('despachos.edit', $despacho) }}" class="btn btn-primary btn-sm">Editar</a>
        <a href="{{ route('despachos.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
    <div class="card">
        <div class="card-header"><h2>Información General</h2></div>
        <div class="card-body">
            <table style="width:100%;font-size:.875rem;border-collapse:collapse;">
                @foreach([
                    ['N° Factura',  $despacho->numero_factura],
                    ['Fecha',       $despacho->fecha->format('d/m/Y')],
                    ['Estado',      ''],
                    ['Creado por',  $despacho->user->name],
                    ['Creado el',   $despacho->created_at->format('d/m/Y H:i')],
                ] as [$label, $val])
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:.5rem .25rem;font-weight:600;color:#64748b;width:40%;">{{ $label }}</td>
                    <td style="padding:.5rem .25rem;">
                        @if($label === 'Estado')
                            <span class="badge {{ $despacho->estado_badge }}">{{ $despacho->estado_label }}</span>
                        @else
                            {{ $val }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2>Farmacia</h2></div>
        <div class="card-body">
            <table style="width:100%;font-size:.875rem;border-collapse:collapse;">
                @foreach([
                    ['Nombre',    $despacho->cliente->nombre],
                    ['RIF',       $despacho->cliente->rif],
                    ['Dirección', $despacho->cliente->direccion ?? '—'],
                    ['Teléfono',  $despacho->cliente->telefono ?? '—'],
                    ['Permiso',   $despacho->cliente->permiso_sanitario ?? '—'],
                ] as [$label, $val])
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:.5rem .25rem;font-weight:600;color:#64748b;width:40%;">{{ $label }}</td>
                    <td style="padding:.5rem .25rem;">{{ $val }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@if($despacho->observaciones)
<div class="card" style="margin-bottom:1rem;">
    <div class="card-header"><h2>Observaciones</h2></div>
    <div class="card-body"><p>{{ $despacho->observaciones }}</p></div>
</div>
@endif

@if($despacho->archivo_adjunto)
<div class="card" style="margin-bottom:1rem;">
    <div class="card-header"><h2>Archivo Adjunto</h2></div>
    <div class="card-body">
        <a href="{{ route('despachos.adjunto', $despacho) }}" class="btn btn-secondary btn-sm">
            ↓ Descargar archivo
        </a>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h2>Productos Despachados</h2>
        <span class="text-muted text-sm">{{ $despacho->items->count() }} producto(s) — {{ $despacho->totalUnidades() }} unidades totales</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Concentración</th>
                    <th>Presentación</th>
                    <th>Lote</th>
                    <th>Cantidad</th>
                    <th>Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($despacho->items as $item)
                <tr>
                    <td><strong>{{ $item->producto->nombre }}</strong></td>
                    <td>{{ $item->producto->concentracion }}</td>
                    <td>{{ $item->producto->presentacion }}</td>
                    <td>{{ $item->lote }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>
                        {{ $item->fecha_vencimiento->format('d/m/Y') }}
                        @if($item->fecha_vencimiento->isPast())
                            <span class="badge badge-danger">Vencido</span>
                        @elseif($item->fecha_vencimiento->diffInDays(now()) <= 90)
                            <span class="badge badge-warning">Próximo</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:1rem;display:flex;justify-content:flex-end;">
    <form method="POST" action="{{ route('despachos.destroy', $despacho) }}"
        onsubmit="return confirm('¿Eliminar este despacho? Esta acción no se puede deshacer.')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Eliminar despacho</button>
    </form>
</div>
@endsection
