@extends('layouts.app')
@section('content')

<div class="container-lg">
    <h1 class="my-4">Proveedores</h1>
    <div class="d-flex justify-content-center gap-3 mb-4 container container-botones">

        <!-- <button class="btn main-btn" onclick="toggleButtons()">¿Qué deseas hacer?</button> -->

        <!-- @can('agregar-producto') -->
            <a href="{{ route('proveedores.create') }}" class="btn btn-lg">
               <i class="fas fa-plus-circle"></i> Agregar proveedor
            </a>
        <!-- @endcan -->

    </div>
    
</div>

<main class="container-lg">

    <div id="proveedores-table"></div>

    <div id="editProveedorButton" style="display: none;">
        <!-- @can('editar-producto') -->
            <a href="javascript:void(0);" type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#editProveedorModal" title="Editar proveedor" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        <!-- @endcan -->
    </div>

    <div id="disableProveedorButton" style="display: none;">
        <!-- @can('deshabilitar-producto') -->
            <button type="button" id="disableProveedor" class="btn btn-sm btn-disable" title="Deshabilitar proveedor" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-ban" ></i>
            </button>
        <!-- @endcan -->
    </div>

    <div id="enableProveedorButton" style="display: none;">
        <!-- @can('habilitar-producto') -->
            <button type="button" id="enableProveedor" class="btn btn-sm btn-enable" title="Habilitar proveedor" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-check"></i>
            </button>
        <!-- @endcan -->
    </div>

</main>


<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="editProveedorModal" tabindex="-1" aria-labelledby="editProveedorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editProveedorModalLabel">Editar Proveedor</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProveedorForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_id" class="form-label" style="color: #aed5b6; ">
                            ID Proveedor
                        </label>
                        <input type="text" class="form-control" id="edit_id" name="id" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-pencil"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_contacto" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-address-book"></i> Contacto
                        </label>
                        <input type="text" class="form-control" id="edit_contacto" name="contacto"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_telefono" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-square-phone"></i> Teléfono
                        </label>
                        <input type="text" class="form-control" id="edit_contacto" name="telefono"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-at"></i> E-mail
                        </label>
                        <input type="text" class="form-control" id="edit_email" name="direccion" ></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_direccion" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-location-dot"></i> Dirección
                        </label>
                        <input type="text" class="form-control" id="edit_direccion" name="direccion"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_cuit" class="form-label" style="color: #aed5b6;">
                            <i class="fa-solid fa-id-card"></i> CUIT
                        </label>
                        <input type="cuit" class="form-control" id="edit_cuit" name="cuit">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn shadow">Actualizar</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>




@push('js')
<script src="{{ asset('js/proveedores/index.js') }}"></script>
<script>
    var proveedoresIndexUrl = "{{ route('api.proveedores.index') }}";
    var proveedoresStoreUrl = "{{ route('api.proveedores.store') }}";
    var proveedoresUpdatetUrl = "{{ route('api.proveedores.update', 'id') }}";
    var editProveedorUrlTemplate = "{{ route('proveedores.edit', ':id') }}"; 
    var disableProveedorUrl = "{{ route('proveedores.disable', 'id') }}";
    var enableProveedorUrl = "{{ route('proveedores.enable', 'id') }}";

</script>


@endpush
@endsection
