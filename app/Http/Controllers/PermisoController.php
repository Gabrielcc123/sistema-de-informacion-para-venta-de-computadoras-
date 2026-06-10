<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permisos = Permiso::all();
        return response()->json($permisos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:permiso,nombre',
            'descripcion' => 'nullable|string'
        ]);

        $permiso = Permiso::create($request->all());

        return response()->json([
            'message' => 'Permiso del sistema creado con éxito',
            'data' => $permiso
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permiso = Permiso::findOrFail($id);
        return response()->json($permiso, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:permiso,nombre,' . $id . ',idPermiso',
            'descripcion' => 'nullable|string'
        ]);

        $permiso->update($request->all());

        return response()->json([
            'message' => 'Permiso actualizado correctamente',
            'data' => $permiso
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return response()->json([
            'message' => 'Permiso eliminado de los registros de seguridad'
        ], 200);
    }
}