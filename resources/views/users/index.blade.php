
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center my-4" style="margin:2%;">Lista de Usuarios</h2>

        <div class="table-responsive">
            <table class="table shadow table-bordered table-hover ">
                <thead>
                    <tr class="table-warning text-center">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="col-4">Permisos</th>
                        <th class="col-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</tdc>
                            <td class="col-2">{{ $user->name }}</td>
                            <td class="col-2">{{ $user->email }}</td>
                            <td>
                                @foreach($user->getRoleNames() as $role)
                                    <span class="badge bg-secondary">{{ $role }}</span>
                                @endforeach
                            </td>
                            <td class="col-4">
                                @if($user->getDirectPermissions()->isNotEmpty() || $user->hasRole('Administrador'))
                                    @if($user->hasRole('Administrador'))
                                        @foreach($user->getAllPermissions() as $permission)
                                            <span class="badge bg-success">{{ $permission->name }}</span>
                                        @endforeach
                                    @else
                                        @foreach($user->getDirectPermissions() as $permission)
                                            <span class="badge bg-success">{{ $permission->name }}</span>
                                        @endforeach
                                    @endif
                                @else
                                    <span class="text-muted">Sin permisos</span>
                                @endif
                            </td>
                            <td class="col-2 text-center">
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    @if(Auth::user()->hasRole('Administrador') || $user->id === Auth::user()->id)
                                    
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn shadow btn-primary btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Editar
                                        </a>
                                    @endif

                                        {{-- Evitar que el administrador se elimine a sí mismo --}}
                                    @if(Auth::user()->hasRole('Administrador') && $user->id !== Auth::user()->id)
                                        

                                        @if (Auth::user()->id != $user->id)
                                            {{-- Mostrar botón para eliminar --}}
                                            <button type="submit" class="btn shadow btn-danger btn-sm" onclick="confirmDelete('{{$user->id}}')">
                                                <i class="fa-solid fa-trash-can"></i> Eliminar
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
