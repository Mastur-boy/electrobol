@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-device-ssd me-2"></i>Gestión de Artículos
            </h2>
        </div>
        <div class="col text-end">
            <a href="{{ route('articulos.create') }}" class="btn btn-custom">
                <i class="bi bi-plus-lg me-2"></i>Registrar Artículo
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Artículo</th>
                            <th>Garantía</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articulos as $articulo)
                        <tr>
                            <td>{{ $articulo->id }}</td>
                            <td>{{ $articulo->cliente->nombre_completo }}</td>
                            <td>
                                <strong>{{ $articulo->tipo }}</strong><br>
                                <small>{{ $articulo->marca }} {{ $articulo->modelo }}</small>
                            </td>
                            <td>
                                @if($articulo->tiene_garantia_vigente)
                                    <span class="badge bg-success">Vigente</span>
                                @else
                                    <span class="badge bg-secondary">Sin garantía</span>
                                @endif
                            </td>
                            <td>
                                @if($articulo->reparacion)
                                    <span class="status-badge status-{{ $articulo->reparacion->estado }}">
                                        {{ ucfirst($articulo->reparacion->estado) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning">Pendiente asignación</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('articulos.show', $articulo) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(!$articulo->reparacion)
                                    <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay artículos registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection