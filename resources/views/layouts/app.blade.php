<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>ElectroBol - Sistema de Reparaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #77dd77;
        }
        
        /* Menú hamburguesa */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            margin-right: 1rem;
        }
        
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
        }
        
        /* Sidebar responsivo */
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 56px;
                width: 280px;
                z-index: 1000;
                transition: left 0.3s ease;
                height: calc(100vh - 56px);
                overflow-y: auto;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            main {
                width: 100%;
            }
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
            border-radius: 8px;
            margin: 2px 8px;
        }
        .sidebar .nav-link:hover {
            background-color: #f0fdf4;
            color: #77dd77;
        }
        .sidebar .nav-link.active {
            background-color: #77dd77;
            color: white;
        }
        
        .btn-custom {
            background-color: #77dd77;
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #66cc66;
            color: white;
            transform: translateY(-1px);
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-evaluacion { background-color: #ffc107; color: #000; }
        .status-reparacion { background-color: #17a2b8; color: #fff; }
        .status-entregado { background-color: #28a745; color: #fff; }
        
        .card-stats {
            border: none;
            border-radius: 1rem;
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
        .card {
            border-radius: 1rem;
            overflow: hidden;
        }
        .card-header {
            border-bottom: 2px solid #77dd77;
        }
        .table-responsive {
            border-radius: 0.5rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .badge {
            font-weight: 500;
        }
        
        /* Tablas responsivas */
        @media (max-width: 768px) {
            .table {
                font-size: 0.85rem;
                min-width: 600px;
            }
            .table th, .table td {
                padding: 0.5rem;
            }
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            .card-stats h2 {
                font-size: 1.5rem;
            }
        }
        
        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #77dd77;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #66cc66;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom shadow-sm">
        <div class="container-fluid">
            <button class="menu-toggle" id="menuToggle">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand fw-bold text-white" href="{{ route('dashboard') }}">
                <i class="bi bi-tools me-2"></i>ElectroBol
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            @if(Auth::user()->role === 'admin')
                                <span class="badge bg-danger ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2"></i>Panel Admin</a></li>
                            @endif
                            @if(Auth::user()->tecnico)
                                <li><a class="dropdown-item" href="{{ route('tecnico.dashboard') }}"><i class="bi bi-tools me-2"></i>Panel Técnico</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        
                        {{-- Menú para ADMIN --}}
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                                    <i class="bi bi-people me-2"></i>Clientes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('articulos.*') ? 'active' : '' }}" href="{{ route('articulos.index') }}">
                                    <i class="bi bi-device-ssd me-2"></i>Artículos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reparaciones.*') ? 'active' : '' }}" href="{{ route('reparaciones.index') }}">
                                    <i class="bi bi-wrench me-2"></i>Reparaciones
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tecnicos.*') ? 'active' : '' }}" href="{{ route('tecnicos.index') }}">
                                    <i class="bi bi-person-badge me-2"></i>Técnicos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('repuestos.*') ? 'active' : '' }}" href="{{ route('repuestos.index') }}">
                                    <i class="bi bi-box-seam me-2"></i>Repuestos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.buscar-tecnico') }}">
                                    <i class="bi bi-file-text me-2"></i>Reportes
                                </a>
                            </li>
                            <li class="nav-item mt-3">
                                <hr class="my-2">
                                <strong class="text-muted small px-3">ADMINISTRACIÓN</strong>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-lock me-2"></i>Panel Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reportes') ? 'active' : '' }}" href="{{ route('admin.reportes') }}">
                                    <i class="bi bi-graph-up me-2"></i>Reportes Admin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.auditoria') ? 'active' : '' }}" href="{{ route('admin.auditoria') }}">
                                    <i class="bi bi-clock-history me-2"></i>Auditoría
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.respaldos') ? 'active' : '' }}" href="{{ route('admin.respaldos') }}">
                                    <i class="bi bi-database me-2"></i>Respaldos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.configuracion') ? 'active' : '' }}" href="{{ route('admin.configuracion') }}">
                                    <i class="bi bi-gear me-2"></i>Configuración
                                </a>
                            </li>
                        
                        {{-- Menú para TÉCNICOS --}}
                        @elseif(Auth::user()->tecnico)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tecnico.dashboard') ? 'active' : '' }}" href="{{ route('tecnico.dashboard') }}">
                                    <i class="bi bi-tools me-2"></i>Mi Panel
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tecnico.reparaciones') ? 'active' : '' }}" href="{{ route('tecnico.reparaciones') }}">
                                    <i class="bi bi-list-check me-2"></i>Mis Reparaciones
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reparaciones.*') ? 'active' : '' }}" href="{{ route('reparaciones.index') }}">
                                    <i class="bi bi-eye me-2"></i>Ver Todas
                                </a>
                            </li>
                        
                        {{-- Menú para USUARIO NORMAL --}}
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                                    <i class="bi bi-house me-2"></i>Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('reparaciones.*') ? 'active' : '' }}" href="{{ route('reparaciones.index') }}">
                                    <i class="bi bi-wrench me-2"></i>Mis Reparaciones
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
            
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 fade-in">
                {{-- Alertas --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Menú móvil
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                if (overlay) overlay.classList.toggle('show');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
        
        // Cerrar menú al hacer clic en un enlace (móvil)
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    if (overlay) overlay.classList.remove('show');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>