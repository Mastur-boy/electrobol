@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </h2>
            <p class="text-muted">Bienvenido al sistema de reparaciones ElectroBol</p>
        </div>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Total Reparaciones</h6>
                            <h2 class="mb-0">{{ $totalReparaciones }}</h2>
                        </div>
                        <i class="bi bi-tools fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-dark-50">En Proceso</h6>
                            <h2 class="mb-0">{{ $reparacionesEnProceso }}</h2>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Entregadas</h6>
                            <h2 class="mb-0">{{ $reparacionesEntregadas }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-stats bg-info text-white shadow-sm">
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
    </div>

    <div class="row">
        {{-- Gráfico de reparaciones por estado --}}
        <div class="col-md-6 mb-4">
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

        {{-- Top técnicos --}}
        <div class="col-md-6 mb-4">
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
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalTecnicos = $reparacionesPorTecnico->sum('reparaciones_count');
                                @endphp
                                @foreach($reparacionesPorTecnico as $tecnico)
                                    <tr>
                                        <td>{{ $tecnico->nombre_completo }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $tecnico->reparaciones_count }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($totalTecnicos > 0)
                                                {{ round(($tecnico->reparaciones_count / $totalTecnicos) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
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

    <div class="row">
        {{-- Reparaciones por mes --}}
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up me-2"></i>Reparaciones por Mes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="mesesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Gráfico de meses
    const mesesCtx = document.getElementById('mesesChart').getContext('2d');
    const mesesData = @json($reparacionesPorMes);
    
    const mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    new Chart(mesesCtx, {
        type: 'line',
        data: {
            labels: mesesData.map(item => `${mesesNombres[item.mes - 1]} ${item.año}`),
            datasets: [{
                label: 'Reparaciones',
                data: mesesData.map(item => item.total),
                borderColor: '#77dd77',
                backgroundColor: 'rgba(119, 221, 119, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
    .card-stats {
        border-radius: 1rem;
        transition: transform 0.2s;
    }
    .card-stats:hover {
        transform: translateY(-5px);
    }
    .card-stats .card-title {
        font-size: 0.875rem;
        opacity: 0.8;
    }
    .card-stats h2 {
        font-weight: bold;
    }
</style>
@endpush
@endsection