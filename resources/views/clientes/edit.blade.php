@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-pencil-square me-2"></i>Editar Cliente
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.show', $cliente) }}">{{ $cliente->nombre }} {{ $cliente->apellido }}</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-pencil me-2"></i>Modificar Información del Cliente
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $cliente->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido', $cliente->apellido) }}" required>
                        @error('apellido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 👈 CAMPO CI AGREGADO --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIT/CI *</label>
                        <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci', $cliente->ci) }}" required>
                        @error('ci')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Número de identificación único</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $cliente->telefono) }}" placeholder="Ej: 71234567">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $cliente->email) }}" placeholder="cliente@ejemplo.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <textarea name="direccion" class="form-control @error('direccion') is-invalid @enderror" rows="2" placeholder="Calle, número, zona, ciudad">{{ old('direccion', $cliente->direccion) }}</textarea>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Información:</strong> Los campos marcados con * son obligatorios. El NIT/CI debe ser único.
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-custom">
                        <i class="bi bi-save me-2"></i>Actualizar Cliente
                    </button>
                </div>
            </form>
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
        transition: all 0.3s ease;
    }
    .btn-custom:hover {
        background-color: #66cc66;
        color: white;
        transform: translateY(-1px);
    }
    .btn-secondary {
        padding: 10px 24px;
    }
    .form-label {
        font-weight: 600;
        color: #333;
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