@extends('layouts.app')
@section('title', 'Editar Cliente')

@section('content')
<div class="page-header">
    <h1>Editar Cliente</h1>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                    class="form-control {{ $errors->has('nombre') ? 'is-invalid' : '' }}">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">RIF *</label>
                <input type="text" name="rif" value="{{ old('rif', $cliente->rif) }}"
                    class="form-control {{ $errors->has('rif') ? 'is-invalid' : '' }}">
                @error('rif')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $cliente->email) }}" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">SICM</label>
                <input type="text" name="sicm" value="{{ old('sicm', $cliente->sicm) }}"
                    class="form-control {{ $errors->has('sicm') ? 'is-invalid' : '' }}">
                @error('sicm')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Archivo RIF</label>
                <input type="file" name="rif_archivo" class="form-control {{ $errors->has('rif_archivo') ? 'is-invalid' : '' }}">
                @error('rif_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->rif_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'rif']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Archivo Factura</label>
                <input type="file" name="factura_archivo" class="form-control {{ $errors->has('factura_archivo') ? 'is-invalid' : '' }}">
                @error('factura_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->factura_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'factura']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Permiso de Instalación (archivo)</label>
                <input type="file" name="permiso_instalacion_archivo" class="form-control {{ $errors->has('permiso_instalacion_archivo') ? 'is-invalid' : '' }}">
                @error('permiso_instalacion_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->permiso_instalacion_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'permiso_instalacion']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Cédula del regente (archivo)</label>
                <input type="file" name="cedula_regente_archivo" class="form-control {{ $errors->has('cedula_regente_archivo') ? 'is-invalid' : '' }}">
                @error('cedula_regente_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->cedula_regente_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'cedula_regente']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Título farmacéutico (archivo)</label>
                <input type="file" name="titulo_farmaceutico_archivo" class="form-control {{ $errors->has('titulo_farmaceutico_archivo') ? 'is-invalid' : '' }}">
                @error('titulo_farmaceutico_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->titulo_farmaceutico_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'titulo_farmaceutico']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Última relación psicotrópica (archivo)</label>
                <input type="file" name="ultima_relacion_psicotropica_archivo" class="form-control {{ $errors->has('ultima_relacion_psicotropica_archivo') ? 'is-invalid' : '' }}">
                @error('ultima_relacion_psicotropica_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->ultima_relacion_psicotropica_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'ultima_relacion_psicotropica']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Carta de solicitud (archivo)</label>
                <input type="file" name="carta_solicitud_archivo" class="form-control {{ $errors->has('carta_solicitud_archivo') ? 'is-invalid' : '' }}">
                @error('carta_solicitud_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->carta_solicitud_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'carta_solicitud']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label">Cédula del farmacéutico regente</label>
                <input type="text" name="cedula_farmaceutico_regente" value="{{ old('cedula_farmaceutico_regente', $cliente->cedula_farmaceutico_regente) }}"
                    class="form-control {{ $errors->has('cedula_farmaceutico_regente') ? 'is-invalid' : '' }}">
                @error('cedula_farmaceutico_regente')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Orden de compra (archivo)</label>
                <input type="file" name="orden_compra_archivo" class="form-control {{ $errors->has('orden_compra_archivo') ? 'is-invalid' : '' }}">
                @error('orden_compra_archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($cliente->orden_compra_archivo)
                    <div style="margin-top:.25rem;">
                        <a class="btn btn-secondary btn-sm" href="{{ route('clientes.documento', [$cliente, 'orden_compra']) }}">Descargar</a>
                    </div>
                @endif
            </div>

            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Dirección</label>
                <textarea name="direccion" rows="2" class="form-control">{{ old('direccion', $cliente->direccion) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', $cliente->activo) ? 'checked' : '' }} style="margin-right:.4rem;">
                    Activo
                </label>
            </div>
        </div>
        <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
        </form>
    </div>
</div>
@endsection
