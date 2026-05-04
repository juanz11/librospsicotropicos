<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::query()
            ->when($request->search, fn ($q, $s) => $q->where('nombre', 'like', "%$s%")->orWhere('rif', 'like', "%$s%"))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'            => 'required|string|max:255',
            'rif'               => 'required|string|max:20|unique:clientes,rif',
            'direccion'         => 'nullable|string|max:500',
            'telefono'          => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'permiso_sanitario' => 'nullable|string|max:100',
            'activo'            => 'boolean',
        ]);

        $data['activo'] = $request->boolean('activo', true);

        Cliente::create($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre'            => 'required|string|max:255',
            'rif'               => 'required|string|max:20|unique:clientes,rif,' . $cliente->id,
            'direccion'         => 'nullable|string|max:500',
            'telefono'          => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'permiso_sanitario' => 'nullable|string|max:100',
        ]);

        $data['activo'] = $request->boolean('activo');

        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->despachos()->exists()) {
            return back()->with('error', 'No se puede eliminar: el cliente tiene despachos asociados.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
