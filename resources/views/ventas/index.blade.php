@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <h1 class="text-center my-4" style="margin:2%;">Historial de Ventas</h1>

    <!-- Formulario de búsqueda por fecha -->
    <form action="{{ route('ventas.index') }}" method="GET" class="mb-4">
        <div class="form-group">
            <label for="fecha">Buscar por fecha:</label>
            <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}" class="form-control shadow" />
        </div>
        <div class="d-flex justify-content-end" style="margin:10px">
            <button type="submit" class="btn shadow" style="background-color: #aed6b5; margin-right:10px" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Buscar</button>
            <a href="{{ route('ventas.index') }}" class="btn shadow btn-secondary">Mostrar todas</a>
        </div>
    </form>

    <!-- Tabla de ventas -->
    <table class="table shadow table-bordered table-hover">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Productos</th>
                <th>Método de Pago</th>
                <th>Monto Total</th>
                <th>Fecha de Venta</th>
                <th class="col-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>
                        @foreach ($venta->productos as $producto)
                            {{ $producto->nombre }} (Cantidad: {{ $producto->pivot->cantidad }})
                        @endforeach
                    </td>
                    <td>{{ $venta->metodoPago ? $venta->metodoPago->nombre : 'No especificado' }}</td>
                    <td>${{ number_format($venta->monto_total, 2) }}</td>
                    <td>{{ $venta->fecha_venta}}</td>
                    <td>
                        <form id="delete-form-{{ $venta->id }}" action="{{ route('ventas.destroy', $venta->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            @can('editar-venta')
                                <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                            @endcan

                            @can('eliminar-venta')
                                <button id="" type="button "class="btn btn-danger btn-sm" onclick="confirmDelete('{{$venta->id}}')"><i class="fa-solid fa-trash-can"></i> Eliminar</button>
                            @endcan
                        </form>
                        </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron ventas para esta fecha.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@push('js')
    <script>
        function confirmDelete(ventaId) {
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
                    document.getElementById('delete-form-' + ventaId).submit();
                }
            });
        }
    </script>
@endpush

@endsection
