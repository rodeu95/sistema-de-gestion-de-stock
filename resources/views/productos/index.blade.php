@extends('layouts.app')
@section('content')

<div class="container-lg">
    <h1 class="my-4">Lista de Productos</h1>
    <div class="d-flex flex-column align-items-end mb-3">

        @can('agregar-producto')
            <a href="javascript:void(0);" class="btn shadow" style="background-color: #aed6b5; color:#000;" 
               onmouseover="this.style.backgroundColor= '#d7f5dd';" 
               onmouseout="this.style.backgroundColor='#aed6b5';"
               data-bs-toggle="modal" data-bs-target="#addProductModal">
               <i class="fas fa-plus-circle"></i> Agregar Producto
            </a>
        @endcan
    </div>
    
</div>



<main class="container-lg">
    <div id="gridjs-table"></div>
</main>

<!-- MODAL DE AGREGACIÓN -->
<div class="modal fadae" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addProductModalLabel">Agregar Producto</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    @csrf
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ingrese el código del producto" required>
                    </div>

                    <!-- Campo Nombre -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del producto" required>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ingrese una descripción del producto"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="unidad" class="form-label">Unidad</label>
                        <select class="form-select" id="unidad" name="unidad" onchange="updateStockStep()" required>
                            <option value="" selected disabled></option>
                            <option value="UN">UN</option>
                            <option value="KG">KG</option>
                        </select>
                    </div>

                    <div id="hidden-inputs"></div>
                    <!-- Campo Precio -->
                    <div class="mb-3">
                        <label for="precioVenta" class="form-label">Precio Venta</label>
                        <div class="input-group">
                            <button class="btn btn-outline" style="border-color:#aed5b6; border-width: 2px;" onmouseleave="this.style.backgroundColor='transparent';"  onmouseover="this.style.backgroundColor= '#aed6b5';"  type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Calcular precio de venta</button>
                            
                            
                            
                        </div>
                        <p>
                            <div class="collapse" id="collapseExample">
                                <div class="card card-body" style="background-color:#fff3cd;">
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
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="stock" 
                            name="stock" 
                            placeholder="Ingrese la cantidad de stock disponible"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="numero_lote" class="form-label">Numero de Lote</label>
                        <input type="text" class="form-control" id="numero_lote" name="numero_lote" placeholder="Ingrese el número de lote">
                    </div>

                    <div class="mb-3">
                        <label for="fchVto" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="fchVto" name="fchVto">
                    </div>

                    <!-- Campo Categoría -->
                    <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_id" name="categoria_id" required>
                            <option value="" selected disabled>Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn shadow" style="background-color: #aed6b5; color:#000;" 
                    onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                    onmouseout="this.style.backgroundColor='#aed6b5';">Agregar</button>
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
                        <label for="edit_codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="edit_codigo" name="codigo" value="{{old('codigo', $producto->codigo)  }}" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" value="{{ old('descripcion', $producto->descripcion) }}"  rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_unidad" class="form-label">Unidad</label>
                        <select class="form-select" id="edit_unidad" name="unidad" required>
                            <option value="" selected disabled>Seleccione unidad</option>
                            <option value="UN">UN</option>
                            <option value="KG">KG</option>
                        </select>
                    </div>

                    <div id="edit_hidden_inputs"></div>

                    <div class="mb-3">
                        <label for="edit_precioVenta" class="form-label">Precio Venta</label>
                        <input type="number" class="form-control" id="edit_precioVenta" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}"  required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="edit_stock" name="stock" value="{{ old('stock', $producto->stock) }}"  required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_numero_lote" class="form-label">Numero de Lote</label>
                        <input type="text" class="form-control" id="edit_numero_lote" name="numero_lote" value="{{ $producto->numero_lote }}"  readonly>
                    </div>

                    <div class="mb-3">
                        <label for="edit_fchVto" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="edit_fchVto" name="fchVto" value="{{ old('fchVto', $producto->fchVto) }}" >
                    </div>

                    <div class="mb-3">
                        <label for="edit_categoria_id" class="form-label">Categoría</label>
                        <select class="form-select" id="edit_categoria_id" name="categoria_id" required>
                            <option value="" selected disabled>Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn shadow" style="background-color: #aed6b5; color:#000;" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';" onclick="submitEditarProducto()">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>




@push('js')
<script src="{{ asset('js/productos/index.js') }}"></script>
<script>
    var productosIndexUrl = "{{ route('productos.index') }}";
    var productoEditUrl = "{{ route('productos.update', 'codigo') }}";
    var editProductUrl = "{{route('productos.edit', 'codigo')}}";
    console.log(editProductUrl);

    var editProductUrlTemplate = "{{ route('productos.edit', ':codigo') }}"; 
    console.log(editProductUrlTemplate);
</script>


@endpush
@endsection
