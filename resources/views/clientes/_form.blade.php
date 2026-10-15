<x-ui.field label="Nombre" name="nombre" :value="$cliente?->nombre" required />
<x-ui.field label="Correo electrónico" name="email" type="email" :value="$cliente?->email" />
<x-ui.field label="Teléfono" name="telefono" :value="$cliente?->telefono" />
<x-ui.field label="Dirección" name="direccion" :value="$cliente?->direccion" />
