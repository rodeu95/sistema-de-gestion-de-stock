<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
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
            background-color: #aed5b6;
            color: black;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>{{ $heading }}</h1>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio Costo</th>
                <th>IVA(%)</th>
                <th>% de Utilidad</th>
                <th>Precio de Venta</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    @if($producto->unidad === 'UN')
                        <td>${{ number_format($producto->precio_costo, 2) }} x UN</td>
                    @elseif($producto->unidad === 'KG')
                        <td>${{ number_format($producto->precio_costo, 2) }} x KG</td>
                    @endif
                    <td>{{ $producto->iva }}%</td>
                    <td>{{ $producto->utilidad }}%</td>
                    @if($producto->unidad === 'UN')
                        <td>${{ number_format($producto->precio_venta, 2) }} x UN</td>
                    @elseif($producto->unidad === 'KG')
                        <td>${{ number_format($producto->precio_venta, 2) }} x KG</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>