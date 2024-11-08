@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="text-center my-4">Inventario de Productos</h1>
</div>

<main class="container-lg">

    <table class="table shadow table-bordered">
        <thead>
            <tr class="text-center">
                <th class="table-primary">Codigo</th>
                <th class="table-secondary">Nombre</th>
                <th class="table-success">Categoría</th>
                <th class="table-danger">Precio Costo</th>
                <th class="table-danger">IVA</th>
                <th class="table-danger">% Utilidad</th>
                <th class="table-danger">Precio de Venta</th>
                <th class="table-warning">Stock</th>
                <th class="table-info">Lotes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}</td>
                    @if($producto->unidad === 'UN')
                        <td>{{ number_format($producto->precio_costo, 2) }} x UN</td>
                    @endif
                    @if($producto->unidad === 'KG')
                        <td>{{ number_format($producto->precio_costo, 2) }} x KG</td>
                    @endif
                    <td>{{ $producto->iva }}%</td>
                    <td>{{ $producto->utilidad }}%</td>
                    @if($producto->unidad === 'UN')
                        <td>${{ number_format($producto->precio_venta, 2) }} x UN</td>
                    @elseif($producto->unidad === 'KG')
                        <td>${{ number_format($producto->precio_venta, 2) }} x KG</td>
                    @endif
                    @if($producto->unidad === 'UN')
                        <td>{{ $producto->stock }} unidades</td>
                    @elseif($producto->unidad === 'KG')
                        <td>{{ $producto->stock }} kg.</td>
                    @endif
                    <td>
                        @if($producto->lotes->isNotEmpty())
                            <table class="table shadow table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nro. de Lote</th>
                                        <th>Fecha de Vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producto->lotes as $lote)
                                        <tr>
                                            <td>{{ $lote->numero_lote }}</td>
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
