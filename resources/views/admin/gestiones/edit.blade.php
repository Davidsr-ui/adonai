@extends('adminlte::page')

@section('content_header')
    <h1><b>Editar gestión educativa</b></h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Llene los datos del formulario</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/gestiones/'.$gestion->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="año">Año</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="number" class="form-control" name="año" 
                                        value="{{ old('año', $gestion->año) }}" 
                                        placeholder="2024" min="2000" max="2100">
                                </div>
                                @error('año')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre de la Gestión</label> <b>(*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="nombre" 
                                        value="{{ old('nombre', $gestion->nombre) }}" 
                                        placeholder="Ej: Gestión 2024" required>
                                </div>
                                @error('nombre')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha de Inicio</label> <b>(*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control" name="fecha_inicio" 
                                        value="{{ old('fecha_inicio', $gestion->fecha_inicio?->format('Y-m-d')) }}" required>
                                </div>
                                @error('fecha_inicio')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_fin">Fecha de Fin</label> <b>(*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                    </div>
                                    <input type="date" class="form-control" name="fecha_fin" 
                                        value="{{ old('fecha_fin', $gestion->fecha_fin?->format('Y-m-d')) }}" required>
                                </div>
                                @error('fecha_fin')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="estado">Estado</label> <b>(*)</b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                    </div>
                                    <select class="form-control" name="estado" required>
                                        <option value="">Seleccione un estado</option>
                                        <option value="Planificado" {{ old('estado', $gestion->estado) == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                                        <option value="Activo" {{ old('estado', $gestion->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="Finalizado" {{ old('estado', $gestion->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                    </select>
                                </div>
                                @error('estado')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{url('/admin/gestiones')}}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop