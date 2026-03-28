@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-tools me-2"></i>Mis Reparaciones
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tecnico.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mis Reparaciones</li>
                </ol>
            </nav>
            <p class="text-muted">Técnico: {{ $tecnico->nombre }} {{ $tecnico->apellido }}</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('tecnico.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>
    </div>

    @if($reparaciones->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Artículo</th>
                                <th>Estado</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reparaciones as $reparacion)
                            <tr>
                                <td class="fw-bold">#{{ $reparacion->id }}</td>
                                <td>
                                    {{ $reparacion->articulo->cliente->nombre_completo ?? 'N/A' }}
                                </td>
                                <td>
                                    <strong>{{ $reparacion->articulo->tipo ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $reparacion->articulo->marca ?? '' }} {{ $reparacion->articulo->modelo ?? '' }}</small>
                                </td>
                                <td>
                                    @switch($reparacion->estado)
                                        @case('evaluacion')
                                            <span class="badge bg-warning text-dark">En Evaluación</span>
                                            @break
                                        @case('reparacion')
                                            <span class="badge bg-info">En Reparación</span>
                                            @break
                                        @case('entregado')
                                            <span class="badge bg-success">Entregado</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $reparacion->estado }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($reparacion->created_at)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Ver Detalle
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                {{ $reparaciones->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <h5>No tienes reparaciones asignadas</h5>
            <p class="mb-0">Cuando te asignen un equipo, aparecerá aquí.</p>
        </div>
    @endif
</div>
@endsection