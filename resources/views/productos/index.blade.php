
@extends('layouts.app')
@section('content')

 
    <div class="container-lg">
        <h1 class="text-center my-4">Lista de Productos</h1>
        <div class="text-end mb-3">
            @can('agregar-producto')
                <a href="{{ route('productos.create') }}" class="btn shadow" style="background-color: #aed6b5; color:#000;" onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                onmouseout="this.style.backgroundColor='#aed6b5';">Agregar Producto</a>
            @endcan
        </div>
    </div>
    
    <div class="row mb-3 content-center">
        <div class="col md-4 ms-auto">
            <form action="{{ route('productos.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre" value="{{ request('search') }}" style="width: 300px;">
                <button type="submit" class="btn shadow ms-2" style="background-color: #aed6b5; color:#000;" onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                onmouseout="this.style.backgroundColor='#aed6b5';">Buscar</button>
            </form>
        </div>
    </div>

    <main class="container-lg">

        <table class="table shadow table-striped table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th class="col-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->fchVto }}</td>
                        <td>${{ number_format($producto->precio_venta, 2) }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>
                            <form id="delete-form-{{ $producto->id }}" action="{{ route('productos.destroy', $producto->id) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <!-- <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> Show</a> -->

                                @can('editar-producto')
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></i> Edit</a>
                                @endcan

                                @can('eliminar-producto')
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{$producto->id}}')"><i class="fa-solid fa-trash-can"></i> Eliminar</button>
                                @endcan
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

@push('js')
    <script>
        function confirmDelete(productId) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "No podrás volver atrás",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Eliminar de todas formas",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + productId).submit();
                }
            });
        }
    </script>
@endpush
@endsection
