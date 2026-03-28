<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElectroBol - Panel de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .welcome-card {
            background: white;
            padding: 3rem;
            border-radius: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            border: 2px solid #77dd77;
            max-width: 500px;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .welcome-card:hover {
            transform: translateY(-5px);
        }
        .avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #77dd77, #66cc66);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 5px 15px rgba(119, 221, 119, 0.3);
        }
        .avatar i {
            font-size: 3rem;
            color: white;
        }
        .btn-logout {
            background-color: #77dd77;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background-color: #66cc66;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(119, 221, 119, 0.4);
        }
        .btn-primary {
            background-color: #77dd77;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #66cc66;
            transform: translateY(-2px);
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 1rem;
            padding: 1rem;
            margin: 1.5rem 0;
            text-align: left;
        }
        .info-box p {
            margin-bottom: 0.5rem;
        }
        .info-box i {
            color: #77dd77;
            width: 25px;
        }
        .badge-role {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        .badge-user {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #77dd77, transparent);
            margin: 1.5rem 0;
        }
    </style>
</head>
<body>

<div class="welcome-card">
    <div class="avatar">
        <i class="bi bi-person-circle"></i>
    </div>
    
    <h1 class="display-5 fw-bold mb-2">¡Bienvenido!</h1>
    <p class="fs-4 text-muted mb-1">Hola, <strong style="color: #77dd77;">{{ Auth::user()->name }}</strong></p>
    
    <div class="badge-role badge-user">
        <i class="bi bi-person-check me-1"></i>Usuario Regular
    </div>
    
    <div class="divider"></div>
    
    <div class="info-box">
        <p><i class="bi bi-envelope me-2"></i> <strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p><i class="bi bi-calendar me-2"></i> <strong>Miembro desde:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
        <p><i class="bi bi-shield-check me-2"></i> <strong>Estado:</strong> 
            @if(Auth::user()->is_active)
                <span class="text-success">Cuenta activa</span>
            @else
                <span class="text-danger">Cuenta inactiva</span>
            @endif
        </p>
    </div>
    
    <p class="text-muted small">
        <i class="bi bi-info-circle"></i> Has ingresado correctamente al sistema de reparaciones ElectroBol.
    </p>
    
    <div class="mt-3">
        <a href="{{ route('reparaciones.index') }}" class="btn btn-primary">
            <i class="bi bi-wrench me-2"></i>Ver mis reparaciones
        </a>
    </div>
    
    <form action="{{ route('logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-logout shadow-sm">
            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>