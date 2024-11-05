<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div id="banner" class="text-center mb-4">
            <img src="{{ asset('img/banner.png') }}" alt="banner" class="img-fluid">
        </div>
    </header>
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-4 mt-md-0">
                <section id="formulario2" class="bg-light p-4 rounded shadow">
                    <h2 class="text-center mb-4">Iniciar Sesión</h2>

                    @if(session('success'))
                        <div class="alert alert-success" id="alert-box">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('usuario.login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fa-solid fa-user"></i> Usuario
                            </label>
                            <input type="text" id="usuarioIni" name="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fa-solid fa-lock"></i> Contraseña
                            </label>
                            <input type="password" id="contraseñaIni" name="password" class="form-control" required>
                            <div class="form-text text-end">
                                <button type="button" id="contraseñaIni-toggle" onclick="togglePasswordVisibility('contraseñaIni')" class="btn btn-link">Mostrar</button>
                            </div>
                        </div>
                        <button type="submit" class="btn" style="background-color: #aed6b5;" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Entrar</button>
                        <button type="reset" class="btn" style="background-color: #aed6b5" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Borrar</button>
                    </form>
                </section>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
