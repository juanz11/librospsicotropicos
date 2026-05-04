@extends('layouts.app')
@section('title', 'Nuevo Medicamento')

@section('content')
<div class="page-header">
    <h1>Nuevo Medicamento</h1>
    <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('productos.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nombre <span style="color:red">*</span></label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                    class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}" placeholder="MEZHITIN">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Principio Activo</label>
                <input type="text" name="principio_activo" value="{{ old('principio_activo') }}" class="form-control" placeholder="Metilfenidato">
            </div>
            <div class="form-group">
                <label class="form-label">Concentración</label>
                <input type="text" name="concentracion" value="{{ old('concentracion') }}" class="form-control" placeholder="10mg">
            </div>
            <div class="form-group">
                <label class="form-label">Presentación</label>
                <input type="text" name="presentacion" value="{{ old('presentacion') }}" class="form-control" placeholder="Comprimidos x 50">
            </div>
            <div class="form-group">
                <label class="form-label">Laboratorio</label>
                <input type="text" name="laboratorio" value="{{ old('laboratorio') }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Registro Sanitario</label>
                <input type="text" name="registro_sanitario" value="{{ old('registro_sanitario') }}" class="form-control" placeholder="RS-2024-001">
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', '1') ? 'checked' : '' }} style="margin-right:.4rem;">
                    Activo
                </label>
            </div>
        </div>
        <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
        </form>
    </div>
</div>
@endsection
