<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title', 'Gestión de Productos')</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('dashboard.partials.header')

        <div class="container">
            {{-- Mostrar mensaje de error si existe --}}
            @if (session('error'))
                <div id="message" class="alert alert-danger" style="margin:1%;">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('alert'))
                <div id="message" class="alert alert-warning" style="margin:1%;">
                <i class="fa-solid fa-circle-info"></i> {{ session('alert') }}
                </div>
            @endif


            @yield('content')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('js/ventas.js') }}"></script>
        <script src="{{ asset('js/index.js') }}"></script>

        <script>
            // Función para ocultar el mensaje de error después de 5 segundos (5000 ms)
            window.onload = function() {
                const message = document.getElementById('message');
                if (message) {
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 5000); // Cambia 5000 por el tiempo en milisegundos que desees
                }
            };
        </script>

    </body>
</html>
