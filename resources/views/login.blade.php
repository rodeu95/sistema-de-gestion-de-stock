<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Bienvenido</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form id="registerForm" method="POST" action="{{ route('user.store') }}" class="register">
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
            <form id="loginForm" method="post" action="{{ route('usuario.login') }}" class="login">
                @csrf
                <h1>Iniciar Sesión</h1>
                
                <input type="text" id="usuarioIni" name="usuario" placeholder="Username" required>
                <div class="input-container">
                    <input type="password" id="passwordIni" name="password" placeholder="Contraseña" required><i class="fa-solid fa-eye icon" id="togglePasswordIni"></i>
                </div>
                
                <button type="submit" >Entrar</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                
                <div class="toggle-panel toggle-left">
                    <div id="logo" class="text-center">
                        <img src="{{asset('img/logo-sin fondo (1).png')}}" class="img-fluid" style="height:auto; width:150px">
                    </div>
                    <h1>¡Bienvenido de nuevo!</h1>
                    <p>Presiona INICIAR SESIÓN para ingresar</p>
                    <button class="hidden" id="login" onmouseout="this.style.backgroundColor='transparent';">Iniciar sesión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <div id="logo" class="text-center">
                        <img src="{{asset('img/logo-sin fondo (1).png')}}" class="img-fluid" style="height:auto; width:150px">
                    </div>
                    <h1>¡Bienvenido!</h1>
                    <p>Presiona REGISTRARSE si es tu primera vez</p>
                    <button class="hidden" id="register" onmouseout="this.style.backgroundColor='transparent';" >Registrarse</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var loginUrl = "{{ route('usuario.login') }}";
    </script>
</body>

</html>
