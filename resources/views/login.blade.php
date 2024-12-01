<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <title>Login</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <section id="formulario2" class="bg-light p-4 rounded shadow mt-4">

                    <div class="text-center mb-4">
                        <img src="{{asset('img/logo-sin fondo (2).png')}}" class="img-fluid" style="height:auto; width:150px">
                    </div>
                        
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

                    <form id="loginForm" method="post" action="{{ route('usuario.login') }}">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    <script>
        var loginUrl = "{{ route('usuario.login') }}"
    </script>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Modern Login Page | AsmrProg</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <!-- <div id="logo" class="text-center">
                <img src="{{asset('img/logo-sin fondo (2).png')}}" class="img-fluid" style="height:auto; width:150px;">
            </div> -->
            <form onsubmit="return validarContraseña()" method="POST" action="{{ route('user.store') }}" class="register">
                @csrf
                <h1>Registrarse</h1>
                
                <input type="text" placeholder="Nombre Completo" id="name" name="name" required>
                <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Username" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <div class="input-container">
                    <input type="password" id="passwordReg" name="password" placeholder="Contraseña" required><i class="fa-solid fa-eye icon" id="togglePasswordReg"></i>
                </div>
                <button type="submit">Registrar</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <!-- <div id="logo" class="text-center">
                <img src="{{asset('img/logo-sin fondo (2).png')}}" class="img-fluid" style="height:auto; width:150px">
            </div> -->
            <form id="loginForm" method="post" action="{{ route('usuario.login') }}" class="login">
                @csrf
                <h1>Iniciar Sesión</h1>
                
                <input type="text" id="usuarioIni" name="usuario" placeholder="Username" required>
                <div class="input-container">
                    <input type="password" id="passwordIni" name="password" placeholder="Contraseña" required><i class="fa-solid fa-eye icon" id="togglePasswordIni"></i>
                </div>
                
                <button type="submit">Entrar</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                
                <div class="toggle-panel toggle-left">
                    <div id="logo" class="text-center">
                        <img src="{{asset('img/logo-sin fondo (2).png')}}" class="img-fluid" style="height:auto; width:150px">
                    </div>
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Presiona INICIAR SESIÓN para ingresar</p>
                    <button class="hidden" id="login" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='transparent';">Iniciar esión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <div id="logo" class="text-center">
                        <img src="{{asset('img/logo-sin fondo (2).png')}}" class="img-fluid" style="height:auto; width:150px">
                    </div>
                    <h1>¡Bienvenido!</h1>
                    <p>Presiona REGISTRARSE si es tu primera vez</p>
                    <button class="hidden" id="register" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='transparent';">Registrarse</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var loginUrl = "{{ route('usuario.login') }}"
    </script>
</body>

</html>
