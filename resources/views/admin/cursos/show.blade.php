@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Curso</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información del Curso</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5><i class="fas fa-book"></i> Datos Generales</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Nivel Educativo</label>
                                        <p>
                                            <span class="badge badge-info badge-lg">
                                                <i class="fas fa-layer-group"></i> 
                                                {{ $curso->nivel->nombre ?? 'Sin nivel asignado' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Nombre del Curso</label>
                                        <p><strong>{{ $curso->nombre }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Estado</label>
                                        <p>
                                            @if($curso->estado == 'Activo')
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Código del Curso</label>
                                        <p>{{ $curso->codigo ?? 'No asignado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Área Curricular</label>
                                        <p>{{ $curso->area_curricular ?? 'No especificada' }}</p>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3"><i class="fas fa-graduation-cap"></i> Información Académica</h5>
                            <hr>
                            <div class="row">
                               
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Horas Semanales</label>
                                        <p>
                                            <span class="badge badge-warning badge-lg">
                                                <i class="fas fa-clock"></i> {{ $curso->horas_semanales }} horas
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Total de Horas/Semestre</label>
                                        <p>
                                            <span class="badge badge-secondary badge-lg">
                                                {{ $curso->horas_semanales * 16 }} horas
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fecha de Creación</label>
                                        <p>{{ \Carbon\Carbon::parse($curso->created_at)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Docentes Asignados -->
                            <h5 class="mt-4"><i class="fas fa-chalkboard-teacher"></i> Docentes Asignados</h5>
                            <hr>
                            @if($curso->docentes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>DNI</th>
                                                <th>Docente</th>
                                                <th>Código Docente</th>
                                                <th>Especialidad</th>
                                                <th>Grado</th>
                                                <th class="text-center">Tutor de Aula</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($curso->docentes as $docente)
                                            <tr>
                                                <td>{{ $docente->persona->dni }}</td>
                                                <td>
                                                    <i class="fas fa-user"></i> 
                                                    {{ $docente->persona->nombres }} {{ $docente->persona->apellidos }}
                                                </td>
                                                <td>{{ $docente->codigo_docente }}</td>
                                                <td>{{ $docente->especialidad ?? 'No especificada' }}</td>
                                                <td>
                                                    @if($docente->pivot->grado_id)
                                                        {{ \App\Models\Grado::find($docente->pivot->grado_id)->nombre ?? 'N/A' }}
                                                    @else
                                                        <span class="text-muted">No asignado</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($docente->pivot->es_tutor_aula)
                                                        <span class="badge badge-primary">
                                                            <i class="fas fa-check"></i> Sí
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">No</span>
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
                                    No hay docentes asignados a este curso actualmente.
                                </div>
                            @endif

                            <!-- Sección de Horarios -->
                            <h5 class="mt-4"><i class="fas fa-calendar-alt"></i> Horarios</h5>
                            <hr>
                            @if($curso->horarios->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Día</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Aula</th>
                                                <th>Grado</th>
                                                <th>Docente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($curso->horarios as $horario)
                                            <tr>
                                                <td><strong>{{ $horario->dia_semana }}</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                                                <td>{{ $horario->aula ?? 'No asignada' }}</td>
                                                <td>{{ $horario->grado->nombre ?? 'N/A' }}</td>
                                                <td>
                                                    @if($horario->docente)
                                                        {{ $horario->docente->persona->nombres }} {{ $horario->docente->persona->apellidos }}
                                                    @else
                                                        <span class="text-muted">Sin asignar</span>
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
                                    No hay horarios configurados para este curso.
                                </div>
                            @endif

                            <!-- Estadísticas -->
                            <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $curso->docentes->count() }}</h3>
                                            <p>Docentes Asignados</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $curso->matriculas->count() }}</h3>
                                            <p>Matrículas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $curso->horarios->count() }}</h3>
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
                                            <h3>{{ $curso->asistencias->count() }}</h3>
                                            <p>Registros de Asistencia</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-clipboard-check"></i>
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
                                        <p>{{ \Carbon\Carbon::parse($curso->updated_at)->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Tiempo desde creación</label>
                                        <p>{{ \Carbon\Carbon::parse($curso->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.cursos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editCursoModal">
                                    <i class="fas fa-edit"></i> Editar Curso
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCursoModal">
                                    <i class="fas fa-trash"></i> Eliminar Curso
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="editCursoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Editar Curso
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
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
                                            <option value="{{ $nivel->id }}" {{ $curso->nivel_id == $nivel->id ? 'selected' : '' }}>
                                                {{ $nivel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo">Código del Curso</label>
                                    <input type="text" 
                                           name="codigo" 
                                           class="form-control" 
                                           value="{{ $curso->codigo }}" 
                                           placeholder="Ej: MAT-101">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nombre">Nombre del Curso <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="nombre" 
                                           class="form-control" 
                                           value="{{ $curso->nombre }}" 
                                           required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado <span class="text-danger">*</span></label>
                                    <select name="estado" class="form-control" required>
                                        <option value="Activo" {{ $curso->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="Inactivo" {{ $curso->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="area_curricular">Área Curricular</label>
                                    <input type="text" 
                                           name="area_curricular" 
                                           class="form-control" 
                                           value="{{ $curso->area_curricular }}" 
                                           placeholder="Ej: Ciencias, Humanidades, etc.">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="horas_semanales">Horas Semanales <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="horas_semanales" 
                                           class="form-control" 
                                           value="{{ $curso->horas_semanales }}" 
                                           min="1" 
                                           max="40" 
                                           required>
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
    <div class="modal fade" id="deleteCursoModal" tabindex="-1" role="dialog">
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
                <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>¿Está seguro de que desea eliminar el curso?</p>
                        <p class="mb-0"><strong>{{ $curso->nombre }}</strong></p>
                        @if($curso->codigo)
                            <p class="text-muted">Código: {{ $curso->codigo }}</p>
                        @endif
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-circle"></i>
                            Esta acción no se puede deshacer. Si el curso tiene docentes asignados o matrículas, no podrá eliminarse.
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