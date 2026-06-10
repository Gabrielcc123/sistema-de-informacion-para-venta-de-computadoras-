<?php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use Illuminate\Http\Request;

class NotaVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Trae las cabeceras de ventas con toda la información vinculada organizada
        $ventas = NotaVenta::with(['cliente', 'pago', 'usuario'])->get();
        return response()->json($ventas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idCliente' => 'required|exists:cliente,idCliente',
            'idPago' => 'required|exists:pago,idPago',
            'idUsuario' => 'required|exists:usuario,idUsuario',
            'total' => 'required|numeric|min:0'
        ]);

        // Automatizamos la fecha de registro al día actual del servidor
        $venta = NotaVenta::create([
            'idCliente' => $request->idCliente,
            'idPago' => $request->idPago,
            'idUsuario' => $request->idUsuario,
            'fecha' => now()->toDateString(),
            'total' => $request->total
        ]);

        return response()->json([
            'message' => 'Cabecera de Nota de Venta registrada con éxito',
            'data' => $venta
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Muestra la nota de venta con sus datos generales y el desglose de sus ítems comprados
        $venta = NotaVenta::with(['cliente', 'pago', 'usuario', 'detalles.productoServicio'])->findOrFail($id);
        return response()->json($venta, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $venta = NotaVenta::findOrFail($id);

        $request->validate([
            'idCliente' => 'required|exists:cliente,idCliente',
            'idPago' => 'required|exists:pago,idPago',
            'idUsuario' => 'required|exists:usuario,idUsuario',
            'total' => 'required|numeric|min:0'
        ]);

        $venta->update($request->all());

        return response()->json([
            'message' => 'Nota de Venta modificada correctamente',
            'data' => $venta
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $venta = NotaVenta::findOrFail($id);
        $venta->delete();

        return response()->json([
            'message' => 'Nota de Venta eliminada del sistema (Se aplicó borrado en cascada a sus detalles)'
        ], 200);
    }
}