@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-wrench me-2"></i>Gestión de Reparaciones
            </h2>
            <p class="text-muted">Administra todas las reparaciones del taller</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('reparaciones.create') }}" class="btn btn-custom">
                <i class="bi bi-plus-lg me-2"></i>Nueva Reparación
            </a>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reparaciones.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="evaluacion" {{ request('estado') == 'evaluacion' ? 'selected' : '' }}>En Evaluación</option>
                        <option value="reparacion" {{ request('estado') == 'reparacion' ? 'selected' : '' }}>En Reparación</option>
                        <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Técnico</label>
                    <select name="tecnico_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($tecnicos ?? [] as $tecnico)
                            <option value="{{ $tecnico->id }}" {{ request('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                                {{ $tecnico->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Buscar por Cliente o Artículo</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, teléfono, artículo..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-custom w-100">
                        <i class="bi bi-search me-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de reparaciones --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Artículo</th>
                            <th>Técnico</th>
                            <th>Estado</th>
                            <th>Fecha Asignación</th>
                            <th>Acciones</th>
                        </thead>
                    <tbody>
                        @forelse($reparaciones as $reparacion)                       
                            <td class="fw-bold">#{{ $reparacion->id }} </td>
                            <td>
                                {{ $reparacion->articulo->cliente->nombre_completo }}<br>
                                <small class="text-muted">{{ $reparacion->articulo->cliente->telefono ?? 'Sin teléfono' }}</small>
                            </td>
                            <td>
                                <strong>{{ $reparacion->articulo->tipo }}</strong><br>
                                <small>{{ $reparacion->articulo->marca }} {{ $reparacion->articulo->modelo }}</small>
                            </td>
                            <td>{{ $reparacion->tecnico->nombre_completo ?? 'No asignado' }}</td>
                            <td>
                                <span class="status-badge status-{{ $reparacion->estado }}">
                                    @switch($reparacion->estado)
                                        @case('evaluacion')
                                            <i class="bi bi-clipboard-check me-1"></i>Evaluación
                                            @break
                                        @case('reparacion')
                                            <i class="bi bi-tools me-1"></i>Reparación
                                            @break
                                        @case('entregado')
                                            <i class="bi bi-check-circle me-1"></i>Entregado
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reparacion->fecha_asignacion)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                @if($reparacion->estado == 'entregado')
                                    <a href="{{ route('reparaciones.factura', $reparacion) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                        <i class="bi bi-receipt"></i> Factura
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <h5>No hay reparaciones registradas</h5>
                                <p class="mb-0">Registra un artículo y asígnalo a un técnico para crear una reparación.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($reparaciones, 'links'))
        <div class="card-footer bg-white">
            {{ $reparaciones->links() }}
        </div>
        @endif
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
</style>
@endpush