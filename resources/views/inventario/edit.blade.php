@extends('layouts.app')

@section('content')
<div class="container-lg" style="margin:2%;">
    <h3 class="justify-content mb-4">Actualizar Inventario</h3>
    
    <div class="row">
        <!-- Columna 1: Lista de productos con bajo stock -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0 justify-content text-center" style="text-shadow:none">
                        <div class="icon-box">
                            <i class="fa-solid fa-arrow-trend-down"></i>
                        </div>
                        Productos con Bajo Stock
                    </h5>
                </div>
                <div class="card-body">
                    
                        <div class="mb-3">
                            <ul class="list-group bg-light rounded shadow">
                                <div class="p-4">
                                    @forelse($bajoStock as $producto)
                                        <li class="list-group-item justify-content-between align-items-center">
                                            <span>
                                                <div class="text-muted text-uppercase large"><Strong>{{ $producto->nombre }}</Strong></div>
                                                <hr>
                                                <p class="text-muted text-uppercase small">Código: <span class="text-dark">{{ $producto->codigo }}</span></p>
                                                <p class="text-muted text-uppercase small">Stock: <span class="text-dark">{{ $producto->stock }}</span></p>
                                            </span>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-center">No hay productos con bajo stock.</li>
                                    @endforelse
                                </div>
                                
                            </ul>
                        </div>
                </div>
            </div>            
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0 justify-content text-center" style="text-shadow:none; ">
                        <div class="icon-box">
                            <i class="fa-solid fa-circle-plus"></i>
                        </div>
                        Actualizar Stock
                    </h5>
                </div>
                <div class="card-body">
                    <section class="bg-light p-4 rounded shadow">
                        <div class="mb-3">            

                        <form action="{{ route('lotes.store') }}" id="addLoteForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="producto_cod" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-barcode"></i> Código de producto
                                </label>
                                <input type="text" class="form-control" id="producto_cod" name="producto_cod" placeholder="Ingrese el código del producto" required>
                            </div>
                            <div class="mb-3">
                                <label for="numero-lote" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-dolly"></i> Número de Lote
                                </label>
                                <input type="text" id="numero-lote" name="numero_lote" class="form-control" placeholder="Ingrese el número del lote" required>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad-lote" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-boxes-stacked"></i> Cantidad
                                </label>
                                <input type="number" id="cantidad-lote" name="cantidad" class="form-control" placeholder="Ingrese la cantidad en el lote" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha-expiracion" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-regular fa-calendar"></i> Fecha de Expiración
                                </label>
                                <input type="date" id="fecha-expiracion" name="fecha_vencimiento" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha-ingreso" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-regular fa-calendar"></i> Fecha de Ingreso
                                </label>
                                <input type="date" id="fecha-ingreso" name="fecha_ingreso" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn">
                                    Agregar Lote
                                </button>
                            </div>
                        </form>
                        </div>
                    </section>
                    
                </div>
            </div>            
        </div>
    </div>
</div>


@push('js')
<script src="{{ asset('js/inventario/inventarioUpdate.js') }}"></script>
<script src="{{ asset('js/requiredField.js') }}"></script>
<script>
    var updateInventarioUrl = "{{ route('api.inventario.update', ':codigo') }}";
    var editInventarioUrl = "{{ route('api.inventario.edit', ':codigo') }}";
    console.log(editInventarioUrl);
    console.log(updateInventarioUrl);
    var productos = @json($productos);
</script>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('producto_cod')?.focus();
  });
</script>

@endpush
@endsection
