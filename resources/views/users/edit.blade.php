@extends('layouts.app')

@section('content')
    <div class="container" style="margin:2%;">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card shadow mb-4">
                    <div class="card-header" style="background-color:#aed6b5;">
                        <h5 class="justify-content text-white text-center">Editar usuario</h5>
                    
                        <div class="card-body">
                            <section id="formulario1" class="bg-light p-4 rounded shadow">
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

                                    <!-- Nombre de usuario -->
                                    <div class="form-group">
                                        <label for="usuario" class="form-label">
                                            <i class="fa-solid fa-user"></i>
                                            Nombre de Usuario
                                        </label>
                                        <input type="text" name="usuario" class="form-control" value="{{ $user->usuario }}" required>
                                    </div>

                                    <!-- Correo electrónico -->
                                    <div class="form-group">
                                        <label for="email" class="form-label" style="margin-top:10px;">
                                            <i class="fa-solid fa-envelope"></i> Correo Electrónico
                                        </label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                    </div>

                                    <!-- Contraseña editable solo para el propio usuario o cajero -->
                                    @if(auth()->user()->id == $user->id || auth()->user()->hasRole('Cajero'))
                                        <div class="form-group">
                                            <label for="password" class="form-label" style="margin-top:10px;">
                                                <i class="fa-solid fa-lock"></i> Nueva Contraseña
                                            </label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                    @endif

                                    <!-- Roles editables solo para el rol de Administrador -->
                                    @if(auth()->user()->hasRole('Administrador'))
                                        <div class="form-group">
                                            <label for="tipoUsuario" class="form-label" style="margin-top:10px; text-decoration:underline">Roles</label>
                                            <select id="tipoUsuario" name="roles[]" class="form-select">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Permisos editables solo para el rol de Administrador -->
                                        
                                        <div class="form-group">
                                            <label id="atributos" for="atributos-container" class="form-label" style="margin-top:10px; text-decoration:underline; display:none;">Permisos</label>
                                            <div id="atributos-container" class="mb-3" style="overflow-y: auto; max-height: 150px; border-radius: 0.25rem; display:none;">
                                            @foreach ($permissions as $permission)
                                                <div class="form-check">
                                                    <input type="checkbox" name="permissions[]" class="form-check-input" value="{{ $permission->id }}" id="{{ $permission->id }}" style="margin-left:20px;">
                                                    <label class="form-check-label" for="{{ $permission->id }}">{{ $permission->description }}</label>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                       
                                    @endif

                                    <div class="d-flex justify-content-end" style="margin:10px;">
                                        <button type="submit" class="btn" style="background-color: #aed6b5; margin-right:10px" 
                                                onmouseover="this.style.backgroundColor= '#d7f5dd';" 
                                                onmouseout="this.style.backgroundColor='#aed6b5';">Guardar Cambios
                                        </button>
                                        <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
