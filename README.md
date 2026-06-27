# 🖥️ Sistema de Información — Iris Computer

> Sistema web desarrollado con Laravel 12 para la gestión integral de una tienda de computadoras y servicios técnicos.

## 🌐 Repositorio

**URL:** https://github.com/Gabrielcc123/Sistema-de-informaci-n-para-iris-computer

## 🛠️ Tecnologías utilizadas

| Capa | Tecnología |
|------|-------------|
| **Backend** | PHP 8.2 + Laravel 12.60.2 |
| **Base de datos** | MySQL / MariaDB |
| **Frontend** | Blade Templates + Livewire |
| **Autenticación** | Laravel Auth + Breeze (Volt) |
| **Estilos** | Tailwind CSS + CSS personalizado |
| **JavaScript** | Alpine.js + Livewire |
| **Gestor de paquetes**| pnpm |

## 📋 Módulos del sistema

| Módulo | Descripción | Estado |
|--------|-------------|--------|
| 🔐 **Autenticación** | Login, registro, recuperación, protección de rutas | ✅ Implementado |
| 👤 **Usuarios** | CRUD de usuarios del sistema |  ✅ Implementado  |
| 🎭 **Roles** | Gestión de roles y accesos |  ✅ Implementado  |
| 🔐 **Permisos** | Control de acceso por módulo y vistas |  ✅ Implementado |
| 📦 **Productos** | Gestión de inventario de productos |  ✅ Implementado  |
| 📁 **Categorías** | Clasificación de productos |  ✅ Implementado  |
| 👥 **Clientes** | Base de datos de clientes |  ✅ Implementado  |
| 🖥️ **Equipos** | Registro de equipos de clientes |  ✅ Implementado  |
| 📝 **Órdenes** | Gestión de órdenes de servicio técnico | ✅ Implementado  |
| 💰 **Ventas** | Notas de venta y detalles de facturación |  ✅ Implementado  |
| 💳 **Pagos** | Registro y catálogo de pagos | ⏳ Pendiente |
| 📊 **Bitácora** | Auditoría de acciones del sistema |  ✅ Implementado  |
| 🔧 **Servicios** | Catálogo de servicios técnicos |  ✅ Implementado  |
| 🏠 **Dashboard** | Panel de control con estadísticas |  ✅ Implementado  |

## 📁 Estructura del proyecto

```text
Sistema-Web-Tienda-de-Computadoras/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/VerifyEmailController.php
│   │   │   ├── BitacoraController.php
│   │   │   ├── CategoriaController.php
│   │   │   ├── ClienteController.php
│   │   │   ├── Controller.php
│   │   │   ├── DetalleVentaController.php
│   │   │   ├── EquipoController.php
│   │   │   ├── NotaVentaController.php
│   │   │   ├── OrdenController.php
│   │   │   ├── PagoController.php
│   │   │   ├── PermisoController.php
│   │   │   ├── ProductoController.php
│   │   │   ├── ProductoServicioController.php
│   │   │   ├── RolController.php
│   │   │   └── UsuarioController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   ├── Livewire/
│   │   └── Actions/Logout.php
│   ├── Models/
│   │   ├── Bitacora.php
│   │   ├── Categoria.php
│   │   ├── Cliente.php
│   │   ├── DetalleVenta.php
│   │   ├── Equipo.php
│   │   ├── NotaVenta.php
│   │   ├── Orden.php
│   │   ├── Pago.php
│   │   ├── Permiso.php
│   │   ├── Producto.php
│   │   ├── ProductoServicio.php
│   │   ├── Rol.php
│   │   ├── User.php
│   │   └── Usuario.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── VoltServiceProvider.php
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── PermisoSeeder.php
│       ├── RolSeeder.php
│       ├── UserSeeder.php
│       └── UsuarioSeeder.php
├── public/
│   ├── img/
│   │   └── logo.png
│   ├── favicon.ico
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── components/
│       │   ├── layouts/
│       │   │   ├── app/
│       │   │   ├── auth/
│       │   │   ├── app.blade.php
│       │   │   └── auth.blade.php
│       ├── flux/
│       ├── livewire/
│       │   ├── auth/
│       │   │   └── login.blade.php
│       │   └── settings/
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── auth.php
│   ├── console.php
│   └── web.php
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
├── pnpm-lock.yaml
├── README.md
└── vite.config.js
```
## 🔐 Usuarios de prueba

Después de ejecutar los seeders, puedes iniciar sesión con las siguientes credenciales:

| ID | Rol | Email | Contraseña | Permisos |
|----|-----|-------|------------|----------|
| 1 | 👑 Administrador | admin@iris.com | `password` | Acceso total al sistema |
| 2 | 💰 Vendedor | vendedor@iris.com | `password` | Gestión de ventas, productos y clientes |
| 3 | 🔧 Técnico | tecnico@iris.com | `password` | Gestión de equipos y órdenes de servicio |

> ⚠️ **Importante:** Cambiar estas contraseñas en producción antes de desplegar.

### Cómo usar los seeders

```bash
# Ejecutar todos los seeders
php artisan db:seed

# O si quieres reiniciar todo desde cero
php artisan migrate:fresh --seed
```
