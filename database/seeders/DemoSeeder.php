<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Datos de demostración coherentes: el stock sale de registrar entradas y salidas reales.
 * Usuario de prueba: demo@gestor.test / demo1234
 */
class DemoSeeder extends Seeder
{
    public function run(StockService $stock): void
    {
        User::updateOrCreate(
            ['email' => 'demo@gestor.test'],
            ['name' => 'Usuario Demo', 'password' => Hash::make('demo1234'), 'email_verified_at' => now()]
        );

        if (Producto::exists()) {
            return;
        }

        $productos = collect([
            ['Teclado mecánico 60%', 'Switches rojos, retroiluminado RGB.'],
            ['Mouse inalámbrico', 'Sensor óptico de 16000 DPI, batería recargable.'],
            ['Monitor 24" Full HD', 'Panel IPS, 75 Hz, HDMI y DisplayPort.'],
            ['Audífonos con micrófono', 'Cancelación de ruido, conexión USB-C.'],
            ['Webcam 1080p', 'Enfoque automático y micrófono integrado.'],
            ['Disco SSD 1 TB', 'NVMe M.2, hasta 3500 MB/s.'],
            ['Memoria RAM 16 GB', 'DDR4 3200 MHz, kit de 2 x 8 GB.'],
            ['Cable HDMI 2 m', 'HDMI 2.0 con soporte 4K.'],
        ])->map(fn (array $p) => Producto::create(['nombre' => $p[0], 'descripcion' => $p[1], 'stock' => 0]));

        $proveedores = collect([
            ['Distribuidora Andina', 'ventas@andina.example', '601 555 0101', 'Calle 45 # 12-30, Bogotá'],
            ['TecnoMayorista S.A.S.', 'pedidos@tecnomayorista.example', '604 555 0144', 'Carrera 50 # 8-21, Medellín'],
        ])->map(fn (array $p) => Proveedor::create(['nombre' => $p[0], 'email' => $p[1], 'telefono' => $p[2], 'direccion' => $p[3]]));

        $clientes = collect([
            ['Laura Gómez', 'laura@example.com', '300 555 0182', 'Cra 27 # 36-14, Bucaramanga'],
            ['Compuservicios del Oriente', 'compras@compuservicios.example', '607 555 0166', 'Calle 35 # 19-41, Bucaramanga'],
            ['Andrés Rojas', 'andres.rojas@example.com', '310 555 0199', 'Av. Quebradaseca # 30-11, Bucaramanga'],
        ])->map(fn (array $c) => Cliente::create(['nombre' => $c[0], 'email' => $c[1], 'telefono' => $c[2], 'direccion' => $c[3]]));

        // Compras iniciales a los proveedores
        $compras = [20, 35, 12, 18, 9, 25, 30, 40];
        foreach ($productos as $i => $producto) {
            $stock->registrarEntrada([
                'producto_id' => $producto->id,
                'proveedor_id' => $proveedores[$i % 2]->id,
                'cantidad' => $compras[$i],
            ]);
        }

        // Ventas a los clientes (tres productos quedan con stock bajo a propósito)
        $ventas = [[0, 16], [1, 12], [2, 10], [3, 5], [4, 7], [5, 7], [6, 4], [7, 20], [1, 2]];
        foreach ($ventas as $n => [$indice, $cantidad]) {
            $stock->registrarSalida([
                'producto_id' => $productos[$indice]->id,
                'cliente_id' => $clientes[$n % 3]->id,
                'cantidad' => $cantidad,
            ]);
        }
    }
}
