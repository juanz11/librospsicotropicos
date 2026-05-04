@extends('layouts.app')
@section('title', 'Farmacias')

@section('content')
<div class="page-header">
    <h1>Farmacias (Clientes)</h1>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">+ Nueva Farmacia</a>
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
                <tr><th>Nombre</th><th>RIF</th><th>Teléfono</th><th>Permiso Sanitario</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($clientes as $c)
                <tr>
                    <td><strong>{{ $c->nombre }}</strong></td>
                    <td>{{ $c->rif }}</td>
                    <td>{{ $c->telefono ?? '—' }}</td>
                    <td>{{ $c->permiso_sanitario ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $c->activo ? 'badge-success' : 'badge-danger' }}">
                            {{ $c->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('clientes.edit', $c) }}" class="btn btn-secondary btn-sm">Editar</a>
                            <form method="POST" action="{{ route('clientes.destroy', $c) }}"
                                onsubmit="return confirm('¿Eliminar esta farmacia?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:2rem;">Sin farmacias registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clientes->hasPages())
    <div style="padding:1rem 1.25rem;">{{ $clientes->links() }}</div>
    @endif
</div>
@endsection
