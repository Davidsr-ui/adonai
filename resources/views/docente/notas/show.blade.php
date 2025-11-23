@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle de la Nota</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información de la Nota</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $nota->tipo_evaluacion_badge }} badge-lg">
                            {{ $nota->tipo_evaluacion }}
                        </span>
                        <span class="badge badge-{{ $nota->estado_nota_badge }} badge-lg">
                            {{ $nota->estado_nota_texto }}
                        </span>
                        @if($nota->visible_tutor)
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-eye"></i> Visible Tutores
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Notas -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5><i class="fas fa-star"></i> Calificaciones</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-pen"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nota Práctica</span>
                                    <span class="info-box-number">{{ $nota->nota_practica ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-primary">
                                <span class="info-box-icon"><i class="fas fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nota Teoría</span>
                                    <span class="info-box-number">{{ $nota->nota_teoria ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-{{ $nota->estado_nota_badge }}">
                                <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nota Final</span>
                                    <span class="info-box-number" style="font-size: 2.5rem;"><strong>{{ $nota->nota_final }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-secondary">
                                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tipo Evaluación</span>
                                    <span class="info-box-number" style="font-size: 1.2rem;">{{ $nota->tipo_evaluacion }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Información del Estudiante -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-graduate"></i> Datos del Estudiante</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Nombre Completo</label>
                                        <p><strong>{{ $nota->matricula->estudiante->persona->nombres }} {{ $nota->matricula->estudiante->persona->apellidos }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DNI</label>
                                        <p>{{ $nota->matricula->estudiante->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código Estudiante</label>
                                        <p><strong>{{ $nota->matricula->estudiante->codigo_estudiante }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Grado</label>
                                        <p>
                                            @if($nota->matricula->grado)
                                                <span class="badge badge-info">
                                                    {{ $nota->matricula->grado->nombre_completo }}
                                                </span>
                                            @else
                                                <span class="text-muted">No asignado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Estado Matrícula</label>
                                        <p>
                                            <span class="badge badge-{{ $nota->matricula->estado_badge }}">
                                                {{ $nota->matricula->estado }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Curso -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-book"></i> Datos del Curso y Docente</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Curso</label>
                                        <p><strong>{{ $nota->matricula->curso->nombre }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código del Curso</label>
                                        <p>{{ $nota->matricula->curso->codigo ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Créditos</label>
                                        <p>
                                            <span class="badge badge-primary">
                                                {{ $nota->matricula->curso->creditos }} créditos
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Docente Calificador</label>
                                        <p>
                                            <strong>{{ $nota->docente->persona->nombres }} {{ $nota->docente->persona->apellidos }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $nota->docente->codigo_docente }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles de la Evaluación -->
                    <h5 class="mt-4"><i class="fas fa-clipboard-check"></i> Detalles de la Evaluación</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Periodo</label>
                                <p>
                                    <span class="badge badge-secondary badge-lg">
                                        {{ $nota->periodo->nombre }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de Evaluación</label>
                                <p>
                                    @if($nota->fecha_evaluacion)
                                        <strong>{{ $nota->fecha_evaluacion_formateada }}</strong>
                                    @else
                                        <span class="text-muted">No registrada</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Visible para Tutores</label>
                                <p>
                                    @if($nota->visible_tutor)
                                        <span class="badge badge-success badge-lg">
                                            <i class="fas fa-eye"></i> Sí
                                        </span>
                                    @else
                                        <span class="badge badge-secondary badge-lg">
                                            <i class="fas fa-eye-slash"></i> No
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de Publicación</label>
                                <p>
                                    @if($nota->fecha_publicacion)
                                        {{ $nota->fecha_publicacion_formateada }}
                                    @else
                                        <span class="text-muted">No publicada</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($nota->descripcion)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Descripción</label>
                                <p>{{ $nota->descripcion }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($nota->observaciones)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Observaciones</label>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    {{ $nota->observaciones }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Estado de la Nota -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-{{ $nota->estado_nota_badge }}">
                                <h5><i class="fas fa-info-circle"></i> Estado de la Evaluación</h5>
                                <p>
                                    El estudiante obtuvo una calificación de <strong>{{ $nota->nota_final }}</strong> puntos, 
                                    lo cual es considerado como <strong>{{ $nota->estado_nota_texto }}</strong>.
                                    @if($nota->estaAprobada())
                                        El estudiante ha <strong>APROBADO</strong> esta evaluación.
                                    @else
                                        El estudiante está <strong>DESAPROBADO</strong> en esta evaluación.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Promedio del Estudiante -->
                    <h5 class="mt-4"><i class="fas fa-chart-line"></i> Estadísticas del Estudiante</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $nota->matricula->estudiante->asistencias()->count() }}</h3>
                                    <p>Total Asistencias</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format(\App\Models\Nota::calcularPromedioPorMatricula($nota->matricula_id, $nota->periodo_id) ?? 0, 2) }}</h3>
                                    <p>Promedio del Periodo</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calculator"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ number_format(\App\Models\Nota::calcularPromedioPorMatricula($nota->matricula_id) ?? 0, 2) }}</h3>
                                    <p>Promedio General del Curso</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial de Notas del Curso -->
                    <h5 class="mt-4"><i class="fas fa-history"></i> Historial de Notas en este Curso</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Periodo</th>
                                    <th>Tipo</th>
                                    <th class="text-center">N. Práctica</th>
                                    <th class="text-center">N. Teoría</th>
                                    <th class="text-center">N. Final</th>
                                    <th class="text-center">Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $notasHistorial = \App\Models\Nota::where('matricula_id', $nota->matricula_id)
                                                                     ->with('periodo')
                                                                     ->orderBy('periodo_id')
                                                                     ->orderBy('created_at')
                                                                     ->get();
                                @endphp
                                @forelse($notasHistorial as $historial)
                                <tr class="{{ $historial->id == $nota->id ? 'table-active' : '' }}">
                                    <td>{{ $historial->periodo->nombre }}</td>
                                    <td>
                                        <span class="badge badge-{{ $historial->tipo_evaluacion_badge }}">
                                            {{ $historial->tipo_evaluacion }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $historial->nota_practica ?? '-' }}</td>
                                    <td class="text-center">{{ $historial->nota_teoria ?? '-' }}</td>
                                    <td class="text-center">
                                        <strong class="badge badge-{{ $historial->estado_nota_badge }}">
                                            {{ $historial->nota_final }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $historial->estado_nota_badge }}">
                                            {{ $historial->estado_nota_texto }}
                                        </span>
                                    </td>
                                    <td>{{ $historial->fecha_evaluacion_formateada }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No hay historial de notas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Fechas del Sistema -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha de Registro</label>
                                <p>{{ \Carbon\Carbon::parse($nota->created_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($nota->updated_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('docente.notas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                @if(!$nota->visible_tutor)
                                <form action="{{ route('docente.notas.publicar', $nota->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-eye"></i> Publicar para Tutores
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('docente.notas.despublicar', $nota->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-eye-slash"></i> Despublicar
                                    </button>
                                </form>
                                @endif
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