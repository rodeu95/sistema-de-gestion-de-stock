@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Actualizar Inventario</h1>
    
    <div class="row" style="margin-top: 4%">
        <!-- Columna 1: Lista de productos con bajo stock -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color:#aed6b5;">
                    <h5 class="justify-content text-white text-center">Productos con Bajo Stock</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <ul class="list-group">
                            @forelse($bajoStock as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $producto->nombre }} (Stock: {{ $producto->stock }})</span>
                                    <!-- <button id="editButton" class="btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editStockModal"
                                    data-codigo="{{ $producto->codigo }}"
                                    data-nombre="{{ $producto->nombre }}"
                                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                        Actualizar Stock
                                    </button> -->
                                </li>
                            @empty
                                <li class="list-group-item text-center">No hay productos con bajo stock.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>            
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color:#aed6b5;">
                    <h5 class="justify-content text-white text-center">Actualizar Stock</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">            

                        <form action="{{ route('lotes.store') }}" id="addLoteForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="producto_cod" class="form-label" style="color: #aed5b6;">
                                    <i class="fa-solid fa-barcode"></i> Código de producto
                                </label>
                                <input type="text" class="form-control" id="producto_cod" name="producto_cod" placeholder="Ingrese el código del producto" required>
                            </div>
                            <div class="mb-3">
                                <label for="numero-lote" class="form-label" style="color: #aed5b6;">
                                    <i class="fa-solid fa-dolly"></i> Número de Lote
                                </label>
                                <input type="text" id="numero-lote" name="numero_lote" class="form-control" placeholder="Ingrese el número del lote" required>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad-lote" class="form-label" style="color: #aed5b6;">
                                    <i class="fa-solid fa-boxes-stacked"></i> Cantidad
                                </label>
                                <input type="number" id="cantidad-lote" name="cantidad" class="form-control" placeholder="Ingrese la cantidad en el lote" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha-expiracion" class="form-label" style="color: #aed5b6;">
                                    <i class="fa-regular fa-calendar"></i> Fecha de Expiración
                                </label>
                                <input type="date" id="fecha-expiracion" name="fecha_vencimiento" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha-ingreso" class="form-label" style="color: #aed5b6;">
                                    <i class="fa-regular fa-calendar"></i> Fecha de Ingreso
                                </label>
                                <input type="date" id="fecha-ingreso" name="fecha_ingreso" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn" style="background-color: #aed6b5; color: white;">
                                    Agregar Lote
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>


@push('js')
<script src="{{ asset('js/inventario/inventarioUpdate.js') }}"></script>
<script>
    var updateInventarioUrl = "{{ route('api.inventario.update', ':codigo') }}";
    var editInventarioUrl = "{{ route('api.inventario.edit', ':codigo') }}";
    console.log(editInventarioUrl);
    console.log(updateInventarioUrl);
    var productos = @json($productos);
</script>
@endpush
@endsection
