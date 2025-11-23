@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle de Relación Tutor-Estudiante</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información de la Relación</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $relacion->estado_badge }} badge-lg">
                            {{ $relacion->estado }}
                        </span>
                        <span class="badge badge-{{ $relacion->tipo_badge }} badge-lg">
                            {{ $relacion->tipo }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información del Tutor -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-user"></i> Datos del Tutor</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Nombre Completo</label>
                                        <p><strong>{{ $relacion->tutor->persona->nombres }} {{ $relacion->tutor->persona->apellidos }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DNI</label>
                                        <p>{{ $relacion->tutor->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Celular</label>
                                        <p>{{ $relacion->tutor->persona->celular ?? 'No registrado' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <p>{{ $relacion->tutor->persona->email ?? 'No registrado' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Dirección</label>
                                        <p>{{ $relacion->tutor->persona->direccion ?? 'No registrada' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Ocupación</label>
                                        <p>{{ $relacion->tutor->ocupacion ?? 'No especificada' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Teléfono Trabajo</label>
                                        <p>{{ $relacion->tutor->telefono_trabajo ?? 'No registrado' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Estudiante -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-graduate"></i> Datos del Estudiante</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Nombre Completo</label>
                                        <p><strong>{{ $relacion->estudiante->persona->nombres }} {{ $relacion->estudiante->persona->apellidos }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DNI</label>
                                        <p>{{ $relacion->estudiante->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código Estudiante</label>
                                        <p><strong>{{ $relacion->estudiante->codigo_estudiante }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fecha de Nacimiento</label>
                                        <p>{{ \Carbon\Carbon::parse($relacion->estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Edad</label>
                                        <p>{{ \Carbon\Carbon::parse($relacion->estudiante->persona->fecha_nacimiento)->age }} años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Grado Actual</label>
                                        <p>
                                            @if($relacion->estudiante->grado)
                                                <span class="badge badge-info">
                                                    {{ $relacion->estudiante->grado->nombre_completo }}
                                                </span>
                                            @else
                                                <span class="text-muted">No asignado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Estado del Estudiante</label>
                                        <p>
                                            @if($relacion->estudiante->persona->estado == 'Activo')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Relación -->
                    <h5 class="mt-4"><i class="fas fa-link"></i> Información de la Relación</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Relación Familiar</label>
                                <p>
                                    <span class="badge badge-secondary badge-lg">
                                        {{ $relacion->relacion_familiar }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Tipo de Relación</label>
                                <p>
                                    <span class="badge badge-{{ $relacion->tipo_badge }} badge-lg">
                                        @if($relacion->tipo == 'Principal')
                                            <i class="fas fa-star"></i>
                                        @endif
                                        {{ $relacion->tipo }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Autorización de Recojo</label>
                                <p>
                                    @if($relacion->autorizacion_recojo)
                                        <span class="badge badge-success badge-lg">
                                            <i class="fas fa-check-circle"></i> Autorizado
                                        </span>
                                    @else
                                        <span class="badge badge-danger badge-lg">
                                            <i class="fas fa-times-circle"></i> No Autorizado
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Estado</label>
                                <p>
                                    <span class="badge badge-{{ $relacion->estado_badge }} badge-lg">
                                        {{ $relacion->estado }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    @if($relacion->tipo == 'Principal')
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tutor Principal:</strong> Esta persona es el contacto principal del estudiante y tiene todas las responsabilidades y derechos sobre su educación.
                    </div>
                    @endif

                    @if($relacion->autorizacion_recojo)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Autorización de Recojo:</strong> Esta persona está autorizada para recoger al estudiante de la institución.
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Sin Autorización:</strong> Esta persona NO está autorizada para recoger al estudiante de la institución.
                    </div>
                    @endif

                    <!-- Estadísticas -->
                    <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Información Adicional</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $relacion->estudiante->tutorEstudiantes()->count() }}</h3>
                                    <p>Total Tutores del Estudiante</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $relacion->tutor->tutorEstudiantes()->count() }}</h3>
                                    <p>Total Estudiantes del Tutor</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $relacion->estudiante->tutorEstudiantes()->where('autorizacion_recojo', true)->count() }}</h3>
                                    <p>Personas Autorizadas (Recojo)</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha de Registro</label>
                                <p>{{ \Carbon\Carbon::parse($relacion->created_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($relacion->updated_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.tutor-estudiante.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <a href="{{ route('admin.tutores.show', $relacion->tutor->id) }}" class="btn btn-info">
                                    <i class="fas fa-user"></i> Ver Perfil del Tutor
                                </a>
                                <a href="{{ route('admin.estudiantes.show', $relacion->estudiante->id) }}" class="btn btn-primary">
                                    <i class="fas fa-user-graduate"></i> Ver Perfil del Estudiante
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