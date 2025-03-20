@extends('layouts.app')

@section('content')

    <div class="container" style="margin: 2%;">
        <!-- Título de la página -->
        <h3 class="mb-2">Agregar nuevo proveedor</h3>
        <div class="row">
            <div class="col-lg-12">

                <section id="formularioProv" class="mt-4 ">
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
                    <form action="{{ route('proveedores.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Primera columna -->
                            <div class="col-md-4">
                                <!-- Campo Código -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fa-solid fa-pencil"></i> Nombre
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del proveedor" required>
                                </div>

                                <!-- Campo Nombre -->
                                <div class="mb-3">
                                    <label for="contacto" class="form-label">
                                        <i class="fa-solid fa-address-book"></i> Contacto
                                    </label>
                                    <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Nombre de la persona de contacto" required>
                                </div>

                                <!-- Campo Unidad -->
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">
                                        <i class="fa-solid fa-square-phone"></i> Teléfono
                                    </label>
                                    <div>
                                        
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese número de teléfono" required>
                                    </div>

                                </div>

                            </div>

                            <!-- Segunda columna -->
                            <div class="col-md-4">
                                <!-- Campo Descripción -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fa-solid fa-at"></i> E-mail
                                    </label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Ingrese e-mail del proveedor" required>
                                </div>

                                <div class="mb-3">
                                    <label for="direccion" class="form-label">
                                        <i class="fa-solid fa-location-dot"></i> Dirección
                                    </label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección del proveedor" required>
                                </div>

                                <!-- Campo Categoría -->
                                <div class="mb-3">
                                    <label for="cuit" class="form-label">
                                        <i class="fa-solid fa-id-card"></i> Número de CUIT
                                    </label>
                                    <input type="text" class="form-control" id="cuit" name="cuit" placeholder="Ingrese CUIT del proveedor" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label id="cat_prov" class="form-label" style="margin-bottom:20px;">Categorías</label>
                                    <div id="categorias-container" class="mb-3" style="overflow-y: auto; margin-bottom: 20px; max-height: 250px; border-radius: 0.25rem;">
                                        @foreach ($categorias as $categoria)
                                            <div class="form-check">
                                                <input type="checkbox" name="categorias[]" class="form-check-input" value="{{ $categoria->id }}" id="{{ $categoria->id }}" style="margin-left:20px;">
                                                <label class="form-check-label" for="{{ $categoria->id }}">{{ $categoria->nombre }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn" style="margin-right:10px" ">Guardar Proveedor</button>
                            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary" style="background-color:grey;">Cancelar</a>
                        </div>
                    </form>
                </section>

            </div>
        </div>
    </div>




@push('js')
<script>
    
</script>
@endpush
@endsection
