<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt; // <-- IMPORTANTE: Asegurar que Volt esté importado arriba
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\NotaVentaController;
use App\Http\Controllers\OrdenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Redirección de la raíz al login
Route::redirect('/', '/login');

// 2. Grupo de rutas protegidas globales (El usuario debe estar logeado)
Route::middleware(['auth'])->group(function () {
    
    // Vista del Dashboard principal con redirección por rol
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->tipoSupervisor) {
            return view('livewire.dashboard.admin_page');
        }

        if ($user->tipoAssesor) {
            return view('livewire.dashboard.vendedor_page');
        }

        if ($user->tipoTecnico) {
            return view('livewire.dashboard.tecnico_page');
        }

        // Fallback por seguridad
        return view('livewire.dashboard.admin_page');
    })->name('dashboard');

    // ---------------------------------------------------------------------
    // 🛠️ RUTAS DE CONFIGURACIÓN DE PERFIL (Restauradas del Starter Kit)
    // ---------------------------------------------------------------------
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // ---------------------------------------------------------------------
// ---------------------------------------------------------------------
    // 📊 MÓDULO BITÁCORA: Solo el Administrador (tipoSupervisor = 1)
    // ---------------------------------------------------------------------
    Route::middleware(['role:supervisor'])->group(function () {
        // Quitamos el BitacoraController y usamos Volt directamente
        Volt::route('/bitacora', 'bitacora.index')->name('bitacora.index');

        // -----------------------------------------------------------------
        // 👤 MÓDULO USUARIOS: Solo Administradores
        // -----------------------------------------------------------------
        Volt::route('/usuarios', 'usuarios.index')->name('usuarios.index');
        Volt::route('/usuarios/crear', 'usuarios.crear')->name('usuarios.crear');
    });

    // ---------------------------------------------------------------------
    // 💰 MÓDULO VENTAS: Administradores y Vendedores
    // ---------------------------------------------------------------------
    Route::middleware(['role:supervisor,vendedor'])->group(function () {
        Volt::route('/ventas', 'ventas.index')->name('ventas.index');
        Volt::route('/ventas/crear', 'ventas.crear')->name('ventas.crear');
        Volt::route('/ventas/{id}/detalle', 'ventas.detalle')->name('ventas.detalle');
    });

    // ---------------------------------------------------------------------
    // 🖥️ MÓDULO SOPORTE TÉCNICO: Equipos y Órdenes
    // ---------------------------------------------------------------------
    Route::middleware(['auth', 'role:supervisor,vendedor,tecnico'])->group(function () {
        Volt::route('/equipos', 'equipos.index')->name('equipos.index');
    });

    Route::middleware(['auth', 'role:supervisor'])->group(function () {
        Volt::route('/ordenes', 'ordenes.index')->name('ordenes.index');
    });

    Route::middleware(['auth', 'role:tecnico'])->group(function () {
        Volt::route('/ordenes/mis-ordenes', 'ordenes.mis-ordenes')->name('ordenes.mis-ordenes');
    });

    // ---------------------------------------------------------------------
    // 📦 MÓDULO INVENTARIO: Productos y Servicios
    // ---------------------------------------------------------------------
    Volt::route('/inventario', 'inventario.index')->name('inventario.index');
    Volt::route('/inventario/crear', 'inventario.crear')->name('inventario.crear');

    // ---------------------------------------------------------------------
    // 👥 MÓDULO CLIENTES
    // ---------------------------------------------------------------------
    Volt::route('/clientes', 'clientes.index')->name('clientes');

    // ---------------------------------------------------------------------
    // 🏷️ MÓDULO CATEGORÍAS: Solo Administradores
    // ---------------------------------------------------------------------
    Route::middleware(['role:supervisor'])->group(function () {
        Volt::route('/categorias', 'categorias.index')->name('categorias.index');
    });

    // ---------------------------------------------------------------------
    // 📊 MÓDULO REPORTES: Solo Administradores
    // ---------------------------------------------------------------------
    Route::middleware(['role:supervisor'])->group(function () {
        Volt::route('/reportes', 'reportes.index')->name('reportes.index');
    });
});

// 3. Carga de las rutas de autenticación de Livewire/Volt
require __DIR__.'/auth.php';