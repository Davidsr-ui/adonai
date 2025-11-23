@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Comportamiento</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información del Comportamiento</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $comportamiento->tipo_badge }} badge-lg">
                            <i class="fas {{ $comportamiento->tipo_icon }}"></i>
                            {{ $comportamiento->tipo }}
                        </span>
                        @if($comportamiento->notificado_tutor)
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-bell"></i> Notificado
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información Principal -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-graduate"></i> Datos del Estudiante</h5>
                            <hr>
                            <div class="form-group">
                                <label>Nombre Completo</label>
                                <p><strong>{{ $comportamiento->estudiante->persona->nombres }} {{ $comportamiento->estudiante->persona->apellidos }}</strong></p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DNI</label>
                                        <p>{{ $comportamiento->estudiante->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Código</label>
                                        <p><strong>{{ $comportamiento->estudiante->codigo_estudiante }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Grado</label>
                                <p>
                                    @if($comportamiento->estudiante->grado)
                                        <span class="badge badge-info">
                                            {{ $comportamiento->estudiante->grado->nombre_completo }}
                                        </span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5><i class="fas fa-chalkboard-teacher"></i> Datos del Docente</h5>
                            <hr>
                            @if($comportamiento->docente)
                                <div class="form-group">
                                    <label>Nombre Completo</label>
                                    <p><strong>{{ $comportamiento->docente->persona->nombres }} {{ $comportamiento->docente->persona->apellidos }}</strong></p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>DNI</label>
                                            <p>{{ $comportamiento->docente->persona->dni }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Código</label>
                                            <p><strong>{{ $comportamiento->docente->codigo_docente }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    No se asignó un docente a este registro.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detalles del Comportamiento -->
                    <h5 class="mt-4"><i class="fas fa-clipboard-list"></i> Detalles del Comportamiento</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha del Incidente</label>
                                <p>
                                    <strong>{{ $comportamiento->fecha_formateada }}</strong>
                                    <br>
                                    <span class="badge badge-secondary">{{ $comportamiento->dia_semana }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo de Comportamiento</label>
                                <p>
                                    <span class="badge badge-{{ $comportamiento->tipo_badge }} badge-lg">
                                        <i class="fas {{ $comportamiento->tipo_icon }}"></i>
                                        {{ $comportamiento->tipo }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Notificado a Tutor</label>
                                <p>
                                    @if($comportamiento->notificado_tutor)
                                        <span class="badge badge-success badge-lg">
                                            <i class="fas fa-check"></i> Sí
                                        </span>
                                    @else
                                        <span class="badge badge-secondary badge-lg">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha de Notificación</label>
                                <p>{{ $comportamiento->fecha_notificacion_formateada }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción del Comportamiento</label>
                                <div class="callout callout-{{ $comportamiento->tipo_badge }}">
                                    {{ $comportamiento->descripcion }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($comportamiento->sancion)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Sanción Aplicada</label>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>{{ $comportamiento->sancion }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Estadísticas del Estudiante -->
                    <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Resumen de Comportamiento del Estudiante</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $resumen['total'] }}</h3>
                                    <p>Total</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $resumen['positivos'] }}</h3>
                                    <p>Positivos</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-smile"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $resumen['negativos'] }}</h3>
                                    <p>Negativos</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-frown"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ $resumen['neutros'] }}</h3>
                                    <p>Neutros</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-meh"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $resumen['con_sancion'] }}</h3>
                                    <p>Con Sanción</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $resumen['notificados'] }}</h3>
                                    <p>Notificados</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial Reciente -->
                    <h5 class="mt-4"><i class="fas fa-history"></i> Últimos 10 Comportamientos del Estudiante</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Docente</th>
                                    <th class="text-center">Notif.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosComportamientos as $item)
                                <tr class="{{ $item->id == $comportamiento->id ? 'table-active' : '' }}">
                                    <td>{{ $item->fecha_formateada }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->tipo_badge }}">
                                            <i class="fas {{ $item->tipo_icon }}"></i>
                                            {{ $item->tipo }}
                                        </span>
                                    </td>
                                    <td>{{ \Str::limit($item->descripcion, 80) }}</td>
                                    <td>
                                        @if($item->docente)
                                            {{ $item->docente->persona->apellidos }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->notificado_tutor)
                                            <i class="fas fa-check text-success"></i>
                                        @else
                                            <i class="fas fa-times text-danger"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay historial</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Fechas del Sistema -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de Registro</label>
                                <p>{{ \Carbon\Carbon::parse($comportamiento->created_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($comportamiento->updated_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.comportamientos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <a href="{{ route('admin.estudiantes.show', $comportamiento->estudiante->id) }}" class="btn btn-info">
                                    <i class="fas fa-user-graduate"></i> Ver Perfil del Estudiante
                                </a>
                                @if($comportamiento->docente)
                                <a href="{{ route('admin.docentes.show', $comportamiento->docente->id) }}" class="btn btn-success">
                                    <i class="fas fa-chalkboard-teacher"></i> Ver Perfil del Docente
                                </a>
                                @endif
                                @if(!$comportamiento->notificado_tutor)
                                <form action="{{ route('admin.comportamientos.notificar', $comportamiento->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-bell"></i> Notificar a Tutor
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.comportamientos.cancelar-notificacion', $comportamiento->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-bell-slash"></i> Cancelar Notificación
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