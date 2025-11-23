@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Mensaje</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="badge badge-{{ $mensaje->badge_prioridad }}">
                            <i class="fas {{ $mensaje->icono_prioridad }}"></i> {{ $mensaje->prioridad }}
                        </span>
                        {{ $mensaje->asunto }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('tutor.mensajeria') }}" class="btn btn-tool">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información del mensaje -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-user"></i> De:</strong>
                            {{ $mensaje->remitente->persona->nombres ?? 'N/A' }}
                            {{ $mensaje->remitente->persona->apellidos ?? '' }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar"></i> Fecha:</strong>
                            {{ $mensaje->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        @if($mensaje->estudiante)
                            <div class="col-md-6">
                                <strong><i class="fas fa-user-graduate"></i> Estudiante:</strong>
                                {{ $mensaje->estudiante->persona->nombres }}
                                {{ $mensaje->estudiante->persona->apellidos }}
                            </div>
                        @endif
                        @if($mensaje->destinatarios->first())
                            <div class="col-md-6">
                                <strong><i class="fas fa-user-check"></i> Para:</strong>
                                {{ $mensaje->destinatarios->first()->destinatario->persona->nombres ?? 'N/A' }}
                                {{ $mensaje->destinatarios->first()->destinatario->persona->apellidos ?? '' }}
                            </div>
                        @endif
                    </div>

                    <hr>

                    <!-- Contenido del mensaje -->
                    <div class="mb-3">
                        <strong><i class="fas fa-envelope-open-text"></i> Mensaje:</strong>
                        <div class="mt-2 p-3" style="background-color: #f8f9fa; border-radius: 5px;">
                            {!! nl2br(e($mensaje->contenido)) !!}
                        </div>
                    </div>

                    <!-- Archivos adjuntos -->
                    @if($mensaje->tiene_archivos)
                        <div class="mb-3">
                            <strong><i class="fas fa-paperclip"></i> Archivos adjuntos ({{ $mensaje->cantidad_archivos }}):</strong>
                            <ul class="list-group mt-2">
                                @foreach($mensaje->archivos as $archivo)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-file"></i> {{ $archivo['nombre'] }}
                                            <small class="text-muted">({{ number_format($archivo['tamaño'] / 1024, 2) }} KB)</small>
                                        </span>
                                        <a href="{{ asset('storage/' . $archivo['path']) }}" 
                                           class="btn btn-sm btn-primary" 
                                           download>
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <hr>

                    <!-- Botones de acción -->
                    <div class="row">
                        <div class="col-md-12">
                            @if($mensaje->remitente_id != Auth::id())
                                <button class="btn btn-primary" data-toggle="modal" data-target="#responderModal">
                                    <i class="fas fa-reply"></i> Responder
                                </button>
                            @endif
                            <a href="{{ route('tutor.mensajeria') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Responder -->
    <div class="modal fade" id="responderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tutor.mensajeria.responder', $mensaje->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title"><i class="fas fa-reply"></i> Responder Mensaje</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Respondiendo a:</strong> {{ $mensaje->asunto }}
                        </div>

                        <div class="form-group">
                            <label>Mensaje <b class="text-danger">*</b></label>
                            <textarea name="contenido" class="form-control" rows="5" required placeholder="Escriba su respuesta aquí..."></textarea>
                        </div>

                        <div class="form-group">
                            <label>Archivos Adjuntos (Opcional)</label>
                            <input type="file" name="archivos[]" class="form-control-file" multiple>
                            <small class="form-text text-muted">Máximo 10MB por archivo</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Enviar Respuesta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop