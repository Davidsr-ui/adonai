@extends('adminlte::page')

@section('title', 'Detalle del Tutor')

@section('content_header')
    <h1><b>Detalle del Tutor</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">

                <div class="card-header">
                    <h3 class="card-title">Información del Tutor</h3>
                </div>

                <div class="card-body">

                    {{-- ===================== DATOS PERSONALES ===================== --}}
                    <div class="row">
                        <div class="col-md-8">

                            <h5>Datos Personales</h5>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>DNI</label>
                                    <p>{{ $tutor->persona->dni ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label>Nombres</label>
                                    <p>{{ $tutor->persona->nombres ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label>Apellidos</label>
                                    <p>{{ $tutor->persona->apellidos ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <label>Fecha de Nacimiento</label>
                                    <p>
                                        @if ($tutor->persona && $tutor->persona->fecha_nacimiento)
                                            {{ \Carbon\Carbon::parse($tutor->persona->fecha_nacimiento)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <label>Edad</label>
                                    <p>
                                        @if ($tutor->persona && $tutor->persona->fecha_nacimiento)
                                            {{ \Carbon\Carbon::parse($tutor->persona->fecha_nacimiento)->age }} años
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <label>Género</label>
                                    <p>
                                        @if ($tutor->persona)
                                            @if ($tutor->persona->genero == 'M')
                                                Masculino
                                            @elseif($tutor->persona->genero == 'F')
                                                Femenino
                                            @else
                                                Otro
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Dirección</label>
                                    <p>{{ $tutor->persona->direccion ?? 'No especificada' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Teléfono</label>
                                    <p>{{ $tutor->persona->telefono ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label>Teléfono Emergencia</label>
                                    <p>{{ $tutor->persona->telefono_emergencia ?? 'No especificado' }}</p>
                                </div>
                            </div>

                            {{-- ===================== INFO TUTOR ===================== --}}
                            <h5 class="mt-3">Información del Tutor</h5>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Código Tutor</label>
                                    <p><strong>{{ $tutor->codigo_tutor ?? 'No asignado' }}</strong></p>
                                </div>

                                <div class="col-md-4">
                                    <label>Ocupación</label>
                                    <p>{{ $tutor->ocupacion ?? 'No especificada' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <label>Estado</label>
                                    <p>
                                        @if ($tutor->persona && $tutor->persona->estado == 'Activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                        </div>

                        {{-- FOTO --}}
                        <div class="col-md-4">
                            <div class="text-center">
                                @if ($tutor->persona && $tutor->persona->foto_perfil)
                                    <img src="{{ asset('storage/' . $tutor->persona->foto_perfil) }}" class="img-thumbnail"
                                        style="max-width: 200px;">
                                @else
                                    <div class="p-5 bg-light rounded">
                                        <i class="fas fa-user fa-5x text-muted"></i>
                                        <p class="text-muted mt-2">Sin foto</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ===================== ESTUDIANTES ===================== --}}
                    @if ($tutor->estudiantes->count() > 0)
                        <h5 class="mt-4">Estudiantes a Cargo</h5>
                        <hr>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Estudiante</th>
                                    <th>Grado</th>
                                    <th>Relación</th>
                                    <th>Tipo</th>
                                    <th>Autorizado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tutor->estudiantes as $estudiante)
                                    <tr>
                                        <td>{{ $estudiante->codigo_estudiante ?? 'N/A' }}</td>
                                        <td>
                                            @if ($estudiante->persona)
                                                {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}
                                            @else
                                                Sin datos
                                            @endif
                                        </td>
                                        <td>{{ $estudiante->grado->nombre ?? '-' }}</td>
                                        <td>{{ $estudiante->pivot->relacion_familiar ?? 'N/A' }}</td>
                                        <td>
                                            @if ($estudiante->pivot->tipo == 'Principal')
                                                <span class="badge badge-primary">Principal</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $estudiante->pivot->tipo }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($estudiante->pivot->autorizacion_recojo)
                                                <span class="badge badge-success">Sí</span>
                                            @else
                                                <span class="badge badge-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($estudiante->pivot->estado == 'Activo')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle"></i> No tiene estudiantes asignados.
                        </div>
                    @endif

                    {{-- BOTONES --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="{{ route('admin.tutores.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>

                            <button class="btn btn-success" data-toggle="modal"
                                data-target="#editTutorModal{{ $tutor->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- =============================================================
   ============= MODAL EDITAR TUTOR — OPCIÓN A ==================
   ============================================================= --}}
    <div class="modal fade" id="editTutorModal{{ $tutor->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form action="{{ route('admin.tutores.update', $tutor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Editar Tutor</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <h5>Datos Personales</h5>
                        <hr>

                        <div class="row">

                            <div class="col-md-4">
                                <label>DNI *</label>
                                <input type="text" name="dni" class="form-control"
                                    value="{{ $tutor->persona->dni }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Nombres *</label>
                                <input type="text" name="nombres" class="form-control"
                                    value="{{ $tutor->persona->nombres }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Apellidos *</label>
                                <input type="text" name="apellidos" class="form-control"
                                    value="{{ $tutor->persona->apellidos }}" required>
                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-4">
                                <label>Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento"
                                    value="{{ $tutor->persona->fecha_nacimiento }}" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label>Género *</label>
                                <select name="genero" class="form-control" required>
                                    <option value="M" {{ $tutor->persona->genero == 'M' ? 'selected' : '' }}>Masculino
                                    </option>
                                    <option value="F" {{ $tutor->persona->genero == 'F' ? 'selected' : '' }}>Femenino
                                    </option>
                                    <option value="Otro" {{ $tutor->persona->genero == 'Otro' ? 'selected' : '' }}>Otro
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Estado *</label>
                                <select name="estado" class="form-control" required>
                                    <option value="Activo" {{ $tutor->persona->estado == 'Activo' ? 'selected' : '' }}>
                                        Activo</option>
                                    <option value="Inactivo"
                                        {{ $tutor->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>

                            {{-- FOTO PERFIL --}}
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label>Foto de Perfil</label>
                                    <input type="file" name="foto_perfil" class="form-control">

                                    @if ($tutor->persona && $tutor->persona->foto_perfil)
                                        <p class="mt-2">Foto actual:</p>
                                        <img src="{{ asset('storage/' . $tutor->persona->foto_perfil) }}"
                                            class="img-thumbnail" style="max-width: 120px;">
                                    @else
                                        <p class="text-muted mt-2">Sin foto actual</p>
                                    @endif
                                </div>
                            </div>

                            <h5 class="mt-4">Datos del Tutor</h5>
                            <hr>

                            <div class="row">

                                <div class="col-md-6">
                                    <label>Código Tutor</label>
                                    <input type="text" name="codigo_tutor" value="{{ $tutor->codigo_tutor }}"
                                        class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label>Ocupación</label>
                                    <input type="text" name="ocupacion" value="{{ $tutor->ocupacion }}"
                                        class="form-control">
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

@stop
