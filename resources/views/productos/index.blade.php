@extends('layouts.app')
@section('title', 'Medicamentos')

@section('content')
<div class="page-header">
    <h1>Medicamentos (Psicotrópicos)</h1>
    <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">+ Nuevo Medicamento</a>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-body">
        <form method="GET" style="display:flex;gap:.75rem;align-items:flex-end;">
            <div style="flex:1;">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nombre o principio activo...">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">✕</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nombre</th><th>Principio Activo</th><th>Concentración</th><th>Presentación</th><th>Laboratorio</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($productos as $p)
                <tr>
                    <td><strong>{{ $p->nombre }}</strong></td>
                    <td>{{ $p->principio_activo ?? '—' }}</td>
                    <td>{{ $p->concentracion ?? '—' }}</td>
                    <td>{{ $p->presentacion ?? '—' }}</td>
                    <td>{{ $p->laboratorio ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $p->activo ? 'badge-success' : 'badge-danger' }}">
                            {{ $p->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('productos.edit', $p) }}" class="btn btn-secondary btn-sm">Editar</a>
                            <form method="POST" action="{{ route('productos.destroy', $p) }}"
                                onsubmit="return confirm('¿Eliminar este medicamento?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:#94a3b8;padding:2rem;">Sin medicamentos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($productos->hasPages())
    <div style="padding:1rem 1.25rem;">{{ $productos->links() }}</div>
    @endif
</div>
@endsection
