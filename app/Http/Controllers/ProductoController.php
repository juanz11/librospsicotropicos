<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::query()
            ->when($request->search, fn ($q, $s) => $q->where('nombre', 'like', "%$s%")->orWhere('principio_activo', 'like', "%$s%"))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'             => 'required|string|max:255',
            'concentracion'      => 'nullable|string|max:50',
            'presentacion'       => 'nullable|string|max:100',
            'principio_activo'   => 'nullable|string|max:255',
            'laboratorio'        => 'nullable|string|max:255',
            'registro_sanitario' => 'nullable|string|max:100',
        ]);

        $data['activo'] = $request->boolean('activo', true);

        Producto::create($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre'             => 'required|string|max:255',
            'concentracion'      => 'nullable|string|max:50',
            'presentacion'       => 'nullable|string|max:100',
            'principio_activo'   => 'nullable|string|max:255',
            'laboratorio'        => 'nullable|string|max:255',
            'registro_sanitario' => 'nullable|string|max:100',
        ]);

        $data['activo'] = $request->boolean('activo');

        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->despachoItems()->exists()) {
            return back()->with('error', 'No se puede eliminar: el producto tiene despachos asociados.');
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado.');
    }

    /** API para autocompletar en el formulario de despacho */
    public function apiShow(Producto $producto)
    {
        return response()->json([
            'id'            => $producto->id,
            'nombre'        => $producto->nombre,
            'concentracion' => $producto->concentracion,
            'presentacion'  => $producto->presentacion,
        ]);
    }
}
