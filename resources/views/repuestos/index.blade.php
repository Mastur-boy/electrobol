@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-box-seam me-2"></i>Gestión de Repuestos
            </h2>
            <p class="text-muted">Administra el inventario de repuestos del taller</p>
        </div>
        <div class="col text-end">
            <a href="{{ route('repuestos.create') }}" class="btn btn-custom">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Repuesto
            </a>
        </div>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Total Repuestos</h6>
                            <h2 class="mb-0">{{ $repuestos->count() }}</h2>
                        </div>
                        <i class="bi bi-box-seam fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-dark-50">Stock Bajo</h6>
                            <h2 class="mb-0">
                                {{ $repuestos->filter(fn($r) => $r->stock_actual <= $r->stock_minimo)->count() }}
                            </h2>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Stock Total</h6>
                            <h2 class="mb-0">{{ $repuestos->sum('stock_actual') }}</h2>
                        </div>
                        <i class="bi bi-calculator fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Valor Inventario</h6>
                            <h2 class="mb-0">
                                Bs {{ number_format($repuestos->sum(fn($r) => $r->stock_actual * $r->precio_unitario), 0) }}
                            </h2>
                        </div>
                        <i class="bi bi-cash-stack fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Stock</th>
                            <th>Stock Mínimo</th>
                            <th>Precio Unitario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($repuestos as $repuesto)
                        <tr class="{{ $repuesto->stock_actual <= $repuesto->stock_minimo ? 'table-warning' : '' }}">
                            <td><strong>{{ $repuesto->codigo }}</strong></td>
                            <td>{{ $repuesto->nombre }}</td>
                            <td>{{ Str::limit($repuesto->descripcion, 30) ?? 'N/A' }}</td>

                            <td>
                                <span class="fw-bold {{ $repuesto->stock_actual <= $repuesto->stock_minimo ? 'text-danger' : 'text-success' }}">
                                    {{ $repuesto->stock_actual }}
                                </span>
                            </td>

                            <td>{{ $repuesto->stock_minimo }}</td>
                            <td>Bs {{ number_format($repuesto->precio_unitario, 2) }}</td>

                            <td>
                                @if($repuesto->stock_actual <= 0)
                                    <span class="badge bg-danger">Agotado</span>
                                @elseif($repuesto->stock_actual <= $repuesto->stock_minimo)
                                    <span class="badge bg-warning">Stock Bajo</span>
                                @else
                                    <span class="badge bg-success">Disponible</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('repuestos.show', $repuesto) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="{{ route('repuestos.edit', $repuesto) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#stockModal{{ $repuesto->id }}">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>

                                {{-- NUEVO BOTÓN RETIRAR --}}
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#retirarModal{{ $repuesto->id }}">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal AGREGAR stock --}}
                        <div class="modal fade" id="stockModal{{ $repuesto->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajustar Stock - {{ $repuesto->nombre }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form action="{{ route('repuestos.recibir', $repuesto) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Stock Actual</label>
                                                <input type="number" class="form-control"
                                                       value="{{ $repuesto->stock_actual }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Cantidad a Agregar</label>
                                                <input type="number" name="cantidad"
                                                       class="form-control" required min="1">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                Agregar Stock
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- NUEVO MODAL RETIRAR --}}
                        <div class="modal fade" id="retirarModal{{ $repuesto->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Retirar Stock - {{ $repuesto->nombre }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('repuestos.retirar', $repuesto) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">

                                            <div class="mb-3">
                                                <label class="form-label">Stock Actual</label>
                                                <input type="number" class="form-control"
                                                       value="{{ $repuesto->stock_actual }}" readonly>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Cantidad a Retirar</label>
                                                <input type="number" name="cantidad"
                                                       class="form-control"
                                                       required min="1"
                                                       max="{{ $repuesto->stock_actual }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Reparación</label>
                                                <select name="reparacion_id" class="form-select" required>
                                                    <option value="">Seleccione una reparación</option>
                                                    @foreach($reparaciones ?? [] as $reparacion)
                                                        <option value="{{ $reparacion->id }}">
                                                            #{{ $reparacion->id }} - {{ $reparacion->articulo->tipo ?? 'Sin artículo' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                Retirar Stock
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <h5>No hay repuestos registrados</h5>
                                <p class="mb-0">Haz clic en "Nuevo Repuesto" para comenzar.</p>
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
    .table-warning {
        background-color: #fff3cd;
    }
</style>
@endpush