@extends('layouts.app')

@section('content')
    <div class="container mt-5 ">
        <div class="row g-4">
            <!-- Resumen de Ventas de Hoy -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header text-center" style="background-color:#aed6b5">
                        <h5 class="mb-0 text-white"><i class="fas fa-chart-line me-2"></i>Ventas de Hoy</h5>
                    
                        <div class="card-body">
                            <section class="bg-light p-4 rounded shadow section-index">
                                <p class="fs-5">Total de Ventas: <strong>{{ $totalVentasHoy }}</strong></p>
                                <p class="fs-5">Monto Total: <strong>${{ number_format($montoTotalHoy, 2) }}</strong></p>
                                <p class="fs-5">
                                    @can('registrar-venta')
                                        <a href="{{ route('ventas.create') }}" class="btn btn-access" >
                                            <i class="fas fa-cash-register me-2"></i>Nueva venta
                                        </a>
                                    @endcan
                                </p>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card shadow border-0">
                        <div class="card-header  text-center" style="background-color:#aed6b5">
                            <h5 class="mb-0 text-white"><i class="fas fa-calendar-week me-2"></i>Ventas de los Últimos 7 Días</h5>
                        
                                <div class="card-body">
                                    <section class="bg-light p-4 rounded shadow section-index">
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
            <div class="col-lg-6 d-flex flex-column gap-3">
               

                @if(count($productosVencidos) > 0)
                    <a href="{{ route('productos.vencidos') }}" class="btn btn-danger text-white btn-sm w-100 d-flex justify-content-between align-items-center ">
                        <span><i class="fas fa-exclamation-circle me-2"></i>Hay productos vencidos</span>
                        <span class="badge bg-white text-danger cantidad">{{ count($productosVencidos) }}</span>
                    </a>
                @endif

                @if(count($productosProximosAVencer) > 0)
                    <a href="{{ route('productos.por-vencer') }}" class="btn text-white btn-mid-warning btn-sm w-100 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-exclamation-circle me-2"></i>Hay productos próximos a vencerse</span>
                        <span class="badge bg-white text-warning cantidad">{{ count($productosProximosAVencer) }}</span>
                    </a>
                @endif

                @if(count($bajoStock) > 0)
                    <a href="{{ route('inventario.edit' ) }}" class="btn btn-warning text-white btn-sm w-100 d-flex justify-content-between align-items-center ">
                        <span><i class="fas fa-exclamation-circle me-2"></i>Hay productos con bajo stock</span>
                        <span class="badge bg-white text-warning cantidad">{{ count($bajoStock) }}</span>
                    </a>
                @endif
 
                <div class="card shadow border-0">
                    <div class="card-header  text-center" style="background-color:#aed6b5">
                        <h5 class="mb-0 text-white"><i class="fa-solid fa-chart-pie"></i> Productos Más Vendidos</h5>

                        <div class="card-body">
                            <section class="bg-light p-4 rounded shadow section-index">
                                <div style="width: 300px; height: 300px; margin: 0 auto;">
                                    <canvas id="topProductosChart"
                                    data-labels = "{{ json_encode($labelsTop) }}"
                                    data-data = "{{ json_encode($dataTop) }}">
                                    </canvas>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>                    
                
            </div>
                            
        </div>

       <!-- Accesos Rápidos -->
        <div class="row g-4 mt-4 mb-5">
            <div class="col-lg-12">
                <div class="card shadow border-0">
                    <div class="card-header text-dark" style="background-color:#aed6b5">
                        <h5 class="mb-0 text-white"><i class="fas fa-bolt me-2"></i>Accesos Rápidos</h5>
                    </div>
                    <div class="card-body d-flex justify-content-around">
                        @can('registrar-venta')
                            <a href="{{ route('ventas.create') }}" class="btn btn-access" >
                                <i class="fas fa-cash-register me-2"></i>Registrar Venta
                            </a>
                        @endcan
                        @can('agregar-producto')
                            <a href="{{ route('productos.create') }}" class="btn btn-access">
                                <i class="fas fa-plus-circle me-2"></i>Agregar Producto
                            </a>
                        @endcan
                        @can('ver-ventas')
                            <a href="{{ route('ventas.index') }}" class="btn btn-access" >
                                <i class="fas fa-history me-2"></i>Ver Historial de Ventas
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('js')

<script>
    function toggleButtons() {
        document.querySelector('.container-botones').classList.toggle('active');
    }
</script>
@endpush
@endsection
