@extends('layouts.app')

@section('content')
<div class="container.lg">
    <h1 class="my-4"">Ventas en Efectivo - Hoy</h1>

    <div class="table-wrapper mb-3 shadow">
        <div class="table-responsive rounded-3 overflow-hidden">
            <table class="table mb-0">
                <thead>
                    <tr class="table-warning text-center" >
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">ID venta</th>
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Fecha</th>
                        <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Monto Total</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($totalVentasHoy as $venta)
                    <tr>
                        <td>{{ $venta->id }}</td>
                        <td>{{ $venta->fecha_venta }}</td>
                        <td>${{ number_format($venta->monto_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <div class="mt-4">
        <h3>Total en Caja: ${{ number_format($montoTotalHoy, 2) }}</h3>
    </div>
</div>
@endsection
