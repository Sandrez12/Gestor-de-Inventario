<x-ui.field label="Nombre" name="nombre" :value="$producto?->nombre" required />
<x-ui.field label="Descripción" name="descripcion" type="textarea" :value="$producto?->descripcion" />
<x-ui.field label="Stock" name="stock" type="number" min="0" :value="$producto?->stock ?? 0" required
            hint="Para sumar o restar unidades usa Entradas y Salidas; así queda el historial." />
