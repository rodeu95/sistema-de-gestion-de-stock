<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title', 'La Gran Tienda')</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet" />
        <!-- <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" /> -->
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
        <link href="{{ asset('css/cards.css') }}" rel="stylesheet">
        <!-- <link href="{{ asset('css/inventarioIndex.css') }}" rel="stylesheet"> -->
        <link href="{{ asset('css/create-edit-users.css') }}" rel="stylesheet">
        <link href="{{ asset('css/productos/index.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('dashboard.partials.header')

        <!-- Modal para mostrar detalles del producto -->
        <div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="barcodeModalLabel">Detalles del Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="barcodeModalContent">
                        <!-- Aquí se mostrarán los datos del producto -->
                        <div class="mb-3 text-center">
                            <strong><div id="producto-nombre" class="text-muted text-uppercase large" ></div></strong>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- Columna de etiquetas -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <p class="text-muted text-uppercase small mb-0">Código:</p>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <p class="text-muted text-uppercase small mb-0">Precio:</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="text-muted text-uppercase small mb-0">Stock disponible:</p>
                                </div>
                            </div>

                            <!-- Columna de datos -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <p id="producto-codigo" class="text-dark mb-0 text-muted"></p>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <strong><p id="producto-precio" class="text-dark mb-0 text-muted"></p></strong>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p id="producto-stock" class="text-dark mb-0 text-muted"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <div class="container">
            {{-- Mostrar mensaje de error si existe --}}
            @if (session('error'))
                <div id="message" class="alert alert-danger" style="margin:1%;">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div id="message" class="alert alert-warning" style="margin:1%;">
                <i class="fa-solid fa-circle-info"></i> {{ session('info') }}
                </div>
            @endif

            @if(session('success'))
                <div id="message" class="alert alert-success" style="margin:1%;">
                    <i class="fa-solid fa-square-check"></i> {{ session('success') }}
                </div>
            @endif


            @yield('content')
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
        <!-- <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script> -->
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ asset('js/index.js') }}"></script>
        <script src="{{ asset('js/barcode.js') }}"></script>
        <script src="{{ asset('js/logout.js') }}"></script>
        <script>
            var productoShowUrl = "{{ route('api.producto.show', 'codigo') }}";
        </script>
        @stack('js')

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
        @if(session('swal'))
            <script>
                Swal.fire({!! json_encode(session('swal')) !!});
            </script>
        @endif

    </body>
</html>
