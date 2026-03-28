@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-box-seam me-2"></i>Detalle del Repuesto
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('repuestos.index') }}">Repuestos</a></li>
                    <li class="breadcrumb-item active">{{ $repuesto->nombre }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Información del Repuesto
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong><i class="bi bi-upc-scan me-2"></i>Código:</strong> {{ $repuesto->codigo }}</p>
                    <p><strong><i class="bi bi-tag me-2"></i>Nombre:</strong> {{ $repuesto->nombre }}</p>
                    <p><strong><i class="bi bi-file-text me-2"></i>Descripción:</strong> {{ $repuesto->descripcion ?? 'Sin descripción' }}</p>
                    <p><strong><i class="bi bi-building me-2"></i>Proveedor:</strong> {{ $repuesto->proveedor ?? 'No especificado' }}</p>
                    <hr>
                    <p><strong><i class="bi bi-calculator me-2"></i>Stock Actual:</strong> 
                        <span class="fw-bold {{ $repuesto->stock_actual <= $repuesto->stock_minimo ? 'text-danger' : 'text-success' }}">
                            {{ $repuesto->stock_actual }}
                        </span>
                    </p>
                    <p><strong><i class="bi bi-exclamation-triangle me-2"></i>Stock Mínimo:</strong> {{ $repuesto->stock_minimo }}</p>
                    <p><strong><i class="bi bi-cash-stack me-2"></i>Precio Unitario:</strong> Bs {{ number_format($repuesto->precio_unitario, 2) }}</p>
                    <hr>
                    <p><strong><i class="bi bi-calendar me-2"></i>Fecha Registro:</strong> {{ $repuesto->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong><i class="bi bi-pencil me-2"></i>Última Actualización:</strong> {{ $repuesto->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-gear me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#agregarStockModal">
                        <i class="bi bi-plus-circle me-2"></i>Agregar Stock
                    </button>
                    <a href="{{ route('repuestos.edit', $repuesto) }}" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Editar Repuesto
                    </a>
                    <button type="button" class="btn btn-danger w-100" onclick="confirmarEliminacion({{ $repuesto->id }})">
                        <i class="bi bi-trash me-2"></i>Eliminar Repuesto
                    </button>
                </div>
            </div>

            {{-- Modal Agregar Stock --}}
            <div class="modal fade" id="agregarStockModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar Stock - {{ $repuesto->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('repuestos.recibir', $repuesto) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Stock Actual</label>
                                    <input type="number" class="form-control" value="{{ $repuesto->stock_actual }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Cantidad a Agregar</label>
                                    <input type="number" name="cantidad" class="form-control" required min="1">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Agregar Stock</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-wrench me-2"></i>Reparaciones donde se ha utilizado
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Reparación ID</th>
                                    <th>Cliente</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Fecha Uso</th>
                                    <th>Acciones</th>
                                </thead>
                            <tbody>
                                @forelse($repuesto->reparaciones as $reparacion)
                                  <tr>
                                    <td>#{{ $reparacion->id }}</td>
                                    <td>{{ $reparacion->articulo->cliente->nombre }} {{ $reparacion->articulo->cliente->apellido }}</td>
                                    <td>{{ $reparacion->articulo->tipo }} {{ $reparacion->articulo->marca }}</td>
                                    <td>{{ $reparacion->pivot->cantidad }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reparacion->pivot->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                  </tr>
                                @empty
                                  <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Este repuesto no ha sido utilizado en ninguna reparación.
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
</div>

<script>
function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de eliminar este repuesto? Esta acción no se puede deshacer.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/repuestos/' + id;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection