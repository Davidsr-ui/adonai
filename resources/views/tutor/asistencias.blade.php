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
                    <div class="card-tools">
                        @if($tutor->estudiantes->count() > 1)
                            <select id="estudianteSelect" class="form-control form-control-sm" onchange="cambiarEstudiante()">
                                @foreach($tutor->estudiantes as $est)
                                    <option value="{{ $est->id }}" {{ $estudiante && $estudiante->id == $est->id ? 'selected' : '' }}>
                                        {{ $est->persona->apellidos }} {{ $est->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($estudiante)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-user"></i> Estudiante:</strong> {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}<br>
                            <strong><i class="fas fa-graduation-cap"></i> Grado:</strong> {{ $estudiante->grado->nombre ?? 'N/A' }}<br>
                            <strong><i class="fas fa-layer-group"></i> Nivel:</strong> {{ $estudiante->grado->nivel->nombre ?? 'N/A' }}
                        </div>

                        @if($asistencias->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="tablaAsistencias">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Fecha</th>
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

                            <!-- Estadísticas del Estudiante -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $presentes }}</h3>
                                            <p>Presentes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $ausentes }}</h3>
                                            <p>Ausentes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $tardanzas }}</h3>
                                            <p>Tardanzas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ number_format($porcentaje, 1) }}%</h3>
                                            <p>% Asistencia</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-percentage"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-{{ $porcentaje >= 85 ? 'success' : ($porcentaje >= 70 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $porcentaje }}%">
                                    <strong>{{ number_format($porcentaje, 1) }}% de Asistencia</strong>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('tutor.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay registros de asistencias para este estudiante.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No tienes estudiantes asignados.
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
        function cambiarEstudiante() {
            const estudianteId = document.getElementById('estudianteSelect').value;
            window.location.href = '{{ route("tutor.asistencias") }}?estudiante_id=' + estudianteId;
        }

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