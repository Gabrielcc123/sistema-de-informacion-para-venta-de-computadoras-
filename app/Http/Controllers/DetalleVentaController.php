<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = DetalleVenta::with('productoServicio')->get();
        return response()->json($detalles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idNotaVenta' => 'required|exists:notaVenta,nroNotaVenta',
            'idProductoServicio' => 'required|exists:productoServicio,idProductoServicio',
            'cantidad' => 'required|integer|min:1',
            'precioUnitario' => 'required|numeric|min:0'
        ]);

        // Calculamos el subtotal de forma automatizada en el backend antes de insertar
        $subTotal = $request->cantidad * $request->precioUnitario;

        $detalle = DetalleVenta::create([
            'idNotaVenta' => $request->idNotaVenta,
            'idProductoServicio' => $request->idProductoServicio,
            'cantidad' => $request->cantidad,
            'precioUnitario' => $request->precioUnitario,
            'subTotal' => $subTotal
        ]);

        return response()->json([
            'message' => 'Renglón de detalle agregado correctamente',
            'data' => $detalle
        ], 201);
    }

    /**
     * Display the specified resource using composite keys.
     */
    public function show($idNotaVenta, $idProductoServicio)
    {
        $detalle = DetalleVenta::with('productoServicio')
            ->where('idNotaVenta', $idNotaVenta)
            ->where('idProductoServicio', $idProductoServicio)
            ->firstOrFail();

        return response()->json($detalle, 200);
    }

    /**
     * Update the specified resource using composite keys.
     */
    public function update(Request $request, $idNotaVenta, $idProductoServicio)
    {
        $detalle = DetalleVenta::where('idNotaVenta', $idNotaVenta)
            ->where('idProductoServicio', $idProductoServicio)
            ->firstOrFail();

        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'precioUnitario' => 'required|numeric|min:0'
        ]);

        $subTotal = $request->cantidad * $request->precioUnitario;

        $detalle->where('idNotaVenta', $idNotaVenta)
                ->where('idProductoServicio', $idProductoServicio)
                ->update([
                    'cantidad' => $request->cantidad,
                    'precioUnitario' => $request->precioUnitario,
                    'subTotal' => $subTotal
                ]);

        return response()->json(['message' => 'Renglón de detalle actualizado con éxito'], 200);
    }

    /**
     * Remove the specified resource using composite keys.
     */
    public function destroy($idNotaVenta, $idProductoServicio)
    {
        $detalle = DetalleVenta::where('idNotaVenta', $idNotaVenta)
            ->where('idProductoServicio', $idProductoServicio)
            ->firstOrFail();

        $detalle->where('idNotaVenta', $idNotaVenta)
                ->where('idProductoServicio', $idProductoServicio)
                ->delete();

        return response()->json(['message' => 'Renglón eliminado del detalle'], 200);
    }
}