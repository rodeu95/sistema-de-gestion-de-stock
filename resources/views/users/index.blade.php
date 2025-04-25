
@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <h1 class="my-4">Lista de Usuarios</h2>
        <div class="table-wrapper mb-3 shadow">
            <div class="table-responsive rounded-3 overflow-auto" style="max-width: 100%; overflow-x: auto;">
                <table class="table mb-0">
                    <thead >
                        <tr>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">ID</th>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Nombre</th>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Email</th>
                            <th style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Roles</th>
                            @role('Administrador')
                                <th class="col-4" style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Permisos</th>
                            @endrole
                            <th class="col-2" style="color:#fff; background-color:#acd8b5; text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.6);">Acciones</th>
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
                                                <span class="badge shadow badgePermisos">{{ $permission->description }}</span>
                                            @endforeach
                                        @else
                                            @foreach($user->getDirectPermissions() as $permission)
                                                <span class="badge shadow badgePermisos">{{ $permission->description }}</span>
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
            confirmButtonColor: "#acd8b5",
            cancelButtonColor: "grey",
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
