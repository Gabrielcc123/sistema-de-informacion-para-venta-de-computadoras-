# 🖥️ ROADMAP DE DESARROLLO — IRIS COMPUTER
> **Sistema:** Gestión interna de venta de componentes y soporte técnico  
> **Stack:** Laravel 12 · Livewire + Volt · Tailwind CSS v4 · Flux · MySQL  
> **Última actualización:** Mayo 2026  

---

## ✅ ESTADO ACTUAL (YA IMPLEMENTADO)

| Módulo | Estado | Notas |
|--------|--------|-------|
| Login con rate limiting (3 intentos / 60s) | ✅ | Registra bloqueo en bitácora |
| Registro de usuario | ✅ | Sobre tabla `users` (Starter Kit) |
| Forgot / Reset password | ✅ | Vía token + email |
| Verificación de email | ✅ | |
| Cambio de contraseña (perfil) | ✅ | Registra en bitácora |
| Sesión única (`AuthenticateSession`) | ✅ | |
| Control de roles con flags booleanos | ✅ | `tipoSupervisor`, `tipoAssesor`, `tipoTecnico` |
| Middleware `RoleMiddleware` | ✅ | Alias `role` |
| Bitácora básica | ✅ | Bloqueo + cambio de contraseña |
| Selector de apariencia (dark/light/system) | ✅ | |
| Rutas protegidas por rol | ✅ | `/bitacora`, `/ventas`, `/ordenes` |
| Tests Pest (Auth, Settings, Dashboard) | ✅ | |

---

## 🗺️ VISIÓN GENERAL DEL ROADMAP

```
FASE 1 — Auth & Seguridad         ✅ CERRADA (base sólida)
FASE 2 — Dashboard & Layout       🔄 EN CURSO (estructura visual)
FASE 3 — Gestión de Usuarios      ⏳ PRÓXIMO PASO
FASE 4 — Inventario               ⏳
FASE 5 — Clientes                 ⏳
FASE 6 — Ventas                   ⏳
FASE 7 — Soporte Técnico          ⏳
FASE 8 — Bitácora Extendida       ⏳
FASE 9 — Reportes & Dashboard     ⏳
FASE 10 — Refinamiento Final      ⏳
```

---

## 🔥 FASE 2 — DASHBOARD & LAYOUT MAESTRO

> **Objetivo:** Convertir el sistema en una plataforma navegable y visual por roles.

### 2.1 Layout maestro unificado

**Archivos a crear/modificar:**
- `resources/views/components/layouts/app.blade.php` — layout principal
- `resources/views/components/layouts/sidebar.blade.php` — menú lateral
- `resources/views/components/layouts/navbar.blade.php` — barra superior

**Lógica del sidebar dinámico:**
```php
// El sidebar debe mostrar módulos según el rol del usuario autenticado
@if(Auth::user()->tipoSupervisor)
    // Ver: Usuarios, Bitácora, Reportes, Ventas, Órdenes, Inventario
@elseif(Auth::user()->tipoAssesor)
    // Ver: Ventas, Clientes, Productos
@elseif(Auth::user()->tipoTecnico)
    // Ver: Órdenes de servicio, Equipos
@endif
```

**Elementos del layout:**
- Logo IRIS COMPUTER
- Nombre y rol del usuario logueado
- Menú de navegación filtrado por rol
- Indicador de sesión activa
- Botón de logout

### 2.2 Dashboard por rol

**Archivos a crear:**
- `resources/views/livewire/dashboard/admin.blade.php`
- `resources/views/livewire/dashboard/tecnico.blade.php`
- `resources/views/livewire/dashboard/vendedor.blade.php`

| Rol | Widgets del dashboard |
|-----|----------------------|
| Supervisor/Admin | Ventas del día, órdenes pendientes, usuarios activos, últimas acciones bitácora |
| Vendedor (Asesor) | Ventas propias del día, nota de venta rápida, clientes recientes |
| Técnico | Órdenes asignadas, equipos pendientes, órdenes finalizadas hoy |

### 2.3 Ruta del dashboard con redirección por rol

```php
// routes/web.php
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->tipoSupervisor) return view('dashboard.admin');
    if ($user->tipoAssesor)    return view('dashboard.vendedor');
    if ($user->tipoTecnico)    return view('dashboard.tecnico');
    return view('dashboard.default');
})->middleware('auth')->name('dashboard');
```

### 2.4 Registrar login/logout en bitácora

**Archivos a modificar:**
- `resources/views/livewire/auth/login.blade.php` — agregar registro post-login
- `app/Livewire/Actions/Logout.php` — agregar registro de logout

```php
// Al hacer login exitoso:
Bitacora::registrar('Inicio de sesión exitoso', $usuario->idUsuario);

// Al hacer logout:
Bitacora::registrar('Cierre de sesión', Auth::id());
```

**✅ Resultado Fase 2:** Interfaz administrativa funcional, navegable y diferenciada por rol.

---

## 🔥 FASE 3 — GESTIÓN DE USUARIOS (CRUD ADMIN)

> **Objetivo:** Que el Supervisor pueda administrar completamente el personal del sistema.

### 3.1 Listado de usuarios

**Archivo:** `resources/views/livewire/usuarios/index.blade.php`  
**Componente:** `app/Livewire/Usuarios/Index.php`

**Tabla a mostrar (campos de `usuario`):**

| idUsuario | Nombre completo | Teléfono | Rol | Estado | Acciones |
|-----------|----------------|----------|-----|--------|----------|

**Funcionalidades:**
- Paginación
- Filtro por estado (activo/inactivo)
- Filtro por rol
- Búsqueda por nombre
- Botones: Editar · Activar/Desactivar · Resetear contraseña

### 3.2 Crear usuario

**Archivo:** `resources/views/livewire/usuarios/crear.blade.php`

**Campos del formulario (tabla `usuario`):**
```
nombre        → requerido, string, máx 100
apellido      → requerido, string, máx 100
password      → requerido, confirmado, Password::defaults()
telefono      → opcional, máx 30
tipoSupervisor → checkbox (solo admins)
tipoAssesor    → checkbox
tipoTecnico    → checkbox
```

> ⚠️ **Regla de negocio:** Un usuario puede tener múltiples roles activos simultáneamente (ej: supervisor + asesor).

**Validación importante:**
- Al crear usuario, `estado = 1` por defecto
- Al menos un rol debe estar activo

### 3.3 Editar usuario

**Archivo:** `resources/views/livewire/usuarios/editar.blade.php`

**Campos editables:**
- nombre, apellido, teléfono
- Cambio de roles (solo supervisor)

> ⚠️ **NO permitir** que un usuario se quite su propio rol de supervisor.

### 3.4 Activar / Desactivar usuario

**Lógica (no eliminar — solo cambiar estado):**
```php
// En el componente Livewire
public function toggleEstado(int $id): void
{
    $usuario = Usuario::findOrFail($id);
    
    // No puede desactivarse a sí mismo
    if ($usuario->idUsuario === Auth::id()) {
        // Mostrar error: "No puedes desactivar tu propia cuenta"
        return;
    }
    
    $usuario->estado = !$usuario->estado;
    $usuario->save();
    
    $accion = $usuario->estado ? 'Usuario activado' : 'Usuario desactivado';
    Bitacora::registrar("$accion: {$usuario->nombre} {$usuario->apellido}");
}
```

### 3.5 Reset de contraseña por admin

> El admin fuerza una contraseña temporal al empleado.

**Flujo:**
1. Admin hace clic en "Resetear contraseña"
2. Modal de confirmación: "¿Resetear contraseña de [Nombre]?"
3. Admin ingresa la nueva contraseña temporal
4. Sistema actualiza y registra en bitácora

```php
Bitacora::registrar("Reset de contraseña para: {$usuario->nombre} {$usuario->apellido}");
```

### 3.6 Rutas del módulo usuarios

```php
Route::middleware(['auth', 'role:supervisor'])->prefix('usuarios')->group(function () {
    Route::get('/',          ...)->name('usuarios.index');
    Route::get('/crear',     ...)->name('usuarios.crear');
    Route::get('/{id}/editar', ...)->name('usuarios.editar');
    // Las acciones de toggle y reset son llamadas Livewire (no rutas GET)
});
```

**✅ Resultado Fase 3:** Administración completa del personal. El supervisor tiene control total sobre accesos y roles.

---

## 🔥 FASE 4 — INVENTARIO

> **Objetivo:** Control de categorías, productos, servicios y stock.

### 4.1 CRUD Categorías

**Tabla:** `categoria (idCategoria, nombre, descripcion)`  
**Archivo:** `resources/views/livewire/inventario/categorias/index.blade.php`

**Operaciones:**
- Listar categorías
- Crear / Editar inline (modal o panel lateral)
- Eliminar (con validación: no eliminar si tiene productos asociados → mostrar error)

### 4.2 CRUD Productos

**Tablas relacionadas:** `productoServicio` + `producto` (relación 1:1 por especialización)

**Al crear un Producto se insertan 2 registros:**
```php
// 1. Registro base en productoServicio
$base = ProductoServicio::create([
    'idCategoria'    => $request->idCategoria,
    'nombre'         => $request->nombre,
    'precioUnitario' => $request->precioUnitario,
    'garantia'       => $request->garantia,
    'tipo'           => 'Producto',
]);

// 2. Registro especializado en producto
Producto::create([
    'idProducto'   => $base->idProductoServicio,
    'stock'        => $request->stock,
    'marca'        => $request->marca,
    'modelo'       => $request->modelo,
    'numeroSerie'  => $request->numeroSerie,
]);
```

**Campos del formulario producto:**

| Campo | Origen tabla | Requerido |
|-------|-------------|-----------|
| Categoría | productoServicio.idCategoria | ✅ |
| Nombre | productoServicio.nombre | ✅ |
| Precio unitario | productoServicio.precioUnitario | ✅ |
| Garantía | productoServicio.garantia | ❌ |
| Stock inicial | producto.stock | ✅ |
| Marca | producto.marca | ❌ |
| Modelo | producto.modelo | ❌ |
| Número de serie | producto.numeroSerie | ❌ |

**Validación de stock:**
```php
// Alerta visual cuando stock < umbral mínimo (ej: 5 unidades)
// No bloquear la venta, solo advertir al asesor
```

### 4.3 CRUD Servicios

**Tabla:** Solo `productoServicio` con `tipo = 'Servicio'`

**Campos del formulario servicio:**
- Categoría
- Nombre del servicio
- Precio unitario
- Garantía / tiempo estimado

> Los servicios **no tienen stock**, por eso no insertan en `producto`.

### 4.4 Vista unificada de inventario

**Archivo:** `resources/views/livewire/inventario/index.blade.php`

**Funcionalidades:**
- Tabla con tabs: "Productos" | "Servicios"
- Filtro por categoría
- Búsqueda por nombre / marca / modelo
- Badge de stock bajo (ej: `< 5` → rojo)
- Acciones: Ver detalle · Editar · Ajustar stock

### 4.5 Ajuste de stock (entrada/salida manual)

> Para correcciones administrativas (no confundir con el descuento automático en ventas).

**Modal de ajuste:**
- Tipo: Entrada / Salida
- Cantidad
- Motivo (texto libre)

```php
// Registrar en bitácora cada ajuste
Bitacora::registrar("Ajuste de stock [{$tipo}] para: {$producto->nombre} — Cantidad: {$cantidad}. Motivo: {$motivo}");
```

**✅ Resultado Fase 4:** Sistema de inventario funcional con trazabilidad de cambios.

---

## 🔥 FASE 5 — CLIENTES

> **Objetivo:** CRUD completo de clientes vinculado al módulo de ventas.

### 5.1 CRUD Clientes

**Tabla:** `cliente (idCliente, ci, nombre, apellido, telefono)`

**Archivo:** `resources/views/livewire/clientes/index.blade.php`

**Campos del formulario:**

| Campo | Regla |
|-------|-------|
| CI | Requerido, único en tabla `cliente` |
| Nombre | Requerido |
| Apellido | Requerido |
| Teléfono | Opcional |

**Funcionalidades:**
- Búsqueda rápida por CI o nombre (para usar dentro de la nota de venta)
- Listar · Crear · Editar
- No eliminar clientes con ventas asociadas

### 5.2 Búsqueda rápida de cliente (componente reutilizable)

> Este componente se usará embebido dentro del formulario de venta.

```php
// Livewire component: BuscadorCliente
// Input de texto → busca en tiempo real por CI o nombre
// Al seleccionar: emite evento con idCliente al componente padre
```

**✅ Resultado Fase 5:** Base de clientes lista para el módulo de ventas.

---

## 🔥 FASE 6 — VENTAS

> **Objetivo:** Núcleo comercial del sistema. Generación de notas de venta con detalle.

### 6.1 Flujo completo de una venta

```
[Asesor selecciona cliente] → [Agrega productos/servicios] 
→ [Selecciona método de pago] → [Confirma venta] 
→ [Sistema genera notaVenta + detalleVenta + descuenta stock]
```

### 6.2 Formulario de nota de venta

**Archivo:** `resources/views/livewire/ventas/crear.blade.php`  
**Tablas involucradas:** `notaVenta`, `detalleVenta`, `pago`, `productoServicio`, `producto`

**Cabecera de la venta (`notaVenta`):**

| Campo | Valor |
|-------|-------|
| idPago | Select de tipos de pago (tabla `pago`) |
| idAssesor | Auth::id() automático |
| idCliente | Buscador de cliente (Fase 5) |
| fecha | Carbon::today() automático |
| total | Calculado automáticamente |

**Detalle de la venta (`detalleVenta`):**
- Buscador de producto/servicio (por nombre o categoría)
- Tabla dinámica de ítems con:
  - Nombre del producto/servicio
  - Cantidad (editable, validar contra stock)
  - Precio unitario (pre-llenado, editable)
  - Subtotal (calculado: cantidad × precioUnitario)
- Botón "+" para agregar ítem
- Botón "×" para eliminar ítem

**Cálculo del total:**
```php
// Recalcular en tiempo real con Livewire
public function calcularTotal(): void
{
    $this->total = collect($this->items)
        ->sum(fn($item) => $item['cantidad'] * $item['precioUnitario']);
}
```

### 6.3 Confirmación y persistencia

```php
// Transacción atómica para evitar inconsistencias
DB::transaction(function () {
    $nota = NotaVenta::create([...]);
    
    foreach ($this->items as $item) {
        DetalleVenta::create([
            'idNotaVenta'        => $nota->nroNotaVenta,
            'idProductoServicio' => $item['id'],
            'cantidad'           => $item['cantidad'],
            'precioUnitario'     => $item['precioUnitario'],
            'subTotal'           => $item['subtotal'],
        ]);
        
        // Descontar stock solo si es Producto (no Servicio)
        if ($item['tipo'] === 'Producto') {
            Producto::where('idProducto', $item['id'])
                ->decrement('stock', $item['cantidad']);
        }
    }
    
    Bitacora::registrar("Venta registrada. Nota #{$nota->nroNotaVenta}. Total: {$nota->total}");
});
```

### 6.4 Listado de ventas

**Archivo:** `resources/views/livewire/ventas/index.blade.php`

**Funcionalidades:**
- Tabla con: Nro. Nota · Fecha · Cliente · Asesor · Método de pago · Total · Acciones
- Filtros: Por fecha, por asesor (solo supervisor ve todos; asesor solo ve los suyos), por método de pago
- Acción: Ver detalle de la venta
- (Fase avanzada) Imprimir / exportar nota de venta

### 6.5 Catálogo de métodos de pago

**Tabla:** `pago (idPago, tipoPago, descripcion)`

**Seeder sugerido:**
```php
['tipoPago' => 'Efectivo',       'descripcion' => 'Pago en efectivo bolivianos'],
['tipoPago' => 'QR',             'descripcion' => 'Pago mediante código QR'],
['tipoPago' => 'Transferencia',  'descripcion' => 'Transferencia bancaria'],
['tipoPago' => 'Tarjeta',        'descripcion' => 'Tarjeta de débito/crédito'],
```

**✅ Resultado Fase 6:** Sistema comercial operativo con control de inventario automático.

---

## 🔥 FASE 7 — SOPORTE TÉCNICO (ÓRDENES)

> **Objetivo:** Gestión de mantenimiento, reparación y ensamblaje de equipos.

### 7.1 Registro de equipos

**Tabla:** `equipo (idEquipo, marca, modelo, numeroSerie, estado, descripcion, gama)`

**Archivo:** `resources/views/livewire/equipos/index.blade.php`

**Campos del formulario:**

| Campo | Descripción |
|-------|-------------|
| Marca | Ej: HP, Lenovo, Dell |
| Modelo | Ej: Pavilion 15 |
| Número de serie | Identificador único del equipo |
| Estado | Ej: Recibido · En diagnóstico · En reparación · Finalizado · Entregado |
| Descripción | Problema reportado por el cliente |
| Gama | Ej: Básica · Media · Alta |

### 7.2 Creación de órdenes técnicas

**Tabla:** `orden (idNotaVenta, idEquipo, idTecnico, estado)`

> ⚠️ **Relación clave:** Una orden está ligada a una `notaVenta`. Es decir, el servicio técnico **siempre genera una venta** (aunque sea de costo 0).

**Flujo para crear una orden:**
1. Se crea (o selecciona) el equipo del cliente
2. Se genera la nota de venta con el servicio técnico como ítem
3. Se asigna un técnico (usuario con `tipoTecnico = 1`)
4. Se establece estado inicial: `Pendiente`

**Estados del flujo técnico:**
```
Pendiente → En diagnóstico → En reparación → Finalizado → Entregado
```

### 7.3 Vista del técnico

**Archivo:** `resources/views/livewire/ordenes/mis-ordenes.blade.php`

**Solo muestra las órdenes asignadas al técnico logueado:**
```php
Orden::where('idTecnico', Auth::id())
     ->whereIn('estado', ['Pendiente', 'En diagnóstico', 'En reparación'])
     ->get();
```

**Acciones del técnico:**
- Ver detalle del equipo y problema
- Cambiar estado de la orden (avanzar al siguiente estado)
- Agregar notas de diagnóstico / reparación

### 7.4 Vista del supervisor sobre órdenes

**Archivo:** `resources/views/livewire/ordenes/index.blade.php`

**Funcionalidades:**
- Ver todas las órdenes (filtrar por técnico, estado, fecha)
- Reasignar técnico
- Cambiar estado manualmente
- Ver historial de cambios de estado

### 7.5 Registro de cambios de estado en bitácora

```php
Bitacora::registrar(
    "Orden #{$orden->idNotaVenta}-{$orden->idEquipo} cambió a estado: {$nuevoEstado}"
);
```

**✅ Resultado Fase 7:** Módulo técnico completo con trazabilidad de reparaciones.

---

## 🔥 FASE 8 — BITÁCORA EXTENDIDA

> **Objetivo:** Auditoría empresarial completa de todas las acciones del sistema.

### 8.1 Eventos a registrar (faltantes)

Completar el método `Bitacora::registrar()` en todos los módulos:

| Evento | Dónde agregar |
|--------|--------------|
| Login exitoso | `auth/login.blade.php` |
| Logout | `Livewire/Actions/Logout.php` |
| Crear usuario | `Livewire/Usuarios/Crear.php` |
| Editar usuario | `Livewire/Usuarios/Editar.php` |
| Activar/Desactivar usuario | `Livewire/Usuarios/Index.php` |
| Reset contraseña (admin) | `Livewire/Usuarios/Index.php` |
| Crear producto/servicio | `Livewire/Inventario/Crear.php` |
| Ajuste de stock | `Livewire/Inventario/AjusteStock.php` |
| Crear cliente | `Livewire/Clientes/Crear.php` |
| Registrar venta | `Livewire/Ventas/Crear.php` |
| Crear orden técnica | `Livewire/Ordenes/Crear.php` |
| Cambio de estado de orden | `Livewire/Ordenes/Detalle.php` |

### 8.2 Panel de bitácora (mejorado)

**Archivo:** `resources/views/livewire/bitacora/index.blade.php` (mejorar el existente)

**Funcionalidades:**
- Tabla: Fecha · Hora · Usuario · Acción · IP
- Filtros: Por usuario, por fecha (rango), por tipo de acción
- Búsqueda en texto de la acción
- Paginación (20 registros por página)
- Solo accesible por `role:supervisor`

**✅ Resultado Fase 8:** Trazabilidad empresarial completa.

---

## 🔥 FASE 9 — REPORTES & MÉTRICAS

> **Objetivo:** Visualización ejecutiva para la toma de decisiones.

### 9.1 Reportes de ventas

**Archivo:** `resources/views/livewire/reportes/ventas.blade.php`

**Métricas:**
- Total vendido por día / semana / mes
- Ventas por asesor
- Productos más vendidos
- Método de pago más usado

### 9.2 Reportes de inventario

- Productos con stock bajo (< umbral)
- Movimientos de stock (entradas/salidas)
- Productos sin movimiento

### 9.3 Reportes de soporte técnico

- Órdenes por técnico
- Tiempo promedio de resolución
- Órdenes por estado
- Equipos más reparados (por marca/modelo)

### 9.4 Dashboard enriquecido

**Agregar al dashboard del supervisor:**
- Gráfico de barras: ventas por día (últimos 7 días)
- Gráfico de torta: distribución de métodos de pago
- Contador en tiempo real: órdenes pendientes hoy

> **Librería sugerida:** Chart.js (compatible con Livewire sin conflictos de Alpine)

**✅ Resultado Fase 9:** Sistema gerencial con métricas operacionales.

---

## 🔥 FASE 10 — REFINAMIENTO FINAL

> **Objetivo:** Pulir el sistema para uso en producción.

### 10.1 UX / Frontend

- [ ] Mensajes flash consistentes (éxito, error, advertencia) usando componentes Flux
- [ ] Loaders / spinners en todas las acciones Livewire lentas
- [ ] Confirmación antes de acciones destructivas (modales)
- [ ] Validaciones en tiempo real (wire:model.live)
- [ ] Responsive en vistas clave (dashboard, ventas, órdenes)
- [ ] Unificar estilo visual de vistas de auth (register, forgot-password) con el diseño corporativo

### 10.2 Seguridad

- [ ] Confirmar que todas las rutas tienen el middleware correcto
- [ ] Revisar que ningún rol accede a datos de otro (ej: asesor solo ve sus ventas)
- [ ] Validar que el stock nunca quede negativo
- [ ] Protección CSRF en todos los formularios (Livewire lo hace automáticamente)
- [ ] Revisar políticas de sesión única

### 10.3 Unificación de modelos de usuario

> **Deuda técnica actual:** Coexisten `User` (Starter Kit) y `Usuario` (negocio).

**Plan:**
1. Migrar la auth completamente a `Usuario`
2. Actualizar el guard en `config/auth.php`:
```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model'  => App\Models\Usuario::class,
    ],
],
```
3. Actualizar todos los componentes Livewire de settings que usen `User`
4. Eliminar el modelo `User` (o mantenerlo solo para tests)

### 10.4 Preparación para producción

- [ ] Configurar `.env` de producción (APP_ENV=production, APP_DEBUG=false)
- [ ] Revisar `config/database.php` para MySQL en producción
- [ ] Ejecutar `php artisan optimize` y `php artisan view:cache`
- [ ] Política de backups de la base de datos
- [ ] HTTPS configurado en el servidor

**✅ Resultado Fase 10:** Sistema listo para entrega y uso real.

---

## 📦 ESTRUCTURA DE ARCHIVOS FINAL ESPERADA

```
resources/views/
├── livewire/
│   ├── auth/                    ✅ Completo
│   ├── settings/                ✅ Completo
│   ├── bitacora/                ✅ (extender en Fase 8)
│   ├── dashboard/               🔄 Fase 2
│   │   ├── admin.blade.php
│   │   ├── vendedor.blade.php
│   │   └── tecnico.blade.php
│   ├── usuarios/                ⏳ Fase 3
│   │   ├── index.blade.php
│   │   ├── crear.blade.php
│   │   └── editar.blade.php
│   ├── inventario/              ⏳ Fase 4
│   │   ├── index.blade.php
│   │   ├── categorias/
│   │   ├── productos/
│   │   └── servicios/
│   ├── clientes/                ⏳ Fase 5
│   │   ├── index.blade.php
│   │   └── crear.blade.php
│   ├── ventas/                  ⏳ Fase 6
│   │   ├── index.blade.php
│   │   ├── crear.blade.php
│   │   └── detalle.blade.php
│   ├── equipos/                 ⏳ Fase 7
│   │   └── index.blade.php
│   ├── ordenes/                 ⏳ Fase 7
│   │   ├── index.blade.php      (supervisor)
│   │   ├── mis-ordenes.blade.php (técnico)
│   │   └── detalle.blade.php
│   └── reportes/                ⏳ Fase 9
│       ├── ventas.blade.php
│       ├── inventario.blade.php
│       └── tecnico.blade.php
```

---

## 🧠 REGLAS DE NEGOCIO IMPORTANTES (RESUMEN)

| Regla | Descripción |
|-------|-------------|
| Nunca eliminar usuarios | Solo desactivar (`estado = 0`) |
| Nunca eliminar clientes con ventas | Mostrar error al intentarlo |
| Stock negativo no permitido | Validar antes de confirmar venta |
| Servicios no tienen stock | Solo `productoServicio`, no insertar en `producto` |
| Orden siempre ligada a nota de venta | Soporte técnico siempre genera venta |
| Asesor solo ve sus propias ventas | Supervisor ve todas |
| Técnico solo ve sus órdenes asignadas | Supervisor ve todas |
| Toda acción crítica → bitácora | Login, logout, ventas, cambios de estado, resets |
| Un usuario puede tener múltiples roles | Flags booleanos independientes |
| Admin no puede desactivarse a sí mismo | Validar `Auth::id() !== $usuario->idUsuario` |

---

## 🔢 ORDEN DE DESARROLLO RECOMENDADO

```
1. [ ] Fase 2  — Dashboard y layout (base visual de todo)
2. [ ] Fase 3  — Gestión de usuarios (control de acceso completo)
3. [ ] Fase 4  — Inventario (base para las ventas)
4. [ ] Fase 5  — Clientes (base para las ventas)
5. [ ] Fase 6  — Ventas (núcleo del negocio)
6. [ ] Fase 7  — Soporte técnico (módulo especializado)
7. [ ] Fase 8  — Bitácora extendida (ir completando en paralelo)
8. [ ] Fase 9  — Reportes (cuando haya datos reales)
9. [ ] Fase 10 — Refinamiento y producción
```

---

*Documentación técnica — IRIS COMPUTER · Fase 2 al 10 · Generado Mayo 2026*