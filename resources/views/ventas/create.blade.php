@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <h3 style="margin:2%;">Registrar Venta</h3>
    
    <!-- Mostrar mensaje de advertencia si la caja está cerrada -->
    @if(!$cajaAbierta)
        <div class="alert alert-danger">
            La caja está cerrada. No puedes registrar ventas en este momento.
        </div>
    @endif

    <!-- Formulario de Registro de Venta -->
    <form action="{{ route('ventas.store') }}" method="POST" id="ventasForm">
        @csrf
        <div class="row">
            <div class="col-lg-4">
                <!-- Sección de selección de productos -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="justify-content text-white text-center">Seleccionar Producto</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="producto-select" class="form-label" style="color: #aed5b6; ">Producto</label>
                            <select id="producto-select" class="form-select" name="producto_cod[]">
                                <option value="" disabled selected>Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    @if($producto->stock > 0 && $producto->estado === 1)
                                        <option value="{{ $producto->codigo }}" data-precio="{{ $producto->precio_venta }}" data-unidad="{{$producto->unidad}}" data-stock="{{$producto->stock}}">
                                            {{ $producto->nombre }} - ${{ $producto->precio_venta }} x {{$producto->unidad}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cantidad-input" class="form-label" style="color: #aed5b6; ">Cantidad</label>
                            <input 
                                type="number" 
                                id="cantidad-input" 
                                class="form-control" 
                                name="cantidad" 
                                value="" 
                                min="1"
                            >
                        </div>

                        <button type="button" id="add-product" class="btn btn-secondary mb-3" @if(!$cajaAbierta) disabled @endif style="background-color: grey">Agregar Producto</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Sección de productos seleccionados -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="justify-content text-white text-center">Productos Seleccionados</h5>
                    </div>
                    <div class="card-body">
                        <ul id="product-list" class="list-group mb-3">
                            <!-- Aquí se agregarán los productos seleccionados -->
                        </ul>

                        <div id="hidden-inputs"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Sección de monto total -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="justify-content text-white text-center">Monto Total</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="monto_total" class="form-label" style="color: #aed5b6; ">Monto Total</label>
                            <input type="number" name="monto_total" id="monto_total" class="form-control form-control-xl" style="font-size: 2.5rem;" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pago_id" class="form-label" style="color: #aed5b6; ">Método de Pago</label>
                            <select class="form-select" name="metodo_pago_id" id="metodo_pago_id">
                                <option value="" disabled selected>Seleccione un método de pago</option>
                                @foreach ($metodosdepago as $metododepago)
                                    <option value="{{$metododepago->id}}">{{$metododepago->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_venta" class="form-label" style="color: #aed5b6; ">Fecha de Venta</label>
                            <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn" style="margin-right:10px" @if(!$cajaAbierta) disabled @endif> Agregar Venta </button>
                            <a href="javascript:history.back()" class="btn btn-secondary" style="background-color: grey">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>          
</div>

@push('js')
<script src="{{ asset('js/ventas.js') }}"></script>
@endpush
@endsection
