@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Grado</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información del Grado</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5><i class="fas fa-layer-group"></i> Datos Generales</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Nivel Educativo</label>
                                        <p>
                                            <span class="badge badge-info badge-lg">
                                                <i class="fas fa-graduation-cap"></i> 
                                                {{ $grado->nivel->nombre ?? 'Sin nivel asignado' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Nombre del Grado</label>
                                        <p><strong>{{ $grado->nombre }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Sección</label>
                                        <p>
                                            @if($grado->seccion)
                                                <span class="badge badge-secondary badge-lg">{{ $grado->seccion }}</span>
                                            @else
                                                <span class="text-muted">No tiene sección</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Estado</label>
                                        <p>
                                            @if($grado->estado == 'Activo')
                                                <span class="badge badge-success badge-lg">
                                                    <i class="fas fa-check-circle"></i> Activo
                                                </span>
                                            @else
                                                <span class="badge badge-danger badge-lg">
                                                    <i class="fas fa-times-circle"></i> Inactivo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Turno</label>
                                        <p>
                                            @if($grado->turno)
                                                <span class="badge badge-warning badge-lg">
                                                    <i class="fas fa-clock"></i> {{ $grado->turno->nombre }}
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($grado->turno->hora_inicio)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($grado->turno->hora_fin)->format('H:i') }}
                                                </small>
                                            @else
                                                <span class="text-muted">Sin turno asignado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fecha de Creación</label>
                                        <p>{{ \Carbon\Carbon::parse($grado->created_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3"><i class="fas fa-users"></i> Capacidad y Ocupación</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Capacidad Máxima</label>
                                        <p>
                                            <span class="badge badge-primary badge-lg">
                                                <i class="fas fa-users"></i> {{ $grado->capacidad_maxima }} estudiantes
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Estudiantes Matriculados</label>
                                        <p>
                                            <span class="badge badge-info badge-lg">
                                                {{ $grado->estudiantes->count() }} estudiantes
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Vacantes Disponibles</label>
                                        <p>
                                            <span class="badge badge-success badge-lg">
                                                {{ $grado->capacidad_disponible }} vacantes
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Porcentaje de Ocupación</label>
                                        <p>
                                            @php
                                                $porcentaje = $grado->porcentaje_ocupacion;
                                                $colorBadge = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
                                            @endphp
                                            <span class="badge badge-{{ $colorBadge }} badge-lg">
                                                {{ $porcentaje }}%
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Barra de progreso de ocupación -->
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Ocupación Visual:</label>
                                    <div class="progress" style="height: 30px;">
                                        @php
                                            $colorProgress = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
                                        @endphp
                                        <div class="progress-bar bg-{{ $colorProgress }}" 
                                             role="progressbar" 
                                             style="width: {{ $porcentaje }}%;" 
                                             aria-valuenow="{{ $porcentaje }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $grado->estudiantes->count() }} / {{ $grado->capacidad_maxima }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Estudiantes -->
                            <h5 class="mt-4"><i class="fas fa-user-graduate"></i> Estudiantes Matriculados</h5>
                            <hr>
                            @if($grado->estudiantes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>N°</th>
                                                <th>DNI</th>
                                                <th>Apellidos y Nombres</th>
                                                <th>Género</th>
                                                <th>Edad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($grado->estudiantes as $index => $estudiante)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $estudiante->persona->dni }}</td>
                                                <td>
                                                    <i class="fas fa-user"></i> 
                                                    {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                                </td>
                                                <td class="text-center">
                                                    @if($estudiante->persona->genero == 'M')
                                                        <span class="badge badge-primary">M</span>
                                                    @elseif($estudiante->persona->genero == 'F')
                                                        <span class="badge badge-pink">F</span>
                                                    @else
                                                        <span class="badge badge-secondary">Otro</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->age }} años
                                                </td>
                                                <td class="text-center">
                                                    @if($estudiante->persona->estado == 'Activo')
                                                        <span class="badge badge-success">Activo</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactivo</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    No hay estudiantes matriculados en este grado actualmente.
                                </div>
                            @endif

                            <!-- Sección de Horarios -->
                            <h5 class="mt-4"><i class="fas fa-calendar-alt"></i> Horarios del Grado</h5>
                            <hr>
                            @if($grado->horarios->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Día</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Curso</th>
                                                <th>Docente</th>
                                                <th>Aula</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($grado->horarios->sortBy('dia_semana') as $horario)
                                            <tr>
                                                <td><strong>{{ $horario->dia_semana }}</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                                                <td>{{ $horario->curso->nombre ?? 'N/A' }}</td>
                                                <td>
                                                    @if($horario->docente)
                                                        {{ $horario->docente->persona->nombres }} {{ $horario->docente->persona->apellidos }}
                                                    @else
                                                        <span class="text-muted">Sin asignar</span>
                                                    @endif
                                                </td>
                                                <td>{{ $horario->aula ?? 'No asignada' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    No hay horarios configurados para este grado.
                                </div>
                            @endif

                            <!-- Estadísticas -->
                            <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $grado->estudiantes->count() }}</h3>
                                            <p>Estudiantes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $grado->capacidad_disponible }}</h3>
                                            <p>Vacantes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chair"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $grado->horarios->count() }}</h3>
                                            <p>Horarios</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $grado->matriculas->count() }}</h3>
                                            <p>Matrículas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <h5 class="mt-4"><i class="fas fa-info-circle"></i> Información Adicional</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Última Actualización</label>
                                        <p>{{ \Carbon\Carbon::parse($grado->updated_at)->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tiempo desde creación</label>
                                        <p>{{ \Carbon\Carbon::parse($grado->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.grados.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editGradoModal">
                                    <i class="fas fa-edit"></i> Editar Grado
                                </button>
                                @if($grado->estudiantes->count() == 0)
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteGradoModal">
                                        <i class="fas fa-trash"></i> Eliminar Grado
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar (simplificado) -->
    <div class="modal fade" id="editGradoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Editar Grado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.grados.update', $grado->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nivel_id">Nivel <span class="text-danger">*</span></label>
                                    <select name="nivel_id" class="form-control" required>
                                        <option value="">-- Seleccione un nivel --</option>
                                        @foreach(\App\Models\Nivel::where('estado', 'Activo')->orderBy('orden')->get() as $nivel)
                                            <option value="{{ $nivel->id }}" {{ $grado->nivel_id == $nivel->id ? 'selected' : '' }}>
                                                {{ $nivel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="turno_id">Turno</label>
                                    <select name="turno_id" class="form-control">
                                        <option value="">-- Sin turno --</option>
                                        @foreach(\App\Models\Turno::where('estado', 'activo')->get() as $turno)
                                            <option value="{{ $turno->id }}" {{ $grado->turno_id == $turno->id ? 'selected' : '' }}>
                                                {{ $turno->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre del Grado <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" value="{{ $grado->nombre }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seccion">Sección</label>
                                    <input type="text" name="seccion" class="form-control" value="{{ $grado->seccion }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacidad_maxima">Capacidad Máxima <span class="text-danger">*</span></label>
                                    <input type="number" name="capacidad_maxima" class="form-control" value="{{ $grado->capacidad_maxima }}" min="{{ $grado->estudiantes->count() }}" max="100" required>
                                    <small class="text-muted">Mínimo: {{ $grado->estudiantes->count() }} (estudiantes actuales)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado <span class="text-danger">*</span></label>
                                    <select name="estado" class="form-control" required>
                                        <option value="Activo" {{ $grado->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="Inactivo" {{ $grado->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    @if($grado->estudiantes->count() == 0)
    <div class="modal fade" id="deleteGradoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.grados.destroy', $grado->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>¿Está seguro de que desea eliminar el grado?</p>
                        <p class="mb-0"><strong>{{ $grado->nombre_completo }}</strong></p>
                        <p class="text-muted">Nivel: {{ $grado->nivel->nombre ?? 'N/A' }}</p>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-circle"></i>
                            Esta acción no se puede deshacer.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
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