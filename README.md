# Gestor de Inventario

Aplicación web para que empresas con almacenes pequeños controlen sus productos, proveedores, clientes y el movimiento de mercancía (entradas y salidas).

![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-FB70A9)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white)

## Funcionalidades

- **Productos, clientes y proveedores:** alta, edición, listado y eliminación.
- **Entradas y salidas** de inventario.
- **Papelera:** los registros eliminados se pueden consultar, **restaurar** o borrar definitivamente.
- **Autenticación** completa con Laravel Jetstream: registro, verificación de correo, gestión de perfil y autenticación en dos pasos.

## Tecnologías

PHP 8.1+, Laravel 10, Jetstream, Livewire 3, Sanctum, Blade, Tailwind CSS, Vite.

## Estructura

```
app/
  Http/Controllers/   Producto, Cliente, Proveedor, Entrada, Salida
  Models/             Producto, Cliente, Proveedor, Entrada, Salida, User
database/migrations/  Esquema de inventario y autenticación
resources/views/      Vistas Blade por módulo
routes/web.php        Rutas protegidas por autenticación
```

## Instalación

Requisitos: PHP 8.1+, Composer, Node.js y MySQL (o SQLite).

```bash
git clone https://github.com/Sandrez12/Gestor-de-Inventario.git
cd Gestor-de-Inventario

composer install
npm install

cp .env.example .env
php artisan key:generate
# configura la base de datos en .env

php artisan migrate
npm run dev          # en una terminal
php artisan serve    # en otra terminal
```

Abre `http://127.0.0.1:8000` y crea tu usuario desde el registro.
