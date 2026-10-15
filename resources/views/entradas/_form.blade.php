<x-ui.field label="Producto" name="producto_id" type="select" required>
    <option value="">Selecciona un producto</option>
    @foreach ($productos as $p)
        <option value="{{ $p->id }}" @selected(old('producto_id', $entrada?->producto_id) == $p->id)>{{ $p->nombre }}</option>
    @endforeach
</x-ui.field>

<x-ui.field label="Proveedor" name="proveedor_id" type="select" required>
    <option value="">Selecciona un proveedor</option>
    @foreach ($proveedores as $t)
        <option value="{{ $t->id }}" @selected(old('proveedor_id', $entrada?->proveedor_id) == $t->id)>{{ $t->nombre }}</option>
    @endforeach
</x-ui.field>

<x-ui.field label="Cantidad" name="cantidad" type="number" min="1" :value="$entrada?->cantidad" required />
