<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElectroBol - Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: white;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover {
            background-color: #f0fdf4;
            color: #77dd77;
        }
        .sidebar .nav-link.active {
            background-color: #77dd77;
            color: white;
        }
        .navbar-brand {
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }
        .bg-custom-green { background-color: #77dd77 !important; }
        .card-stats {
            border: none;
            border-radius: 1rem;
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
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
    </style>
</head>
<body>

<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">
        <i class="bi bi-tools me-2"></i>ElectroBol Admin
    </a>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-link px-3 bg-dark border-0 text-white-50">
                    <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clientes.index') }}">
                            <i class="bi bi-people me-2"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articulos.index') }}">
                            <i class="bi bi-device-ssd me-2"></i> Artículos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reparaciones.index') }}">
                            <i class="bi bi-wrench me-2"></i> Reparaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tecnicos.index') }}">
                            <i class="bi bi-person-badge me-2"></i> Técnicos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('repuestos.index') }}">
                            <i class="bi bi-box-seam me-2"></i> Repuestos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reportes') }}">
                            <i class="bi bi-file-text me-2"></i> Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.auditoria') }}">
                            <i class="bi bi-clock-history me-2"></i> Auditoría
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.configuracion') }}">
                            <i class="bi bi-gear me-2"></i> Configuración
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-house me-2"></i> Volver al Sitio
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 text-dark">Panel Administrativo</h1>
                <div class="text-muted small">Bienvenido, {{ Auth::user()->name }}</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 0.8rem;">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Tarjetas de Estadísticas --}}
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50">Clientes</h6>
                                    <h2 class="mb-0">{{ $totalClientes }}</h2>
                                </div>
                                <i class="bi bi-people fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50">Artículos</h6>
                                    <h2 class="mb-0">{{ $totalArticulos }}</h2>
                                </div>
                                <i class="bi bi-device-ssd fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50">Técnicos</h6>
                                    <h2 class="mb-0">{{ $totalTecnicos }}</h2>
                                </div>
                                <i class="bi bi-person-badge fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card card-stats bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-dark-50">Reparaciones</h6>
                                    <h2 class="mb-0">{{ $totalReparaciones }}</h2>
                                </div>
                                <i class="bi bi-wrench fs-1 text-dark-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card card-stats bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50">Repuestos</h6>
                                    <h2 class="mb-0">{{ $totalRepuestos }}</h2>
                                </div>
                                <i class="bi bi-box-seam fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-stats bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50">Ingresos Totales</h6>
                                    <h2 class="mb-0">Bs {{ number_format($ingresosTotales, 2) }}</h2>
                                </div>
                                <i class="bi bi-cash-stack fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card card-stats bg-dark text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-white-50">Usuarios</h6>
                                    <h2 class="mb-0">{{ $users->count() }}</h2>
                                </div>
                                <i class="bi bi-person-circle fs-1 text-white-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gráficos --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-pie-chart me-2"></i>Reparaciones por Estado
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="estadosChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-trophy me-2"></i>Top 5 Técnicos
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Técnico</th>
                                            <th class="text-center">Reparaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topTecnicos as $tecnico)
                                        <tr>
                                            <td>{{ $tecnico->nombre }} {{ $tecnico->apellido }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $tecnico->reparaciones_count }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Últimas Reparaciones --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2"></i>Últimas Reparaciones
                    </h5>
                    <a href="{{ route('reparaciones.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Artículo</th>
                                    <th>Técnico</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasReparaciones as $reparacion)
                                <tr>
                                    <td class="fw-bold">#{{ $reparacion->id }}</td>
                                    <td>{{ $reparacion->articulo->cliente->nombre }} {{ $reparacion->articulo->cliente->apellido }}</td>
                                    <td>
                                        {{ $reparacion->articulo->tipo }}<br>
                                        <small>{{ $reparacion->articulo->marca }} {{ $reparacion->articulo->modelo }}</small>
                                    </td>
                                    <td>{{ $reparacion->tecnico->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reparacion->estado }}">
                                            {{ ucfirst($reparacion->estado) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($reparacion->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Gestión de Usuarios --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle me-2"></i>Gestión de Usuarios
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST" class="row g-3 mb-4">
                        @csrf
                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control" placeholder="Nombre" required>
                        </div>
                        <div class="col-md-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-2">
                            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="col-md-2">
                            <select name="role" class="form-select">
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn bg-custom-green text-white w-100">
                                <i class="bi bi-plus-lg"></i> Registrar
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td class="text-muted">{{ $user->email }}</td>
                                    <td>
                                        <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 120px;">
                                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Usuario</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Tú</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Gráfico de estados
    const estadosCtx = document.getElementById('estadosChart').getContext('2d');
    const estadosData = @json($reparacionesPorEstado);
    
    new Chart(estadosCtx, {
        type: 'doughnut',
        data: {
            labels: estadosData.map(item => {
                switch(item.estado) {
                    case 'evaluacion': return 'En Evaluación';
                    case 'reparacion': return 'En Reparación';
                    case 'entregado': return 'Entregado';
                    default: return item.estado;
                }
            }),
            datasets: [{
                data: estadosData.map(item => item.total),
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
</body>
</html>