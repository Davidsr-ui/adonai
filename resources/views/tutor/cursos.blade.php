@extends('adminlte::page')

@section('content_header')
    <h1><b>Cursos Matriculados</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Cursos del Estudiante</h3>
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
                            <div class="row">
                                <div class="col-md-4">
                                    <strong><i class="fas fa-user"></i> Estudiante:</strong><br>
                                    {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="fas fa-graduation-cap"></i> Grado:</strong><br>
                                    {{ $estudiante->grado->nombre ?? 'N/A' }}
                                </div>
                                <div class="col-md-4">
                                    <strong><i class="fas fa-layer-group"></i> Nivel:</strong><br>
                                    {{ $estudiante->grado->nivel->nombre ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        @if(count($cursos) > 0)
                            <div class="row">
                                @foreach($cursos as $curso)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card card-widget widget-user-2">
                                            <div class="widget-user-header bg-gradient-primary">
                                                <h3 class="widget-user-username">{{ $curso->curso_nombre }}</h3>
                                                <h5 class="widget-user-desc">
                                                    <i class="fas fa-chalkboard-teacher"></i> 
                                                    {{ $curso->nombres }} {{ $curso->apellidos }}
                                                </h5>
                                            </div>
                                            <div class="card-footer p-3">
                                                <div class="mb-2">
                                                    @if($curso->codigo)
                                                        <span class="badge badge-info">
                                                            <i class="fas fa-barcode"></i> {{ $curso->codigo }}
                                                        </span>
                                                    @endif
                                                    @if($curso->horas_semanales)
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-clock"></i> {{ $curso->horas_semanales }} hrs/sem
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($curso->area_curricular)
                                                    <p class="text-muted mb-2">
                                                        <small><i class="fas fa-tag"></i> {{ $curso->area_curricular }}</small>
                                                    </p>
                                                @endif
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button class="btn btn-info btn-sm btn-block" 
                                                                onclick="verDetalleCurso({{ $curso->id }})">
                                                            <i class="fas fa-info-circle"></i> Detalles
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="{{ route('tutor.mensajeria') }}?docente_user_id={{ $curso->user_id }}&estudiante_id={{ $estudiante->id }}" 
                                                           class="btn btn-primary btn-sm btn-block">
                                                            <i class="fas fa-envelope"></i> Mensaje
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Resumen -->
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle"></i>
                                <strong>Total de cursos:</strong> {{ count($cursos) }}
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('tutor.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay cursos registrados para este grado.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No tiene estudiantes asignados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle Curso -->
    <div class="modal fade" id="detalleCursoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Detalle del Curso</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalContent">
                    <p class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    function cambiarEstudiante() {
        const estudianteId = document.getElementById('estudianteSelect').value;
        window.location.href = '{{ route("tutor.cursos-matriculados") }}?estudiante_id=' + estudianteId;
    }

    function verDetalleCurso(cursoId) {
        @foreach($cursos as $curso)
            if ({{ $curso->id }} == cursoId) {
                let html = `
                    <h5><i class="fas fa-book"></i> {{ $curso->curso_nombre }}</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <strong><i class="fas fa-chalkboard-teacher"></i> Docente:</strong><br>
                            {{ $curso->nombres }} {{ $curso->apellidos }}
                        </div>
                        @if($curso->codigo)
                        <div class="col-md-6 mb-2">
                            <strong><i class="fas fa-barcode"></i> Código:</strong><br>
                            {{ $curso->codigo }}
                        </div>
                        @endif
                        @if($curso->horas_semanales)
                        <div class="col-md-6 mb-2">
                            <strong><i class="fas fa-clock"></i> Horas/Semana:</strong><br>
                            {{ $curso->horas_semanales }} horas
                        </div>
                        @endif
                        @if($curso->area_curricular)
                        <div class="col-md-12 mb-2">
                            <strong><i class="fas fa-tag"></i> Área Curricular:</strong><br>
                            {{ $curso->area_curricular }}
                        </div>
                        @endif
                        <div class="col-md-12 mb-2">
                            <strong><i class="fas fa-graduation-cap"></i> Grado:</strong><br>
                            {{ $estudiante->grado->nombre ?? 'N/A' }}
                        </div>
                    </div>
                `;
                $('#modalContent').html(html);
                $('#detalleCursoModal').modal('show');
            }
        @endforeach
    }
</script>
@stop