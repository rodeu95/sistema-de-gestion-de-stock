<h2 style="text-align: center; font-size: 24px; margin-bottom: 10px;">Reporte de Ventas</h2>
<table>
    <thead>
        <tr>
            <th style="background-color: #aed5b6; font-size: 14px;">Método de Pago</th>
            <th style="background-color: #aed5b6; font-size: 14px;">Monto Total</th>
            <th style="background-color: #aed5b6; font-size: 14px;">Fecha de Venta</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ventas as $venta)
            <tr>
                <td>{{ $venta->metodoPago->nombre }}</td>
                <td style="text-align: right;">${{ number_format($venta->monto_total, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
