@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color:#aed6b5;">
                <h5 class="justify-content text-white text-center">Editar Producto</h5>
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

                            <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                                @csrf
                                @method('PUT') <!-- Método PUT para la actualización -->

                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" >{{ old('descripcion', $producto->descripcion) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="precio_venta" class="form-label">Precio</label>
                                    <input type="number" class="form-control" id="precio_venta" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" step="0.01" required>
                                </div>

                                <div class="mb-3">
                                    <label for="stock" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" class="form-control" id="fchVto" name="fchVto" value="{{ old('fchVto', $producto->fchVto) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="categoria_id" class="form-label">Categoría</label>
                                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                                        <option value="" selected disabled>Seleccione una categoría</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn" style="background-color: #aed6b5; margin-right:10px" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Actualizar Producto</button>
                                    <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
