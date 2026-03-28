<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELECTROBOL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-card {
            border-radius: 1.5rem;
            border: none;
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
        }
        .btn-custom {
            background-color: #77dd77;
            border: none;
            padding: 0.8rem;
            font-weight: bold;
            color: white;
        }
        .btn-custom:hover { background-color: #66cc66; color: white; }
    </style>
</head>
<body>

<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3 text-dark">Bienvenido a <span style="color: #77dd77;"> Electrobol</span></h1>
            <p class="col-lg-10 fs-4 text-muted">Sistema Gestión de Reparaciones</p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            
            {{-- Sección de errores corregida --}}
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm mb-3" style="border-radius: 1rem;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="p-4 p-md-5 border login-card bg-white" method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="floatingInput" placeholder="nombre@ejemplo.com" required value="{{ old('email') }}">
                    <label for="floatingInput">Correo electrónico</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                    <label for="floatingPassword">Contraseña</label>
                </div>
                <div class="checkbox mb-3">
                    <label><input type="checkbox" name="remember"> Recordarme</label>
                </div>
                <button class="w-100 btn btn-lg btn-custom shadow-sm" type="submit">Iniciar Sesión</button>
                <hr class="my-4">
                <small class="text-muted text-center d-block">Si no tienes acceso, solicita tu activación al administrador.</small>
            </form>
        </div>
    </div>
</div>

</body>
</html>