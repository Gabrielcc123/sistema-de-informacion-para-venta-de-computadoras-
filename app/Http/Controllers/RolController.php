<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::all();
        return response()->json($roles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:rol,nombre',
            'descripcion' => 'nullable|string'
        ]);

        $rol = Rol::create($request->all());

        return response()->json([
            'message' => 'Rol de usuario creado con éxito',
            'data' => $rol
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Retorna el rol incluyendo la lista de permisos vinculados (Eager Loading)
        $rol = Rol::with('permisos')->findOrFail($id);
        return response()->json($rol, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:rol,nombre,' . $id . ',idRol',
            'descripcion' => 'nullable|string'
        ]);

        $rol->update($request->all());

        return response()->json([
            'message' => 'Rol actualizado correctamente',
            'data' => $rol
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return response()->json([
            'message' => 'Rol eliminado del sistema'
        ], 200);
    }
}
