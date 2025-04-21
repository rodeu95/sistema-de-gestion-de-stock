<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <style>
        body {
            font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #acd8b5;
            color: black;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        @page {
            size: A4 landscape;
        }
    </style>
</head>
<body>
    <h1>Inventario</h1>

    <table>
        <thead>
            <tr class="text-center">
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio Costo</th>
                <th>Precio de Venta</th>
                <th>Stock</th>
                <th>Lotes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                @if($producto->estado === 1)
                    <tr>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}</td>
                        <td>
                            {{ number_format($producto->precio_costo, 2) }} x {{ $producto->unidad }}
                        </td>
                        <td>
                            {{ number_format($producto->precio_venta, 2) }} x {{ $producto->unidad }}
                        </td>
                        @if($producto->unidad === 'UN')
                            <td>{{ $producto->stock }} unidades</td>
                        @elseif($producto->unidad === 'KG')
                            <td>{{ $producto->stock }} kg.</td>
                        @endif
                        <td>
                            @if($producto->lotes->isNotEmpty())
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nro. de Lote</th>
                                            <th>Fecha de Vencimiento</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($producto->lotes as $lote)
                                            <tr>
                                                <td>{{ $lote->numero_lote }}</td>
                                                <td>{{ \Carbon\Carbon::parse($lote->fecha_vencimiento)->format('d/m/Y') }}</td>
                                                <td>{{ $lote->cantidad }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <span>Sin lotes</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>