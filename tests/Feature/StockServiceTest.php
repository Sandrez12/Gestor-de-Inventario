<?php

namespace Tests\Feature;

use App\Exceptions\InsufficientStockException;
use App\Models\Cliente;
use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Salida;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    private StockService $stock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stock = app(StockService::class);
    }

    public function test_una_entrada_suma_stock(): void
    {
        $producto = Producto::factory()->conStock(10)->create();

        $this->stock->registrarEntrada(['producto_id' => $producto->id, 'proveedor_id' => Proveedor::factory()->create()->id, 'cantidad' => 5]);

        $this->assertSame(15, $producto->fresh()->stock);
    }

    public function test_una_salida_resta_stock(): void
    {
        $producto = Producto::factory()->conStock(10)->create();

        $this->stock->registrarSalida(['producto_id' => $producto->id, 'cliente_id' => Cliente::factory()->create()->id, 'cantidad' => 4]);

        $this->assertSame(6, $producto->fresh()->stock);
    }

    public function test_una_salida_sin_stock_suficiente_falla_y_no_deja_rastro(): void
    {
        $producto = Producto::factory()->conStock(3)->create();

        try {
            $this->stock->registrarSalida(['producto_id' => $producto->id, 'cliente_id' => Cliente::factory()->create()->id, 'cantidad' => 4]);
            $this->fail('Debió lanzar InsufficientStockException');
        } catch (InsufficientStockException) {
            $this->assertSame(3, $producto->fresh()->stock);
            $this->assertSame(0, Salida::count());
        }
    }

    public function test_editar_una_salida_sin_stock_suficiente_no_altera_el_stock(): void
    {
        // Antes del arreglo, el stock se devolvía y luego fallaba la validación, dejándolo inflado.
        $producto = Producto::factory()->conStock(10)->create();
        $cliente = Cliente::factory()->create();
        $salida = $this->stock->registrarSalida(['producto_id' => $producto->id, 'cliente_id' => $cliente->id, 'cantidad' => 6]); // stock = 4

        try {
            $this->stock->actualizarSalida($salida, ['producto_id' => $producto->id, 'cliente_id' => $cliente->id, 'cantidad' => 50]);
            $this->fail('Debió lanzar InsufficientStockException');
        } catch (InsufficientStockException) {
            $this->assertSame(4, $producto->fresh()->stock);
            $this->assertSame(6, $salida->fresh()->cantidad);
        }
    }

    public function test_cambiar_el_producto_de_una_salida_ajusta_ambos_productos(): void
    {
        $a = Producto::factory()->conStock(10)->create();
        $b = Producto::factory()->conStock(10)->create();
        $cliente = Cliente::factory()->create();
        $salida = $this->stock->registrarSalida(['producto_id' => $a->id, 'cliente_id' => $cliente->id, 'cantidad' => 4]);

        $this->stock->actualizarSalida($salida, ['producto_id' => $b->id, 'cliente_id' => $cliente->id, 'cantidad' => 3]);

        $this->assertSame(10, $a->fresh()->stock);
        $this->assertSame(7, $b->fresh()->stock);
    }

    public function test_no_se_puede_eliminar_una_entrada_cuyo_stock_ya_se_vendio(): void
    {
        $producto = Producto::factory()->conStock(0)->create();
        $entrada = $this->stock->registrarEntrada(['producto_id' => $producto->id, 'proveedor_id' => Proveedor::factory()->create()->id, 'cantidad' => 5]);
        $this->stock->registrarSalida(['producto_id' => $producto->id, 'cliente_id' => Cliente::factory()->create()->id, 'cantidad' => 4]); // quedan 1

        try {
            $this->stock->eliminarEntrada($entrada);
            $this->fail('Debió lanzar InsufficientStockException');
        } catch (InsufficientStockException) {
            $this->assertSame(1, $producto->fresh()->stock);
            $this->assertNull($entrada->fresh()->deleted_at);
        }
    }

    public function test_eliminar_y_restaurar_una_salida_repone_y_vuelve_a_descontar(): void
    {
        $producto = Producto::factory()->conStock(10)->create();
        $salida = $this->stock->registrarSalida(['producto_id' => $producto->id, 'cliente_id' => Cliente::factory()->create()->id, 'cantidad' => 4]);

        $this->stock->eliminarSalida($salida);
        $this->assertSame(10, $producto->fresh()->stock);

        $this->stock->restaurarSalida(Salida::onlyTrashed()->find($salida->id));
        $this->assertSame(6, $producto->fresh()->stock);
    }

    public function test_restaurar_una_salida_falla_si_ya_no_hay_stock(): void
    {
        $producto = Producto::factory()->conStock(5)->create();
        $cliente = Cliente::factory()->create();
        $salida = $this->stock->registrarSalida(['producto_id' => $producto->id, 'cliente_id' => $cliente->id, 'cantidad' => 5]);
        $this->stock->eliminarSalida($salida); // stock 5
        $this->stock->registrarSalida(['producto_id' => $producto->id, 'cliente_id' => $cliente->id, 'cantidad' => 5]); // stock 0

        $this->expectException(InsufficientStockException::class);

        $this->stock->restaurarSalida(Salida::onlyTrashed()->first());
    }

    public function test_eliminar_y_restaurar_una_entrada(): void
    {
        $producto = Producto::factory()->conStock(0)->create();
        $entrada = $this->stock->registrarEntrada(['producto_id' => $producto->id, 'proveedor_id' => Proveedor::factory()->create()->id, 'cantidad' => 7]);

        $this->stock->eliminarEntrada($entrada);
        $this->assertSame(0, $producto->fresh()->stock);

        $this->stock->restaurarEntrada(Entrada::onlyTrashed()->first());
        $this->assertSame(7, $producto->fresh()->stock);
    }
}
