@extends('adminlte::page')

@section('title', 'Dashboard Tutor')

@section('content_header')
    <h1>Dashboard del Tutor</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Perfil de Tutor No Asignado
                </h3>
            </div>
            <div class="card-body text-center py-5">
                <i class="fas fa-user-times fa-5x text-warning mb-4"></i>
                <h3>No tienes un perfil de tutor asignado</h3>
                <p class="text-muted">
                    Aunque tu cuenta tiene el rol de tutor, aún no se ha creado tu perfil de tutor en el sistema.
                </p>
                <hr>
                <p class="mb-4">
                    <strong>Por favor contacta al administrador del sistema para:</strong>
                </p>
                <ul class="list-unstyled text-left" style="max-width: 500px; margin: 0 auto;">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i>
                        Crear tu perfil de tutor en el módulo de tutores
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i>
                        Vincular tu perfil de persona con el perfil de tutor
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success"></i>
                        Asignar estudiantes bajo tu tutela
                    </li>
                </ul>
                <hr class="mt-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Usuario actual:</strong> {{ Auth::user()->name }} ({{ Auth::user()->email }})<br>
                    <strong>Persona vinculada:</strong> {{ Auth::user()->persona->nombres }} {{ Auth::user()->persona->apellidos }}
                </div>
                <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary mt-3">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .fa-5x {
        font-size: 5rem;
    }
</style>
@stop