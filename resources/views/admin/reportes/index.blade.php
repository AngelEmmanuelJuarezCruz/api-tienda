@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="page-title">Reportes del sistema</h1>
            <p class="muted text-sm">Selecciona el tipo de reporte, rango de fechas y formato de descarga.</p>
        </div>
    </div>

    @if (session('warning'))
        <div class="alert-error" style="background:#fff7ed;color:#9a3412;">
            {{ session('warning') }}
        </div>
    @endif

    <div class="panel-white">
        <form method="GET" class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div>
                <label class="form-label" for="tipo">Tipo de reporte</label>
                <select id="tipo" name="tipo">
                    <option value="ventas" {{ $tipo === 'ventas' ? 'selected' : '' }}>Ventas</option>
                    <option value="entradas" {{ $tipo === 'entradas' ? 'selected' : '' }}>Entradas</option>
                    <option value="salidas" {{ $tipo === 'salidas' ? 'selected' : '' }}>Salidas</option>
                    <option value="inventario" {{ $tipo === 'inventario' ? 'selected' : '' }}>Inventario</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="desde">Desde</label>
                <input id="desde" type="date" name="desde" value="{{ $desde }}">
            </div>
            <div>
                <label class="form-label" for="hasta">Hasta</label>
                <input id="hasta" type="date" name="hasta" value="{{ $hasta }}">
            </div>
            <div class="lg:col-span-2 flex items-end gap-3">
                <button type="submit" class="btn btn-outline">Aplicar filtros</button>
            </div>
        </form>
    </div>

    <div class="panel-white">
        <form method="POST" action="{{ route('admin.reportes.export') }}" id="export-form" class="grid grid-cols-1 lg:grid-cols-6 gap-4">
            @csrf
            <input type="hidden" name="tipo" value="{{ $tipo }}">
            <input type="hidden" name="desde" value="{{ $desde }}">
            <input type="hidden" name="hasta" value="{{ $hasta }}">
            <div class="lg:col-span-2">
                <label class="form-label" for="formato">Formato</label>
                <select id="formato" name="formato" required>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                    <option value="ambos">Ambos (ZIP)</option>
                </select>
            </div>
            <div class="lg:col-span-4 flex items-end gap-3">
                <button type="submit" class="btn btn-primary" id="export-btn">Descargar reporte</button>
                <div id="export-status" class="hidden text-sm muted">Generando archivo...</div>
            </div>
            <div class="lg:col-span-6 hidden" id="export-bar">
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full" style="background: rgba(15, 118, 110, 0.12);">
                    <div id="export-progress" class="h-full w-0 rounded-full" style="background: var(--brand);"></div>
                </div>
            </div>
        </form>
    </div>

    <div class="panel-white">
        @if ($tipo === 'ventas')
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <div class="text-xs muted">Ventas</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_ventas'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Total</div>
                    <div class="text-2xl font-semibold">${{ number_format($resumen['total_monto'], 2) }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Items vendidos</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_items'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Ticket promedio</div>
                    <div class="text-2xl font-semibold">${{ number_format($resumen['ticket_promedio'], 2) }}</div>
                </div>
            </div>

            <div class="mb-4">
                <div class="text-sm font-semibold">Top productos</div>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($resumen['top_productos'] as $item)
                        <span class="badge">{{ $item['producto']?->nombre ?? 'Producto' }} ({{ $item['cantidad'] }})</span>
                    @endforeach
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row->folio }}</td>
                                <td>{{ $row->fecha?->format('Y-m-d H:i') }}</td>
                                <td>{{ $row->usuario?->name ?? 'Sin usuario' }}</td>
                                <td>${{ number_format((float) $row->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center muted">Sin resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @elseif ($tipo === 'entradas')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <div class="text-xs muted">Registros</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_registros'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Cantidad total</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_cantidad'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Costo total</div>
                    <div class="text-2xl font-semibold">${{ number_format($resumen['total_costo'], 2) }}</div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Usuario</th>
                            <th>Cantidad</th>
                            <th>Costo unitario</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row->fecha?->format('Y-m-d H:i') }}</td>
                                <td>{{ $row->producto?->nombre ?? 'Sin producto' }}</td>
                                <td>{{ $row->proveedor?->nombre ?? 'Sin proveedor' }}</td>
                                <td>{{ $row->usuario?->name ?? 'Sin usuario' }}</td>
                                <td>{{ $row->cantidad }}</td>
                                <td>${{ number_format((float) $row->costo_unitario, 2) }}</td>
                                <td>${{ number_format((float) ($row->cantidad * $row->costo_unitario), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center muted">Sin resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @elseif ($tipo === 'salidas')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <div class="text-xs muted">Registros</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_registros'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Cantidad total</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_cantidad'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Motivos</div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($resumen['motivos'] as $motivo => $count)
                            <span class="badge">{{ $motivo }} ({{ $count }})</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Usuario</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row->fecha?->format('Y-m-d H:i') }}</td>
                                <td>{{ $row->producto?->nombre ?? 'Sin producto' }}</td>
                                <td>{{ $row->usuario?->name ?? 'Sin usuario' }}</td>
                                <td>{{ $row->cantidad }}</td>
                                <td>{{ $row->motivo }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center muted">Sin resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <div class="text-xs muted">Productos</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_productos'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Stock total</div>
                    <div class="text-2xl font-semibold">{{ $resumen['total_stock'] }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Valor total</div>
                    <div class="text-2xl font-semibold">${{ number_format($resumen['total_valor'], 2) }}</div>
                </div>
                <div>
                    <div class="text-xs muted">Bajo stock</div>
                    <div class="text-2xl font-semibold">{{ $resumen['bajo_stock'] }}</div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoria</th>
                            <th>SKU</th>
                            <th>Precio compra</th>
                            <th>Precio venta</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row->nombre }}</td>
                                <td>{{ $row->categoria?->nombre ?? 'Sin categoria' }}</td>
                                <td>{{ $row->sku ?? $row->codigo_barras }}</td>
                                <td>${{ number_format((float) $row->precio_compra, 2) }}</td>
                                <td>${{ number_format((float) $row->precio_venta, 2) }}</td>
                                <td>{{ $row->stock_actual }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center muted">Sin resultados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('export-form');
        const btn = document.getElementById('export-btn');
        const status = document.getElementById('export-status');
        const bar = document.getElementById('export-bar');
        const progress = document.getElementById('export-progress');

        if (!form || !btn || !status || !bar || !progress) return;

        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed');
            status.classList.remove('hidden');
            bar.classList.remove('hidden');
            progress.style.width = '12%';

            let value = 12;
            const timer = setInterval(() => {
                value = Math.min(value + 10, 90);
                progress.style.width = `${value}%`;
            }, 600);

            setTimeout(() => {
                clearInterval(timer);
                progress.style.width = '100%';
                status.textContent = 'Exportacion enviada. Evita dar clic varias veces.';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    status.classList.add('hidden');
                    bar.classList.add('hidden');
                    status.textContent = 'Generando archivo...';
                    progress.style.width = '0%';
                }, 4000);
            }, 8000);
        });
    });
</script>
@endsection
