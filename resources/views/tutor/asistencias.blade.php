@extends('adminlte::page')

@section('title', 'Asistencias de Mis Estudiantes')

@section('content_header')
    <h1><b>Asistencias de Mis Estudiantes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Control de Asistencias</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->persona && Auth::user()->persona->tutor)
                        @php
                            // Obtener los estudiantes del tutor
                            $estudiantes = Auth::user()->persona->tutor->estudiantes;
                            $estudiantesIds = $estudiantes->pluck('id');
                            
                            // Obtener las asistencias de esos estudiantes
                            $asistencias = \App\Models\Asistencia::whereIn('estudiante_id', $estudiantesIds)
                                ->with(['estudiante.persona', 'curso', 'docente.persona'])
                                ->orderBy('fecha', 'desc')
                                ->get();
                        @endphp
                        
                        @if($asistencias->count() > 0)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Puedes ver el historial de asistencias de tus estudiantes.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="tablaAsistencias">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Estudiante</th>
                                            <th>Curso</th>
                                            <th class="text-center">Estado</th>
                                            <th>Docente</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asistencias as $asistencia)
                                            <tr>
                                                <td>
                                                    <strong>{{ $asistencia->fecha_formateada }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $asistencia->dia_semana }}</small>
                                                </td>
                                                <td>
                                                    <i class="fas fa-user-graduate"></i>
                                                    <strong>{{ $asistencia->estudiante->persona->apellidos }}, 
                                                    {{ $asistencia->estudiante->persona->nombres }}</strong>
                                                </td>
                                                <td>{{ $asistencia->curso->nombre }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $asistencia->estado_badge }}">
                                                        @if($asistencia->estado == 'Presente')
                                                            <i class="fas fa-check"></i>
                                                        @elseif($asistencia->estado == 'Ausente')
                                                            <i class="fas fa-times"></i>
                                                        @elseif($asistencia->estado == 'Tardanza')
                                                            <i class="fas fa-clock"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                        {{ $asistencia->estado }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($asistencia->docente)
                                                        {{ $asistencia->docente->persona->apellidos }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $asistencia->observaciones ?? '-' }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Estadísticas por Estudiante -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5><b>Estadísticas por Estudiante</b></h5>
                                    <hr>
                                </div>
                                @foreach($estudiantes as $estudiante)
                                    @php
                                        $asistenciasEstudiante = $asistencias->where('estudiante_id', $estudiante->id);
                                        $totalAsistencias = $asistenciasEstudiante->count();
                                        $presentes = $asistenciasEstudiante->where('estado', 'Presente')->count();
                                        $ausentes = $asistenciasEstudiante->where('estado', 'Ausente')->count();
                                        $tardanzas = $asistenciasEstudiante->where('estado', 'Tardanza')->count();
                                        $porcentaje = $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100, 2) : 0;
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">
                                                    <i class="fas fa-user-graduate"></i>
                                                    {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-3 text-center">
                                                        <div class="text-success">
                                                            <h3><i class="fas fa-check-circle"></i></h3>
                                                            <h4>{{ $presentes }}</h4>
                                                            <small>Presentes</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-3 text-center">
                                                        <div class="text-danger">
                                                            <h3><i class="fas fa-times-circle"></i></h3>
                                                            <h4>{{ $ausentes }}</h4>
                                                            <small>Ausentes</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-3 text-center">
                                                        <div class="text-warning">
                                                            <h3><i class="fas fa-clock"></i></h3>
                                                            <h4>{{ $tardanzas }}</h4>
                                                            <small>Tardanzas</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-3 text-center">
                                                        <div class="text-info">
                                                            <h3><i class="fas fa-percentage"></i></h3>
                                                            <h4>{{ $porcentaje }}%</h4>
                                                            <small>Asistencia</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress mt-3">
                                                    <div class="progress-bar bg-{{ $porcentaje >= 85 ? 'success' : ($porcentaje >= 70 ? 'warning' : 'danger') }}" 
                                                         style="width: {{ $porcentaje }}%">
                                                        {{ $porcentaje }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Estadísticas Generales -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $asistencias->where('estado', 'Presente')->count() }}</h3>
                                            <p>Total Presentes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $asistencias->where('estado', 'Ausente')->count() }}</h3>
                                            <p>Total Ausentes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $asistencias->where('estado', 'Tardanza')->count() }}</h3>
                                            <p>Total Tardanzas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $asistencias->count() }}</h3>
                                            <p>Total Registros</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay registros de asistencias para tus estudiantes.
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
            $('#tablaAsistencias').DataTable({
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