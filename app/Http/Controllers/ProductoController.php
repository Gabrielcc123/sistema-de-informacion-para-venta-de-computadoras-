<?php

namespace App\Http\Controllers;

use App\Models\ProductoServicio;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Trae solo los ítems que son productos uniendo ambas tablas de la herencia
        $productos = Producto::with('datosGenerales')->get();
        return response()->json($productos, 200);
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
            'stock' => 'required|integer|min:0',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'numeroSerie' => 'nullable|string|max:100'
        ]);

        // Usamos una transacción de Base de Datos para asegurar que se inserte en ambas tablas o en ninguna
        DB::beginTransaction();
        try {
            // 1. Insertar en la tabla padre (productoServicio) obligando a que el tipo sea 'Producto'
            $itemGeneral = ProductoServicio::create([
                'idCategoria' => $request->idCategoria,
                'nombre' => $request->nombre,
                'precioUnitario' => $request->precioUnitario,
                'garantia' => $request->garantia,
                'tipo' => 'Producto'
            ]);

            // 2. Insertar en la tabla hija (producto) usando el ID recién generado
            $productoFisico = Producto::create([
                'idProducto' => $itemGeneral->idProductoServicio,
                'stock' => $request->stock,
                'marca' => $request->marca,
                'modelo' => $request->modelo,
                'numeroSerie' => $request->numeroSerie
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Producto de hardware registrado exitosamente en el inventario',
                'data' => $productoFisico->load('datosGenerales')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al registrar el producto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::with('datosGenerales')->findOrFail($id);
        return response()->json($producto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $productoFisico = Producto::findOrFail($id);
        $itemGeneral = ProductoServicio::findOrFail($id);

        $request->validate([
            'idCategoria' => 'required|exists:categoria,idCategoria',
            'nombre' => 'required|string|max:150',
            'precioUnitario' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            // Actualizar datos de la tabla padre
            $itemGeneral->update([
                'idCategoria' => $request->idCategoria,
                'nombre' => $request->nombre,
                'precioUnitario' => $request->precioUnitario,
                'garantia' => $request->garantia
            ]);

            // Actualizar datos de la tabla hija
            $productoFisico->update($request->only(['stock', 'marca', 'modelo', 'numeroSerie']));

            DB::commit();
            return response()->json(['message' => 'Producto actualizado con éxito'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Al borrar el registro en la tabla padre, el ON DELETE CASCADE se encarga de limpiar la tabla hija automáticamente
        $itemGeneral = ProductoServicio::findOrFail($id);
        $itemGeneral->delete();

        return response()->json(['message' => 'Producto eliminado correctamente del inventario y catálogo'], 200);
    }
}
