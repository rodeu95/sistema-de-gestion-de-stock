@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
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

                            <form action="{{ route('ventas.update', $venta->id) }}" method="POST" id="ventasForm">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="producto-select" class="form-label">Producto</label>
                                    <select id="producto-select" class="form-select" name="producto_cod[]">
                                        <option value="" disabled selected>Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->codigo }}" data-precio="{{ $producto->precio }}">
                                                {{ $producto->nombre }} - ${{ $producto->precio }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="cantidad-input" class="form-label">Cantidad</label>
                                    <input type="number" id="cantidad-input" class="form-control" min="1">
                                    
                                </div>

                                <button type="button" id="add-product" class="btn btn-secondary mb-3">Agregar Producto</button>

                                <ul id="product-list" class="list-group mb-3">
                                    @foreach($venta->productos as $producto)
                                        <li class="list-group-item product-list-item" 
                                            data-precio="{{ $producto->precio }}" 
                                            data-cantidad="{{ $producto->pivot->cantidad }}">
                                            {{ $producto->nombre }} - {{ $producto->pivot->cantidad }} x ${{ $producto->precio }} 
                                            = ${{ number_format($producto->pivot->cantidad * $producto->precio, 2) }}
                                            <button type="button" class="btn btn-danger btn-sm float-end remove-product">Eliminar</button>
                                            <input type="hidden" name="producto_cod[]" value="{{ $producto->codigo }}">
                                            <input type="hidden" name="cantidad[]" value="{{ $producto->pivot->cantidad }}">
                                        </li>
                                    @endforeach
                                </ul>

                                <div id="hidden-inputs"></div>

                                <div class="mb-3">
                                    <label for="monto_total" class="form-label" style="margin-top:1%;">Monto Total</label>
                                    <input type="number" name="monto_total" id="monto_total" class="form-control" value="{{ old('monto_total', $venta->monto_total) }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_venta" class="form-label" style="margin-top:1%;">Fecha de Venta</label>
                                    <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn" style="background-color: #aed6b5; margin-right:10px" 
                                            onmouseover="this.style.backgroundColor= '#66a5ad';" 
                                            onmouseout="this.style.backgroundColor='#aed6b5';">
                                        Actualizar Venta
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

@endsection
