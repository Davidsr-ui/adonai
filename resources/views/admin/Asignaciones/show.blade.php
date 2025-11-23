@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle de la Asignación</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información de la Asignación</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información del Docente -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-tie"></i> Datos del Docente</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Nombre Completo</label>
                                        <p><strong>{{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellidos }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DNI</label>
                                        <p>{{ $asignacion->docente->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código Docente</label>
                                        <p><strong>{{ $asignacion->docente->codigo_docente }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Especialidad</label>
                                        <p>{{ $asignacion->docente->especialidad ?? 'No especificada' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tipo de Contrato</label>
                                        <p>
                                            @if($asignacion->docente->tipo_contrato == 'Nombrado')
                                                <span class="badge badge-success">{{ $asignacion->docente->tipo_contrato }}</span>
                                            @elseif($asignacion->docente->tipo_contrato == 'Contratado')
                                                <span class="badge badge-info">{{ $asignacion->docente->tipo_contrato }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $asignacion->docente->tipo_contrato }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Curso y Grado -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-book"></i> Datos Académicos</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Curso</label>
                                        <p><strong>{{ $asignacion->curso->nombre }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código del Curso</label>
                                        <p>{{ $asignacion->curso->codigo ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Área Curricular</label>
                                        <p>{{ $asignacion->curso->area_curricular ?? 'No especificada' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Créditos</label>
                                        <p>
                                            <span class="badge badge-primary">
                                                {{ $asignacion->curso->creditos }} créditos
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Horas Semanales</label>
                                        <p>
                                            <span class="badge badge-warning">
                                                {{ $asignacion->curso->horas_semanales }} horas
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Información del Grado -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-layer-group"></i> Información del Grado</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Nivel</label>
                                        <p>
                                            <span class="badge badge-info badge-lg">
                                                {{ $asignacion->grado->nivel->nombre ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Grado</label>
                                        <p><strong>{{ $asignacion->grado->nombre_completo }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Capacidad</label>
                                        <p>{{ $asignacion->grado->capacidad_maxima }} estudiantes</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Turno</label>
                                        <p>
                                            @if($asignacion->grado->turno)
                                                <span class="badge badge-secondary">
                                                    {{ $asignacion->grado->turno->nombre }}
                                                </span>
                                            @else
                                                <span class="text-muted">No asignado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la Gestión y Tutoría -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-calendar-alt"></i> Gestión y Tutoría</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Gestión</label>
                                        <p>
                                            <span class="badge badge-secondary badge-lg">
                                                {{ $asignacion->gestion->nombre }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Año</label>
                                        <p>{{ $asignacion->gestion->año }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Estado Gestión</label>
                                        <p>
                                            @if($asignacion->gestion->estado == 'Activo')
                                                <span class="badge badge-success">{{ $asignacion->gestion->estado }}</span>
                                            @elseif($asignacion->gestion->estado == 'Finalizado')
                                                <span class="badge badge-secondary">{{ $asignacion->gestion->estado }}</span>
                                            @else
                                                <span class="badge badge-info">{{ $asignacion->gestion->estado }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Tutor de Aula</label>
                                        <p>
                                            @if($asignacion->es_tutor_aula)
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-check-circle"></i> SÍ - Es tutor de este grado
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    Este docente es el tutor responsable del grado {{ $asignacion->grado->nombre_completo }}
                                                </small>
                                            @else
                                                <span class="badge badge-secondary badge-lg">
                                                    <i class="fas fa-times-circle"></i> NO - Solo docente de curso
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Información Adicional</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $asignacion->grado->estudiantes->count() }}</h3>
                                    <p>Estudiantes en el Grado</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $asignacion->curso->creditos }}</h3>
                                    <p>Créditos del Curso</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $asignacion->curso->horas_semanales }}</h3>
                                    <p>Horas Semanales</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $asignacion->docente->docenteCursos->count() }}</h3>
                                    <p>Total Asignaciones Docente</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha de Asignación</label>
                                <p>{{ \Carbon\Carbon::parse($asignacion->created_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($asignacion->updated_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <a href="{{ route('admin.docentes.show', $asignacion->docente->id) }}" class="btn btn-info">
                                    <i class="fas fa-user-tie"></i> Ver Perfil del Docente
                                </a>
                                <a href="{{ route('admin.cursos.show', $asignacion->curso->id) }}" class="btn btn-primary">
                                    <i class="fas fa-book"></i> Ver Detalles del Curso
                                </a>
                                <a href="{{ route('admin.grados.show', $asignacion->grado->id) }}" class="btn btn-success">
                                    <i class="fas fa-layer-group"></i> Ver Detalles del Grado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        @if(session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                showConfirmButton: true,
                timer: 3000
            });
        @endif
    </script>
@stop