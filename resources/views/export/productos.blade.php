@extends('layouts.app')

@section('content')
<div class="container" style="margin: 2%;">
    <h1 class="mb-4">Filtrar Productos</h1>
    <form id="filterForm" method="GET" action="{{ route('generate-pdf') }}">
        <div class="row mb-3">
            <!-- Filtro por Categoría -->
            <div class="col-md-3">
                <label for="categoria" class="form-label">Categoría</label>
                <select name="categoria" id="categoria" class="form-control">
                    <option value="">Todas</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Bajo Stock -->
            <div class="col-md-3">
                <label for="bajo_stock" class="form-label">Bajo Stock</label>
                <select name="bajo_stock" id="bajo_stock" class="form-control">
                    <option value="">No filtrar</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <!-- Filtro por Fecha de Vencimiento -->
            <div class="col-md-3">
                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                <input type="date" name="fchVto" id="fecha_vencimiento" class="form-control">
            </div>

            <!-- Filtro por Lote -->
            <div class="col-md-3">
                <label for="lote" class="form-label">Lote</label>
                <input type="text" name="lote" id="lote" class="form-control" placeholder="Número de Lote">
            </div>
        </div>

        <!-- Botones -->
        <div class="row">
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-export"><i class="fa-solid fa-file-pdf"></i> Exportar PDF</button>
            </div>
        </div>
    </form>
</div>
@endsection
