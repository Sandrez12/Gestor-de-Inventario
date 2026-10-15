<x-ui.page title="Panel" subtitle="Resumen de tu inventario">
    <x-slot name="actions">
        <x-ui.button :href="route('entradas.create')" variant="secondary">+ Entrada</x-ui.button>
        <x-ui.button :href="route('salidas.create')">− Salida</x-ui.button>
    </x-slot>

    {{-- Indicadores --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php
            $tarjetas = [
                ['Productos', $totalProductos, 'En el catálogo', 'text-ink-900'],
                ['Unidades en stock', number_format($unidades, 0, ',', '.'), 'Suma de existencias', 'text-ink-900'],
                ['Entradas del mes', '+'.number_format($entradasMes, 0, ',', '.'), 'Unidades compradas', 'text-emerald-700'],
                ['Salidas del mes', '−'.number_format($salidasMes, 0, ',', '.'), 'Unidades vendidas', 'text-indigo-700'],
            ];
        @endphp
        @foreach ($tarjetas as [$etiqueta, $valor, $nota, $color])
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $etiqueta }}</p>
                <p class="mt-2 text-3xl font-bold tabular-nums {{ $color }}">{{ $valor }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $nota }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-5 lg:grid-cols-5">
        {{-- Stock bajo --}}
        <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2" aria-labelledby="t-bajo">
            <div class="flex items-center justify-between">
                <h2 id="t-bajo" class="font-semibold text-ink-900">Stock bajo</h2>
                @if ($totalStockBajo > 0)
                    <x-ui.badge tone="amber">{{ $totalStockBajo }} {{ $totalStockBajo === 1 ? 'producto' : 'productos' }}</x-ui.badge>
                @endif
            </div>
            <ul class="mt-4 divide-y divide-gray-100">
                @forelse ($stockBajo as $p)
                    <li class="flex items-center justify-between gap-3 py-2.5 text-sm">
                        <a href="{{ route('productos.edit', $p) }}" class="font-medium text-gray-800 hover:text-indigo-700">{{ $p->nombre }}</a>
                        <x-ui.badge :tone="$p->stock === 0 ? 'red' : 'amber'">{{ $p->stock === 0 ? 'Agotado' : $p->stock.' uds.' }}</x-ui.badge>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-500">Todo en orden: ningún producto con stock bajo.</li>
                @endforelse
            </ul>
            <p class="mt-3 text-xs text-gray-400">Se considera stock bajo {{ \App\Models\Producto::STOCK_BAJO }} unidades o menos.</p>
        </section>

        {{-- Últimos movimientos --}}
        <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-3" aria-labelledby="t-mov">
            <h2 id="t-mov" class="font-semibold text-ink-900">Últimos movimientos</h2>
            <ul class="mt-4 divide-y divide-gray-100">
                @forelse ($movimientos as $m)
                    @php $es = $m['tipo'] === 'entrada'; @endphp
                    <li class="flex items-center justify-between gap-3 py-2.5 text-sm">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-gray-800">{{ $m['registro']->producto?->nombre }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $es ? 'Compra a' : 'Venta a' }} {{ $m['tercero'] ?? '—' }} · {{ $m['registro']->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <x-ui.badge :tone="$es ? 'green' : 'red'">{{ $es ? '+' : '−' }}{{ $m['registro']->cantidad }}</x-ui.badge>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-500">Aún no hay movimientos registrados.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-ui.page>
