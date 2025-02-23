
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="my-4" style="margin:2%;">Lista de Usuarios</h2>

        <div class="table-responsive">
            <table class="table shadow">
                <thead >
                    <tr>
                        <th style="color:grey">ID</th>
                        <th style="color:grey">Nombre</th>
                        <th style="color:grey">Email</th>
                        <th style="color:grey">Roles</th>
                        @role('Administrador')
                            <th class="col-4" style="color:grey">Permisos</th>
                        @endrole
                        <th class="col-2" style="color:grey">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td class="col-2">{{ $user->name }}</td>
                            <td class="col-2">{{ $user->email }}</td>
                            <td>
                                @foreach($user->getRoleNames() as $role)
                                    <span class="badge bg-secondary badgeRoles" style="box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4);">{{ $role }}</span>
                                @endforeach
                            </td>
                            @role('Administrador')
                            <td class="col-4">
                                @if($user->getDirectPermissions()->isNotEmpty() || $user->hasRole('Administrador'))
                                    @if($user->hasRole('Administrador'))
                                        @foreach($user->permissions as $permission)
                                            <span class="badge badgePermisos" style="background-color: #aed5b6;box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4);">{{ $permission->description }}</span>
                                        @endforeach
                                    @else
                                        @foreach($user->getDirectPermissions() as $permission)
                                            <span class="badge badgePermisos" style="background-color: #aed5b6; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.4);">{{ $permission->description }}</span>
                                        @endforeach
                                    @endif
                                @else
                                    <span class="text-muted">Sin permisos</span>
                                @endif
                            </td>
                            @endrole
                            <td class="col-2 text-center">
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    @if(Auth::user()->hasRole('Administrador') || $user->id === Auth::user()->id)
                                    
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm" style="background-color:transparent;">
                                            <i class="fa-solid fa-pen-to-square" title="Editar usuario"></i>
                                        </a>
                                    @endif

                                        {{-- Evitar que el administrador se elimine a sí mismo --}}
                                    @if(Auth::user()->hasRole('Administrador') && $user->id !== Auth::user()->id)
                                        
                                        @if (Auth::user()->id != $user->id)
                                            {{-- Mostrar botón para eliminar --}}
                                            <button type="button" class="btn btn-sm" onclick="confirmDelete('{{$user->id}}')" style="background-color:transparent;">
                                                <i class="fa-solid fa-trash-can" title="Eliminar usuario"></i>
                                            </button>
                                        @endif
                                    @endif

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>

@push('js')
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás volver atrás",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Eliminar de todas formas",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        });
    }
</script>
@endpush
@endsection
