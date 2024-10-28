
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <title>Lista de Usuarios</title>
</head>
@include('dashboard.partials.header')

<body>
    <div class="container mt-5">
        <h2 class="text-center">Lista de Usuarios</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
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
                                <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    @role('Administrador')
            
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Editar
                                        </a>

                                        {{-- Evitar que el administrador se elimine a sí mismo --}}
                                        @if (Auth::user()->id != $user->id)
                                            {{-- Mostrar botón para eliminar --}}
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas eliminar este usuario?');">
                                                <i class="fa-solid fa-trash-can"></i> Eliminar
                                            </button>
                                        @endif
                                    @endrole

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
