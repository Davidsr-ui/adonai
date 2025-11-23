@extends('adminlte::page')

@section('title', 'Detalle del Horario')

@section('content_header')
    <h1>Detalle del Horario</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Información del Horario</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.horarios.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información Principal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Gestión</span>
                                    <span class="info-box-number">{{ $horario->gestion->nombre }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-book"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Curso</span>
                                    <span class="info-box-number">{{ $horario->curso->nombre }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Grado y Sección</span>
                                    <span class="info-box-number">{{ $horario->grado->nombre }} {{ $horario->grado->seccion }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chalkboard-teacher"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Docente</span>
                                    <span class="info-box-number">
                                        {{ $horario->docente ? $horario->docente->persona->apellidos . ' ' . $horario->docente->persona->nombres : 'Sin asignar' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Horario Detallado -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-clock"></i> Horario de Clase</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><i class="fas fa-calendar-day"></i> Día de la Semana</label>
                                                <p class="form-control-static">
                                                    <span class="badge badge-primary" style="font-size: 14px;">
                                                        {{ $horario->dia_semana }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><i class="fas fa-clock"></i> Hora de Inicio</label>
                                                <p class="form-control-static">
                                                    <strong>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><i class="fas fa-clock"></i> Hora de Fin</label>
                                                <p class="form-control-static">
                                                    <strong>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><i class="fas fa-hourglass-half"></i> Duración</label>
                                                <p class="form-control-static">
                                                    @php
                                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                                        $duracion = $inicio->diff($fin);
                                                        $horas = $duracion->h;
                                                        $minutos = $duracion->i;
                                                    @endphp
                                                    <span class="badge badge-info" style="font-size: 13px;">
                                                        @if($horas > 0)
                                                            {{ $horas }} {{ $horas == 1 ? 'hora' : 'horas' }}
                                                        @endif
                                                        @if($minutos > 0)
                                                            {{ $minutos }} {{ $minutos == 1 ? 'minuto' : 'minutos' }}
                                                        @endif
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fas fa-door-open"></i> Aula Asignada</label>
                                                <p class="form-control-static">
                                                    @if($horario->aula)
                                                        <span class="badge badge-success" style="font-size: 14px;">
                                                            Aula {{ $horario->aula }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">No asignada</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><i class="fas fa-info-circle"></i> Estado</label>
                                                <p class="form-control-static">
                                                    <span class="badge badge-success" style="font-size: 14px;">Activo</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información Adicional</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Fecha de Registro</label>
                                                <p>{{ \Carbon\Carbon::parse($horario->created_at)->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Última Actualización</label>
                                                <p>{{ \Carbon\Carbon::parse($horario->updated_at)->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($horario->docente)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="callout callout-info">
                                                <h5><i class="fas fa-user-tie"></i> Datos del Docente</h5>
                                                <p>
                                                    <strong>Código:</strong> {{ $horario->docente->codigo_docente }}<br>
                                                    <strong>Especialidad:</strong> {{ $horario->docente->especialidad ?? 'No especificada' }}<br>
                                                    <strong>Email:</strong> {{ $horario->docente->persona->user->email ?? 'No disponible' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editHorarioModal">
                                    <i class="fas fa-edit"></i> Editar Horario
                                </button>
                                <button type="button" class="btn btn-danger" onclick="confirmarEliminacion()">
                                    <i class="fas fa-trash"></i> Eliminar Horario
                                </button>
                                <form id="formEliminar" action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal EDIT (Opcional - puedes incluirlo o redirigir al index) -->
    <div class="modal fade" id="editHorarioModal" tabindex="-1" role="dialog" aria-labelledby="editHorarioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.horarios.update', $horario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editHorarioModalLabel">Editar Horario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Día <b>*</b></label>
                                    <select name="dia_semana" class="form-control" required>
                                        <option value="Lunes" {{ $horario->dia_semana == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                                        <option value="Martes" {{ $horario->dia_semana == 'Martes' ? 'selected' : '' }}>Martes</option>
                                        <option value="Miércoles" {{ $horario->dia_semana == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                        <option value="Jueves" {{ $horario->dia_semana == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                                        <option value="Viernes" {{ $horario->dia_semana == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                                        <option value="Sábado" {{ $horario->dia_semana == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Aula</label>
                                    <input type="text" name="aula" value="{{ $horario->aula }}" class="form-control" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hora Inicio <b>*</b></label>
                                    <input type="time" name="hora_inicio" value="{{ $horario->hora_inicio }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hora Fin <b>*</b></label>
                                    <input type="time" name="hora_fin" value="{{ $horario->hora_fin }}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        function confirmarEliminacion() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el horario permanentemente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEliminar').submit();
                }
            });
        }

        // Mostrar mensaje de éxito si hay uno en la sesión
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
@stop

@section('css')
    <style>
        .info-box-number {
            font-size: 16px !important;
            font-weight: normal !important;
        }
        .form-control-static {
            padding-top: 7px;
            font-size: 15px;
        }
        .callout {
            border-left: 5px solid #17a2b8;
        }
    </style>
@stop