@extends('layouts.app')
@section('content')

<div class="container-lg">
    <h1 class="my-4">Lista de Productos</h1>
    <div class="d-flex justify-content-center gap-3 mb-4 container container-botones">

        <!-- <button class="btn main-btn" onclick="toggleButtons()">¿Qué deseas hacer?</button> -->

        @can('agregar-producto')
            <a href="javascript:void(0);" class="btn btn-lg" 
               data-bs-toggle="modal" data-bs-target="#addProductModal">
               <i class="fas fa-plus-circle"></i> Agregar producto
            </a>
        @endcan

        @can('ver-productos-vencidos')
            <a href="{{ route('productos.vencidos') }}" 
            id="vencidosBtn"
            class="btn btn-lg" 
            ><i class="fa-regular fa-calendar-xmark"></i> Ver productos vencidos</a>
        @endcan

        @can('ver-productos-a-vencer')
            <a href="{{ route('productos.por-vencer') }}"
            id="porVencerBtn" 
            class="btn btn-lg" 
            ><i class="fa-regular fa-clock"></i> Ver próximos a vencer</a>
        @endcan
    </div>
    
</div>

<main class="container-lg">
    <div id="gridjs-table"></div>

    <div id="editButtonTemplate" style="display: none;">
        @can('editar-producto')
            <a href="javascript:void(0);" type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal" title="Editar producto" data-codigo="${codigo}" style="background-color:transparent;">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
        @endcan
    </div>

    <div id="disableButtonTemplate" style="display: none;">
        @can('deshabilitar-producto')
            <button type="button" id="disableButton" class="btn btn-sm btn-disable" title="Deshabilitar producto" data-codigo="${codigo}" style="background-color:transparent;">
                <i class="fa-solid fa-ban" ></i>
            </button>
        @endcan
    </div>

    <div id="enableButtonTemplate" style="display: none;">
        @can('habilitar-producto')
            <button type="button" id="enableButton" class="btn btn-sm btn-enable" title="Habilitar producto" data-codigo="${codigo}" style="background-color:transparent;">
                <i class="fa-solid fa-check"></i>
            </button>
        @endcan
    </div>

</main>

<!-- MODAL DE AGREGACIÓN -->
<div class="modal fadae" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" >
            <div class="modal-header ">
                <h4 class="modal-title" id="addProductModalLabel">Agregar Producto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <form id="addProductForm">
                    @csrf
                    <div class="mb-3">
                        <label for="codigo" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-barcode"></i> Código
                        </label>
                        <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ingrese el código del producto" required>
                    </div>

                    <!-- Campo Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label" style="color: #aed5b6;">
                            <i class="fa-solid fa-pencil"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del producto" required>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-bars"></i> Descripción
                        </label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ingrese una descripción del producto"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="unidad" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-weight-hanging"></i> Unidad
                        </label>
                        <div>
                                        
                            <input type="radio" id="unidad_un" name="unidad" value="UN" onchange="updateStockStep()" required>
                            <label for="unidad_un" style="margin-right:10px; color:grey;">UN</label>
                            
                            <input type="radio" id="unidad_kg" name="unidad" value="KG" onchange="updateStockStep()" required>
                            <label for="unidad_kg" style="color:grey;">KG</label>
                        </div>
                        <!-- <select class="form-select" id="unidad" name="unidad" onchange="updateStockStep()" required>
                            <option value="" selected disabled></option>
                            <option value="UN">UN</option>
                            <option value="KG">KG</option>
                        </select> -->
                    </div>

                    <div id="hidden-inputs"></div>
                    <!-- Campo Precio -->
                    <div class="mb-3">
                        <label for="precioVenta" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-dollar-sign"></i> Precio de venta
                        </label>
                        <div class="input-group">
                            <button class="btn"type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Calcular precio de venta</button>                                
                            
                        </div>
                        <p>
                            <div class="collapse" id="collapseExample">
                                <div class="card card-body" style="background-color:#aed5b6;">
                                    <div class="mb-3">
                                        <label for="precioCosto" class="form-label">Precio Costo</label>
                                        <input type="number" class="form-control" name="precio_costo" id="precioCosto" step="0.01" placeholder="Precio Costo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="iva" class="form-label">IVA (%)</label>
                                        <input type="number" name="iva" class="form-control" id="iva" value="21" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="utilidad" class="form-label">% de Utilidad</label>
                                        <input type="number" class="form-control" name="utilidad" id="utilidad" step="0.01" placeholder="% de Utilidad">
                                    </div>
                                    <div class="mb-3">
                                        <label for="precioVenta" class="form-label">Precio de venta</label>
                                        <input type="number" id="precioVenta" class="form-control" step="0.01" name="precio_venta" placeholder="Precio Venta" required>
                                    </div>
                                </div>
                                
                            </div>
                        </p>
                    </div>

                    <!-- Campo Stock -->
                    <!-- <div class="mb-3">
                        <label for="stock" class="form-label" style="color: #aed5b6;">
                            <i class="fa-solid fa-warehouse"></i> Stock
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="stock" 
                            name="stock" 
                            placeholder="Ingrese la cantidad de stock disponible"
                            step="0.01"
                            required
                        >
                    </div> -->

                    <div class="mb-3">
                        <label for="stock_minimo" class="form-label" style="color: #aed5b6;">
                            <i class="fa-solid fa-boxes-stacked"></i> Stock mínimo
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="stock_minimo" 
                            name="stock_minimo" 
                            placeholder="Ingrese el stock mínimo"
                            step="0.01"
                            required
                        >
                    </div>

                    <!-- Campo Categoría -->
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-table-columns"></i> Categoría
                        </label>
                        <select class="form-select" id="categoria_id" name="categoria_id" required>
                            <option value="" selected disabled>Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn">Agregar</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>


<!-- MODAL DE EDICIÓN -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editProductModalLabel">Editar Producto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    @csrf
                    @method('PUT') <!-- Esto es importante para enviar el método PUT en la actualización -->
                    <div class="mb-3">
                        <label for="edit_codigo" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-barcode"></i> Código
                        </label>
                        <input type="text" class="form-control" id="edit_codigo" name="codigo" value="{{old('codigo', $producto->codigo)  }}" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-pencil"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-bars"></i> Descripción
                        </label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" value="{{ old('descripcion', $producto->descripcion) }}"  rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_unidad" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-weight-hanging"></i> Unidad
                        </label>
                        <select class="form-select" id="edit_unidad" name="unidad" required>
                            <option value="" selected disabled>Seleccione unidad</option>
                            <option value="UN">UN</option>
                            <option value="KG">KG</option>
                        </select>
                    </div>

                    <div id="edit_hidden_inputs"></div>

                    <div class="mb-3">
                        <label for="edit_precioVenta" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-dollar-sign"></i> Precio de venta
                        </label>
                        <input type="number" class="form-control" id="edit_precioVenta" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}"  required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_stock" class="form-label" style="color: #aed5b6;">
                            <i class="fa-solid fa-warehouse"></i> Stock mínimo
                        </label>
                        <input type="number" class="form-control" id="edit_stock_minimo" name="stock_minimo" value="{{ old('stock', $producto->stock_minimo) }}"  required>
                    </div>

                    <!-- <div class="mb-3">
                        <label for="edit_numero_lote" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-dolly"></i> Número de Lote
                        </label>
                        <input type="text" class="form-control" id="edit_numero_lote" name="numero_lote" value="{{ $producto->numero_lote }}"  readonly>
                    </div> -->
<!-- 
                    <div class="mb-3">
                        <label for="edit_fchVto" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-regular fa-calendar"></i> Fecha de Vencimiento
                        </label>
                        <input type="date" class="form-control" id="edit_fchVto" name="fchVto" value="{{ old('fchVto', $producto->fchVto) }}" >
                    </div> -->

                    <div class="mb-3">
                        <label for="edit_categoria_id" class="form-label" style="color: #aed5b6; ">
                            <i class="fa-solid fa-table-columns"></i> Categoría
                        </label>
                        <select class="form-select" id="edit_categoria_id" name="categoria_id" required>
                            <option value="" selected disabled>Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ old('nombre', $categoria->id )}}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
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
<script src="{{ asset('js/productos/index.js') }}"></script>
<script>
    var productosIndexUrl = "{{ route('api.productos.index') }}";
    var productosStoreUrl = "{{ route('api.productos.store') }}";
    var productoUpdatetUrl = "{{ route('api.productos.update', 'codigo') }}";
    var editProductUrlTemplate = "{{ route('productos.edit', ':codigo') }}"; 
    var disableProductoUrl = "{{ route('productos.disable', 'codigo') }}";
    var enableProductoUrl = "{{ route('productos.enable', 'codigo') }}";

    function toggleButtons() {
        document.querySelector('.container-botones').classList.toggle('active');
    }
</script>


@endpush
@endsection
