<header>
    <div class="w-100">
        <div class="row">            
            <div class="col-lg-12">
                <nav class="navbar shadow navbar-expand-lg navbar-dark" >
                    <div class="container-fluid d-flex  align-items-center">
                        <button class="btn btn-outline-light d-lg-none ms-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
                            <i class="fas fa-bars"></i>
                        </button>
                        
                        <div class="d-none d-lg-none" style="width: 42px;"></div>

                        <div id="logo">
                            <a href="{{ route('inicio') }}">
                                <img src="{{ asset('img/logo-sin fondo (1).png') }}" alt="Logo">
                            </a>
                        </div>

                        <div class="collapse navbar-collapse d-none d-lg-flex " id="navbarNav">
                            <ul class="navbar-nav">
                                
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('inicio') }}">
                                        <i class="fas fa-home"></i> Inicio
                                    </a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-boxes-stacked"></i>  Productos
                                    </a>
                                    <ul class="dropdown-menu shadow">
                                        @can('ver-productos')
                                            <li><a class="dropdown-item" href="{{ route('productos.index') }}"><i class="fa-solid fa-list"></i> Lista de productos</a></li>
                                        @endcan
                                        @can('agregar-producto')
                                            <li><a class="dropdown-item" href="{{ route('productos.create') }}"><i class="fa-solid fa-circle-plus"></i> Agregar producto</a></li>
                                        @endcan
                                        @can('ver-lotes')
                                            <li><a class="dropdown-item" href="{{ route('lotes.index') }}"><i class="fa-solid fa-dolly"></i> Lotes</a></li>
                                        @endcan
                                        @can('ver-proveedores')
                                            <li><a class="dropdown-item" href="{{ route('proveedores.index') }}"><i class="fa-solid fa-truck"></i> Proveedores</a></li>
                                        @endcan
                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Ventas">
                                        <i class="fas fa-coins"></i> Ventas
                                    </a>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            @can('registrar-venta')
                                                <a class="dropdown-item" href="{{ route('ventas.create') }}"><i class="fa-solid fa-cash-register"></i> Registrar venta</a>
                                            @endcan
                                        </li>
                                        <li>
                                            @can('ver-ventas')
                                                <a class="dropdown-item" href="{{ route('ventas.index') }}"><i class="fa-solid fa-folder-open"></i> Historial de ventas</a>
                                            @endcan
                                        </li>
                                    </ul>
                                </li>
                            
                                <li class="nav-item dropdown">
                                    @can('gestionar-inventario')
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Inventario">
                                            <i class="fas fa-warehouse"></i> Inventario
                                        </a>

                                        <ul class="dropdown-menu shadow">
                                            <li><a class="dropdown-item" href="{{ route('inventario.index') }}"> <i class="fa-solid fa-clipboard-list"></i> Ver inventario</a></li>
                                            <li><a class="dropdown-item" href="{{ route('inventario.edit' ) }}"><i class="fa-solid fa-truck-ramp-box"></i> Actualizar inventario</a></li>
                                        </ul>
                                    @endcan
                                </li>

                                
                                <li class="nav-item dropdown">
                                    @can('exportar-archivos')
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Exportar">
                                        <i class="fas fa-file-export"></i> Exportar
                                        </a>
                                        <ul class="dropdown-menu shadow">
                                            <li><a class="dropdown-item" href="{{ route('ventas.export') }}"><i class="fa-solid fa-file-excel"></i> Exportar Ventas</a></li>
                                            <li><a class="dropdown-item" href=" {{route('productos.export')}}"><i class="fa-solid fa-file-pdf"></i> Exportar Productos</a></li>
                                        </ul>
                                    @endcan
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Usuarios">
                                        <i class="fas fa-users"></i> Usuarios
                                    </a>
                                    <ul class="dropdown-menu shadow">
                                        @can('ver-usuarios')
                                            <li><a class="dropdown-item" href="{{ route  ('users.index')}}"><i class="fa-solid fa-users"></i> Lista de usuarios</a></li>
                                        @endcan
                                        @can('agregar-usuario')
                                            <li><a class="dropdown-item" href="{{ route('users.create') }}"><i class="fa-solid fa-user-plus"></i> Agregar usuario</a></li>
                                        @endcan
                                    </ul>
                                </li>

                                @canany(['abrir-caja', 'cerrar-caja'])
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-text="Caja">
                                            
                                            @if($cajaAbierta)
                                                <i class="fa-solid fa-lock-open"></i> Caja abierta
                                            @else
                                                <i class="fa-solid fa-lock"></i> Caja cerrada
                                            @endif
                                        </a>
                                        <ul class="dropdown-menu shadow">                           
                                            @can('abrir-caja')
                                                @if(!$cajaAbierta)    
                                                    <li>
                                                        <form id="abrir-caja-form" action="{{ route('caja.abrir') }}" method="POST">
                                                            @csrf
                                                        
                                                            <button type="submit" class="dropdown-item"><i class="fa-solid fa-lock-open"></i>  Abrir caja</button>
                                                        </form>
                                                    
                                                    </li>
                                                @endif
                                            @endcan    
                                            @can('cerrar-caja-form')
                                                @if($cajaAbierta)
                                                    <li>
                                                    
                                                        <form id="cerrar-caja" action="{{ route('caja.cerrar') }}" method="POST">
                                                            @csrf
                                                            
                                                            <button type="submit" class="dropdown-item"><i class="fa-solid fa-lock"></i> Cerrar caja</button>
                                                            
                                                        </form>
                                                </li>
                                                @endif
                                            @endcan
                                            @can('ver-total-caja')
                                                <li><a class="dropdown-item" href="{{ route  ('caja.total')}}"><i class="fa-solid fa-hand-holding-dollar"></i> Total en caja</a></li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcanany
                                
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
                                                <a class="dropdown-item" href="{{ route  ('users.edit',Auth::user()->id) }}"><i class="fa-solid fa-user-pen"></i> Editar Usuario</a>
                                            </li>
                                        <li>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                    <button type="button" class="dropdown-item" id="logout-button"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        
                    </div> 
                                     
                </nav>

<!-- Offcanvas lateral para móviles -->
                <div class="offcanvas offcanvas-start side-menu" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
                    <div class="offcanvas-header  d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div id="logo-side" class="me-2">
                                <a href="{{ route('inicio') }}">
                                    <img src="{{ asset('img/logo-sin fondo (1).png') }}" alt="Logo">
                                </a>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
                    </div>

                    <div class="offcanvas-body">
                        <ul class="navbar-nav navbar-main">
                            
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('inicio') }}">
                                    <i class="fas fa-home"></i> Inicio
                                </a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" >
                                    <i class="fa-solid fa-boxes-stacked"></i> Productos
                                </a>
                                <ul class="dropdown-menu-lateral shadow">
                                    @can('ver-productos')
                                        <li><a class="dropdown-item" href="{{ route('productos.index') }}"><i class="fa-solid fa-list"></i> Lista de productos</a></li>
                                    @endcan
                                    @can('agregar-producto')
                                        <li><a class="dropdown-item" href="{{ route('productos.create') }}"><i class="fa-solid fa-circle-plus"></i> Agregar producto</a></li>
                                    @endcan
                                    @can('ver-lotes')
                                        <li><a class="dropdown-item" href="{{ route('lotes.index') }}"><i class="fa-solid fa-dolly"></i> Lotes</a></li>
                                    @endcan
                                    @can('ver-proveedores')
                                        <li><a class="dropdown-item" href="{{ route('proveedores.index') }}"><i class="fa-solid fa-truck"></i> Proveedores</a></li>
                                    @endcan
                                </ul>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-coins"></i> Ventas
                                </a>
                                <ul class="dropdown-menu-lateral shadow">
                                    <li>
                                        @can('registrar-venta')
                                            <a class="dropdown-item" href="{{ route('ventas.create') }}"><i class="fa-solid fa-cash-register"></i> Registrar venta</a>
                                        @endcan
                                    </li>
                                    <li>
                                        @can('ver-ventas')
                                            <a class="dropdown-item" href="{{ route('ventas.index') }}"><i class="fa-solid fa-folder-open"></i> Historial de ventas</a>
                                        @endcan
                                    </li>
                                </ul>
                            </li>

                            
                            <li class="nav-item dropdown">
                                @can('gestionar-inventario')
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-warehouse"></i> Inventario
                                    </a>

                                    <ul class="dropdown-menu-lateral shadow">
                                        <li><a class="dropdown-item" href="{{ route('inventario.index') }}"> <i class="fa-solid fa-clipboard-list"></i> Ver inventario</a></li>
                                        <li><a class="dropdown-item" href="{{ route('inventario.edit' ) }}"><i class="fa-solid fa-truck-ramp-box"></i> Actualizar inventario</a></li>
                                    </ul>
                                @endcan
                            </li>

                            
                            <li class="nav-item dropdown">
                                @can('exportar-archivos')
                                    <a class="nav-link" href="#">
                                    <i class="fas fa-file-export"></i> Exportar
                                    </a>
                                    <ul class="dropdown-menu-lateral shadow">
                                        <li><a class="dropdown-item" href="{{ route('ventas.export') }}"><i class="fa-solid fa-file-excel"></i> Exportar Ventas</a></li>
                                        <li><a class="dropdown-item" href=" {{route('productos.export')}}"><i class="fa-solid fa-file-pdf"></i> Exportar Productos</a></li>
                                    </ul>
                                @endcan
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-users"></i> Usuarios
                                </a>
                                <ul class="dropdown-menu-lateral shadow">
                                    @can('ver-usuarios')
                                        <li><a class="dropdown-item" href="{{ route  ('users.index')}}"><i class="fa-solid fa-users"></i> Lista de usuarios</a></li>
                                    @endcan
                                    @can('agregar-usuario')
                                        <li><a class="dropdown-item" href="{{ route('users.create') }}"><i class="fa-solid fa-user-plus"></i> Agregar usuario</a></li>
                                    @endcan
                                </ul>
                            </li>

                            @canany(['abrir-caja', 'cerrar-caja'])
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#" data-text="Caja">
                                        @if($cajaAbierta)
                                            <i class="fa-solid fa-lock-open"></i> Caja abierta
                                        @else
                                            <i class="fa-solid fa-lock"></i> Caja cerrada
                                        @endif
                                    </a>
                                    <ul class="dropdown-menu-lateral shadow">                           
                                        @can('abrir-caja')
                                            @if(!$cajaAbierta)    
                                                <li>
                                                    <form id="abrir-caja-form" action="{{ route('caja.abrir') }}" method="POST">
                                                        @csrf
                                                    
                                                        <button type="submit" class="dropdown-item"><i class="fa-solid fa-lock-open"></i>  Abrir caja</button>
                                                    </form>
                                                
                                                </li>
                                            @endif
                                        @endcan    
                                        @can('cerrar-caja-form')
                                            @if($cajaAbierta)
                                                <li>
                                                
                                                    <form id="cerrar-caja" action="{{ route('caja.cerrar') }}" method="POST">
                                                        @csrf
                                                        
                                                        <button type="submit" class="dropdown-item"><i class="fa-solid fa-lock"></i> Cerrar caja</button>
                                                        
                                                    </form>
                                            </li>
                                            @endif
                                        @endcan
                                        @can('ver-total-caja')
                                            <li><a class="dropdown-item" href="{{ route  ('caja.total')}}"><i class="fa-solid fa-hand-holding-dollar"></i> Total en caja</a></li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="userDropdown" data-text="{{ Auth::user()->usuario }}">
                                    <i class="fas fa-user-circle"></i>
                                    @if(Auth::check()) 
                                        {{ Auth::user()->usuario }}
                                    @else
                                        <script>window.location.href = "{{ route('usuario.login') }}";</script>
                                    @endif
                                </a>
                                <ul class="dropdown-menu-lateral shadow">
                                    <li>
                                        <a class="dropdown-item" href="{{ route  ('users.edit',Auth::user()->id) }}"><i class="fa-solid fa-user-pen"></i> Editar Usuario</a>
                                    </li>
                                <li>
                                    <form id="logout-form-lateral" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                            <button type="button" class="dropdown-item" id="logout-button-lateral"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div> 
</header>
