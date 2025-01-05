@extends('layouts.app')

@section('content')
<div class="container" >
    <h1 class="my-4">Historial de Ventas</h1>
</div>

<main class="container-lg">

    <div class="row mb-4">
        <div class="form-group col-3">
            <label>Filtrar por fecha</label>
            <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
        </div>

        <div class="form-group col-3">
            <label for="select-mes">Filtrar por mes</label>
            <select id="select-mes" class="form-control">
                <option value="" disabled selected>Seleccione un mes</option>
                <option value="01">Enero</option>
                <option value="02">Febrero</option>
                <option value="03">Marzo</option>
                <option value="04">Abril</option>
                <option value="05">Mayo</option>
                <option value="06">Junio</option>
                <option value="07">Julio</option>
                <option value="08">Agosto</option>
                <option value="09">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
        </div>

        <div class="form-group col-3">
            <label for="year-select">Filtrar por año</label>
            <select id="year-select" class="form-control"></select>
        </div>

        <div class="form-group col-3">
            <button id="apply-filters" class="btn btn-filtros">Aplicar filtros</button>
        </div>
    </div>
        

    <div id="ventas-table"></div>
    <div id="editButtonTemplate" style="display: none;">
        @can('editar-venta')
            <a href="javascript:void(0);" type="button" class="btn btn-sm" data-bs-toggle="modal" title="Editar venta" data-bs-target="#editVentaModal" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endcan
    </div>

    <div id="deleteButtonTemplate" style="display: none;">
        @can('eliminar-venta')
            <button type="button" title="Eliminar venta" class="btn btn-sm btn-delete" data-id="${id}" style="background-color:transparent;">
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

                <form id="editVentasForm" method="POST">

                    @csrf
                    @method('PUT') 

                    <div class="mb-3">
                        <label for="edit_id" class="form-label" style="color: #aed5b6; ">ID Venta</label>
                        <input type="text" class="form-control" id="edit_id" name="id" value="{{old('id', $venta->id)  }}" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="producto-select" class="form-label" style="color: #aed5b6; ">Producto</label>
                        <select id="producto-select" class="form-select">
                            <option value="" disabled selected>Seleccione un producto</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad-input" class="form-label" style="color: #aed5b6; ">Cantidad</label>
                        <input type="number" id="cantidad-input" class="form-control" value="">
                    </div>

                    <button type="button" id="add-product" class="btn mb-3" style="background-color:grey;">Agregar Producto</button>

                    <ul id="product-list" class="list-group mb-3">
                        
                    </ul>

                    <div id="hidden-inputs"></div>

                    <div class="mb-3">
                        <label for="monto_total" class="form-label" style="color: #aed5b6;margin-top:1%;">Monto Total</label>
                        <input type="number" name="monto_total" id="monto_total" class="form-control" value="" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_venta" class="form-label" style="color: #aed5b6; margin-top:1%;">Fecha de Venta</label>
                        <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn" style=" margin-right:10px">
                            Actualizar Venta
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color:grey;">Cancelar</button>
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
    var ventasStoreUrl = "{{ route('api.ventas.store') }}";
    var ventaUpdatetUrl = "{{ route('api.ventas.update', 'id') }}";
    var editVentaUrlTemplate = "{{ route('api.ventas.edit', ':id') }}"; 
    var eliminarVentaUrl = "{{ route('api.ventas.destroy', 'id') }}"
    console.log(editVentaUrlTemplate);
</script>

@endpush

@endsection
