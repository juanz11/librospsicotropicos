@extends('layouts.app')
@section('title', 'Inicio')

@section('content')
<div class="page-header">
    <h1>Bienvenido, {{ Auth::user()->name }}</h1>
    <a href="{{ route('despachos.create') }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Despacho
    </a>
</div>

@php
    $totalDespachos  = \App\Models\Despacho::count();
    $pendientes      = \App\Models\Despacho::where('estado','pendiente')->count();
    $totalClientes   = \App\Models\Cliente::where('activo',true)->count();
    $totalProductos  = \App\Models\Producto::where('activo',true)->count();
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    @foreach([
        ['Despachos totales', $totalDespachos, '#6366f1', '#eef2ff'],
        ['Pendientes',        $pendientes,      '#d97706', '#fef3c7'],
        ['Farmacias activas', $totalClientes,   '#0284c7', '#e0f2fe'],
        ['Medicamentos',      $totalProductos,  '#16a34a', '#f0fdf4'],
    ] as [$label, $val, $color, $bg])
    <div class="card" style="padding:1.25rem;">
        <div style="font-size:.8125rem;font-weight:600;color:{{ $color }};margin-bottom:.5rem;">{{ $label }}</div>
        <div style="font-size:2rem;font-weight:700;color:{{ $color }};">{{ $val }}</div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h2>Últimos despachos</h2>
        <a href="{{ route('despachos.index') }}" class="btn btn-secondary btn-sm">Ver todos</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>N° Factura</th><th>Fecha</th><th>Farmacia</th><th>Estado</th><th></th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\Despacho::with('cliente')->orderByDesc('id')->limit(8)->get() as $d)
                <tr>
                    <td><strong>{{ $d->numero_factura }}</strong></td>
                    <td>{{ $d->fecha->format('d/m/Y') }}</td>
                    <td>{{ $d->cliente->nombre }}</td>
                    <td><span class="badge {{ $d->estado_badge }}">{{ $d->estado_label }}</span></td>
                    <td><a href="{{ route('despachos.show', $d) }}" class="btn btn-secondary btn-sm">Ver</a></td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:2rem;">Sin despachos aún</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
