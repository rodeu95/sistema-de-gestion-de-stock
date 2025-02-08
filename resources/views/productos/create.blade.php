@extends('layouts.app')

@section('content')

    <div class="container" style="margin: 2%;">
        <!-- Título de la página -->
        <h3 class="mb-2">Agregar nuevo producto</h3>
        <div class="row">
            <div class="col-lg-12">

                <section id="formulario1" class="mt-4 ">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Formulario para agregar un nuevo producto -->
                    <form action="{{ route('productos.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Primera columna -->
                            <div class="col-md-4">
                                <!-- Campo Código -->
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">
                                        <i class="fa-solid fa-barcode"></i> Código
                                    </label>
                                    <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ingrese el código del producto" required>
                                </div>

                                <!-- Campo Nombre -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fa-solid fa-pencil"></i> Nombre
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del producto" required>
                                </div>

                                <!-- Campo Unidad -->
                                <div class="mb-3">
                                    <label for="unidad" class="form-label">
                                        <i class="fa-solid fa-weight-hanging"></i> Unidad
                                    </label>
                                    <div>
                                        
                                        <input type="radio" id="unidad_un" name="unidad" value="UN" onchange="updateStockStep()" required>
                                        <label for="unidad_un" style="margin-right:10px;">UN</label>
                                       
                                        <input type="radio" id="unidad_kg" name="unidad" value="KG" onchange="updateStockStep()" required>
                                        <label for="unidad_kg">KG</label>
                                    </div>
                                    
                                    <!-- <select class="form-select" id="unidad" name="unidad" onchange="updateStockStep()"  required>
                                        <option value="" selected disabled>Seleccione la unidad</option>
                                        <option value="UN">UN</option>
                                        <option value="KG">KG</option>
                                    </select> -->

                                </div>

                            </div>

                            <!-- Segunda columna -->
                            <div class="col-md-4">
                                <!-- Campo Descripción -->
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">
                                        <i class="fa-solid fa-bars"></i> Descripción
                                    </label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese una descripción del producto"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="stock_minimo" class="form-label">
                                        <i class="fa-solid fa-boxes-stacked"></i> Stock mínimo
                                    </label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="stock_minimo" 
                                        name="stock_minimo" 
                                        title="Ingrese el stock mínimo"
                                        placeholder="Ingrese el stock mínimo"
                                        step="0.01"
                                        required
                                    >
                                </div>
                                
                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">
                                                <i class="fa-solid fa-warehouse"></i> Stock
                                            </label>
                                            <input 
                                                type="number" 
                                                class="form-control" 
                                                id="stock" 
                                                name="stock"
                                                title="Ingrese la cantidad de stock disponible" 
                                                placeholder="Ingrese la cantidad de stock disponible"
                                                step="0.01"
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock_minimo" class="form-label">
                                                <i class="fa-solid fa-boxes-stacked"></i> Stock mínimo
                                            </label>
                                            <input 
                                                type="number" 
                                                class="form-control" 
                                                id="stock_minimo" 
                                                name="stock_minimo" 
                                                title="Ingrese el stock mínimo"
                                                placeholder="Ingrese el stock mínimo"
                                                step="0.01"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Campo Categoría -->
                                <div class="mb-3">
                                    <label for="categoria_id" class="form-label">
                                        <i class="fa-solid fa-table-columns"></i> Categoría
                                    </label>
                                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                                        <option value="" selected disabled>Seleccione una categoría</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="precioVenta" class="form-label">
                                        <i class="fa-solid fa-dollar-sign"></i> Precio de venta
                                    </label>
                                    <div class="input-group">
                                        <button class="btn "   type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Calcular Precio</button>
                                        <input type="number" id="precioVenta" class="form-control" step="0.01" name="precio_venta" placeholder="Precio Venta" required>
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
                                                    <input type="number" id="precioVentaMod" class="form-control" step="0.01" name="precio_venta" placeholder="Precio Venta" required>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn" style="margin-right:10px" ">Guardar Producto</button>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary" style="background-color:grey;">Cancelar</a>
                        </div>
                    </form>
                </section>

            </div>
        </div>
    </div>


<script>
    // function menuPrecio() {
        
    //     Swal.fire({
    //         title: 'Ingresar detalles del precio',
    //         html:
    //             `<label>Precio Costo:</label><input type="number" id="precioCosto" class="swal2-input" step="0.01" placeholder="Precio Costo" required>
    //             <label>IVA (%):</label><input type="number" id="iva" class="swal2-input" step="0.01" placeholder="21%" readonly required>
    //             <label>% de Utilidad:</label><input type="number" id="utilidad" class="swal2-input" step="0.01" placeholder="% Utilidad" required>`,
    //         focusConfirm: false,
    //         preConfirm: () => {
    //             const precioCosto = parseFloat(document.getElementById('precioCosto').value);
    //             const iva = 21 / 100;
    //             const utilidad = parseFloat(document.getElementById('utilidad').value) / 100;

    //             if (isNaN(precioCosto) || isNaN(iva) || isNaN(utilidad)) {
    //                 Swal.showValidationMessage('Por favor, completa todos los campos requeridos.');
    //                 return false;
    //             }

    //             const precioVenta = precioCosto * (1 + iva + utilidad);
    //             document.getElementById('precioVenta').value = precioVenta.toFixed(2);

    //             const hiddenInputs = document.getElementById('hidden-inputs');
    //             hiddenInputs.innerHTML = `
    //                 <input type="hidden" name="precio_costo" value="${precioCosto}">
    //                 <input type="hidden" name="iva" value="${iva * 100}">
    //                 <input type="hidden" name="utilidad" value="${utilidad * 100}">
    //             `;
    //         },
    //         showCancelButton: true,
    //         confirmButtonText: 'OK'
    //     })
    // }

    document.getElementById('precioCosto').addEventListener('input', updatePrecioVenta);
    document.getElementById('utilidad').addEventListener('input', updatePrecioVenta);

    function updatePrecioVenta() {
        const precioCosto = parseFloat(document.getElementById('precioCosto').value);
        const iva = 21 / 100; // IVA predeterminado
        const utilidad = parseFloat(document.getElementById('utilidad').value) / 100;

        if (!isNaN(precioCosto) && !isNaN(utilidad)) {
            const precioVenta = precioCosto * (1 + iva + utilidad);
            document.getElementById('precioVenta').value = precioVenta.toFixed(2);
            document.getElementById('precioVentaMod').value = precioVenta.toFixed(2);
        }
    }

    function updateStockStep() {
        const unidad = document.getElementById('unidad').value;
        const stockInput = document.getElementById('stock');

        if (unidad === 'KG') {
            stockInput.step = '0.01';
        } else {
            stockInput.step = '1';
        }
    }
</script>

@push('js')
@endpush
@endsection
