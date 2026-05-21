<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario</title>
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
    <h1>Reporte de Inventario</h1>
    <div class="meta">Rango: {{ $desde }} a {{ $hasta }}</div>

    <div class="summary">
        <div>Productos: {{ $resumen['total_productos'] }}</div>
        <div>Stock total: {{ $resumen['total_stock'] }}</div>
        <div>Valor total: ${{ number_format($resumen['total_valor'], 2) }}</div>
        <div>Bajo stock: {{ $resumen['bajo_stock'] }}</div>
    </div>

    <table>
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
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row->nombre }}</td>
                    <td>{{ $row->categoria?->nombre ?? 'Sin categoria' }}</td>
                    <td>{{ $row->sku ?? $row->codigo_barras }}</td>
                    <td class="money">${{ number_format((float) $row->precio_compra, 2) }}</td>
                    <td class="money">${{ number_format((float) $row->precio_venta, 2) }}</td>
                    <td>{{ $row->stock_actual }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Pagina <span class="page"></span> de <span class="total"></span></div>
</body>
</html>
