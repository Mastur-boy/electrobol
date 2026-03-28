@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-pencil-square me-2"></i>Editar Artículo
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('articulos.index') }}">Artículos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('articulos.update', $articulo) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Cliente</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $articulo->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }} {{ $cliente->apellido }} - {{ $cliente->telefono ?? 'Sin teléfono' }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="">Seleccione</option>
                            <option value="Televisor" {{ old('tipo', $articulo->tipo) == 'Televisor' ? 'selected' : '' }}>Televisor</option>
                            <option value="Lavadora" {{ old('tipo', $articulo->tipo) == 'Lavadora' ? 'selected' : '' }}>Lavadora</option>
                            <option value="Refrigerador" {{ old('tipo', $articulo->tipo) == 'Refrigerador' ? 'selected' : '' }}>Refrigerador</option>
                            <option value="Microondas" {{ old('tipo', $articulo->tipo) == 'Microondas' ? 'selected' : '' }}>Microondas</option>
                            <option value="Licuadora" {{ old('tipo', $articulo->tipo) == 'Licuadora' ? 'selected' : '' }}>Licuadora</option>
                            <option value="Otro" {{ old('tipo', $articulo->tipo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Marca *</label>
                        <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror" value="{{ old('marca', $articulo->marca) }}" required>
                        @error('marca')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Modelo *</label>
                        <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $articulo->modelo) }}" required>
                        @error('modelo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Número de Serie</label>
                        <input type="text" name="serial" class="form-control @error('serial') is-invalid @enderror" value="{{ old('serial', $articulo->serial) }}">
                        @error('serial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="tiene_garantia" class="form-check-input" id="tiene_garantia" value="1" {{ old('tiene_garantia', $articulo->tiene_garantia) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tiene_garantia">El equipo tiene garantía vigente</label>
                        </div>
                    </div>
                </div>

                <div class="row" id="fecha_garantia_div" style="{{ old('tiene_garantia', $articulo->tiene_garantia) ? '' : 'display: none;' }}">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Vencimiento de Garantía</label>
                        <input type="date" name="fecha_garantia" class="form-control @error('fecha_garantia') is-invalid @enderror" value="{{ old('fecha_garantia', $articulo->fecha_garantia ? \Carbon\Carbon::parse($articulo->fecha_garantia)->format('Y-m-d') : '') }}">
                        @error('fecha_garantia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción del Problema *</label>
                    <textarea name="problema_descripcion" class="form-control @error('problema_descripcion') is-invalid @enderror" rows="3" required>{{ old('problema_descripcion', $articulo->problema_descripcion) }}</textarea>
                    @error('problema_descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($articulo->reparacion)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Nota:</strong> Este artículo ya tiene una reparación asignada. No se puede modificar la información básica una vez iniciada la reparación.
                    </div>
                @endif

                <div class="text-end mt-4">
                    <a href="{{ route('articulos.show', $articulo) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-custom" {{ $articulo->reparacion ? 'disabled' : '' }}>
                        <i class="bi bi-save me-2"></i>Actualizar Artículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('tiene_garantia').addEventListener('change', function() {
    const div = document.getElementById('fecha_garantia_div');
    if (this.checked) {
        div.style.display = 'block';
    } else {
        div.style.display = 'none';
    }
});
</script>
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
    .btn-custom:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }
</style>
@endpush