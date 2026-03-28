@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-person-circle me-2"></i>Detalle del Cliente
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
                    <li class="breadcrumb-item active">{{ $cliente->nombre }} {{ $cliente->apellido }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle fs-1 text-secondary"></i>
                        <h4 class="mt-2">{{ $cliente->nombre }} {{ $cliente->apellido }}</h4>
                    </div>
                    <hr>
                    <p><strong><i class="bi bi-card-text me-2"></i>NIT/CI:</strong> {{ $cliente->ci ?? 'No registrado' }}</p> 
                    <p><strong><i class="bi bi-telephone me-2"></i>Teléfono:</strong> {{ $cliente->telefono ?? 'No registrado' }}</p>
                    <p><strong><i class="bi bi-envelope me-2"></i>Email:</strong> {{ $cliente->email ?? 'No registrado' }}</p>
                    <p><strong><i class="bi bi-geo-alt me-2"></i>Dirección:</strong> {{ $cliente->direccion ?? 'No registrada' }}</p>
                    <p><strong><i class="bi bi-calendar me-2"></i>Fecha Registro:</strong> {{ $cliente->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-device-ssd me-2"></i>Artículos del Cliente
                        <span class="badge bg-info ms-2">{{ $cliente->articulos->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Artículo</th>
                                    <th>Marca/Modelo</th>
                                    <th>Estado</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </thead>
                            <tbody>
                                @forelse($cliente->articulos as $articulo)
                                  <tr>
                                    <td>{{ $articulo->id }}</td>
                                    <td><strong>{{ $articulo->tipo }}</strong></td>
                                    <td>{{ $articulo->marca }} {{ $articulo->modelo }}</td>
                                    <td>
                                        @if($articulo->reparacion)
                                            <span class="status-badge status-{{ $articulo->reparacion->estado }}">
                                                {{ ucfirst($articulo->reparacion->estado) }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning">Pendiente</span>
                                        @endif
                                    </td>
                                    <td>{{ $articulo->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('articulos.show', $articulo) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                  </tr>
                                @empty
                                   <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Este cliente no tiene artículos registrados.
                                    </td>
                                   </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Editar Cliente
                </a>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
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
</style>
@endpush