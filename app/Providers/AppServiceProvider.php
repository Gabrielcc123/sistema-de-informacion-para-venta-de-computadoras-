<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
// Nuevas importaciones para la bitácora:
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Event;
use App\Models\Bitacora;
use App\Models\Usuario;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Regla fuerte de contraseñas
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

        // 2. AUDITORÍA AUTOMÁTICA DE BITÁCORA (Corregida para tu base de datos)
        
        // Escucha cuando alguien inicia sesión con éxito
        Event::listen(function (Login $event) {
            Bitacora::registrar('Login exitoso', $event->user->idUsuario);
        });

        // Escucha cuando alguien cierra sesión
        Event::listen(function (Logout $event) {
            if ($event->user) {
                Bitacora::registrar('Logout', $event->user->idUsuario);
            }
        });

        // Escucha cuando alguien se equivoca de contraseña
        Event::listen(function (Failed $event) {
            $user = Usuario::where('email', $event->credentials['email'])->first();
            
            // Solo registramos el fallo si el correo le pertenece a un empleado real
            if ($user) {
                Bitacora::registrar('Login fallido', $user->idUsuario);
            }
        });
    }
}