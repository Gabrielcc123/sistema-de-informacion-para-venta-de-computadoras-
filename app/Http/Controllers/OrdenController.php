<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordenes = Orden::with(['equipo', 'notaVenta', 'tecnico'])->get();
        return response()->json($ordenes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idEquipo' => 'required|exists:equipo,idEquipo',
            'idNotaVenta' => 'required|exists:notaVenta,nroNotaVenta',
            'idTecnico' => 'required|exists:usuario,idUsuario',
            'estado' => 'required|string|max:50'
        ]);

        $orden = Orden::create($request->all());

        return response()->json([
            'message' => 'Orden de servicio técnico aperturada con éxito',
            'data' => $orden
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orden = Orden::with(['equipo', 'notaVenta', 'tecnico'])->findOrFail($id);
        return response()->json($orden, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $orden = Orden::findOrFail($id);

        $request->validate([
            'idEquipo' => 'required|exists:equipo,idEquipo',
            'idNotaVenta' => 'required|exists:notaVenta,nroNotaVenta',
            'idTecnico' => 'required|exists:usuario,idUsuario',
            'estado' => 'required|string|max:50'
        ]);

        $orden->update($request->all());

        return response()->json([
            'message' => 'Orden de trabajo actualizada correctamente',
            'data' => $orden
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $orden = Orden::findOrFail($id);
        $orden->delete();

        return response()->json([
            'message' => 'Orden de servicio eliminada correctamente'
        ], 200);
    }
}