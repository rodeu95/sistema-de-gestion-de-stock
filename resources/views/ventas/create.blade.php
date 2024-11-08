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

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color:#aed6b5;">
                    <h5 class="justify-content text-white text-center">Información de la Venta</h5>
                        <div class="card-body">
                            <section id="formulario1" class="bg-light p-4 rounded shadow">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('ventas.store') }}" method="POST" id="ventasForm">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label for="producto-select" class="form-label">Producto</label>
                                        <select id="producto-select" class="form-select" name="producto_cod[]" >
                                            <option value="" disabled selected>Seleccione un producto</option>
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->codigo }}" data-precio="{{ $producto->precio_venta }}">
                                                    {{ $producto->nombre }} - ${{ $producto->precio_venta }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cantidad-input" class="form-label">Cantidad</label>
                                        <input 
                                            type="number" 
                                            id="cantidad-input" 
                                            class="form-control" 
                                            name="cantidad" 
                                            value="" 
                                            @if ($producto->unidad === 'KG')step="0.01" @endif
                                            min="1"
                                        >
                                    </div>

                                    <button type="button" id="add-product" class="btn btn-secondary mb-3" @if(!$cajaAbierta) disabled @endif>Agregar Producto</button>

                                    <ul id="product-list" class="list-group mb-3">
                                        <!-- Aquí se agregarán los productos seleccionados -->
                                    </ul>

                                    <div id="hidden-inputs"></div>

                                    <div class="mb-3">
                                        <label for="monto_total" class="form-label" style="margin-top:1%;">Monto Total</label>
                                        <input type="number" name="monto_total" id="monto_total" class="form-control" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="metodo_pago_id" class="form-label">Método de Pago</label>
                                        <select class="form-select" name="metodo_pago_id" id="metodo_pago_id">
                                            <option value="" disabled selected>Seleccione un método de pago</option>
                                            @foreach ($metodosdepago as $metododepago)
                                                <option value="{{$metododepago->id}}">{{$metododepago->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="fecha_venta" class="form-label">Fecha de Venta</label>
                                        <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn" style="background-color: #aed6b5; margin-right:10px" 
                                                onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                                                onmouseout="this.style.backgroundColor='#aed6b5';"
                                                @if(!$cajaAbierta) disabled @endif>
                                            Agregar Venta
                                        </button>
                                        <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </form>
                            </section>
                        </div>
                </div>
            </div>
        </div>
    </div>          
</div>

@push('js')
@endpush
@endsection
