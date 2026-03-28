@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-clock-history me-2"></i>Auditoría del Sistema
            </h2>
            <p class="text-muted">Registro de todas las actividades realizadas</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">                       
                            <th>Fecha/Hora</th>
                            <th>Técnico</th>
                            <th>Acción</th>
                            <th>Reparación</th>
                            <th>Cliente</th>
                            <th>Artículo</th>
                            <th>Detalle</th>
                         </thead>
                    <tbody>
                        @forelse($historial as $registro)
                            <tr class="{{ !$registro->reparacion || !$registro->reparacion->articulo ? 'table-warning' : '' }}">
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($registro->tecnico)
                                        {{ $registro->tecnico->nombre }} {{ $registro->tecnico->apellido }}
                                    @else
                                        <span class="text-muted">Técnico no disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($registro->accion) }}</span>
                                </td>
                                <td>
                                    @if($registro->reparacion)
                                        <a href="{{ route('reparaciones.show', $registro->reparacion) }}">
                                            #{{ $registro->reparacion->id }}
                                        </a>
                                    @else
                                        <span class="text-muted">Reparación no disponible</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- 👈 CORREGIDO: Verificación de null con nullsafe operator --}}
                                    {{ $registro->reparacion?->articulo?->cliente?->nombre ?? 'N/A' }} 
                                    {{ $registro->reparacion?->articulo?->cliente?->apellido ?? '' }}
                                    @if($registro->reparacion?->articulo?->cliente?->telefono)
                                        <br>
                                        <small class="text-muted">{{ $registro->reparacion->articulo->cliente->telefono }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{-- 👈 CORREGIDO: Verificación de null --}}
                                    @if($registro->reparacion && $registro->reparacion->articulo)
                                        <strong>{{ $registro->reparacion->articulo->tipo }}</strong>
                                        <br>
                                        <small>{{ $registro->reparacion->articulo->marca }} {{ $registro->reparacion->articulo->modelo }}</small>
                                    @else
                                        <span class="text-muted">Artículo no disponible</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($registro->detalle, 100) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No hay registros de auditoría
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $historial->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-warning {
        background-color: #fff3cd !important;
    }
    .text-nowrap {
        white-space: nowrap;
    }
</style>
@endpush