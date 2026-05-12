@extends('layouts.app')
@section('title', 'Clientes')

@section('content')
<div class="page-header">
    <h1>Clientes</h1>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">+ Nuevo Cliente</a>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <form method="GET" style="display:flex;gap:.75rem;align-items:flex-end;">
            <div style="flex:1;">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nombre o RIF...">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">✕</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nombre</th><th>RIF</th><th>Teléfono</th><th>Documentos</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($clientes as $c)
                <tr>
                    <td><strong>{{ $c->nombre }}</strong></td>
                    <td>{{ $c->rif }}</td>
                    <td>{{ $c->telefono ?? '—' }}</td>
                    <td>
                        <div class="flex gap-2">
                            @if($c->rif_archivo)
                                <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$c, 'rif']) }}">RIF</a>
                            @endif
                            @if($c->factura_archivo)
                                <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$c, 'factura']) }}">Factura</a>
                            @endif
                            @if($c->permiso_instalacion_archivo)
                                <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$c, 'permiso_instalacion']) }}">Permiso</a>
                            @endif
                            @if(!$c->rif_archivo && !$c->factura_archivo && !$c->permiso_instalacion_archivo)
                                —
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $c->activo ? 'badge-success' : 'badge-danger' }}">
                            {{ $c->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('clientes.edit', $c) }}" class="btn btn-secondary btn-sm">Editar</a>
                            <form method="POST" action="{{ route('clientes.destroy', $c) }}"
                                onsubmit="return confirm('¿Eliminar este cliente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:2rem;">Sin clientes registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clientes->hasPages())
    <div style="padding:1rem 1.25rem;">{{ $clientes->links() }}</div>
    @endif
</div>
@endsection
