<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Verifica que el usuario autenticado tenga estado activo (1).
     * Si está suspendido, cierra la sesión y redirige al login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $usuario = Auth::user();

            if ($usuario->estado == 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Su cuenta ha sido suspendida. Contacte al administrador.');
            }
        }

        return $next($request);
    }
}
