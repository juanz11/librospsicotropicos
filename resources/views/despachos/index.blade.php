@extends('layouts.app')
@section('title', 'Despachos')

@section('content')
<div class="page-header">
    <h1>Libro de Despachos</h1>
    <div class="flex gap-2">
        <a href="{{ route('despachos.exportar', request()->query()) }}" class="btn btn-success btn-sm">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Exportar Excel
        </a>
        <a href="{{ route('despachos.create') }}" class="btn btn-primary btn-sm">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Despacho
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <form method="GET" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem;align-items:end;">
            <div>
                <label class="form-label">Buscar factura</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="F001-2026">
            </div>
            <div>
                <label class="form-label">Farmacia</label>
                <select name="cliente_id" class="form-control">
                    <option value="">Todas</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="estado" class="form-control">
                    <option value="">Todos</option>
                    <option value="pendiente"  {{ request('estado')=='pendiente'  ? 'selected':'' }}>Pendiente</option>
                    <option value="aprobado"   {{ request('estado')=='aprobado'   ? 'selected':'' }}>Aprobado</option>
                    <option value="despachado" {{ request('estado')=='despachado' ? 'selected':'' }}>Despachado</option>
                </select>
            </div>
            <div>
                <label class="form-label">Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control">
            </div>
            <div>
                <label class="form-label">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control">
            </div>
            <div style="display:flex;gap:.5rem;">
                <button type="submit" class="btn btn-primary btn-sm w-full">Filtrar</button>
                <a href="{{ route('despachos.index') }}" class="btn btn-secondary btn-sm">✕</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>N° Factura</th>
                    <th>Fecha</th>
                    <th>Farmacia</th>
                    <th>Productos</th>
                    <th>Unidades</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($despachos as $d)
                <tr>
                    <td><strong>{{ $d->numero_factura }}</strong></td>
                    <td>{{ $d->fecha->format('d/m/Y') }}</td>
                    <td>
                        {{ $d->cliente->nombre }}
                        <div class="text-sm text-muted">{{ $d->cliente->rif }}</div>
                    </td>
                    <td>{{ $d->items->count() }}</td>
                    <td>{{ $d->totalUnidades() }}</td>
                    <td><span class="badge {{ $d->estado_badge }}">{{ $d->estado_label }}</span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('despachos.show', $d) }}" class="btn btn-secondary btn-sm">Ver</a>
                            <a href="{{ route('despachos.edit', $d) }}" class="btn btn-secondary btn-sm">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:#94a3b8;padding:2.5rem;">
                        No hay despachos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($despachos->hasPages())
    <div style="padding:1rem 1.25rem;">
        {{ $despachos->links() }}
    </div>
    @endif
</div>
@endsection
