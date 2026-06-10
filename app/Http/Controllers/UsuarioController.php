<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'estado' => 'required|boolean',
            'tipoAssesor' => 'required|boolean',
            'tipoSupervisor' => 'required|boolean',
            'tipoTecnico' => 'required|boolean'
        ]);

        // Clonamos los datos para encriptar la clave de forma segura antes de insertar
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $usuario = Usuario::create($data);

        return response()->json([
            'message' => 'Usuario registrado con éxito en el sistema',
            'data' => $usuario
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'estado' => 'required|boolean'
        ]);

        $data = $request->all();

        // Solo actualizamos la contraseña si el administrador envió un nuevo valor
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']); // Evita sobreescribir con vacío
        }

        $usuario->update($data);

        return response()->json([
            'message' => 'Datos del usuario actualizados correctamente',
            'data' => $usuario
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente del sistema'
        ], 200);
    }
}