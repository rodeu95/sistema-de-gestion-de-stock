@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center my-4" style="margin:2%;">Resumen de Ventas en Efectivo - Hoy</h1>

    <div class="table-responsive">
        <table class="table shadow table-hover">
            <thead>
                <tr class="table-warning text-center" >
                    <th style="background-color:#ddd; color:#fff;">ID venta</th>
                    <th style="background-color:#ddd; color:#fff">Fecha</th>
                    <th style="background-color:#ddd; color:#fff">Monto Total</th>
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
