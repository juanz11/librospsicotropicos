@extends('layouts.app')
@section('title', 'Editar Despacho ' . $despacho->numero_factura)

@section('content')
<div class="page-header">
    <h1>Editar Despacho {{ $despacho->numero_factura }}</h1>
    <a href="{{ route('despachos.show', $despacho) }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>

<form method="POST" action="{{ route('despachos.update', $despacho) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="card" style="margin-bottom:1rem;">
    <div class="card-header"><h2>Datos del Despacho</h2></div>
    <div class="card-body">
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">N° Factura <span style="color:red">*</span></label>
                <input type="text" name="numero_factura"
                    value="{{ old('numero_factura', $despacho->numero_factura) }}"
                    class="form-control {{ $errors->has('numero_factura') ? 'is-invalid' : '' }}">
                @error('numero_factura')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Farmacia <span style="color:red">*</span></label>
                <select name="cliente_id" class="form-control {{ $errors->has('cliente_id') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccionar —</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id', $despacho->cliente_id) == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre }} — {{ $c->rif }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Fecha <span style="color:red">*</span></label>
                <input type="date" name="fecha"
                    value="{{ old('fecha', $despacho->fecha->format('Y-m-d')) }}"
                    class="form-control {{ $errors->has('fecha') ? 'is-invalid' : '' }}">
                @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Estado <span style="color:red">*</span></label>
                <select name="estado" class="form-control {{ $errors->has('estado') ? 'is-invalid' : '' }}">
                    <option value="pendiente"  {{ old('estado', $despacho->estado)=='pendiente'  ? 'selected':'' }}>Pendiente</option>
                    <option value="aprobado"   {{ old('estado', $despacho->estado)=='aprobado'   ? 'selected':'' }}>Aprobado</option>
                    <option value="despachado" {{ old('estado', $despacho->estado)=='despachado' ? 'selected':'' }}>Despachado</option>
                </select>
                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" rows="3" class="form-control">{{ old('observaciones', $despacho->observaciones) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Archivo Adjunto
                    @if($despacho->archivo_adjunto)
                        <span class="text-muted">(ya tiene uno — sube otro para reemplazarlo)</span>
                    @endif
                </label>
                <input type="file" name="archivo_adjunto" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    class="form-control {{ $errors->has('archivo_adjunto') ? 'is-invalid' : '' }}">
                @error('archivo_adjunto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div class="card-header">
        <h2>Productos del Despacho</h2>
        <button type="button" id="btn-add-row" class="btn btn-primary btn-sm">+ Agregar Producto</button>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="min-width:200px;">Producto *</th>
                        <th style="min-width:110px;">Presentación</th>
                        <th style="min-width:100px;">Concentración</th>
                        <th style="min-width:120px;">Lote *</th>
                        <th style="min-width:90px;">Cantidad *</th>
                        <th style="min-width:140px;">Vencimiento *</th>
                        <th style="width:50px;"></th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    @php
                        $editItems = old('items') ?? $despacho->items->map(fn($i) => [
                            'producto_id'       => $i->producto_id,
                            'lote'              => $i->lote,
                            'cantidad'          => $i->cantidad,
                            'fecha_vencimiento' => $i->fecha_vencimiento->format('Y-m-d'),
                        ])->toArray();
                    @endphp
                    @foreach($editItems as $i => $item)
                    <tr class="item-row">
                        <td>
                            <select name="items[{{ $i }}][producto_id]" class="form-control producto-select">
                                <option value="">— Seleccionar —</option>
                                @foreach($productos as $p)
                                    <option value="{{ $p->id }}"
                                        data-concentracion="{{ $p->concentracion }}"
                                        data-presentacion="{{ $p->presentacion }}"
                                        {{ ($item['producto_id'] ?? '') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nombre }} — {{ $p->concentracion }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="items[{{ $i }}][presentacion_display]" class="form-control presentacion-field" readonly placeholder="Auto"></td>
                        <td><input type="text" name="items[{{ $i }}][concentracion_display]" class="form-control concentracion-field" readonly placeholder="Auto"></td>
                        <td><input type="text" name="items[{{ $i }}][lote]" class="form-control" value="{{ $item['lote'] ?? '' }}" placeholder="L2024001"></td>
                        <td><input type="number" name="items[{{ $i }}][cantidad]" class="form-control" value="{{ $item['cantidad'] ?? '' }}" min="1"></td>
                        <td><input type="date" name="items[{{ $i }}][fecha_vencimiento]" class="form-control" value="{{ $item['fecha_vencimiento'] ?? '' }}"></td>
                        <td><button type="button" class="btn-remove-row">✕</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="display:flex;gap:.75rem;justify-content:flex-end;">
    <a href="{{ route('despachos.show', $despacho) }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</div>
</form>
@endsection

@push('scripts')
<script>
const productosData = @json($productos->keyBy('id'));
let rowIndex = {{ count($editItems) }};

function attachRowEvents(row) {
    const select    = row.querySelector('.producto-select');
    const presField = row.querySelector('.presentacion-field');
    const concField = row.querySelector('.concentracion-field');
    const removeBtn = row.querySelector('.btn-remove-row');

    select.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        presField.value = opt.dataset.presentacion  || '';
        concField.value = opt.dataset.concentracion || '';
    });

    if (select.value) {
        const opt = select.options[select.selectedIndex];
        presField.value = opt.dataset.presentacion  || '';
        concField.value = opt.dataset.concentracion || '';
    }

    removeBtn.addEventListener('click', function () {
        if (document.querySelectorAll('#items-body .item-row').length <= 1) {
            alert('Debe haber al menos un producto.');
            return;
        }
        row.remove();
    });
}

function buildProductOptions(selectedId) {
    let opts = '<option value="">— Seleccionar —</option>';
    for (const [id, p] of Object.entries(productosData)) {
        opts += `<option value="${id}" data-concentracion="${p.concentracion||''}" data-presentacion="${p.presentacion||''}">${p.nombre} — ${p.concentracion||''}</option>`;
    }
    return opts;
}

document.getElementById('btn-add-row').addEventListener('click', function () {
    const tbody = document.getElementById('items-body');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td><select name="items[${rowIndex}][producto_id]" class="form-control producto-select">${buildProductOptions()}</select></td>
        <td><input type="text" name="items[${rowIndex}][presentacion_display]" class="form-control presentacion-field" readonly placeholder="Auto"></td>
        <td><input type="text" name="items[${rowIndex}][concentracion_display]" class="form-control concentracion-field" readonly placeholder="Auto"></td>
        <td><input type="text" name="items[${rowIndex}][lote]" class="form-control" placeholder="L2024001"></td>
        <td><input type="number" name="items[${rowIndex}][cantidad]" class="form-control" min="1"></td>
        <td><input type="date" name="items[${rowIndex}][fecha_vencimiento]" class="form-control"></td>
        <td><button type="button" class="btn-remove-row">✕</button></td>
    `;
    tbody.appendChild(tr);
    attachRowEvents(tr);
    rowIndex++;
});

document.querySelectorAll('#items-body .item-row').forEach(attachRowEvents);
</script>
@endpush
