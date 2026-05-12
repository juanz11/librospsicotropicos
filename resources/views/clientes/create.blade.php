@extends('layouts.app')
@section('title', 'Nuevo Cliente')

@section('content')
<div class="page-header">
    <h1>Nuevo Cliente</h1>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.store') }}" enctype="multipart/form-data">
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
                <label class="form-label">SICM</label>
                <input type="text" name="sicm" value="{{ old('sicm') }}"
                    class="form-control {{ $errors->has('sicm') ? 'is-invalid' : '' }}" placeholder="SICM 17774">
                @error('sicm')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Archivo RIF</label>
                <input type="file" name="rif_archivo" class="form-control {{ $errors->has('rif_archivo') ? 'is-invalid' : '' }}">
                @error('rif_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Archivo Factura</label>
                <input type="file" name="factura_archivo" class="form-control {{ $errors->has('factura_archivo') ? 'is-invalid' : '' }}">
                @error('factura_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Permiso de Instalación (archivo)</label>
                <input type="file" name="permiso_instalacion_archivo" class="form-control {{ $errors->has('permiso_instalacion_archivo') ? 'is-invalid' : '' }}">
                @error('permiso_instalacion_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
