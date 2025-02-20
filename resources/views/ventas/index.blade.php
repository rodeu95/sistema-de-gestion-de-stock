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
    
    <div id="anularButtonTemplate" style="display: none;">
        @can('anular-venta')
            <button id="anular-venta-btn" type="button" class="btn btn-sm" title="Anular venta" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-ban" ></i>
            </button>
        @endcan
    </div>
    <div id="showVentaTemplate" style="display: none;">
        <button id="show-venta-btn" type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#showVentaModal" title="Detalles de  venta" data-id="${id}" style="background-color:transparent;">
            <i class="fa-solid fa-eye" ></i>
        </button>
    </div>

    
</main>

<div class="modal fade" id="showVentaModal" tabindex="-1" aria-labelledby="showVentaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editProductModalLabel">Detalles de venta</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID Venta: </strong> <span id="venta-id"></span></p>
                <p><strong>Monto Total: </strong> <span id="venta-monto"></span></p>
                <p><strong>Fecha: </strong> <span id="venta-fecha"></span></p>
                <p><strong>Estado: </strong> <span id="venta-estado"></span></p>
                <h5 style="color:#aed5b6">Productos</h5>
                <ul id="lista-productos"></ul>
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
    var eliminarVentaUrl = "{{ route('api.ventas.destroy', 'id') }}";
    console.log(editVentaUrlTemplate);
    var showVentatUrl = "{{ route('api.ventas.show', ':id') }}";
    var anularVentaUrl = "{{ route('api.ventas.anular', 'id') }}";
</script>

@endpush

@endsection
