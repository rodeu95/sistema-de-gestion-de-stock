<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <title>Lista de Productos</title>
</head>
@include('dashboard.partials.header')

<body>
    <header>
        <div class="container-lg">
            <h1 class="text-center my-4">Lista de Productos</h1>
            <div class="text-end mb-3">
                @can('agregar-producto')
                    <a href="{{ route('productos.create') }}" class="btn" style="background-color: #aed6b5; color:#000;" onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                    onmouseout="this.style.backgroundColor='#aed6b5';">Agregar Producto</a>
                @endcan
            </div>
        </div>
    </header>

    <main class="container-lg">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->descripcion }}</td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                                @can('editar-producto')
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                @endcan

                                @can('eliminar-producto')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this product?');"><i class="bi bi-trash"></i> Eliminar</button>
                                @endcan
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación --}}
        
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
