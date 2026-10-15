# Gestor de Inventario

Aplicación web para que empresas con almacenes pequeños controlen sus **productos, clientes y proveedores** y el **movimiento de mercancía** (entradas y salidas), con el stock siempre consistente.

![CI](https://github.com/Sandrez12/Gestor-de-Inventario/actions/workflows/ci.yml/badge.svg)
![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-ready-2496ED?logo=docker&logoColor=white)

## Funcionalidades

- **Panel** con indicadores (productos, unidades en stock, entradas y salidas del mes), productos con **stock bajo** y los últimos movimientos.
- **Productos, clientes y proveedores:** alta, edición, búsqueda por nombre y paginación.
- **Entradas** (compras a proveedores) suman stock y **salidas** (ventas a clientes) lo restan. Una salida nunca puede dejar el stock negativo.
- **Papelera:** los registros eliminados se pueden **restaurar** o borrar de forma definitiva; el historial conserva los nombres aunque se elimine un producto.
- Editar o eliminar un movimiento **recalcula el stock** automáticamente.
- Autenticación completa con Laravel Jetstream: registro, recuperación de contraseña, perfil y autenticación en dos pasos.
- Interfaz en español, responsiva y accesible.

## Cómo se mantiene consistente el stock

Todos los movimientos pasan por `App\Services\StockService`:

- Cada operación corre en una **transacción** y bloquea la fila del producto (`lockForUpdate`), así que si algo falla se deshace todo y dos operaciones simultáneas no se pisan.
- Antes de aplicar un cambio se valida que el resultado **no sea negativo**; si lo fuera, se lanza `InsufficientStockException` y el stock queda intacto.
- Las pruebas cubren los casos límite: editar una salida sin stock suficiente, eliminar una entrada cuyo stock ya se vendió, restaurar una salida sin existencias, cambiar el producto de un movimiento, etc.

## Tecnologías

PHP 8.1+, Laravel 10, Jetstream, Livewire 3, Sanctum, Blade, Tailwind CSS 3, Vite. SQLite por defecto (MySQL/PostgreSQL configurables en `.env`).

## Estructura

```
app/Services/StockService.php        Reglas de inventario (transacciones y validación de stock)
app/Http/Controllers/                Un controlador por módulo + DashboardController
app/Http/Requests/                   Validación (producto, contacto, movimiento)
app/Models/                          Producto, Cliente, Proveedor, Entrada, Salida (con soft delete)
database/factories, seeders/         Datos de prueba y de demostración
resources/views/components/ui/       Componentes Blade reutilizables (tabla, botón, campo, insignia…)
tests/Feature/                       Pruebas del inventario y del servicio de stock
```

## Instalación

Requisitos: PHP 8.1+, Composer y Node.js.

```bash
git clone https://github.com/Sandrez12/Gestor-de-Inventario.git
cd Gestor-de-Inventario

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed

php artisan serve
```

`--seed` carga datos de ejemplo y el usuario **demo@gestor.test / demo1234**. Para desarrollo con recarga de estilos usa `npm run dev` en otra terminal.

Para usar MySQL o PostgreSQL, cambia `DB_CONNECTION` y las variables `DB_*` en `.env`.

## Pruebas

```bash
php artisan test
vendor/bin/pint --test
```

## Despliegue

El `Dockerfile` es multi-etapa (Composer → Vite/Tailwind → PHP + Apache) y `render.yaml` publica el servicio en Render con un clic (**New → Blueprint**).

La configuración incluida es una **demostración pública**: usa SQLite, carga los datos de ejemplo y muestra en el login un botón "Entrar como usuario demo" (`DEMO_MODE=true`). Los datos se reinician con cada despliegue.

Para un uso real:

1. Pon `DEMO_MODE=false`.
2. Define `APP_KEY` (`php artisan key:generate --show`).
3. Conecta una base de datos persistente (`DB_CONNECTION`, `DB_HOST`, …) y configura el correo (`MAIL_*`).

```bash
docker build -t gestor .
docker run -p 8080:80 -e DEMO_MODE=true gestor
```
