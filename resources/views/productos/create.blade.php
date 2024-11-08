@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <!-- Título de la página -->
    <h3 style="margin:2%;">Agregar Nuevo Producto</h3>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color:#aed6b5;">
                    <h5 class="justify-content text-center text-white">Formulario de Nuevo Producto</h5>
                        <div class="card-body">
                            <section id="formulario1" class="bg-light p-4 rounded shadow">

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
                                    <!-- Campo Código -->
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
                                            <button class="btn btn-outline" style="border-color:#aed5b6; border-width: 2px;" onmouseleave="this.style.backgroundColor='transparent';"  onmouseover="this.style.backgroundColor= '#aed6b5';" onclick="menuPrecio()" type="button">Calcular Precio</button>
                                            <input type="number" id="precioVenta" class="form-control" step="0.01" name="precio_venta" placeholder="Precio Venta" required>
                                        </div>
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

                                    <!-- Botones de acción -->
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn" style="background-color: #aed6b5; margin-right:10px" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Guardar Producto</button>
                                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function menuPrecio() {
        
        Swal.fire({
            title: 'Ingresar detalles del precio',
            html:
                `<label>Precio Costo:</label><input type="number" id="precioCosto" class="swal2-input" step="0.01" placeholder="Precio Costo" required>
                <label>IVA (%):</label><input type="number" id="iva" class="swal2-input" step="0.01" placeholder="21%" readonly required>
                <label>% de Utilidad:</label><input type="number" id="utilidad" class="swal2-input" step="0.01" placeholder="% Utilidad" required>`,
            focusConfirm: false,
            preConfirm: () => {
                const precioCosto = parseFloat(document.getElementById('precioCosto').value);
                const iva = 21 / 100;
                const utilidad = parseFloat(document.getElementById('utilidad').value) / 100;

                if (isNaN(precioCosto) || isNaN(iva) || isNaN(utilidad)) {
                    Swal.showValidationMessage('Por favor, completa todos los campos requeridos.');
                    return false;
                }

                const precioVenta = precioCosto * (1 + iva + utilidad);
                document.getElementById('precioVenta').value = precioVenta.toFixed(2);

                const hiddenInputs = document.getElementById('hidden-inputs');
                hiddenInputs.innerHTML = `
                    <input type="hidden" name="precio_costo" value="${precioCosto}">
                    <input type="hidden" name="iva" value="${iva * 100}">
                    <input type="hidden" name="utilidad" value="${utilidad * 100}">
                `;
            },
            showCancelButton: true,
            confirmButtonText: 'OK'
        })
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
