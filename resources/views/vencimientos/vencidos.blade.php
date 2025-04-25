<!-- productos/vencidos.blade.php -->
@extends('layouts.app')

@section('content')
    <h1 class="my-4">Productos Vencidos</h1>

    @if($loteVencido->isEmpty())
        <p id="aviso">
            <i class="fa-regular fa-thumbs-up"></i> No hay productos vencidos
        </p>
    @else
    <div class="container px-2">
        <div class="table-wrapper my-3 shadow-sm rounded-4 border">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Código</th>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Nombre</th>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Fecha de Vencimiento</th>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Número de lote</th>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loteVencido as $lote)
                            <tr>
                                <td>{{ $lote->producto->codigo }}</td>
                                <td>{{ $lote->producto->nombre }}</td>
                                <td>{{ $lote->fecha_vencimiento}} </td>
                                <td>{{ $lote->numero_lote }}</td>
                                <td>{{ $lote->cantidad }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-4 mt-4 mb-5">
            <div class="col-lg-12">
                <div class="card shadow border-0">
                    <div class="card-header">
                        <h5 class="mb-0" style="text-shadow: none;">
                            <div class="icon-box">
                                <i class="fas fa-bolt"></i>
                            </div>
                            Accesos Rápidos</h5>
                    </div>
                    <div class="card-body bg-light d-flex justify-content-around">
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
    @endif
@endsection
