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
            <h1 class="display-4">
                <i class="fa-solid fa-cash-register fa-flip" style="color:#aed6b5; --fa-animation-duration: 15s; margin:10px;"></i> 
                Punto de Venta y Gestión de Productos 
                <i class="fa-solid fa-dolly fa-flip" style="color:#aed6b5; --fa-animation-duration: 15s; margin:10px; "></i>
            </h1>
            <p class="lead">Haga clic para comenzar.</p>
            <a href="{{ route('login') }}" class="btn btn-custom">Comenzar</a>
            <!-- <a href="{{ route('register') }}" class="btn btn-custom">Registrarse</a> -->
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
