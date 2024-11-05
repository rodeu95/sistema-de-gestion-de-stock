<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
    <title>Registro</title>
</head>
<body>
    <header>
        <div id="banner" class="text-center mb-4">
            <img src="{{ asset('img/banner.png') }}" alt="banner" class="img-fluid">
        </div>
    </header>
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <section id="formulario1" class="bg-light p-4 rounded shadow">
                    <h2 class="text-center mb-4">Registrarse</h2>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
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

                    <form onsubmit="return validarContraseña()" method="post" action="{{ route('user.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">
                                Usuario
                            </label>
                            <input type="text" id="usuario" name="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <div class="form-text text-end">
                                <button type="button" id="contraseña-toggle" onclick="togglePasswordVisibility('password')" class="btn btn-link">Mostrar</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" id="confirmPassword" name="password_confirmation" class="form-control" required>
                            <div class="form-text text-end">
                                <button type="button" id="confirmarContraseña-toggle" onclick="togglePasswordVisibility('confirmPassword')" class="btn btn-link">Mostrar</button>
                            </div>
                        </div>
                    
                        <button type="submit" class="btn" style="background-color: #aed6b5; margin-top:20px;" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Registrar</button>
                        <button type="reset" class="btn" style="background-color: #aed6b5; margin-top:20px;" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Borrar</button>
                    </form>

                </section>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>