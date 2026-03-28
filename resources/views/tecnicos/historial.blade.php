@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-clock-history me-2"></i>Historial de Actividades
            </h2>
            <p class="text-muted">Técnico: {{ $tecnico->nombre }} {{ $tecnico->apellido }}</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('tecnicos.show', $tecnico) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="timeline">
                @forelse($historial as $registro)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <span class="badge bg-info me-2">
                                            {{ ucfirst($registro->accion) }}
                                        </span>
                                        Reparación #{{ $registro->reparacion->id }}
                                    </h6>
                                    <p class="mb-1">{{ $registro->detalle }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-device-ssd me-1"></i>
                                        {{ $registro->reparacion->articulo->tipo }} 
                                        {{ $registro->reparacion->articulo->marca }} 
                                        {{ $registro->reparacion->articulo->modelo }}
                                        - Cliente: {{ $registro->reparacion->articulo->cliente->nombre }} {{ $registro->reparacion->articulo->cliente->apellido }}
                                    </small>
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        <h5>No hay registros de actividad</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding: 1rem 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 2rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        padding-left: 4rem;
        margin-bottom: 1.5rem;
    }
    .timeline-marker {
        position: absolute;
        left: 1.5rem;
        top: 0;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #77dd77;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #77dd77;
    }
    .timeline-content {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>
@endpush