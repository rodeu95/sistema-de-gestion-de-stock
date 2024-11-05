@extends('layouts.app')
@section('content')

<div class="container" style="margin:2%;">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color:#aed6b5;">
                    <h5 class="justify-content text-white text-center">Registrar nuevo usuario</h5>
                        <div class="card-body">
                            <section id="formulario1" class="bg-light p-4 rounded shadow">

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

                                <form onsubmit="return validarContraseña()" method="post" action="{{ route('users.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Usuario</label>
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
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Usuario</label>
                                        <select id="tipoUsuario" name="roles[]" class="form-select" required>
                                            <option value="" disabled selected>Selecciona un tipo</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label id="atributos" class="form-label" style="margin-bottom:20px; display:none;">Permisos</label>
                                    <div id="atributos-container" class="mb-3" style="display: none; overflow-y: auto; margin-bottom: 20px; max-height: 100px; border-radius: 0.25rem;">
                                        @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <input type="checkbox" name="permissions[]" class="form-check-input" value="{{ $permission->id }}" id="{{ $permission->id }}" style="margin-left:20px;">
                                                <label class="form-check-label" for="{{ $permission->id }}">{{ $permission->description }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn" style="background-color: #aed6b5; margin-top:20px;" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Registrar</button>
                                    <button type="reset" class="btn" style="background-color: #aed6b5; margin-top:20px;" onmouseover="this.style.backgroundColor= '#d7f5dd';" onmouseout="this.style.backgroundColor='#aed6b5';">Borrar</button>
                                </form>

                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
@endpush
@endsection
