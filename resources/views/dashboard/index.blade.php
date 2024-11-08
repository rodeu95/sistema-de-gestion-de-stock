@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row g-4">
            <!-- Resumen de Ventas de Hoy -->
            <div class="col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-header text-center" style="background-color:#aed6b5">
                        <h5 class="mb-0 text-white"><i class="fas fa-chart-line me-2"></i>Ventas de Hoy</h5>
                    
                            <div class="card-body">
                                <section class="bg-light p-4 rounded shadow">
                                    <p class="fs-5">Total de Ventas: <strong>{{ $totalVentasHoy }}</strong></p>
                                    <p class="fs-5">Monto Total: <strong>${{ number_format($montoTotalHoy, 2) }}</strong></p>
                                </section>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Productos con Bajo Stock -->
            <div class="col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-header text-center" style="background-color:#aed6b5">
                        <h5 class="mb-0 text-white"><i class="fas fa-exclamation-triangle me-2"></i>Productos con Bajo Stock</h5>
                    
                            <div class="card-body">
                                <section class="bg-light p-4 rounded shadow">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($bajoStock as $producto)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $producto->nombre }}
                                                @if($producto->unidad === 'UN')
                                                    <span class="badge text-bg-danger ">{{ $producto->stock }} unidades</span>
                                                @else
                                                    <span class="badge text-bg-danger ">{{ $producto->stock }} kg.</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </section>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas de los últimos 7 días -->
        <div class="row g-4 mt-4">
            <div class="col-lg-12">
                <div class="card shadow border-0">
                    <div class="card-header  text-center" style="background-color:#aed6b5">
                        <h5 class="mb-0 text-white"><i class="fas fa-calendar-week me-2"></i>Ventas de los Últimos 7 Días</h5>
                    
                            <div class="card-body">
                                <section class="bg-light p-4 rounded shadow">
                                    <canvas id="ventasChart" 
                                            data-labels="{{ json_encode($labels) }}" 
                                            data-data="{{ json_encode($data) }}">
                                    </canvas>
                                </section>
                            </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="row g-4 mt-4">
            <div class="col-lg-12">
                <div class="card shadow border-0">
                    <div class="card-header text-dark" style="background-color:#aed6b5">
                        <h5 class="mb-0 text-white"><i class="fas fa-bolt me-2"></i>Accesos Rápidos</h5>
                    </div>
                    <div class="card-body d-flex justify-content-around">
                        @can('registrar-venta')
                            <a href="{{ route('ventas.create') }}" class="btn shadow btn-success" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                <i class="fas fa-cash-register me-2"></i>Registrar Venta
                            </a>
                        @endcan
                        @can('agregar-producto')
                            <a href="{{ route('productos.create') }}" class="btn shadow btn-primary" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                <i class="fas fa-plus-circle me-2"></i>Agregar Producto
                            </a>
                        @endcan
                        <a href="{{ route('ventas.index') }}" class="btn shadow btn-warning" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                            <i class="fas fa-history me-2"></i>Ver Historial de Ventas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
