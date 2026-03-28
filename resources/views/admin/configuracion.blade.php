@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-gear me-2"></i>Configuración del Sistema
            </h2>
            <p class="text-muted">Configuración general del taller</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Información del Taller</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nombre del Taller</label>
                            <input type="text" class="form-control" value="ElectroBol">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" value="76543210">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" value="La Paz - Bolivia">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIT</label>
                            <input type="text" class="form-control" value="123456789">
                        </div>
                        <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Configuración de Facturación</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">IVA (%)</label>
                        <input type="number" class="form-control" value="13">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Costo de Evaluación (Bs)</label>
                        <input type="number" class="form-control" value="1008">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="facturaAuto">
                        <label class="form-check-label" for="facturaAuto">
                            Generar factura automáticamente al entregar
                        </label>
                    </div>
                    <button type="submit" class="btn btn-custom">Guardar Configuración</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Respaldos de Base de Datos</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">Generar respaldo de la base de datos para seguridad de la información.</p>
            <button class="btn btn-warning" onclick="alert('Funcionalidad en desarrollo')">
                <i class="bi bi-database me-2"></i>Generar Respaldo
            </button>
            <button class="btn btn-secondary ms-2" onclick="alert('Funcionalidad en desarrollo')">
                <i class="bi bi-arrow-repeat me-2"></i>Restaurar Respaldo
            </button>
        </div>
    </div>
</div>
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