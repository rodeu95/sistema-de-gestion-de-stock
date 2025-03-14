@extends('layouts.app')

@section('content')

<div class="container" style="margin: 2%;">
    <h1 class="mb-4">Filtrar ventas</h1>
    <form id="filterForm" method="GET" action="{{ route('generate-excel') }}">
        <div class="row mb-4">
            <div class="form-group col-3">
                <label>Filtrar por fecha</label>
                <input type="date" name="fecha_venta" id="fecha_venta" class="form-control">
            </div>

            <div class="form-group col-3">
                <label>Filtrar entre fechas</label>
                <div>
                    <span>Desde</span>
                    <input type="date" name="fechaIni" id="fechaIni" class="form-control" placeholder="Desde">
                </div>
                <div class="mt-2">
                    <span class="mt-4">Hasta</span>
                    <input type="date" name="fechaFin" id="fechaFin" class="form-control">
                </div>
            </div>

            <div class="form-group col-3">
                <label for="select-mes">Filtrar por mes</label>
                <select id="select-mes" class="form-control" name="month">
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
                <select id="year-select" class="form-control" name="year"></select>
            </div>

        </div>

        <div class="row">
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-export"><i class="fa-solid fa-file-excel"></i> Exportar Excel</button>
            </div>
        </div>
    </form>
</div>
@push('js')
<script src="{{ asset('js/ventas/export.js') }}"></script>
@endpush
@endsection