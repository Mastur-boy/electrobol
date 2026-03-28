@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-link me-2"></i>Vincular Técnico con Usuario
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tecnicos.index') }}">Técnicos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tecnicos.show', $tecnico) }}">{{ $tecnico->nombre }} {{ $tecnico->apellido }}</a></li>
                    <li class="breadcrumb-item active">Vincular</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            {{-- Información del Técnico --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Información del Técnico
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle fs-1 text-secondary"></i>
                        <h4 class="mt-2">{{ $tecnico->nombre }} {{ $tecnico->apellido }}</h4>
                        <span class="badge {{ $tecnico->activo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $tecnico->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <hr>
                    <p><strong><i class="bi bi-envelope me-2"></i>Email:</strong> {{ $tecnico->email }}</p>
                    <p><strong><i class="bi bi-telephone me-2"></i>Teléfono:</strong> {{ $tecnico->telefono ?? 'N/A' }}</p>
                    <p><strong><i class="bi bi-tools me-2"></i>Especialidad:</strong> {{ $tecnico->especialidad ?? 'Sin especialidad' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            {{-- Opción 1: Vincular usuario existente --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-check me-2"></i>Opción 1: Vincular Usuario Existente
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tecnicos.vincular', $tecnico) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email del Usuario</label>
                            <input type="email" name="email" class="form-control" placeholder="usuario@ejemplo.com" required>
                            <small class="text-muted">El usuario debe estar registrado en el sistema</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-link me-2"></i>Vincular Usuario
                        </button>
                    </form>
                </div>
            </div>

            {{-- Opción 2: Crear nuevo usuario --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-plus me-2"></i>Opción 2: Crear Nuevo Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tecnicos.crear-usuario', $tecnico) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">CI *</label>
                            <input type="text" name="ci" class="form-control" placeholder="123456789" required>
                            <small class="text-muted">Número de identificación único</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $tecnico->email) }}" required>
                            <small class="text-muted">El email será usado para iniciar sesión</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña *</label>
                            <input type="text" name="password" class="form-control" value="tecnico123" required>
                            <small class="text-muted">Contraseña por defecto: tecnico123</small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Crear Usuario y Vincular
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de usuarios disponibles (MEJORADA) --}}
    <div class="row mt-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people me-2"></i>Usuarios Disponibles para Vincular
                    </h5>
                    <span class="badge bg-secondary">{{ $usuarios->count() }} usuarios disponibles</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                
                                    <th class="text-center">ID</th>
                                    <th>Nombre</th>
                                    <th>CI</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acción</th>
                                </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                <tr class="align-middle">
                                    <td class="text-center fw-bold">{{ $usuario->id }} </td>
                                    <td>
                                        <i class="bi bi-person-circle me-2 text-secondary"></i>
                                        <strong>{{ $usuario->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-card-text me-1"></i>{{ $usuario->ci ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-envelope me-1 text-muted"></i>
                                        {{ $usuario->email }}
                                    </td>
                                    <td>
                                        @if($usuario->role == 'admin')
                                            <span class="badge bg-danger">
                                                <i class="bi bi-shield-lock me-1"></i>Administrador
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="bi bi-person me-1"></i>Usuario
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($usuario->is_active)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Activo
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-x-circle me-1"></i>Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('tecnicos.vincular', $tecnico) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $usuario->email }}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Vincular este usuario">
                                                <i class="bi bi-link me-1"></i> Vincular
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        <h5>No hay usuarios disponibles para vincular</h5>
                                        <p class="mb-0">Puedes crear un nuevo usuario usando el formulario de la derecha.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($usuarios->count() > 0)
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Los usuarios en esta lista aún no están vinculados a ningún técnico.
                        </small>
                        <small class="text-muted">
                            Total: {{ $usuarios->count() }} usuarios
                        </small>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-4 text-end">
        <a href="{{ route('tecnicos.show', $tecnico) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-primary, .btn-success {
        transition: all 0.3s ease;
    }
    .btn-primary:hover, .btn-success:hover {
        transform: translateY(-1px);
    }
    .card {
        border-radius: 1rem;
        overflow: hidden;
    }
    .card-header {
        border-bottom: 2px solid #77dd77;
    }
    .table th {
        font-weight: 600;
        border-top: none;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
    }
</style>
@endpush