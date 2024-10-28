<!DOCTYPE html>
<html>
<head>
    <title>Productos próximos a vencer</title>
</head>
<body>
    <h1>Productos próximos a vencer</h1>

    <p>Los siguientes productos tienen una fecha de vencimiento dentro de los próximos 15 días:</p>

    <ul>
        @foreach($productos as $producto)
            <li>
                {{ $producto->nombre }} - Vence el: {{ $producto->fchVto->format('d/m/Y') }}
                Código: {{$producto->codigo}}
            </li>
        @endforeach
    </ul>

    <p>Por favor, revise estos productos y tome las acciones necesarias.</p>

    <p>Saludos,</p>
    <p>El equipo de gestión de productos</p>
</body>
</html>
