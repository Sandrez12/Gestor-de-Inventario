<x-ui.field label="Nombre" name="nombre" :value="$proveedor?->nombre" required />
<x-ui.field label="Correo electrónico" name="email" type="email" :value="$proveedor?->email" />
<x-ui.field label="Teléfono" name="telefono" :value="$proveedor?->telefono" />
<x-ui.field label="Dirección" name="direccion" :value="$proveedor?->direccion" />
