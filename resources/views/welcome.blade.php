<!DOCTYPE html>
<html lang="es">
<head>
    <title>Bienvenido</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
    <!-- <style>
        body {
            font-family: 'Dosis', sans-serif;
            background-color: #f0f8ff;
        }
        .navbar {
            margin-bottom: 30px;
        }
        .container {
            margin-top: 50px;
        }
        .welcome-content {
            text-align: center;
            margin-top: 50px;
        }
        .btn-custom {
            background-color: #aed6b5;
            border: none;
            padding: 10px 20px;
            color: #000;
            margin: 10px;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #d7f5dd;
        }
    </style> -->
</head>
<body>
    <!-- Navbar -->
    <header class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">La Gran Tienda</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <div class="welcome-content">
            <h1 class="display-4">
                <i class="fa-solid fa-boxes-stacked"></i> Sistema de Gestión de Ventas <i class="fa-solid fa-dolly"></i>
            </h1>
            <p class="lead">Seleccione una opción para comenzar.</p>
            <a href="{{ route('login') }}" class="btn btn-custom">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn btn-custom">Registrarse</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
