@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="text-center my-4">Inventario de Productos</h1>
</div>

<main class="container-lg">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Lotes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>
                        @if($producto->lotes->isNotEmpty())
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Fecha de Vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producto->lotes as $lote)
                                        <tr>
                                            <td>{{ $lote->cantidad }}</td>
                                            <td>{{ \Carbon\Carbon::parse($lote->fecha_vencimiento)->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <span class="text-muted">Sin lotes</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>
@endsection
