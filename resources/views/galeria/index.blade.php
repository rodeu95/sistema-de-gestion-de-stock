<!DOCTYPE html>
<html lang="es">
<head>
    <title>Bienvenido</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Dosis', sans-serif;
            background-color: #f0f8ff;
        }
        .navbar {
            margin-bottom: 30px;
        }

        .navbar-brand{
            color:white;
            font-size: larger;
            font-weight: bold;
        }
        .container {
            position: relative;
            z-index: 2;
            margin-top: 50px;
            text-align: center;
            color: #000;
        }
        .welcome-content {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semitransparente */
            padding: 30px;
            border-radius: 10px;
        }
        .carousel {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .carousel-inner {
            height: 100vh; /* Altura completa de la pantalla */
        }
        .carousel-item {
            height: 100vh; /* Altura completa de la pantalla */
            background-size: cover;
            background-position: center;
        }
        .carousel-caption {
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
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

        #logo img{
        float: left;
        width: auto;
        height: 80px;
        padding-left:10px ;
        /* padding-right:5px; */
        }
        #logo{
            display: block;
        }
        @media (max-width: 576px) {
            #logo {
                display: none !important; /* Ocultar el logo */
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#" id="logo">
                <img src="{{ asset('img/logo-sin fondo.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <div class="welcome-content">
            <h1 class="display-4">Sistema de Gestión de Stock</h1>
            <p class="lead">Seleccione una opción para comenzar.</p>
            <a href="{{ route('login') }}" class="btn btn-custom">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn btn-custom">Registrarse</a>
        </div>
    </div>

    <!-- Carrusel de Imágenes -->
    <div id="ejemploCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <!-- Indicadores del Carrusel -->
        <ol class="carousel-indicators">
            @foreach($slides as $item)
                <li data-bs-target="#ejemploCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
            @endforeach
        </ol>

        <!-- Contenido del Carrusel -->
        <div class="carousel-inner">
            @foreach($slides as $item)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}" style="background-image: url('{{ url($item->url) }}');">
                    <!-- Texto de ejemplo en cada slide (puedes eliminarlo si no quieres texto) -->
                    <div class="carousel-caption d-none d-md-block">
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Controles del Carrusel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#ejemploCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#ejemploCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
