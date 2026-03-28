@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-plus-circle me-2"></i>Nueva Reparación
            </h2>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('reparaciones.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Artículo</label>
                        <select name="articulo_id" class="form-select" required>
                            <option value="">Seleccione un artículo</option>
                            @foreach($articulos as $articulo)
                                <option value="{{ $articulo->id }}">
                                    {{ $articulo->tipo }} - {{ $articulo->marca }} {{ $articulo->modelo }} ({{ $articulo->cliente->nombre_completo }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Técnico</label>
                        <select name="tecnico_id" class="form-select" required>
                            <option value="">Seleccione un técnico</option>
                            @foreach($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id }}">{{ $tecnico->nombre_completo }} - {{ $tecnico->especialidad }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ route('reparaciones.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-custom">Crear Reparación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection