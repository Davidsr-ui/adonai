@extends('adminlte::page')

@section('content_header')
    <h1><b>Edición de turno</b></h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Edite los datos del turno</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/turnos/' . $turno->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nombre">Nombre del turno <b>(*)</b></label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                            <input type="text" id="nombre" name="nombre" class="form-control"
                                value="{{ old('nombre', $turno->nombre) }}" placeholder="Ej: Mañana, Tarde, Noche" required>
                        </div>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="hora_inicio">Hora inicio <b>(*)</b></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control"
                                    value="{{ old('hora_inicio', substr($turno->hora_inicio, 0, 5)) }}" required>
                            </div>
                            @error('hora_inicio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="hora_fin">Hora fin <b>(*)</b></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="time" id="hora_fin" name="hora_fin" class="form-control"
                                    value="{{ old('hora_fin', substr($turno->hora_fin, 0, 5)) }}" required>
                            </div>
                            @error('hora_fin')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                            placeholder="Descripción opcional del turno">{{ old('descripcion', $turno->descripcion) }}</textarea>
                        @error('descripcion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado <b>(*)</b></label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="activo" {{ old('estado', $turno->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('estado', $turno->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr>

                    <div class="form-group">
                        <a href="{{ url('/admin/turnos') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Estilos adicionales --}}
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop