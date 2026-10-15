<x-ui.field label="Producto" name="producto_id" type="select" required>
    <option value="">Selecciona un producto</option>
    @foreach ($productos as $p)
        <option value="{{ $p->id }}" @selected(old('producto_id', $salida?->producto_id) == $p->id)>{{ $p->nombre }} · {{ $p->stock }} en stock</option>
    @endforeach
</x-ui.field>

<x-ui.field label="Cliente" name="cliente_id" type="select" required>
    <option value="">Selecciona un cliente</option>
    @foreach ($clientes as $t)
        <option value="{{ $t->id }}" @selected(old('cliente_id', $salida?->cliente_id) == $t->id)>{{ $t->nombre }}</option>
    @endforeach
</x-ui.field>

<x-ui.field label="Cantidad" name="cantidad" type="number" min="1" :value="$salida?->cantidad" required />
