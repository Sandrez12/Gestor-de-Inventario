<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Entrada;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Salida;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_los_visitantes_son_enviados_al_login(): void
    {
        auth()->logout();

        $this->get('/productos')->assertRedirect('/login');
        $this->get('/')->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_el_panel_muestra_indicadores_y_stock_bajo(): void
    {
        Producto::factory()->conStock(2)->create(['nombre' => 'Producto casi agotado']);
        Producto::factory()->conStock(50)->create(['nombre' => 'Producto abundante']);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Producto casi agotado')
            ->assertSee('Stock bajo')
            ->assertViewHas('totalProductos', 2)
            ->assertViewHas('unidades', 52)
            ->assertViewHas('totalStockBajo', 1);
    }

    public function test_crud_de_productos_con_validacion(): void
    {
        $this->post('/productos', ['nombre' => '', 'stock' => -1])->assertSessionHasErrors(['nombre', 'stock']);

        $this->post('/productos', ['nombre' => 'Teclado', 'descripcion' => 'Mecánico', 'stock' => 8])->assertRedirect('/productos');
        $producto = Producto::firstWhere('nombre', 'Teclado');
        $this->assertSame(8, $producto->stock);

        $this->put("/productos/{$producto->id}", ['nombre' => 'Teclado Pro', 'stock' => 9])->assertRedirect('/productos');
        $this->assertSame('Teclado Pro', $producto->fresh()->nombre);

        $this->delete("/productos/{$producto->id}")->assertRedirect('/productos');
        $this->assertSoftDeleted($producto);
    }

    public function test_la_busqueda_y_la_paginacion_de_productos(): void
    {
        Producto::factory()->count(12)->create();
        Producto::factory()->create(['nombre' => 'Zapato especial']);

        $this->get('/productos')->assertOk()->assertViewHas('registros', fn ($p) => $p->perPage() === 10 && $p->total() === 13);
        $this->get('/productos?q=Zapato')->assertSee('Zapato especial')->assertViewHas('registros', fn ($p) => $p->total() === 1);
    }

    public function test_papelera_de_productos_restaurar_y_borrar_definitivo(): void
    {
        $producto = Producto::factory()->create();
        $producto->delete();

        $this->get('/productos/eliminados')->assertOk()->assertSee($producto->nombre);

        $this->post("/productos/{$producto->id}/restore")->assertRedirect('/productos');
        $this->assertNotSoftDeleted($producto);

        $producto->delete();
        $this->delete("/productos/{$producto->id}/force")->assertRedirect('/productos/eliminados');
        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }

    public function test_clientes_validan_el_correo_y_aceptan_direccion(): void
    {
        $this->post('/clientes', ['nombre' => 'Ana', 'email' => 'no-es-correo'])->assertSessionHasErrors('email');

        $this->post('/clientes', ['nombre' => 'Ana', 'email' => 'ana@example.com', 'direccion' => 'Calle 1'])->assertRedirect('/clientes');
        $this->assertDatabaseHas('clientes', ['nombre' => 'Ana', 'direccion' => 'Calle 1']);
    }

    public function test_crud_de_proveedores(): void
    {
        $this->post('/proveedores', ['nombre' => 'Distribuidora X', 'telefono' => '123'])->assertRedirect('/proveedores');
        $proveedor = Proveedor::firstWhere('nombre', 'Distribuidora X');

        $this->get("/proveedores/{$proveedor->id}/edit")->assertOk()->assertSee('Distribuidora X');
        $this->put("/proveedores/{$proveedor->id}", ['nombre' => 'Distribuidora Y'])->assertRedirect('/proveedores');
        $this->assertSame('Distribuidora Y', $proveedor->fresh()->nombre);
    }

    public function test_registrar_una_entrada_por_http_actualiza_el_stock(): void
    {
        $producto = Producto::factory()->conStock(10)->create();
        $proveedor = Proveedor::factory()->create();

        $this->post('/entradas', ['producto_id' => $producto->id, 'proveedor_id' => $proveedor->id, 'cantidad' => 5])
            ->assertRedirect('/entradas');

        $this->assertSame(15, $producto->fresh()->stock);
        $this->get('/entradas')->assertOk()->assertSee($producto->nombre);
    }

    public function test_una_salida_mayor_al_stock_muestra_el_error_y_no_registra_nada(): void
    {
        $producto = Producto::factory()->conStock(2)->create();
        $cliente = Cliente::factory()->create();

        $this->from('/salidas/create')
            ->post('/salidas', ['producto_id' => $producto->id, 'cliente_id' => $cliente->id, 'cantidad' => 5])
            ->assertRedirect('/salidas/create')
            ->assertSessionHasErrors('cantidad');

        $this->assertSame(2, $producto->fresh()->stock);
        $this->assertSame(0, Salida::count());
    }

    public function test_no_se_pueden_usar_productos_eliminados_en_movimientos(): void
    {
        $producto = Producto::factory()->create();
        $producto->delete();

        $this->post('/entradas', ['producto_id' => $producto->id, 'proveedor_id' => Proveedor::factory()->create()->id, 'cantidad' => 1])
            ->assertSessionHasErrors('producto_id');
    }

    public function test_borrar_definitivamente_una_entrada_solo_borra_esa_entrada(): void
    {
        // Antes se llamaba forceDelete() sobre toda la colección de eliminadas.
        $a = Entrada::factory()->create();
        $b = Entrada::factory()->create();
        $a->delete();
        $b->delete();

        $this->delete("/entradas/{$a->id}/force")->assertRedirect('/entradas/eliminados');

        $this->assertDatabaseMissing('entradas', ['id' => $a->id]);
        $this->assertSoftDeleted($b);
    }

    public function test_el_historial_conserva_el_nombre_de_un_producto_eliminado(): void
    {
        $entrada = Entrada::factory()->create();
        $entrada->producto->delete();

        $this->get('/entradas')->assertOk()->assertSee($entrada->producto->nombre);
    }
}
