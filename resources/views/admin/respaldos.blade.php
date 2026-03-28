@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-database me-2"></i>Respaldos de Base de Datos
            </h2>
            <p class="text-muted">Gestión de copias de seguridad del sistema</p>
        </div>
    </div>

    <div class="row">
        {{-- Generar Respaldo --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-cloud-upload me-2"></i>Generar Nuevo Respaldo
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Crea una copia de seguridad completa de la base de datos del sistema.</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        El respaldo incluirá:
                        <ul class="mb-0 mt-2">
                            <li>Información de clientes</li>
                            <li>Artículos registrados</li>
                            <li>Reparaciones y su historial</li>
                            <li>Técnicos y repuestos</li>
                            <li>Usuarios y configuraciones</li>
                        </ul>
                    </div>
                    <form id="backupForm" method="POST" action="{{ route('admin.backup.generate') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tipo de Respaldo</label>
                            <select name="tipo" class="form-select">
                                <option value="completo">Respaldo Completo</option>
                                <option value="estructura">Solo Estructura</option>
                                <option value="datos">Solo Datos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Incluir en el respaldo</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="incluir_archivos" name="incluir_archivos" value="1">
                                <label class="form-check-label" for="incluir_archivos">
                                    Incluir archivos del sistema (logs, imágenes)
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-custom" onclick="generarRespaldo()">
                            <i class="bi bi-cloud-upload me-2"></i>Generar Respaldo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Programar Respaldos Automáticos --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock me-2"></i>Programar Respaldos Automáticos
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configura respaldos automáticos periódicos.</p>
                    <form id="scheduleForm" method="POST" action="{{ route('admin.backup.schedule') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Frecuencia</label>
                            <select name="frecuencia" class="form-select" id="frecuencia">
                                <option value="diario">Diario</option>
                                <option value="semanal">Semanal</option>
                                <option value="mensual">Mensual</option>
                            </select>
                        </div>
                        <div class="mb-3" id="diaSemanaDiv" style="display: none;">
                            <label class="form-label">Día de la semana</label>
                            <select name="dia_semana" class="form-select">
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                                <option value="0">Domingo</option>
                            </select>
                        </div>
                        <div class="mb-3" id="diaMesDiv" style="display: none;">
                            <label class="form-label">Día del mes</label>
                            <select name="dia_mes" class="form-select">
                                @for($i = 1; $i <= 28; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                                <option value="ultimo">Último día del mes</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora</label>
                            <input type="time" name="hora" class="form-control" value="00:00">
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-calendar-check me-2"></i>Programar Respaldo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Lista de Respaldos Existentes --}}
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-archive me-2"></i>Respaldos Disponibles
                    </h5>
                    <button class="btn btn-sm btn-outline-danger" onclick="limpiarAntiguos()">
                        <i class="bi bi-trash me-1"></i>Limpiar Respaldos Antiguos
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre del Archivo</th>
                                    <th>Tamaño</th>
                                    <th>Fecha de Creación</th>
                                    <th>Tipo</th>
                                    <th>Acciones</th>
                                </thead>
                            <tbody id="backupList">
                                @php
                                    $backups = [];
                                    $backupPath = storage_path('app/backups');
                                    if (is_dir($backupPath)) {
                                        $files = scandir($backupPath);
                                        foreach ($files as $file) {
                                            if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                                                $backups[] = [
                                                    'name' => $file,
                                                    'size' => filesize($backupPath . '/' . $file),
                                                    'date' => date('d/m/Y H:i:s', filemtime($backupPath . '/' . $file))
                                                ];
                                            }
                                        }
                                        rsort($backups);
                                    }
                                @endphp
                                
                                @forelse($backups as $backup)
                                <tr>
                                    <td>
                                        <i class="bi bi-filetype-sql me-2 text-success"></i>
                                        <strong>{{ $backup['name'] }}</strong>
                                    </td>
                                    <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td>
                                        @if(str_contains($backup['name'], 'completo'))
                                            <span class="badge bg-primary">Completo</span>
                                        @elseif(str_contains($backup['name'], 'estructura'))
                                            <span class="badge bg-info">Estructura</span>
                                        @else
                                            <span class="badge bg-secondary">Datos</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.backup.download', ['file' => $backup['name']]) }}" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-download"></i> Descargar
                                        </a>
                                        <button class="btn btn-sm btn-outline-primary" onclick="restaurarRespaldo('{{ $backup['name'] }}')">
                                            <i class="bi bi-arrow-repeat"></i> Restaurar
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="eliminarRespaldo('{{ $backup['name'] }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        <h5>No hay respaldos disponibles</h5>
                                        <p class="mb-0">Genera un nuevo respaldo para comenzar.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Configuración de Almacenamiento --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-hdd-stack me-2"></i>Configuración de Almacenamiento
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.backup.config') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ubicación de respaldos</label>
                            <select name="ubicacion" class="form-select">
                                <option value="local">Almacenamiento Local</option>
                                <option value="cloud">Almacenamiento en la Nube</option>
                                <option value="external">Disco Externo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Número máximo de respaldos a mantener</label>
                            <input type="number" name="max_backups" class="form-control" value="10" min="1" max="50">
                            <small class="text-muted">Los respaldos más antiguos se eliminarán automáticamente</small>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="notificar_email" name="notificar_email" value="1">
                            <label class="form-check-label" for="notificar_email">
                                Enviar notificación por email al completar respaldo
                            </label>
                        </div>
                        <button type="submit" class="btn btn-secondary">
                            <i class="bi bi-save me-2"></i>Guardar Configuración
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Estadísticas de Respaldos --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up me-2"></i>Estadísticas de Respaldos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h3 class="text-primary">{{ count($backups) }}</h3>
                            <small class="text-muted">Total Respaldos</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="text-success">{{ number_format(array_sum(array_column($backups, 'size')) / 1024 / 1024, 2) }} MB</h3>
                            <small class="text-muted">Espacio Total</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-warning">
                                @php
                                    $ultimoBackup = !empty($backups) ? $backups[0]['date'] : 'N/A';
                                @endphp
                                {{ $ultimoBackup }}
                            </h3>
                            <small class="text-muted">Último Respaldo</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-info">
                                @php
                                    $espacioLibre = disk_free_space(storage_path()) / 1024 / 1024 / 1024;
                                @endphp
                                {{ number_format($espacioLibre, 2) }} GB
                            </h3>
                            <small class="text-muted">Espacio Libre</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('frecuencia').addEventListener('change', function() {
    const diaSemanaDiv = document.getElementById('diaSemanaDiv');
    const diaMesDiv = document.getElementById('diaMesDiv');
    
    if (this.value === 'semanal') {
        diaSemanaDiv.style.display = 'block';
        diaMesDiv.style.display = 'none';
    } else if (this.value === 'mensual') {
        diaSemanaDiv.style.display = 'none';
        diaMesDiv.style.display = 'block';
    } else {
        diaSemanaDiv.style.display = 'none';
        diaMesDiv.style.display = 'none';
    }
});

function generarRespaldo() {
    if (confirm('¿Estás seguro de generar un nuevo respaldo? Este proceso puede tomar unos minutos.')) {
        document.getElementById('backupForm').submit();
    }
}

function restaurarRespaldo(filename) {
    if (confirm('⚠️ ADVERTENCIA: Restaurar un respaldo sobrescribirá todos los datos actuales. ¿Estás seguro de continuar?')) {
        window.location.href = '/admin/backup/restore/' + filename;
    }
}

function eliminarRespaldo(filename) {
    if (confirm('¿Estás seguro de eliminar este respaldo?')) {
        window.location.href = '/admin/backup/delete/' + filename;
    }
}

function limpiarAntiguos() {
    if (confirm('¿Eliminar todos los respaldos antiguos (más de 30 días)?')) {
        window.location.href = '/admin/backup/clean';
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
        transition: all 0.3s ease;
    }
    .btn-custom:hover {
        background-color: #66cc66;
        color: white;
        transform: translateY(-1px);
    }
    .card {
        border-radius: 1rem;
        overflow: hidden;
    }
    .card-header {
        border-bottom: 2px solid #77dd77;
    }
</style>
@endpush