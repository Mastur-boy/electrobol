@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-file-text me-2"></i>Reporte de Reparaciones
            </h2>
            <p class="text-muted">Técnico: {{ $tecnico->nombre }} {{ $tecnico->apellido }}</p>
        </div>
        <div class="col text-end">
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bi bi-printer me-2"></i>Imprimir
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Listado de Reparaciones</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Artículo</th>
                            <th>Estado</th>
                            <th>Diagnóstico</th>
                            <th>Fecha Asignación</th>
                            <th>Fecha Entrega</th>
                        </thead>
                    <tbody>
                        @forelse($reparaciones as $reparacion)
                         <tr>
                            <td>#{{ $reparacion->id }}</td>
                            <td>{{ $reparacion->articulo->cliente->nombre }} {{ $reparacion->articulo->cliente->apellido }}</td>
                            <td>
                                {{ $reparacion->articulo->tipo }}<br>
                                <small>{{ $reparacion->articulo->marca }} {{ $reparacion->articulo->modelo }}</small>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $reparacion->estado }}">
                                    {{ ucfirst($reparacion->estado) }}
                                </span>
                            </td>
                            <td>{{ Str::limit($reparacion->diagnostico, 50) ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($reparacion->fecha_asignacion)->format('d/m/Y') }}</td>
                            <td>{{ $reparacion->fecha_entrega ? \Carbon\Carbon::parse($reparacion->fecha_entrega)->format('d/m/Y') : 'Pendiente' }}</td>
                         </tr>
                        @empty
                         <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                No hay reparaciones registradas para este técnico.
                            </td>
                         </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Resumen</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Total Reparaciones:</strong> {{ $reparaciones->count() }}</p>
                        <p><strong>En Evaluación:</strong> {{ $reparaciones->where('estado', 'evaluacion')->count() }}</p>
                        <p><strong>En Reparación:</strong> {{ $reparaciones->where('estado', 'reparacion')->count() }}</p>
                        <p><strong>Entregadas:</strong> {{ $reparaciones->where('estado', 'entregado')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Información del Técnico</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> {{ $tecnico->nombre }} {{ $tecnico->apellido }}</p>
                        <p><strong>Especialidad:</strong> {{ $tecnico->especialidad ?? 'Sin especialidad' }}</p>
                        <p><strong>Teléfono:</strong> {{ $tecnico->telefono ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $tecnico->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
    @media print {
        .btn, nav, .sidebar, .navbar, .breadcrumb, .text-end {
            display: none !important;
        }
        .container-fluid, .col-md-6, .col-md-8, .col-md-4 {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
        }
    }
</style>
@endpush