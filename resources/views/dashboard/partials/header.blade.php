<header>
    <div class="w-100">
        

        <div class="row">
            
            <div class="col-lg-12">
                 
                <nav class="navbar shadow navbar-expand-lg navbar-dark" >
                <div id="logo" >
                    <a href="{{ route('inicio') }}">
                        <img src="{{ asset('img/logo-sin fondo (1).png') }}" alt="Logo">
                    </a>
                </div>
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <!-- Resumen -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('inicio') }}" data-text="Inicio">
                                        <i class="fas fa-home"></i> Inicio
                                    </a>
                                </li>
                                
                                <!-- Gestión de Productos -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Productos">
                                        <i class="fa-solid fa-boxes-stacked"></i> Productos
                                    </a>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            @can('ver-productos')
                                                <a class="dropdown-item" href="{{ route('productos.index') }}">Lista de productos</a>
                                            @endcan
                                        </li>
                                        <li>
                                            @can('agregar-producto')
                                                <a class="dropdown-item" href="{{ route('productos.create') }}">Agregar producto</a>
                                            @endcan
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- Registro y Historial de Ventas -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Ventas">
                                        <i class="fas fa-coins"></i> Ventas
                                    </a>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            @can('registrar-venta')
                                                <a class="dropdown-item" href="{{ route('ventas.create') }}">Registrar venta</a>
                                            @endcan
                                        </li>
                                        <li>
                                            @can('ver-ventas')
                                                <a class="dropdown-item" href="{{ route('ventas.index') }}">Historial de ventas</a>
                                            @endcan
                                        </li>
                                    </ul>
                                </li>

                                <!-- Control de Inventario -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Inventario">
                                        <i class="fas fa-warehouse"></i> Inventario
                                    </a>
                                    <ul class="dropdown-menu shadow">
                                        <li><a class="dropdown-item" href="{{ route('inventario.index') }}">Ver inventario</a></li>
                                        <li><a class="dropdown-item" href="{{ route('inventario.edit' ) }}">Actualizar inventario</a></li>
                                    </ul>
                                </li>

                                <!-- Exportación de Datos -->
                                <li class="nav-item dropdown">
                                    @can('exportar-archivos')
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Exportar">
                                        <i class="fas fa-file-export"></i> Exportar
                                        </a>
                                        <ul class="dropdown-menu shadow">
                                            <li><a class="dropdown-item" href="{{ route('ventas.export') }}">Exportar Ventas <i class="fa-solid fa-file-excel"></i> </a></li>
                                            <li><a class="dropdown-item" href=" {{route('productos.export')}}">Exportar Productos <i class="fa-solid fa-file-pdf"></i> </a></li>
                                        </ul>
                                    @endcan
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Usuarios">
                                        <i class="fas fa-users"></i> Usuarios
                                    </a>
                                    <ul class="dropdown-menu shadow">
                                        <li><a class="dropdown-item" href="{{ route  ('users.index')}}">Lista de usuarios</a></li>
                                        <li><a class="dropdown-item" href="{{ route('users.create') }}">Agregar usuario</a></li>
                                    </ul>
                                </li>

                                @canany(['abrir-caja', 'cerrar-caja'])
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Caja">
                                            <i class="fas fa-cash-register"></i>
                                            @if($cajaAbierta)
                                                Caja Abierta
                                            @else
                                                Caja Cerrada
                                            @endif
                                        </a>
                                        <ul class="dropdown-menu shadow">                           
                                            @can('abrir-caja')
                                                @if(!$cajaAbierta)    
                                                    <li>
                                                        <form action="{{ route('caja.abrir') }}" method="POST">
                                                            @csrf
                                                        
                                                            <button type="submit" class="dropdown-item">Abrir caja</button>
                                                        </form>
                                                    
                                                    </li>
                                                @endif
                                            @endcan    
                                            @can('cerrar-caja')
                                                @if($cajaAbierta)
                                                    <li>
                                                    
                                                        <form action="{{ route('caja.cerrar') }}" method="POST">
                                                            @csrf
                                                            
                                                            <button type="submit" class="dropdown-item">Cerrar caja</button>
                                                            
                                                        </form>
                                                </li>
                                                @endif
                                            @endcan
                                            @can('ver-total-caja')
                                                <li><a class="dropdown-item" href="{{ route  ('caja.total')}}">Total en caja</a></li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcanany
                                <!-- Perfil o Logout -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="{{ Auth::user()->usuario }}">
                                        <i class="fas fa-user-circle"></i>
                                        @if(Auth::check()) 
                                            {{ Auth::user()->usuario }}
                                        @else
                                            <script>window.location.href = "{{ route('usuario.login') }}";</script>
                                        @endif
                                    </a>
                                    <ul class="dropdown-menu shadow dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route  ('users.edit',Auth::user()->id) }}">Editar Usuario</a>
                                            </li>
                                        <li>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                    <button type="button" class="dropdown-item" id="logout-button">Cerrar Sesión</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div> 
</header>
