@extends('adminlte::page')

@section('content_header')
    <h1><b>Mensajería con Docentes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <!-- Columna principal -->
        <div class="col-md-12">
            <!-- Botón nuevo mensaje -->
            <div class="mb-3">
                <button class="btn btn-primary" data-toggle="modal" data-target="#nuevoMensajeModal">
                    <i class="fas fa-plus"></i> Nuevo Mensaje
                </button>
                <span class="ml-3">
                    <span class="badge badge-danger" style="font-size: 14px;">
                        {{ $mensajesNoLeidos }} No leídos
                    </span>
                </span>
            </div>

            <!-- Tabs -->
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#recibidos" role="tab">
                                <i class="fas fa-inbox"></i> Recibidos ({{ $mensajesRecibidos->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#enviados" role="tab">
                                <i class="fas fa-paper-plane"></i> Enviados ({{ $mensajesEnviados->count() }})
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- MENSAJES RECIBIDOS -->
                        <div class="tab-pane fade show active" id="recibidos" role="tabpanel">
                            @if($mensajesRecibidos->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;"></th>
                                                <th>De</th>
                                                <th>Estudiante</th>
                                                <th>Asunto</th>
                                                <th>Fecha</th>
                                                <th style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mensajesRecibidos as $mensaje)
                                                @php
                                                    $leido = $mensaje->destinatarios->where('destinatario_id', Auth::id())->first()->leido ?? false;
                                                @endphp
                                                <tr style="{{ !$leido ? 'font-weight: bold; background-color: #f0f8ff;' : '' }}">
                                                    <td>
                                                        @if(!$leido)
                                                            <span class="badge badge-primary">Nuevo</span>
                                                        @endif
                                                        <span class="badge badge-{{ $mensaje->badge_prioridad }}">
                                                            <i class="fas {{ $mensaje->icono_prioridad }}"></i>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $mensaje->remitente->persona->nombres ?? 'N/A' }}
                                                        {{ $mensaje->remitente->persona->apellidos ?? '' }}
                                                    </td>
                                                    <td>
                                                        @if($mensaje->estudiante)
                                                            {{ $mensaje->estudiante->persona->nombres }}
                                                            {{ $mensaje->estudiante->persona->apellidos }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ Str::limit($mensaje->asunto, 40) }}</td>
                                                    <td>{{ $mensaje->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('tutor.mensajeria.ver', $mensaje->id) }}" 
                                                           class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No hay mensajes recibidos.
                                </div>
                            @endif
                        </div>

                        <!-- MENSAJES ENVIADOS -->
                        <div class="tab-pane fade" id="enviados" role="tabpanel">
                            @if($mensajesEnviados->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;"></th>
                                                <th>Para</th>
                                                <th>Estudiante</th>
                                                <th>Asunto</th>
                                                <th>Fecha</th>
                                                <th style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mensajesEnviados as $mensaje)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-{{ $mensaje->badge_prioridad }}">
                                                            <i class="fas {{ $mensaje->icono_prioridad }}"></i>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($mensaje->destinatarios->first())
                                                            {{ $mensaje->destinatarios->first()->destinatario->persona->nombres ?? 'N/A' }}
                                                            {{ $mensaje->destinatarios->first()->destinatario->persona->apellidos ?? '' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($mensaje->estudiante)
                                                            {{ $mensaje->estudiante->persona->nombres }}
                                                            {{ $mensaje->estudiante->persona->apellidos }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ Str::limit($mensaje->asunto, 40) }}</td>
                                                    <td>{{ $mensaje->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('tutor.mensajeria.ver', $mensaje->id) }}" 
                                                           class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No hay mensajes enviados.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Mensaje -->
    <div class="modal fade" id="nuevoMensajeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('tutor.mensajeria.enviar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title"><i class="fas fa-envelope"></i> Nuevo Mensaje</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Estudiante <b class="text-danger">*</b></label>
                            <select name="estudiante_id" id="estudiante_id" class="form-control" required onchange="cargarDocentes()">
                                <option value="">Seleccione un estudiante</option>
                                @foreach($tutor->estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}">
                                        {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Docente <b class="text-danger">*</b></label>
                            <select name="destinatario_user_id" id="docente_id" class="form-control" required>
                                <option value="">Primero seleccione un estudiante</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Prioridad</label>
                            <select name="prioridad" class="form-control">
                                <option value="Normal">Normal</option>
                                <option value="Alta">Alta</option>
                                <option value="Urgente">Urgente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Asunto <b class="text-danger">*</b></label>
                            <input type="text" name="asunto" class="form-control" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label>Mensaje <b class="text-danger">*</b></label>
                            <textarea name="contenido" class="form-control" rows="5" required></textarea>
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
                            <i class="fas fa-paper-plane"></i> Enviar Mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    const docentesPorEstudiante = @json($docentes->groupBy('estudiante_id'));

    function cargarDocentes() {
        const estudianteId = document.getElementById('estudiante_id').value;
        const docenteSelect = document.getElementById('docente_id');
        
        docenteSelect.innerHTML = '<option value="">Seleccione un docente</option>';
        
        if (estudianteId && docentesPorEstudiante[estudianteId]) {
            docentesPorEstudiante[estudianteId].forEach(function(docente) {
                const option = document.createElement('option');
                option.value = docente.user_id;
                option.textContent = docente.nombres + ' ' + docente.apellidos + ' (' + docente.curso + ')';
                docenteSelect.appendChild(option);
            });
        }
    }
</script>
@stop