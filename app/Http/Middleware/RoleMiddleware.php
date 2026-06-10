<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            if ($role === 'supervisor' && $user->tipoSupervisor) return $next($request);
            if ($role === 'vendedor' && $user->tipoAssesor) return $next($request);
            if ($role === 'tecnico' && $user->tipoTecnico) return $next($request);
        }

        abort(403, 'No tienes permisos para acceder a este módulo.');
    }
}