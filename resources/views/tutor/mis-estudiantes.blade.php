@extends('adminlte::page')

@section('title', 'Mis Estudiantes')

@section('content_header')
    <h1><b>Mis Estudiantes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-user-graduate"></i> Estudiantes Bajo Mi Tutoría</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->persona && Auth::user()->persona->tutor)
                        @php
                            // Obtener los estudiantes asignados a este tutor
                            $estudiantes = Auth::user()->persona->tutor->estudiantes()
                                ->with(['persona', 'grado'])
                                ->get();
                        @endphp
                        
                        @if($estudiantes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="tablaEstudiantes">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Estudiante</th>
                                            <th>DNI</th>
                                            <th>Grado</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($estudiantes as $index => $estudiante)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $estudiante->codigo_estudiante }}</strong>
                                                </td>
                                                <td>
                                                    <i class="fas fa-user-graduate text-primary"></i>
                                                    {{ $estudiante->persona->apellidos }}, 
                                                    {{ $estudiante->persona->nombres }}
                                                </td>
                                                <td>{{ $estudiante->persona->dni }}</td>
                                                <td class="text-center">
                                                    @if($estudiante->grado)
                                                        <span class="badge badge-info">
                                                            {{ $estudiante->grado->nombre_completo }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">Sin grado</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $estudiante->persona->estado == 'Activo' ? 'success' : 'danger' }}">
                                                        {{ $estudiante->persona->estado }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('tutor.notas') }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Ver notas">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                    <a href="{{ route('tutor.asistencias') }}" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Ver asistencias">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Resumen -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="info-box bg-gradient-primary">
                                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total de Estudiantes a mi Cargo</span>
                                            <span class="info-box-number">{{ $estudiantes->count() }}</span>
                                            <span class="progress-description">
                                                Estudiantes bajo mi tutoría
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No tienes estudiantes asignados a tu tutoría en este momento.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tu perfil de tutor no está completo. Por favor contacta al administrador.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#tablaEstudiantes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                },
                responsive: true,
                autoWidth: false,
            });

            @if(session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif
        });
    </script>
@stop