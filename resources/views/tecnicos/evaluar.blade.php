@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-clipboard-check me-2"></i>Evaluar Reparación
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tecnico.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Evaluar Reparación #{{ $reparacione->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Información del Equipo</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Cliente:</strong> 
                        @if($reparacione->articulo && $reparacione->articulo->cliente)
                            {{ $reparacione->articulo->cliente->nombre_completo }}
                        @else
                            <span class="text-danger">No disponible</span>
                        @endif
                    </p>
                    <p><strong>Artículo:</strong> 
                        @if($reparacione->articulo)
                            {{ $reparacione->articulo->tipo }} - {{ $reparacione->articulo->marca }} {{ $reparacione->articulo->modelo }}
                        @else
                            <span class="text-danger">No disponible</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Serial:</strong> {{ $reparacione->articulo->serial ?? 'N/A' }}</p>
                    <p><strong>Problema reportado:</strong></p>
                    <p class="text-muted">{{ $reparacione->articulo->problema_descripcion ?? 'No disponible' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Registrar Evaluación</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reparaciones.evaluar', $reparacione) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Diagnóstico *</label>
                    <textarea name="diagnostico" class="form-control" rows="4" required placeholder="Describa el diagnóstico del equipo..."></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tiempo estimado (horas) *</label>
                        <input type="number" name="tiempo_estimado_horas" class="form-control" step="0.5" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Costo de reparación (Bs) *</label>
                        <input type="number" name="costo_reparacion" class="form-control" step="0.01" required>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ route('tecnico.dashboard') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Registrar Evaluación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection