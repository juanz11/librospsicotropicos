@extends('layouts.app')
@section('title', 'Nuevo Despacho')

@section('content')
<div class="page-header">
    <h1>Nuevo Despacho</h1>
    <a href="{{ route('despachos.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>

<form method="POST" action="{{ route('despachos.store') }}" enctype="multipart/form-data">
@csrf

{{-- Datos generales --}}
<div class="card" style="margin-bottom:1rem;">
    <div class="card-header"><h2>Datos del Despacho</h2></div>
    <div class="card-body">
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label" for="numero_factura">N° Factura <span style="color:red">*</span></label>
                <input type="text" id="numero_factura" name="numero_factura"
                    value="{{ old('numero_factura', $nextFactura) }}"
                    class="form-control {{ $errors->has('numero_factura') ? 'is-invalid' : '' }}">
                @error('numero_factura')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="cliente_id">Farmacia (Cliente) <span style="color:red">*</span></label>
                <select id="cliente_id" name="cliente_id"
                    class="form-control {{ $errors->has('cliente_id') ? 'is-invalid' : '' }}">
                    <option value="">— Seleccionar —</option>
                    @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre }} — {{ $c->rif }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="fecha">Fecha <span style="color:red">*</span></label>
                <input type="date" id="fecha" name="fecha"
                    value="{{ old('fecha', now()->format('Y-m-d')) }}"
                    class="form-control {{ $errors->has('fecha') ? 'is-invalid' : '' }}">
                @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="estado">Estado <span style="color:red">*</span></label>
                <select id="estado" name="estado"
                    class="form-control {{ $errors->has('estado') ? 'is-invalid' : '' }}">
                    <option value="pendiente"  {{ old('estado','pendiente')=='pendiente'  ? 'selected':'' }}>Pendiente</option>
                    <option value="aprobado"   {{ old('estado')=='aprobado'   ? 'selected':'' }}>Aprobado</option>
                    <option value="despachado" {{ old('estado')=='despachado' ? 'selected':'' }}>Despachado</option>
                </select>
                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label" for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="3"
                    class="form-control {{ $errors->has('observaciones') ? 'is-invalid' : '' }}"
                    placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="archivo_adjunto">Archivo Adjunto <span class="text-muted">(PDF, imagen, Word — máx. 5MB)</span></label>
                <input type="file" id="archivo_adjunto" name="archivo_adjunto"
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    class="form-control {{ $errors->has('archivo_adjunto') ? 'is-invalid' : '' }}">
                @error('archivo_adjunto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>
</div>

{{-- Productos --}}
<div class="card" style="margin-bottom:1rem;">
    <div class="card-header">
        <h2>Productos del Despacho</h2>
        <button type="button" id="btn-add-row" class="btn btn-primary btn-sm">
            + Agregar Producto
        </button>
    </div>
    <div class="card-body" style="padding:0;">
        @error('items')<div class="alert alert-error" style="margin:1rem;">{{ $message }}</div>@enderror
        <div class="table-wrap">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="min-width:200px;">Producto <span style="color:red">*</span></th>
                        <th style="min-width:110px;">Presentación</th>
                        <th style="min-width:100px;">Concentración</th>
                        <th style="min-width:120px;">Lote <span style="color:red">*</span></th>
                        <th style="min-width:90px;">Cantidad <span style="color:red">*</span></th>
                        <th style="min-width:140px;">Vencimiento <span style="color:red">*</span></th>
                        <th style="width:50px;"></th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    {{-- Fila inicial o repoblada tras error --}}
                    @php $oldItems = old('items', [[]]); @endphp
                    @foreach($oldItems as $i => $item)
                    <tr class="item-row">
                        <td>
                            <select name="items[{{ $i }}][producto_id]"
                                class="form-control producto-select {{ $errors->has("items.$i.producto_id") ? 'is-invalid' : '' }}"
                                data-index="{{ $i }}">
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
                        <td>
                            <input type="text" name="items[{{ $i }}][presentacion_display]"
                                class="form-control presentacion-field" readonly
                                value="{{ $item['presentacion_display'] ?? '' }}"
                                placeholder="Auto">
                        </td>
                        <td>
                            <input type="text" name="items[{{ $i }}][concentracion_display]"
                                class="form-control concentracion-field" readonly
                                value="{{ $item['concentracion_display'] ?? '' }}"
                                placeholder="Auto">
                        </td>
                        <td>
                            <input type="text" name="items[{{ $i }}][lote]"
                                class="form-control {{ $errors->has("items.$i.lote") ? 'is-invalid' : '' }}"
                                value="{{ $item['lote'] ?? '' }}"
                                placeholder="L2024001">
                        </td>
                        <td>
                            <input type="number" name="items[{{ $i }}][cantidad]"
                                class="form-control {{ $errors->has("items.$i.cantidad") ? 'is-invalid' : '' }}"
                                value="{{ $item['cantidad'] ?? '' }}"
                                min="1" placeholder="0">
                        </td>
                        <td>
                            <input type="date" name="items[{{ $i }}][fecha_vencimiento]"
                                class="form-control {{ $errors->has("items.$i.fecha_vencimiento") ? 'is-invalid' : '' }}"
                                value="{{ $item['fecha_vencimiento'] ?? '' }}">
                        </td>
                        <td>
                            <button type="button" class="btn-remove-row" title="Eliminar fila">✕</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="display:flex;gap:.75rem;justify-content:flex-end;">
    <a href="{{ route('despachos.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Guardar Despacho</button>
</div>

</form>
@endsection

@push('scripts')
<script>
// Datos de productos para autocompletar
const productosData = @json($productos->keyBy('id'));

let rowIndex = {{ count(old('items', [[]])) }};

function attachRowEvents(row) {
    const select = row.querySelector('.producto-select');
    const presField  = row.querySelector('.presentacion-field');
    const concField  = row.querySelector('.concentracion-field');
    const removeBtn  = row.querySelector('.btn-remove-row');

    select.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        presField.value  = opt.dataset.presentacion  || '';
        concField.value  = opt.dataset.concentracion || '';
    });

    // Trigger on load if value already selected
    if (select.value) {
        const opt = select.options[select.selectedIndex];
        presField.value  = opt.dataset.presentacion  || '';
        concField.value  = opt.dataset.concentracion || '';
    }

    removeBtn.addEventListener('click', function () {
        const rows = document.querySelectorAll('#items-body .item-row');
        if (rows.length <= 1) {
            alert('Debe haber al menos un producto en el despacho.');
            return;
        }
        row.remove();
    });
}

function buildProductOptions(selectedId) {
    let opts = '<option value="">— Seleccionar —</option>';
    for (const [id, p] of Object.entries(productosData)) {
        const sel = String(id) === String(selectedId) ? 'selected' : '';
        opts += `<option value="${id}" data-concentracion="${p.concentracion || ''}" data-presentacion="${p.presentacion || ''}" ${sel}>${p.nombre} — ${p.concentracion || ''}</option>`;
    }
    return opts;
}

document.getElementById('btn-add-row').addEventListener('click', function () {
    const tbody = document.getElementById('items-body');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td>
            <select name="items[${rowIndex}][producto_id]" class="form-control producto-select" data-index="${rowIndex}">
                ${buildProductOptions(null)}
            </select>
        </td>
        <td><input type="text" name="items[${rowIndex}][presentacion_display]" class="form-control presentacion-field" readonly placeholder="Auto"></td>
        <td><input type="text" name="items[${rowIndex}][concentracion_display]" class="form-control concentracion-field" readonly placeholder="Auto"></td>
        <td><input type="text" name="items[${rowIndex}][lote]" class="form-control" placeholder="L2024001"></td>
        <td><input type="number" name="items[${rowIndex}][cantidad]" class="form-control" min="1" placeholder="0"></td>
        <td><input type="date" name="items[${rowIndex}][fecha_vencimiento]" class="form-control"></td>
        <td><button type="button" class="btn-remove-row" title="Eliminar">✕</button></td>
    `;
    tbody.appendChild(tr);
    attachRowEvents(tr);
    rowIndex++;
});

// Attach events to existing rows (on page load / after validation error)
document.querySelectorAll('#items-body .item-row').forEach(attachRowEvents);
</script>
@endpush
