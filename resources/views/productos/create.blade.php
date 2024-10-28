@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <!-- Título de la página -->
    <h3 style="margin:2%;">Agregar Nuevo Producto</h3>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Formulario de Nuevo Producto</h5>
                </div>
                <div class="card-body">
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

                        <!-- Campo Precio -->
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" placeholder="Ingrese el precio del producto" required>
                        </div>

                        <!-- Campo Stock -->
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" placeholder="Ingrese la cantidad de stock disponible" required>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="fchVto" name="fchVto" required>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
