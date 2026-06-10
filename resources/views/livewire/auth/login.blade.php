<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';
    public string $password = '';

    public function login()
    {
        // 1. Validar los datos de entrada
        $credentials = $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Verificar si el usuario está bloqueado temporalmente ANTES de consultar la BD
        $this->ensureIsNotRateLimited();

        // 3. Intentar autenticar al usuario
        if (! Auth::attempt($credentials)) {
            // Si falla, sumamos 1 al contador. Si llega a 3, se bloquea por 60 segundos.
            RateLimiter::hit($this->throttleKey(), 60);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // 3.1 Verificar que el usuario no esté suspendido
        $usuario = Auth::user();
        if ($usuario->estado == 0) {
            Auth::logout();
            \App\Models\Bitacora::registrar('Intento de acceso con cuenta suspendida', $usuario->idUsuario);

            throw ValidationException::withMessages([
                'email' => 'Su cuenta ha sido suspendida. Contacte al administrador.',
            ]);
        }

        // 4. Si el login es exitoso, limpiamos el historial de fallos
        RateLimiter::clear($this->throttleKey());

        // 4.1 Registrar en bitácora
        \App\Models\Bitacora::registrar('Login exitoso');

        // 5. CERRAR SESIONES ANTIGUAS
        Auth::logoutOtherDevices($this->password);

        // 6. Regenerar la sesión por seguridad
        session()->regenerate();

        // 7. Redirigir al dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Asegura que la solicitud no haya excedido el límite de intentos (Máximo 3).
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutes = ceil($seconds / 60);

        // BUSCAMOS AL USUARIO Y REGISTRAMOS EL BLOQUEO
        $user = \App\Models\Usuario::where('email', $this->email)->first();
        \App\Models\Bitacora::registrar('Bloqueo temporal de cuenta (Intentos superados)', $user ? $user->id : null);

        throw ValidationException::withMessages([
            'email' => "Demasiados intentos de acceso. Por favor intente nuevamente en {$minutes} minuto(s).",
        ]);
    }

    /**
     * Crea una llave única combinando el correo electrónico y la dirección IP.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
};
?>

<div class="min-h-screen flex items-center justify-center bg-gray-950 relative overflow-hidden">
    
    <div class="absolute inset-0 z-0 opacity-40 mix-blend-screen" 
        style="background-image: url('https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?q=80&w=2000'); background-size: cover; background-position: center;">
    </div>
    <div class="absolute inset-0 z-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative z-10 w-full max-w-sm p-8 bg-[#161618] rounded-2xl shadow-[0_0_50px_rgba(0,0,0,0.8)] border border-gray-800">
        
        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center gap-2 mb-2">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Iris Computer" class="w-12 h-12 object-contain">
                <div class="flex flex-col">
                    <span class="text-blue-500 font-bold text-xl leading-none tracking-wider">IRIS COMPUTER</span>
                    <span class="text-red-500 text-[10px] font-semibold tracking-widest">VENTA DE COMPONENTES</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-200 mt-4">Iniciar Sesión</h2>
        </div>

        <form wire:submit="login" class="space-y-5">
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Correo electrónico</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input wire:model="email" type="email" placeholder="ejemplo@correo.com" 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-700 rounded-lg bg-[#202022] text-gray-200 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 sm:text-sm transition-all duration-300" required>
                </div>
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div x-data="{ showPassword: false }">
                <label class="block text-sm font-medium text-gray-400 mb-1">Contraseña</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input wire:model="password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••" 
                            class="block w-full pl-10 pr-10 py-2.5 border border-gray-700 rounded-lg bg-[#202022] text-gray-200 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 sm:text-sm transition-all duration-300" required>
                    
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-cyan-400 transition-colors">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4m3.045-1.127A9.754 9.754 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.4M9.9 9.9l4.2 4.2m0-4.2l-4.2 4.2" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" 
                    class="w-full flex justify-center py-3 mt-4 border border-transparent rounded-lg text-sm font-bold text-gray-900 bg-cyan-400 hover:bg-cyan-300 shadow-[0_0_15px_rgba(34,211,238,0.5)] hover:shadow-[0_0_25px_rgba(34,211,238,0.8)] focus:outline-none transition-all duration-300">
                Iniciar Sesión
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="#" class="text-xs text-gray-600 hover:text-cyan-400 transition-colors">
                ¿Olvidaste tu contraseña?
            </a>
        </div>
    </div>
</div>