<table>
    <thead>
        <tr>
            <th>Método de pago</th>
            <th>Monto total</th>
            <th>Fecha de Ventas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ventas as $venta)
            <tr>
                <td>{{ $venta->metodoPago->nombre }}</td>
                <td>${{ number_format($venta->monto_total, 2) }}</td>
                <td>{{ $venta->fecha_venta }}</td>
            </tr>
        @endforeach
    </tbody>
</table>