<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Entradas</title>
    <style>
        @page { margin: 18px 16px 32px 16px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { font-size: 18px; margin: 0 0 6px 0; }
        .meta { margin-bottom: 12px; color: #475569; }
        .summary { margin-bottom: 12px; }
        .summary div { display: inline-block; margin-right: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 6px; }
        th { background: #f1f5f9; text-align: left; font-size: 11px; }
        .money { text-align: right; white-space: nowrap; }
        .footer { position: fixed; bottom: -18px; right: 0; font-size: 10px; color: #94a3b8; }
        .footer .page:before { content: counter(page); }
        .footer .total:before { content: counter(pages); }
    </style>
</head>
<body>
    <h1>Reporte de Entradas</h1>
    <div class="meta">Rango: {{ $desde }} a {{ $hasta }}</div>

    <div class="summary">
        <div>Registros: {{ $resumen['total_registros'] }}</div>
        <div>Cantidad total: {{ $resumen['total_cantidad'] }}</div>
        <div>Costo total: ${{ number_format($resumen['total_costo'], 2) }}</div>
    </div>

    <table>
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
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->fecha?->format('Y-m-d H:i') }}</td>
                    <td>{{ $row->producto?->nombre ?? 'Sin producto' }}</td>
                    <td>{{ $row->proveedor?->nombre ?? 'Sin proveedor' }}</td>
                    <td>{{ $row->usuario?->name ?? 'Sin usuario' }}</td>
                    <td>{{ $row->cantidad }}</td>
                    <td class="money">${{ number_format((float) $row->costo_unitario, 2) }}</td>
                    <td class="money">${{ number_format((float) ($row->cantidad * $row->costo_unitario), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Pagina <span class="page"></span> de <span class="total"></span></div>
</body>
</html>
