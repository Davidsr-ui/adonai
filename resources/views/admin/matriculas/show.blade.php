@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle de la Matrícula</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información de la Matrícula</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $matricula->estado_badge }} badge-lg">
                            {{ $matricula->estado }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información del Estudiante -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-graduate"></i> Datos del Estudiante</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Nombre Completo</label>
                                        <p><strong>{{ $matricula->estudiante->persona->nombres }} {{ $matricula->estudiante->persona->apellidos }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DNI</label>
                                        <p>{{ $matricula->estudiante->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código Estudiante</label>
                                        <p><strong>{{ $matricula->estudiante->codigo_estudiante }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fecha de Nacimiento</label>
                                        <p>{{ \Carbon\Carbon::parse($matricula->estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Edad</label>
                                        <p>{{ \Carbon\Carbon::parse($matricula->estudiante->persona->fecha_nacimiento)->age }} años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Estado del Estudiante</label>
                                        <p>
                                            @if($matricula->estudiante->persona->estado == 'Activo')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Curso -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-book"></i> Datos del Curso</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Curso</label>
                                        <p><strong>{{ $matricula->curso->nombre }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código del Curso</label>
                                        <p>{{ $matricula->curso->codigo ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Área Curricular</label>
                                        <p>{{ $matricula->curso->area_curricular ?? 'No especificada' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Créditos</label>
                                        <p>
                                            <span class="badge badge-primary">
                                                {{ $matricula->curso->creditos }} créditos
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Horas Semanales</label>
                                        <p>
                                            <span class="badge badge-warning">
                                                {{ $matricula->curso->horas_semanales }} horas
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
                                                {{ $matricula->grado->nivel->nombre ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Grado</label>
                                        <p><strong>{{ $matricula->grado->nombre_completo }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Capacidad</label>
                                        <p>{{ $matricula->grado->estudiantes->count() }} / {{ $matricula->grado->capacidad_maxima }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Turno</label>
                                        <p>
                                            @if($matricula->grado->turno)
                                                <span class="badge badge-secondary">
                                                    {{ $matricula->grado->turno->nombre }}
                                                </span>
                                            @else
                                                <span class="text-muted">No asignado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la Gestión y Estado -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-calendar-alt"></i> Gestión y Estado</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Gestión</label>
                                        <p>
                                            <span class="badge badge-secondary badge-lg">
                                                {{ $matricula->gestion->nombre }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Año</label>
                                        <p>{{ $matricula->gestion->año }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Estado Gestión</label>
                                        <p>
                                            @if($matricula->gestion->estado == 'Activo')
                                                <span class="badge badge-success">{{ $matricula->gestion->estado }}</span>
                                            @elseif($matricula->gestion->estado == 'Finalizado')
                                                <span class="badge badge-secondary">{{ $matricula->gestion->estado }}</span>
                                            @else
                                                <span class="badge badge-info">{{ $matricula->gestion->estado }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Estado de la Matrícula</label>
                                        <p>
                                            <span class="badge badge-{{ $matricula->estado_badge }} badge-lg">
                                                @if($matricula->estado == 'Matriculado')
                                                    <i class="fas fa-check-circle"></i>
                                                @elseif($matricula->estado == 'Aprobado')
                                                    <i class="fas fa-trophy"></i>
                                                @elseif($matricula->estado == 'Desaprobado')
                                                    <i class="fas fa-times-circle"></i>
                                                @else
                                                    <i class="fas fa-exclamation-circle"></i>
                                                @endif
                                                {{ $matricula->estado }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Notas -->
                    <h5 class="mt-4"><i class="fas fa-clipboard-list"></i> Registro de Notas</h5>
                    <hr>
                    @if($matricula->notas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Periodo</th>
                                        <th>Tipo Evaluación</th>
                                        <th class="text-center">Nota Práctica</th>
                                        <th class="text-center">Nota Teoría</th>
                                        <th class="text-center">Nota Final</th>
                                        <th>Fecha Evaluación</th>
                                        <th>Docente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matricula->notas as $nota)
                                    <tr>
                                        <td>{{ $nota->periodo->nombre ?? 'N/A' }}</td>
                                        <td>{{ $nota->tipo_evaluacion }}</td>
                                        <td class="text-center">{{ $nota->nota_practica ?? '-' }}</td>
                                        <td class="text-center">{{ $nota->nota_teoria ?? '-' }}</td>
                                        <td class="text-center">
                                            <strong>{{ $nota->nota_final }}</strong>
                                        </td>
                                        <td>{{ $nota->fecha_evaluacion ? \Carbon\Carbon::parse($nota->fecha_evaluacion)->format('d/m/Y') : 'No registrada' }}</td>
                                        <td>{{ $nota->docente->persona->apellidos ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="4" class="text-right"><strong>Promedio:</strong></td>
                                        <td class="text-center">
                                            <strong>{{ number_format($matricula->calcularPromedio() ?? 0, 2) }}</strong>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            No hay notas registradas para esta matrícula.
                        </div>
                    @endif

                    <!-- Estadísticas -->
                    <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $matricula->notas->count() }}</h3>
                                    <p>Notas Registradas</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format($matricula->calcularPromedio() ?? 0, 2) }}</h3>
                                    <p>Promedio General</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $matricula->curso->horas_semanales }}</h3>
                                    <p>Horas Semanales</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha de Matrícula</label>
                                <p>{{ \Carbon\Carbon::parse($matricula->created_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($matricula->updated_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.matriculas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <a href="{{ route('admin.estudiantes.show', $matricula->estudiante->id) }}" class="btn btn-info">
                                    <i class="fas fa-user-graduate"></i> Ver Perfil del Estudiante
                                </a>
                                <a href="{{ route('admin.cursos.show', $matricula->curso->id) }}" class="btn btn-primary">
                                    <i class="fas fa-book"></i> Ver Detalles del Curso
                                </a>
                                <a href="{{ route('admin.grados.show', $matricula->grado->id) }}" class="btn btn-success">
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