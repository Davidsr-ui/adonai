@extends('adminlte::page')

@section('title', 'Detalle del Docente')

@section('content_header')
    <h1><b>Detalle del Docente</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">

                <div class="card-header">
                    <h3 class="card-title">Información Personal y Laboral</h3>
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
                                    <p>{{ $docente->persona->dni }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label>Nombres</label>
                                    <p>{{ $docente->persona->nombres }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label>Apellidos</label>
                                    <p>{{ $docente->persona->apellidos }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Fecha de Nacimiento</label>
                                    <p>{{ \Carbon\Carbon::parse($docente->persona->fecha_nacimiento)->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label>Edad</label>
                                    <p>{{ \Carbon\Carbon::parse($docente->persona->fecha_nacimiento)->age }} años</p>
                                </div>
                                <div class="col-md-4">
                                    <label>Género</label>
                                    <p>
                                        @if ($docente->persona->genero == 'M')
                                            Masculino
                                        @elseif($docente->persona->genero == 'F')
                                            Femenino
                                        @else
                                            Otro
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label>Dirección</label>
                                    <p>{{ $docente->persona->direccion ?? 'No especificada' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Teléfono</label>
                                    <p>{{ $docente->persona->telefono ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label>Teléfono Emergencia</label>
                                    <p>{{ $docente->persona->telefono_emergencia ?? 'No especificado' }}</p>
                                </div>
                            </div>

                            {{-- ===================== DATOS LABORALES ===================== --}}
                            <h5 class="mt-3">Datos Laborales</h5>
                            <hr>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Código Docente</label>
                                    <p><strong>{{ $docente->codigo_docente }}</strong></p>
                                </div>
                                <div class="col-md-3">
                                    <label>Especialidad</label>
                                    <p>{{ $docente->especialidad ?? '-' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Contratación</label>
                                    <p>{{ \Carbon\Carbon::parse($docente->fecha_contratacion)->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label>Años de Servicio</label>
                                    <p>{{ \Carbon\Carbon::parse($docente->fecha_contratacion)->diffInYears() }} años</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Tipo Contrato</label>
                                    <p>
                                        @if ($docente->tipo_contrato == 'Nombrado')
                                            <span class="badge badge-success">Nombrado</span>
                                        @elseif($docente->tipo_contrato == 'Contratado')
                                            <span class="badge badge-info">Contratado</span>
                                        @else
                                            <span class="badge badge-warning">{{ $docente->tipo_contrato }}</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <label>Estado</label>
                                    <p>
                                        @if ($docente->persona->estado == 'Activo')
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
                                @if ($docente->persona->foto_perfil)
                                    <img src="{{ asset('storage/' . $docente->persona->foto_perfil) }}" class="img-thumbnail"
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

                    {{-- BOTONES --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>

                            {{-- BOTÓN EDITAR (ABRE MODAL) --}}
                            <button class="btn btn-success" data-toggle="modal"
                                data-target="#editDocenteModal{{ $docente->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- =============================================================
   ============ MODAL EDITAR (COPIADO DEL INDEX) =================
   ============================================================= --}}
    <div class="modal fade" id="editDocenteModal{{ $docente->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form action="{{ route('admin.docentes.update', $docente->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Editar Docente</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <h5>Datos Personales</h5>
                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <label>DNI *</label>
                                <input type="text" name="dni" class="form-control"
                                    value="{{ $docente->persona->dni }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Nombres *</label>
                                <input type="text" name="nombres" class="form-control"
                                    value="{{ $docente->persona->nombres }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Apellidos *</label>
                                <input type="text" name="apellidos" class="form-control"
                                    value="{{ $docente->persona->apellidos }}" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label>Fecha Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento"
                                    value="{{ $docente->persona->fecha_nacimiento }}" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label>Género *</label>
                                <select name="genero" class="form-control" required>
                                    <option value="M" {{ $docente->persona->genero == 'M' ? 'selected' : '' }}>
                                        Masculino</option>
                                    <option value="F" {{ $docente->persona->genero == 'F' ? 'selected' : '' }}>
                                        Femenino</option>
                                    <option value="Otro" {{ $docente->persona->genero == 'Otro' ? 'selected' : '' }}>Otro
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Estado *</label>
                                <select name="estado" class="form-control" required>
                                    <option value="Activo" {{ $docente->persona->estado == 'Activo' ? 'selected' : '' }}>
                                        Activo</option>
                                    <option value="Inactivo"
                                        {{ $docente->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Dirección</label>
                                <input type="text" name="direccion" class="form-control"
                                    value="{{ $docente->persona->direccion }}">
                            </div>

                            <div class="col-md-3">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" class="form-control"
                                    value="{{ $docente->persona->telefono }}">
                            </div>

                            <div class="col-md-3">
                                <label>Teléfono Emergencia</label>
                                <input type="text" name="telefono_emergencia" class="form-control"
                                    value="{{ $docente->persona->telefono_emergencia }}">
                            </div>
                        </div>

                        {{-- FOTO PERFIL --}}
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Foto de Perfil</label>
                                <input type="file" name="foto_perfil" class="form-control">

                                @if ($docente->persona->foto_perfil)
                                    <p class="mt-2">Foto actual:</p>
                                    <img src="{{ asset('storage/' . $docente->persona->foto_perfil) }}"
                                        class="img-thumbnail" style="max-width: 120px;">
                                @endif
                            </div>
                        </div>

                        <h5 class="mt-3">Datos Laborales</h5>
                        <hr>

                        <div class="row">
                            <div class="col-md-3"> 
                                <label>Código Docente *</label>
                                <input type="text" name="codigo_docente" value="{{ $docente->codigo_docente }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Especialidad</label>
                                <input type="text" name="especialidad" value="{{ $docente->especialidad }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label>Fecha Contratación *</label>
                                <input type="date" name="fecha_contratacion"
                                    value="{{ $docente->fecha_contratacion }}" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label>Tipo Contrato *</label>
                                <select name="tipo_contrato" class="form-control" required>
                                    <option value="Nombrado"
                                        {{ $docente->tipo_contrato == 'Nombrado' ? 'selected' : '' }}>Nombrado</option>
                                    <option value="Contratado"
                                        {{ $docente->tipo_contrato == 'Contratado' ? 'selected' : '' }}>Contratado</option>
                                    <option value="Temporal"
                                        {{ $docente->tipo_contrato == 'Temporal' ? 'selected' : '' }}>Temporal</option>
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


@stop
