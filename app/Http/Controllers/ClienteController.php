<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'sicm'              => 'nullable|string|max:50',
            'direccion'         => 'nullable|string|max:500',
            'telefono'          => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'permiso_sanitario' => 'nullable|string|max:100',
            'rif_archivo'       => 'nullable|file|max:5120',
            'factura_archivo'   => 'nullable|file|max:5120',
            'permiso_instalacion_archivo' => 'nullable|file|max:5120',
            'activo'            => 'boolean',
        ]);

        $data['activo'] = $request->boolean('activo', true);

        if ($request->hasFile('rif_archivo')) {
            $data['rif_archivo'] = $request->file('rif_archivo')->store('clientes', 'local');
        }
        if ($request->hasFile('factura_archivo')) {
            $data['factura_archivo'] = $request->file('factura_archivo')->store('clientes', 'local');
        }
        if ($request->hasFile('permiso_instalacion_archivo')) {
            $data['permiso_instalacion_archivo'] = $request->file('permiso_instalacion_archivo')->store('clientes', 'local');
        }

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
            'sicm'              => 'nullable|string|max:50',
            'direccion'         => 'nullable|string|max:500',
            'telefono'          => 'nullable|string|max:30',
            'email'             => 'nullable|email|max:255',
            'permiso_sanitario' => 'nullable|string|max:100',
            'rif_archivo'       => 'nullable|file|max:5120',
            'factura_archivo'   => 'nullable|file|max:5120',
            'permiso_instalacion_archivo' => 'nullable|file|max:5120',
        ]);

        $data['activo'] = $request->boolean('activo');

        if ($request->hasFile('rif_archivo')) {
            if ($cliente->rif_archivo) {
                Storage::disk('local')->delete($cliente->rif_archivo);
            }
            $data['rif_archivo'] = $request->file('rif_archivo')->store('clientes', 'local');
        }
        if ($request->hasFile('factura_archivo')) {
            if ($cliente->factura_archivo) {
                Storage::disk('local')->delete($cliente->factura_archivo);
            }
            $data['factura_archivo'] = $request->file('factura_archivo')->store('clientes', 'local');
        }
        if ($request->hasFile('permiso_instalacion_archivo')) {
            if ($cliente->permiso_instalacion_archivo) {
                Storage::disk('local')->delete($cliente->permiso_instalacion_archivo);
            }
            $data['permiso_instalacion_archivo'] = $request->file('permiso_instalacion_archivo')->store('clientes', 'local');
        }

        $cliente->update($data);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->despachos()->exists()) {
            return back()->with('error', 'No se puede eliminar: el cliente tiene despachos asociados.');
        }

        if ($cliente->rif_archivo) {
            Storage::disk('local')->delete($cliente->rif_archivo);
        }
        if ($cliente->factura_archivo) {
            Storage::disk('local')->delete($cliente->factura_archivo);
        }
        if ($cliente->permiso_instalacion_archivo) {
            Storage::disk('local')->delete($cliente->permiso_instalacion_archivo);
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }

    public function descargarDocumento(Cliente $cliente, string $tipo)
    {
        $map = [
            'rif' => 'rif_archivo',
            'factura' => 'factura_archivo',
            'permiso_instalacion' => 'permiso_instalacion_archivo',
        ];

        abort_unless(isset($map[$tipo]), 404);

        $field = $map[$tipo];
        $path = $cliente->{$field};

        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }
}
