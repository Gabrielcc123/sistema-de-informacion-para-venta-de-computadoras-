<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipos = Equipo::all();
        return response()->json($equipos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'numeroSerie' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
            'gama' => 'nullable|string|max:50'
        ]);

        $equipo = Equipo::create($request->all());

        return response()->json([
            'message' => 'Equipo registrado con éxito en el sistema técnico',
            'data' => $equipo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $equipo = Equipo::findOrFail($id);
        return response()->json($equipo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $equipo = Equipo::findOrFail($id);

        $request->validate([
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'numeroSerie' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
            'gama' => 'nullable|string|max:50'
        ]);

        $equipo->update($request->all());

        return response()->json([
            'message' => 'Ficha técnica del equipo actualizada',
            'data' => $equipo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);
        $equipo->delete();

        return response()->json([
            'message' => 'Registro del equipo eliminado del sistema'
        ], 200);
    }
}
