<header>
    <div class="container-lg">
        <div id="logo">
            <a href="{{ route('inicio') }}">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo">
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12">     
                <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #aed6b5;">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <!-- Resumen -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('inicio') }}" style="color: #000;"><i class="fas fa-home"></i> Inicio</a>
                                </li>
                                
                                <!-- Gestión de Productos -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                        <i class="fa-solid fa-boxes-stacked"></i> Productos
                                    </a>
                                    <ul class="dropdown-menu">
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
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                        <i class="fas fa-coins"></i> Ventas
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            @can('registrar-venta')
                                                <a class="dropdown-item" href="{{ route('ventas.create') }}">Registrar venta</a>
                                            @endcan
                                        </li>
                                        <li><a class="dropdown-item" href="#">Historial de ventas</a></li>
                                    </ul>
                                </li>

                                <!-- Control de Inventario -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                        <i class="fas fa-warehouse"></i> Inventario
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Control de stock</a></li>
                                        <li><a class="dropdown-item" href="#">Actualizar inventario</a></li>
                                    </ul>
                                </li>

                                <!-- Exportación de Datos -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                     <i class="fas fa-file-export"></i> Exportar
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Exportar Excel <i class="fa-solid fa-file-excel"></i> </a></li>
                                        <li><a class="dropdown-item" href="#">Exportar PDF <i class="fa-solid fa-file-pdf"></i> </a></li>
                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                        <i class="fas fa-users"></i> Usuarios
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route  ('users.index')}}">Lista de usuarios</a></li>
                                        <li><a class="dropdown-item" href="{{ route('users.create') }}">Agregar usuario</a></li>
                                    </ul>
                                </li>

                                @canany(['abrir-caja', 'cerrar-caja'])
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #000;">
                                            <i class="fas fa-cash-register"></i>
                                            @if($cajaAbierta)
                                                Caja Abierta
                                            @else
                                                Caja Cerrada
                                            @endif
                                        </a>
                                        <ul class="dropdown-menu">
                                            
                                            
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
                                            
                                        </ul>
                                    </li>
                                @endcanany
                                <!-- Perfil o Logout -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:#000;">
                                        <i class="fas fa-user-circle"></i> {{ Auth::user()->usuario }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route  ('users.edit',Auth::user()->id) }}">Editar Usuario</a>
                                            </li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                    <button type="submit" class="dropdown-item">Cerrar Sesión</button>
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
