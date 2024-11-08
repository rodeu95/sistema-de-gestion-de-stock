@extends('layouts.app')

@section('content')
<div class="container" style="margin:2%;">
    <h1 class="my-4" style="margin:2%;">Historial de Ventas</h1>

    <!-- Formulario de búsqueda por fecha -->
    <!-- <form action="{{ route('ventas.index') }}" method="GET" class="mb-4">
        <div class="form-group">
            <label for="fecha_venta">Buscar por fecha:</label>
            <input type="date" name="fecha_venta" id="fecha_venta" value="{{ request('fecha_venta') }}" class="form-control shadow" />
        </div>
        <div class="d-flex justify-content-end" style="margin:10px">
            <button type="submit" class="btn shadow" style="background-color: #aed6b5; margin-right:10px" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            <a href="{{ route('ventas.index') }}" class="btn shadow btn-secondary"><i class="fa-solid fa-list"></i> Mostrar todas</a>
        </div>
    </form> -->

    <main class="container-lg">
        <div id="ventas-table"></div>
    </main>
    
</div>
@push('js')
<script src="{{ asset('js/ventas/index.js') }}">
    var ventasIndexUrl = "{{ route('ventas.index') }}";
</script>
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
