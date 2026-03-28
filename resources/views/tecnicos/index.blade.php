@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-person-badge me-2"></i>Gestión de Técnicos
            </h2>
            <p class="text-muted">Administra el personal técnico del taller</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('tecnicos.create') }}" class="btn btn-custom">
                <i class="bi bi-person-plus me-2"></i>Nuevo Técnico
            </a>
        </div>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Total Técnicos</h6>
                            <h2 class="mb-0">{{ $tecnicos->count() }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Técnicos Activos</h6>
                            <h2 class="mb-0">{{ $tecnicos->where('activo', true)->count() }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-dark-50">Reparaciones Activas</h6>
                            <h2 class="mb-0">{{ $tecnicos->sum(function($t) { return $t->reparaciones->where('estado', '!=', 'entregado')->count(); }) }}</h2>
                        </div>
                        <i class="bi bi-tools fs-1 text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Reparaciones Completadas</h6>
                            <h2 class="mb-0">{{ $tecnicos->sum(function($t) { return $t->reparaciones->where('estado', 'entregado')->count(); }) }}</h2>
                        </div>
                        <i class="bi bi-check2-all fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de técnicos --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Reparaciones</th>
                            <th>Acciones</th>
                        </thead>
                    <tbody>
                        @forelse($tecnicos as $tecnico)
                         <tr>
                            <td>{{ $tecnico->id }}</td>
                            <td>
                                <strong>{{ $tecnico->nombre_completo }}</strong>
                                @if($tecnico->user)
                                    <br><small class="text-success">✓ Cuenta vinculada</small>
                                @else
                                    <br><small class="text-warning">⚠ Sin usuario</small>
                                @endif
                            </td>
                            <td>{{ $tecnico->especialidad ?? 'Sin especialidad' }}</td>
                            <td>{{ $tecnico->telefono ?? 'N/A' }}</td>
                            <td>{{ $tecnico->email }}</td>
                            <td>
                                @if($tecnico->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $tecnico->reparaciones->count() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('tecnicos.show', $tecnico) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('tecnicos.edit', $tecnico) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('tecnicos.reporte', $tecnico) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-file-text"></i>
                                </a>
                                @if(!$tecnico->user)
                                <a href="{{ route('tecnicos.vincular-form', $tecnico) }}" class="btn btn-sm btn-outline-success" title="Vincular usuario">
                                    <i class="bi bi-link"></i>
                                 </a>
                                @endif
                            </td>
                         </tr>
                        @empty
                         <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <h5>No hay técnicos registrados</h5>
                                <p class="mb-0">Haz clic en "Nuevo Técnico" para comenzar.</p>
                            </td>
                         </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function vincularUsuario(tecnicoId) {
    const email = prompt('Ingrese el email del usuario que será vinculado a este técnico:');
    if (email) {
        window.location.href = '/tecnicos/' + tecnicoId + '/vincular?email=' + email;
    }
}
</script>
@endsection

@push('styles')
<style>
    .btn-custom {
        background-color: #77dd77;
        border: none;
        color: white;
    }
    .btn-custom:hover {
        background-color: #66cc66;
        color: white;
    }
</style>
@endpush