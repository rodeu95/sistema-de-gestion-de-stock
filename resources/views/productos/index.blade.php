@extends('layouts.app')
@section('content')

<div class="container-lg">
    <h1 class="my-4">Lista de Productos</h1>
    <div class="d-flex flex-column align-items-end mb-3">
<!--         
        <div class="mb-3">
            <form action="{{ route('productos.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control shadow" placeholder="Buscar por nombre" value="{{ request('search') }}" style="width: 300px;">
                <button type="submit" class="btn shadow ms-2" style="background-color: #aed6b5; color:#000;" 
                        onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                        onmouseout="this.style.backgroundColor='#aed6b5';"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            </form>
        </div> -->
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
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
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
                                <div class="card card-body" style="background-color:#fbeee6;">
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
<!--  -->

@push('js')
<!-- <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script> -->
<script>

    document.addEventListener('DOMContentLoaded', function () {
        const puedeEditar = true; // Reemplaza con tu verificación de permisos real
        const puedeEliminar = true;
        const grid = new gridjs.Grid({
            columns: [
                'Código', 
                'Nombre', 
                'Fecha de vencimiento', 
                'Precio de Venta', 
                'Stock', 
                {
                    name: 'Acciones',
                    formatter: (cell, row) => {
                        let botones = '';
                
                        if (puedeEditar) {
                            botones += `<a href="" class="btn btn-primary btn-sm">Editar</a>`;
                        }
                        
                        if (puedeEliminar) {
                            botones += `<button class="btn btn-danger btn-sm" onclick="eliminarProducto(${row.cells[0].data})">Eliminar</button>`;
                        }

                        return gridjs.html(botones);
                    }
                }
            ],
            server: {
                url: 'http://localhost/sistema/public/api/productos',
                
                then: data => {
                    console.log(data); // Para ver qué datos se reciben
                    return data.map(producto => [
                        producto.codigo,
                        producto.nombre,
                        producto.fchVto,
                        producto.precio_venta,
                        producto.unidad === 'UN' ? `${producto.stock} unidades` : `${producto.stock} kg.`
                        
                    ]);
                }
                
            },
            resizable: true,
            sort: true,
            pagination: {
                enabled: true,
                limit: 10,
            },
            search: true,
            language: {
                search: {
                    placeholder: 'Buscar...'
                },
                pagination: {
                    previous: 'Anterior',
                    next: 'Siguiente',
                    showing: 'Mostrando',
                    of: 'de',
                    to: 'a'
                }
            },
            style:{
                th:{
                    'background-color':'#fff3cd'
                }
            },
        }).render(document.getElementById('gridjs-table'));
    });
</script>
<script type src="{{ asset('js/productos/index.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script> -->

<script>
    var productosIndexUrl = "{{ route('productos.index') }}";
</script>
@endpush
@endsection
