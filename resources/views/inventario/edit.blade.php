@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Actualizar Inventario</h2>
    
    <div class="row">
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
                                    <button id="editButton" class="btn shadow btn-sm btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editStockModal"
                                    data-codigo="{{ $producto->codigo }}"
                                    data-nombre="{{ $producto->nombre }}">
                                        Actualizar Stock
                                    </button>
                                </li>
                            @empty
                                <li class="list-group-item text-center">No hay productos con bajo stock.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>            
        </div>

        <!-- Columna 2: Actualizar stock filtrando por categorías -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color:#aed6b5;">
                    <h5 class="justify-content text-white text-center">Buscar y Actualizar Stock</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">            
                        <form id="filter-form" class="mb-4">
                            <select id="categoria-select" class="form-select" >
                                <option value="">Seleccione una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </form>

                        <form action="{{ route('inventario.update') }}" method="POST">
                            @csrf
                            <div id="filtered-products">
                                <p class="text-center text-muted">Seleccione una categoría para ver productos.</p>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn shadow" style="background-color: #aed6b5; margin-right:10px" 
                                    onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                                    onmouseout="this.style.backgroundColor='#aed6b5';">
                                    Actualizar inventario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editProductModalLabel">Actualizar Stock</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="update-stock-form" action="{{ route('inventario.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="producto_cod" id="modal-producto-cod">
                    <div class="mb-3">
                        <label for="modal-producto-nombre" class="form-label">Producto</label>
                        <input type="text" id="modal-producto-nombre" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="modal-cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" id="modal-cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn shadow" style="background-color: #aed6b5; margin-right:10px" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="{{ asset('js/inventario/inventarioUpdate.js') }}"></script>
<script>
    var editInventarioUrl = "{{ route('api.inventario.update') }}";
    console.log(editInventarioUrl);
    var productos = @json($productos);
</script>
@endpush
@endsection
