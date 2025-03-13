@extends('layouts.app')

@section('content')
    <div class="container" style="margin:2%;">
        <div class="row">
            <div class="col-lg-12">
                
                    
            <h3 class="justify-content text-white mb-4">Editar usuario</h3>
        
            
                <section id="formulario1">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usuario" class="form-label">
                                        <i class="fa-solid fa-user"></i>
                                        Nombre de Usuario
                                    </label>
                                    <input type="text" name="usuario" id="usuario" class="form-control" value="{{ $user->usuario }}" required>
                                </div>
                                <input type="hidden" id="userId" value="{{ $user->id ?? '' }}">


                                <!-- Correo electrónico -->
                                <div class="form-group">
                                    <label for="email" class="form-label" style="margin-top:10px;">
                                        <i class="fa-solid fa-envelope"></i> Correo Electrónico
                                    </label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                                </div>

                                <!-- Contraseña editable solo para el propio usuario o cajero -->
                                @if(auth()->user()->id == $user->id || auth()->user()->hasRole('Cajero'))
                                    <div class="form-group">
                                        <label for="password" class="form-label" style="margin-top:10px;">
                                            <i class="fa-solid fa-lock"></i> Nueva Contraseña
                                        </label>
                                        <div class="input-container">
                                            <input type="password" id="password" name="password" class="form-control" required><i class="fa-solid fa-eye icon" id="togglePassword"></i>
                                        </div>
                                        <label for="password_confirmation" class="form-label" style="margin-top:10px;">
                                            <i class="fa-solid fa-lock"></i> Confirmar Nueva Contraseña
                                        </label>
                                        <div class="input-container">
                                            <input type="password" id="confirmPassword" name="password_confirmation" class="form-control" required><i class="fa-solid fa-eye icon" id="togglePasswordConfirm"></i>
                                        </div>

                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                @if(auth()->user()->hasRole('Administrador'))
                                    <div class="form-group">
                                        <label for="tipoUsuario" class="form-label">
                                            <i class="fa-solid fa-circle-user"></i> Roles
                                        </label>
                                        <select id="tipoUsuario" name="roles[]" class="form-select">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label id="atributos" for="atributos-container" class="form-label" style="margin-top:10px; text-decoration:underline; display:none;">Permisos</label>
                                        <div id="atributos-container" class="mb-3" style="overflow-y: auto; max-height: 230px; border-radius: 0.25rem; display:none;">
                                        @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                name="permissions[]" 
                                                class="form-check-input" 
                                                value="{{ $permission->id }}" 
                                                id="{{ $permission->id }}" style="margin-left:20px;"
                                                {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $permission->id }}">{{ $permission->description }}</label>
                                            </div>
                                        @endforeach
                                        </div>
                                    </div>
                                
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end" style="margin:10px;">
                            <button type="submit" class="btn" style="margin-right:10px; margin-top:20px;">
                                Guardar Cambios
                            </button>
                            <a href="javascript:history.back()" class="btn" style="margin-top:20px; background-color:grey;">Cancelar</a>
                        </div>
                    </form>
                </section>
                        
                    
                
            </div>
        </div>
    </div>

@push('js')
<script src="{{ asset('js/editUser.js') }}"></script>
@endpush
@endsection
