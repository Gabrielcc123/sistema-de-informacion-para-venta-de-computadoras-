<?php

namespace App\Http\Controllers;

use App\Models\ProductoServicio;
use Illuminate\Http\Request;

class ProductoServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Traemos el catálogo cargando los datos de su categoría de forma eficiente (Eager Loading)
        $catalogo = ProductoServicio::with('categoria')->get();
        return response()->json($catalogo, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idCategoria' => 'required|exists:categoria,idCategoria',
            'nombre' => 'required|string|max:150',
            'precioUnitario' => 'required|numeric|min:0',
            'tipo' => 'required|string|in:Producto,Servicio'
        ]);

        $item = ProductoServicio::create($request->all());

        return response()->json([
            'message' => 'Elemento del catálogo registrado con éxito',
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = ProductoServicio::with('categoria')->findOrFail($id);
        return response()->json($item, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = ProductoServicio::findOrFail($id);

        $request->validate([
            'idCategoria' => 'required|exists:categoria,idCategoria',
            'nombre' => 'required|string|max:150',
            'precioUnitario' => 'required|numeric|min:0',
            'tipo' => 'required|string|in:Producto,Servicio'
        ]);

        $item->update($request->all());

        return response()->json([
            'message' => 'Catálogo actualizado con éxito',
            'data' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = ProductoServicio::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Elemento eliminado correctamente del catálogo'
        ], 200);
    }
}
