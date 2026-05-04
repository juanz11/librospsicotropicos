@extends('layouts.app')
@section('title', 'Nueva Farmacia')

@section('content')
<div class="page-header">
    <h1>Nueva Farmacia</h1>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nombre <span style="color:red">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                    class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" placeholder="Farmacia Central">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">RIF <span style="color:red">*</span></label>
                <input type="text" name="rif" value="{{ old('rif') }}"
                    class="form-control {{ $errors->has('rif') ? 'is-invalid' : '' }}" placeholder="J-12345678-9">
                @error('rif')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}" class="form-control" placeholder="0212-1234567">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Permiso Sanitario</label>
                <input type="text" name="permiso_sanitario" value="{{ old('permiso_sanitario') }}" class="form-control" placeholder="PS-2024-001">
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Dirección</label>
                <textarea name="direccion" rows="2" class="form-control" placeholder="Av. Principal...">{{ old('direccion') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', '1') ? 'checked' : '' }} style="margin-right:.4rem;">
                    Activo
                </label>
            </div>
        </div>
        <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
        </form>
    </div>
</div>
@endsection
