@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="my-4">Lista de Lotes</h1>
</div>

<main class="container-lg">
    <div id="lotes-table"></div>
    <div id="deleteButtonTemplate" style="display: none;">
        @can('gestionar-inventario')
            <button type="button" title="Eliminar lote" class="btn btn-sm btn-delete-lote" data-numero_lote=" ${numero_lote}" style="background-color:transparent;">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        @endcan
    </div>
    <div id="editButtonTemplate" style="display: none;">
        <!-- @can('editar-lotes') -->
            <a href="javascript:void(0);" type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#editLoteModal" title="Editar lote" data-numero_lote="${numero_lote}" style="background-color:transparent;">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        <!-- @endcan -->
    </div>
</main>

<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="editLoteModal" tabindex="-1" aria-labelledby="editLoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editProductModalLabel">Editar Lote</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLoteForm">
                    @csrf
                    @method('PUT') <!-- Esto es importante para enviar el método PUT en la actualización -->
                    <div class="mb-3">
                                <label for="edit_producto_cod" class="form-label">
                                    <i class="fa-solid fa-barcode"></i> Código de producto
                                </label>
                                <input type="text" class="form-control" id="edit_producto_cod" name="producto_cod" placeholder="Ingrese el código del producto" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="edit_numero-lote" class="form-label" >
                                    <i class="fa-solid fa-dolly"></i> Número de Lote
                                </label>
                                <input type="text" id="edit_numero-lote" name="numero_lote" class="form-control" placeholder="Ingrese el número del lote" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="edit_cantidad-lote" class="form-label" >
                                    <i class="fa-solid fa-boxes-stacked"></i> Cantidad
                                </label>
                                <input type="number" id="edit_cantidad-lote" name="cantidad" class="form-control" placeholder="Ingrese la cantidad en el lote" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_fecha-expiracion" class="form-label" >
                                    <i class="fa-regular fa-calendar"></i> Fecha de Expiración
                                </label>
                                <input type="date" id="edit_fecha-expiracion" name="fecha_vencimiento" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_fecha-ingreso" class="form-label" >
                                    <i class="fa-regular fa-calendar"></i> Fecha de Ingreso
                                </label>
                                <input type="date" id="edit_fecha-ingreso" name="fecha_ingreso" class="form-control" required>
                            </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn">Actualizar</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    var loteIndexUrl = "{{ route('api.lotes.index') }}";
    var eliminarLoteUrl = "{{ route('api.lotes.destroy', 'numero_lote') }}";
    var editLoteUrl = "{{ route('api.lotes.edit', 'numero_lote') }}";
    var updateLoteUrl = "{{ route('api.lotes.update', 'numero_lote') }}"
</script>
<script src="{{ asset('js/lotes/index.js') }}"></script>

@endpush
@endsection
