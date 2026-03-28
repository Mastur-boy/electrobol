@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-file-text me-2"></i>Reportes Generales
            </h2>
            <p class="text-muted">Estadísticas y análisis del sistema</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Reparaciones por Mes</h5>
                </div>
                <div class="card-body">
                    <canvas id="reparacionesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Ingresos por Mes</h5>
                </div>
                <div class="card-body">
                    <canvas id="ingresosChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Top Artículos más Reparados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Artículo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th class="text-center">Veces Reparado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topArticulos as $articulo)
                        <tr>
                            <td>{{ $articulo->tipo }}</td>
                            <td>{{ $articulo->marca }}</td>
                            <td>{{ $articulo->modelo }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $articulo->reparaciones_count }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    
    const reparacionesData = @json($reparacionesPorMes);
    const ingresosData = @json($ingresosPorMes);
    
    new Chart(document.getElementById('reparacionesChart'), {
        type: 'bar',
        data: {
            labels: reparacionesData.map(item => `${mesesNombres[item.mes - 1]} ${item.año}`),
            datasets: [{
                label: 'Reparaciones',
                data: reparacionesData.map(item => item.total),
                backgroundColor: '#77dd77',
                borderColor: '#66cc66',
                borderWidth: 1
            }]
        }
    });
    
    new Chart(document.getElementById('ingresosChart'), {
        type: 'line',
        data: {
            labels: ingresosData.map(item => `${mesesNombres[item.mes - 1]} ${item.año}`),
            datasets: [{
                label: 'Ingresos (Bs)',
                data: ingresosData.map(item => item.total),
                borderColor: '#77dd77',
                backgroundColor: 'rgba(119, 221, 119, 0.1)',
                fill: true,
                tension: 0.4
            }]
        }
    });
</script>
@endsection