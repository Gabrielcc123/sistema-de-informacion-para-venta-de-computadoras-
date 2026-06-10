<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metodosPago = Pago::all();
        return response()->json($metodosPago, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipoPago' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $pago = Pago::create($request->all());

        return response()->json([
            'message' => 'Nueva modalidad de pago registrada',
            'data' => $pago
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pago = Pago::findOrFail($id);
        return response()->json($pago, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $request->validate([
            'tipoPago' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $pago->update($request->all());

        return response()->json([
            'message' => 'Modalidad de pago actualizada correctamente',
            'data' => $pago
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return response()->json([
            'message' => 'Modalidad de pago eliminada del sistema'
        ], 200);
    }
}