@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Estudiante</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información del Estudiante</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Datos Personales</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">DNI</label>
                                        <p>{{ $estudiante->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Nombres</label>
                                        <p>{{ $estudiante->persona->nombres }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Apellidos</label>
                                        <p>{{ $estudiante->persona->apellidos }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fecha de Nacimiento</label>
                                        <p>{{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Edad</label>
                                        <p>{{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->age }} años</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Género</label>
                                        <p>
                                            @if ($estudiante->persona->genero == 'M')
                                                Masculino
                                            @elseif($estudiante->persona->genero == 'F')
                                                Femenino
                                            @else
                                                Otro
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Dirección</label>
                                        <p>{{ $estudiante->persona->direccion ?? 'No especificada' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Teléfono</label>
                                        <p>{{ $estudiante->persona->telefono ?? 'No especificado' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Teléfono de Emergencia</label>
                                        <p>{{ $estudiante->persona->telefono_emergencia ?? 'No especificado' }}</p>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3">Información Académica</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Código Estudiante</label>
                                        <p><strong>{{ $estudiante->codigo_estudiante }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Año de Ingreso</label>
                                        <p>{{ $estudiante->año_ingreso }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Años en la Institución</label>
                                        <p>{{ date('Y') - $estudiante->año_ingreso }} años</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Condición</label>
                                        <p>
                                            @if ($estudiante->condicion == 'Regular')
                                                <span class="badge badge-success">{{ $estudiante->condicion }}</span>
                                            @elseif($estudiante->condicion == 'Irregular')
                                                <span class="badge badge-warning">{{ $estudiante->condicion }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ $estudiante->condicion }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Grado Actual</label>
                                        <p>{{ $estudiante->grado ? $estudiante->grado->nombre_completo : 'Sin asignar' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Nivel</label>
                                        <p>{{ $estudiante->grado && $estudiante->grado->nivel ? $estudiante->grado->nivel->nombre : '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Turno</label>
                                        <p>{{ $estudiante->grado && $estudiante->grado->turno ? $estudiante->grado->turno->nombre : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                @if ($estudiante->persona->foto_perfil)
                                    <img src="{{ asset('storage/' . $estudiante->persona->foto_perfil) }}"
                                        alt="Foto de {{ $estudiante->persona->nombres }}" class="img-thumbnail"
                                        style="max-width: 200px;">
                                @else
                                    <div class="bg-light p-5 rounded">
                                        <i class="fas fa-user-graduate fa-5x text-muted"></i>
                                        <p class="text-muted mt-2">Sin foto</p>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <label>Estado</label>
                                <p>
                                    @if ($estudiante->persona->estado == 'Activo')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($estudiante->tutores->count() > 0)
                        <h5 class="mt-4">Tutores</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>DNI</th>
                                            <th>Tutor</th>
                                            <th>Relación</th>
                                            <th>Tipo</th>
                                            <th>Teléfono</th>
                                            <th>Autorizado Recojo</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estudiante->tutores as $tutor)
                                            <tr>
                                                <td>{{ $tutor->persona->dni }}</td>
                                                <td>{{ $tutor->persona->apellidos }} {{ $tutor->persona->nombres }}</td>
                                                <td>{{ $tutor->pivot->relacion_familiar }}</td>
                                                <td>
                                                    @if ($tutor->pivot->tipo == 'Principal')
                                                        <span class="badge badge-primary">{{ $tutor->pivot->tipo }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-secondary">{{ $tutor->pivot->tipo }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $tutor->persona->telefono ?? '-' }}</td>
                                                <td>
                                                    @if ($tutor->pivot->autorizacion_recojo)
                                                        <span class="badge badge-success">Sí</span>
                                                    @else
                                                        <span class="badge badge-danger">No</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($tutor->pivot->estado == 'Activo')
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
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            @if ($estudiante->asistencias && $estudiante->asistencias->count() > 0)
                                <h5 class="mt-4">Resumen de Asistencias</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h4>{{ $estudiante->asistencias->where('estado', 'Presente')->count() }}
                                                </h4>
                                                <p>Presente</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h4>{{ $estudiante->asistencias->where('estado', 'Ausente')->count() }}
                                                </h4>
                                                <p>Ausente</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h4>{{ $estudiante->asistencias->where('estado', 'Tardanza')->count() }}
                                                </h4>
                                                <p>Tardanza</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h4>{{ $estudiante->asistencias->where('estado', 'Justificado')->count() }}
                                                </h4>
                                                <p>Justificado</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if ($estudiante->comportamientos && $estudiante->comportamientos->count() > 0)
                                <h5 class="mt-4">Registro de Comportamiento</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-smile"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Positivo</span>
                                                <span
                                                    class="info-box-number">{{ $estudiante->comportamientos->where('tipo', 'Positivo')->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-frown"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Negativo</span>
                                                <span
                                                    class="info-box-number">{{ $estudiante->comportamientos->where('tipo', 'Negativo')->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-secondary">
                                            <span class="info-box-icon"><i class="fas fa-meh"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Neutro</span>
                                                <span
                                                    class="info-box-number">{{ $estudiante->comportamientos->where('tipo', 'Neutro')->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <a href="#" class="btn btn-success" data-toggle="modal"
                                    data-target="#editEstudianteModal{{ $estudiante->id }}">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('admin.tutor-estudiante.index') }}" class="btn btn-primary">
                                    <i class="fas fa-users"></i> Asignar Tutor
                                </a>
                                <a href="{{ route('admin.matriculas.index', ['estudiante_id' => $estudiante->id]) }}"
                                    class="btn btn-info">
                                    <i class="fas fa-graduation-cap"></i> Ver Matrículas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- ============================================================== --}}
{{-- ===================== MODAL EDITAR =========================== --}}
{{-- ============================================================== --}}
<div class="modal fade" id="editEstudianteModal{{ $estudiante->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Editar Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <h5>Datos Personales</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <label>DNI *</label>
                            <input type="text" name="dni" value="{{ $estudiante->persona->dni }}"
                                class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Nombres *</label>
                            <input type="text" name="nombres" value="{{ $estudiante->persona->nombres }}"
                                class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Apellidos *</label>
                            <input type="text" name="apellidos" value="{{ $estudiante->persona->apellidos }}"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="col-md-4">
                            <label>Fecha de nacimiento *</label>
                            <input type="date" name="fecha_nacimiento"
                                value="{{ $estudiante->persona->fecha_nacimiento }}" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Género *</label>
                            <select name="genero" class="form-control" required>
                                <option value="M" {{ $estudiante->persona->genero == 'M' ? 'selected' : '' }}>
                                    Masculino</option>
                                <option value="F" {{ $estudiante->persona->genero == 'F' ? 'selected' : '' }}>
                                    Femenino</option>
                                <option value="Otro" {{ $estudiante->persona->genero == 'Otro' ? 'selected' : '' }}>
                                    Otro</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Estado *</label>
                            <select name="estado" class="form-control" required>
                                <option value="Activo"
                                    {{ $estudiante->persona->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo"
                                    {{ $estudiante->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- FOTO DE PERFIL --}}
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto de Perfil</label>
                                <input type="file" name="foto_perfil" class="form-control">

                                @if ($estudiante->persona->foto_perfil)
                                    <img src="{{ asset('storage/' . $estudiante->persona->foto_perfil) }}"
                                        class="img-thumbnail mt-2" width="120">
                                @else
                                    <p class="text-muted mt-2">Sin foto actual</p>
                                @endif

                                <small class="text-muted">
                                    Formatos permitidos: JPG, JPEG, PNG — Máx. 2MB
                                </small>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-3">Datos Académicos</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Código Estudiante</label>
                            <input type="text" name="codigo_estudiante"
                                value="{{ $estudiante->codigo_estudiante }}" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Año de ingreso</label>
                            <input type="number" name="año_ingreso" value="{{ $estudiante->año_ingreso }}"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label>Condición</label>
                            <select name="condicion" class="form-control">
                                <option value="Regular" {{ $estudiante->condicion == 'Regular' ? 'selected' : '' }}>
                                    Regular</option>
                                <option value="Irregular"
                                    {{ $estudiante->condicion == 'Irregular' ? 'selected' : '' }}>Irregular</option>
                                <option value="Retirado" {{ $estudiante->condicion == 'Retirado' ? 'selected' : '' }}>
                                    Retirado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Actualizar</button>
                </div>

            </form>

        </div>
    </div>
</div>
