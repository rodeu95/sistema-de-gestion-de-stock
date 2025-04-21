@extends('layouts.app')
@section('content')

<div class="container" style="margin:2%;">
    <div class="row">
        <div class="col-lg-12">                
            <h3 class="justify-content mb-4">Registrar nuevo usuario</h3>
                
            <section id="formSectionUserCreate" class="shadow formSection">

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

                <form onsubmit="return validarContraseña()" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-user"></i> Nombre de usuario
                                </label>
                                <input type="text" id="usuario" name="usuario" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-pencil"></i> Nombre Completo
                                </label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-envelope"></i> E-mail
                                </label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-lock"></i> Contraseña
                                </label>
                                <div class="input-container">
                                    <input type="password" id="password" name="password" class="form-control" required><i class="fa-solid fa-eye icon" id="togglePassword"></i>
                                </div>
                                
                            
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-lock"></i> Confirmar Contraseña
                                </label>
                                <div class="input-container">
                                    <input type="password" id="confirmPassword" name="password_confirmation" class="form-control" required><i class="fa-solid fa-eye icon" id="togglePasswordConfirm"></i>
                                </div>
                                
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipoUsuario" class="form-label">
                                    <span class="asterisk" title="Campo requerido">*</span> <i class="fa-solid fa-circle-user"></i> Roles
                                </label>
                                <select id="tipoUsuario" name="roles[]" class="form-select" required>
                                    <option value="" disabled selected>Selecciona un tipo</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-6">
                                <label id="atributos" class="form-label" style="margin-bottom:20px; display:none;"><i class="fa-solid fa-key"></i> Permisos</label>
                                <div id="atributos-container" class="mb-3" style="display: none; overflow-y: auto; margin-bottom: 20px; max-height: 250px; border-radius: 0.25rem;">
                                    @foreach ($permissions as $permission)
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" class="form-check-input" value="{{ $permission->id }}" id="{{ $permission->id }}" style="margin-left:20px;">
                                            <label class="form-check-label" for="{{ $permission->id }}">{{ $permission->description }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    
                    <div class="d-flex justify-content-end" style="margin:10px;">
                        <button type="submit" class="btn" style="margin-top:20px; margin-right:10px">Crear</button>
                        <button type="reset" class="btn" style="margin-top:20px; background-color:grey;" >Borrar</button>
                    </div>
                    
                </form>

            </section>            
        </div>
    </div>
</div>

@push('js')
<script src="{{ asset('js/createUser.js') }}"></script>
<script src="{{ asset('js/requiredField.js') }}"></script>
@endpush
@endsection
