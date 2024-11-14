@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <h1 class="my-4" style="margin:2%;">Historial de Ventas</h1>
</div>

<main class="container-lg">
    <div id="ventas-table"></div>
    <div id="editButtonTemplate" style="display: none;">
        @can('editar-venta')
            <a href="javascript:void(0);" type="button" class="btn shadow btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editVentaModal" data-id="${id}">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endcan
    </div>

    <div id="deleteButtonTemplate" style="display: none;">
        @can('eliminar-venta')
            <button type="button" class="btn shadow btn-danger btn-sm btn-delete" data-id="${id}">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        @endcan
    </div>
</main>

<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="editVentaModal" tabindex="-1" aria-labelledby="editVentaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editVentaModalLabel">Editar Venta</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ventasForm">
                    @csrf
                    @method('PUT') 

                    <div class="mb-3">
                        <label for="edit_id" class="form-label">ID Venta</label>
                        <input type="text" class="form-control" id="edit_id" name="id" value="{{old('id', $venta->id)  }}" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="producto-select" class="form-label">Producto</label>
                        <select id="producto-select" class="form-select">
                            <option value="" disabled selected>Seleccione un producto</option>
                            <!-- @foreach($venta->productos as $producto)
                                <option value="{{ $producto->codigo }}" data-precio="{{ $producto->precio_venta }}">
                                    {{ $producto->nombre }} - ${{ $producto->precio_venta }}
                                </option>
                            @endforeach -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad-input" class="form-label">Cantidad</label>
                        <input type="number" id="cantidad-input" class="form-control" value="">
                    </div>

                    <button type="button" id="add-product" class="btn btn-secondary mb-3">Agregar Producto</button>

                    <ul id="product-list" class="list-group mb-3">
                        
                    </ul>

                    <div id="hidden-inputs"></div>

                    <div class="mb-3">
                        <label for="monto_total" class="form-label" style="margin-top:1%;">Monto Total</label>
                        <input type="number" name="monto_total" id="monto_total" class="form-control" value="" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_venta" class="form-label" style="margin-top:1%;">Fecha de Venta</label>
                        <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style="background-color: #aed6b5; margin-right:10px" 
                                onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                                onmouseout="this.style.backgroundColor='#aed6b5';">
                            Actualizar Venta
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>    
                </form>
            </div>
        </div>
    </div>
</div>
    

@push('js')
<script src="{{ asset('js/ventas/index.js') }}"></script>

<script>
    var ventasIndexUrl = "{{ route('api.ventas.index') }}";
    var ventasStoreUrl = "{{ route('ventas.store') }}";
    var ventaUpdatetUrl = "{{ route('api.ventas.update', 'id') }}";
    var editVentaUrlTemplate = "{{ route('ventas.edit', ':id') }}"; 
    var eliminarVentaUrl = "{{ route('api.ventas.destroy', 'id') }}"
    console.log(editVentaUrlTemplate);
</script>

@endpush

@endsection
