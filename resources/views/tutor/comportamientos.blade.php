@extends('adminlte::page')

@section('title', 'Comportamientos de Mis Estudiantes')

@section('content_header')
    <h1><b>Comportamientos de Mis Estudiantes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-user-check"></i> Registro de Comportamientos</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->persona && Auth::user()->persona->tutor)
                        @php
                            // Obtener los estudiantes del tutor
                            $estudiantes = Auth::user()->persona->tutor->estudiantes;
                            $estudiantesIds = $estudiantes->pluck('id');
                            
                            // ✅ CORREGIDO: Sin ->curso
                            $comportamientos = \App\Models\Comportamiento::whereIn('estudiante_id', $estudiantesIds)
                                ->where('notificado_tutor', true)
                                ->with(['estudiante.persona', 'estudiante.grado', 'docente.persona'])
                                ->orderBy('fecha', 'desc')
                                ->get();
                        @endphp
                        
                        @if($comportamientos->count() > 0)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Puedes ver los comportamientos que los docentes han notificado de tus estudiantes.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="tablaComportamientos">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Estudiante</th>
                                            <th>Grado</th>
                                            <th class="text-center">Tipo</th>
                                            <th>Descripción</th>
                                            <th>Docente</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($comportamientos as $comportamiento)
                                            <tr class="{{ $comportamiento->tipo == 'Negativo' ? 'table-danger' : ($comportamiento->tipo == 'Positivo' ? 'table-success' : '') }}">
                                                <td>
                                                    <strong>{{ $comportamiento->fecha_formateada }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $comportamiento->dia_semana }}</small>
                                                </td>
                                                <td>
                                                    <i class="fas fa-user-graduate"></i>
                                                    <strong>{{ $comportamiento->estudiante->persona->apellidos }}, 
                                                    {{ $comportamiento->estudiante->persona->nombres }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $comportamiento->estudiante->grado->nombre_completo ?? 'Sin grado' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $comportamiento->tipo_badge }} badge-lg">
                                                        @if($comportamiento->tipo == 'Positivo')
                                                            <i class="fas fa-smile"></i>
                                                        @elseif($comportamiento->tipo == 'Negativo')
                                                            <i class="fas fa-frown"></i>
                                                        @else
                                                            <i class="fas fa-meh"></i>
                                                        @endif
                                                        {{ $comportamiento->tipo }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ \Str::limit($comportamiento->descripcion, 50) }}</small>
                                                    @if($comportamiento->sancion)
                                                        <br>
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-exclamation-triangle"></i> Con sanción
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($comportamiento->docente)
                                                        <i class="fas fa-chalkboard-teacher"></i>
                                                        {{ $comportamiento->docente->persona->apellidos }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info" 
                                                            data-toggle="modal" 
                                                            data-target="#verComportamientoModal{{ $comportamiento->id }}"
                                                            title="Ver detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal Ver Detalle -->
                                            <div class="modal fade" id="verComportamientoModal{{ $comportamiento->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-{{ $comportamiento->tipo_badge }}">
                                                            <h5 class="modal-title">
                                                                <i class="fas {{ $comportamiento->tipo_icon }}"></i> 
                                                                Detalle del Comportamiento {{ $comportamiento->tipo }}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Información del Estudiante -->
                                                            <div class="card card-outline card-info">
                                                                <div class="card-header">
                                                                    <h5 class="card-title"><i class="fas fa-user"></i> Información del Estudiante</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p><strong>Estudiante:</strong><br>
                                                                            {{ $comportamiento->estudiante->persona->apellidos }}, 
                                                                            {{ $comportamiento->estudiante->persona->nombres }}</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <p><strong>DNI:</strong><br>
                                                                            {{ $comportamiento->estudiante->persona->dni }}</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <p><strong>Código:</strong><br>
                                                                            {{ $comportamiento->estudiante->codigo_estudiante }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p><strong>Grado:</strong><br>
                                                                            <span class="badge badge-info badge-lg">
                                                                                {{ $comportamiento->estudiante->grado->nombre_completo ?? 'Sin grado' }}
                                                                            </span>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <p><strong>Docente que reportó:</strong><br>
                                                                            @if($comportamiento->docente)
                                                                                {{ $comportamiento->docente->persona->apellidos }}, 
                                                                                {{ $comportamiento->docente->persona->nombres }}
                                                                            @else
                                                                                <span class="text-muted">No especificado</span>
                                                                            @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Detalles del Comportamiento -->
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><strong>Fecha del Incidente:</strong><br>
                                                                    {{ $comportamiento->fecha_formateada }} 
                                                                    ({{ $comportamiento->dia_semana }})</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><strong>Tipo de Comportamiento:</strong><br>
                                                                    <span class="badge badge-{{ $comportamiento->tipo_badge }} badge-lg">
                                                                        <i class="fas {{ $comportamiento->tipo_icon }}"></i>
                                                                        {{ $comportamiento->tipo }}
                                                                    </span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p><strong>Descripción del Comportamiento:</strong></p>
                                                                    <div class="alert alert-light" style="white-space: pre-line;">
                                                                        {{ $comportamiento->descripcion }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            @if($comportamiento->sancion)
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="alert alert-warning">
                                                                        <h5><i class="fas fa-exclamation-triangle"></i> <strong>Sanción Aplicada:</strong></h5>
                                                                        <p>{{ $comportamiento->sancion }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif

                                                            <!-- Información de Notificación -->
                                                            <div class="card card-outline card-secondary">
                                                                <div class="card-header">
                                                                    <h5 class="card-title"><i class="fas fa-bell"></i> Información de Notificación</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p><strong>Estado de Notificación:</strong><br>
                                                                            <span class="badge badge-success">
                                                                                <i class="fas fa-check"></i> Notificado al Tutor
                                                                            </span>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <p><strong>Fecha de Notificación:</strong><br>
                                                                            {{ $comportamiento->fecha_notificacion_formateada }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                <i class="fas fa-times"></i> Cerrar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Estadísticas -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5><b>Resumen de Comportamientos</b></h5>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3>{{ $comportamientos->count() }}</h3>
                                            <p>Total Registros</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-list"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $comportamientos->where('tipo', 'Positivo')->count() }}</h3>
                                            <p>Comportamientos Positivos</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-smile"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $comportamientos->where('tipo', 'Negativo')->count() }}</h3>
                                            <p>Comportamientos Negativos</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-frown"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $comportamientos->whereNotNull('sancion')->count() }}</h3>
                                            <p>Con Sanción</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay comportamientos notificados para tus estudiantes.
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
    <style>
        .badge-lg {
            font-size: 1em;
            padding: 0.5em 0.8em;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#tablaComportamientos').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                },
                responsive: true,
                autoWidth: false,
                order: [[0, 'desc']]
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