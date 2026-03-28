@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="bi bi-person-x fs-1 text-warning d-block mb-3"></i>
                    <h3 class="mb-3">Cuenta no vinculada a un técnico</h3>
                    <p class="text-muted mb-4">
                        Tu cuenta de usuario no está asociada a ningún técnico del taller.
                        Por favor, contacta al administrador para que te vincule con tu perfil de técnico.
                    </p>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="btn btn-primary">
                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection