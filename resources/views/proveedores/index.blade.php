@extends('layouts.app')
@section('content')

<div class="container-lg">
    <h1 class="my-4">Lista de proveedores</h1>
    <div class="d-flex justify-content-center gap-3 mb-4 container container-botones">

        @can('agregar-proveedor')
            <a href="{{ route('proveedores.create') }}" class="btn btn-lg">
               <i class="fas fa-plus-circle"></i> Agregar proveedor
            </a>
        @endcan

    </div>
    
</div>

<main class="container-lg">
    <div lass="row">
        <div class="col-3">
            <label for="categoriaFiltro">Filtrar por Categoría:</label>
            <select id="categoriaFiltro" class="form-control">
                <option value="" class="text-muted">Todas las categorías</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div id="proveedores-table"></div>

    <div id="editProveedorButton" style="display: none;">
        @can('editar-proveedor')
            <a href="javascript:void(0);" type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#editProveedorModal" title="Editar proveedor" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endcan
    </div>

    <div id="disableProveedorButton" style="display: none;">
        @can('deshabilitar-proveedor')
            <button type="button" id="disableProveedor" class="btn btn-sm btn-disable" title="Deshabilitar proveedor" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-ban"></i>
            </button>
        @endcan
    </div>

    <div id="enableProveedorButton" style="display: none;">
        @can('habilitar-proveedor')
            <button type="button" id="enableProveedor" class="btn btn-sm btn-enable" title="Habilitar proveedor" data-id="${id}" style="background-color:transparent;">
                <i class="fa-solid fa-check"></i>
            </button>
        @endcan
    </div>

    <div id="mostrarCategorias" style="display: none;">
        <button type="button" id="categoriasButton" class="btn btn-sm btn-categorias" title="Ver categorías que vende el proveedor" data-id="${id}" style="background-color:transparent;" data-bs-toggle="modal" data-bs-target="#categoriasProveedorModal">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
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
                        <label for="edit_id" class="form-label" style="color: #acd8b5; ">
                            ID Proveedor
                        </label>
                        <input type="text" class="form-control" id="edit_id" name="id" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label" style="color: #acd8b5; ">
                            <i class="fa-solid fa-pencil"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_contacto" class="form-label" style="color: #acd8b5; ">
                            <i class="fa-solid fa-address-book"></i> Contacto
                        </label>
                        <input type="text" class="form-control" id="edit_contacto" name="contacto"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_telefono" class="form-label" style="color: #acd8b5; ">
                            <i class="fa-solid fa-square-phone"></i> Teléfono
                        </label>
                        <input type="text" class="form-control" id="edit_telefono" name="telefono"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label" style="color: #acd8b5; ">
                            <i class="fa-solid fa-at"></i> E-mail
                        </label>
                        <input type="text" class="form-control" id="edit_email" name="email" ></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_direccion" class="form-label" style="color: #acd8b5; ">
                            <i class="fa-solid fa-location-dot"></i> Dirección
                        </label>
                        <input type="text" class="form-control" id="edit_direccion" name="direccion"></input>
                    </div>

                    <div class="mb-3">
                        <label for="edit_cuit" class="form-label" style="color: #acd8b5;">
                            <i class="fa-solid fa-id-card"></i> CUIT
                        </label>
                        <input type="cuit" class="form-control" id="edit_cuit" name="cuit">
                    </div>

                    <label id="edit_cat_prov" class="form-label" style="margin-bottom:20px; color: #acd8b5;">Categorías</label>
                    <div id="categorias-container"  style="overflow-y: auto; max-height: 250px; border-radius: 0.25rem; background: linear-gradient(to right, #acd8b5, #66a5ad); padding: 10px;">
                            
                            <!-- Las categorías se llenan aquí con AJAX -->
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn shadow">Actualizar</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<!--MODAL DE CATEGORÍAS-->
<div class="modal fade" id="categoriasProveedorModal" tabindex="-1" aria-labelledby="editProveedorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="categoriasProveedorModalLabel">Categorías de productos por Proveedor</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="listaCategorias">
                    
                </ul>
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
    var editProveedorUrlTemplate = "{{ route('api.proveedores.edit', ':id') }}"; 
    var disableProveedorUrl = "{{ route('proveedores.disable', 'id') }}";
    var enableProveedorUrl = "{{ route('proveedores.enable', 'id') }}";
    var categoriasIndex = "{{ route('categorias.index') }}";
    var categoriasProveedorUrl = "{{ route('categorias.proveedor', 'id') }}";
    var proveedoresFiltradoUrl = "{{ route('proveedores.filtar') }}";
</script>


@endpush
@endsection
