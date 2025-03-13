@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="my-4" style="margin:2%;">Ventas en Efectivo - Hoy</h1>

    <div class="table-responsive">
        <table class="table shadow">
            <thead>
                <tr class="table-warning text-center" >
                    <th style="background-color:#fff; color:grey;">ID venta</th>
                    <th style="background-color:#fff; color:grey">Fecha</th>
                    <th style="background-color:#fff; color:grey">Monto Total</th>
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

    <div class="mt-4">
        <h3>Total en Caja: ${{ number_format($montoTotalHoy, 2) }}</h3>
    </div>
</div>
@endsection
