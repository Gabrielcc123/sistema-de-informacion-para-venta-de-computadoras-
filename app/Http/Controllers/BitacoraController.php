<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retornamos los logs ordenados por fecha y hora más recientes de manera eficiente
        $logs = Bitacora::with('usuario')
                        ->orderBy('fecha', 'desc')
                        ->orderBy('hora', 'desc')
                        ->get();
        return response()->json($logs, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idUsuario' => 'required|exists:usuario,idUsuario',
            'accion' => 'required|string'
        ]);

        // Automatizamos la captura de red, fecha y hora del servidor de forma nativa
        $log = Bitacora::create([
            'idUsuario' => $request->idUsuario,
            'accion' => $request->accion,
            'ip' => $request->ip() ?? '127.0.0.1',
            'fecha' => now()->toDateString(),
            'hora' => now()->toTimeString()
        ]);

        return response()->json([
            'message' => 'Evento de auditoría registrado',
            'data' => $log
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $log = Bitacora::with('usuario')->findOrFail($id);
        return response()->json($log, 200);
    }

    /**
     * Update/Edit no permitidos en Bitácoras por integridad de software.
     */
    public function update(Request $request, $id)
    {
        return response()->json(['error' => 'Acción no permitida por seguridad del sistema.'], 403);
    }

    /**
     * Destroy no permitido en Bitácoras para evitar alteración de logs.
     */
    public function destroy($id)
    {
        return response()->json(['error' => 'Acción no permitida por seguridad del sistema.'], 403);
    }
}
