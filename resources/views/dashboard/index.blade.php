@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Resumen de Ventas de Hoy -->
            <div class="col-lg-6 ">
                <div class="card mb-6 text-center">
                    <div class="card-header">
                        <h5>Ventas de Hoy</h5>
                    </div>
                    <div class="card-body">
                        <p>Total de Ventas: {{ $totalVentasHoy }}</p>
                        <p>Monto Total: ${{ $montoTotalHoy }}</p>
                    </div>
                </div>
            </div>

            <!-- Productos con Bajo Stock -->
            <div class="col-lg-6">
                <div class="card mb-6 text-center">
                    <div class="card-header">
                        <h5>Productos con Bajo Stock</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($bajoStock as $producto)
                                <li>{{ $producto->nombre }} - {{ $producto->stock }} unidades restantes</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 d-flex justify-content-center">
                <div class="card mb-6 text-center" style="margin: 10px; width: 50%;">
                    <div class="card-header">
                        <h5>Ventas de los últimos 7 días</h5>
                    </div>
                    <div class="card-body">
                        <div style="width: 80%; margin:auto;">
                            <canvas id="ventasChart" 
                                data-labels="{{ json_encode($labels) }}" 
                                data-data="{{ json_encode($data) }}">
                            </canvas>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>

        <!-- Accesos Rápidos -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Accesos Rápidos</h5>
                    </div>
                    <div class="card-body">
                        @can('registrar-venta')
                            <a href="{{ route('ventas.create') }}" class="btn" style="background-color: #aed6b5" onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                            onmouseout="this.style.backgroundColor='#aed6b5';">Registrar venta</a>
                        @endcan
                        @can('agregar-producto')
                            <a href="{{ route('productos.create') }}" class="btn" style="background-color: #aed6b5" onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                            onmouseout="this.style.backgroundColor='#aed6b5';">Agregar producto</a>
                        @endcan
                        <a href="{{ url('historial_ventas') }}" class="btn" style="background-color: #aed6b5" onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                        onmouseout="this.style.backgroundColor='#aed6b5';">Ver Historial de Ventas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
