@extends('adminlte::page')

@section('title', 'Mis Cursos')

@section('content_header')
    <h1><b>Mis Cursos Asignados</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-book"></i> Listado de Mis Cursos</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->persona && Auth::user()->persona->docente)
                        @php
                            // ✅ CORRECTO: Usar DocenteCurso para obtener curso + grado
                            $docente = Auth::user()->persona->docente;
                            $asignaciones = \App\Models\DocenteCurso::where('docente_id', $docente->id)
                                ->with(['curso', 'grado', 'gestion'])
                                ->get();
                        @endphp
                        
                        @if($asignaciones->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Curso</th>
                                            <th>Grado</th>
                                            <th>Gestión</th>
                                            <th>Créditos</th>
                                            <th>Tutor Aula</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asignaciones as $index => $asignacion)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $asignacion->curso->nombre }}</strong>
                                                    @if($asignacion->curso->descripcion)
                                                        <br>
                                                        <small class="text-muted">{{ $asignacion->curso->descripcion }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $asignacion->grado->nombre_completo }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ $asignacion->gestion->nombre }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-primary">
                                                        {{ $asignacion->curso->creditos }} créditos
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($asignacion->es_tutor_aula)
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-star"></i> Sí
                                                        </span>
                                                    @else
                                                        <span class="text-muted">No</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('docente.estudiantes.index', ['curso_id' => $asignacion->curso_id, 'grado_id' => $asignacion->grado_id]) }}" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Ver estudiantes">
                                                        <i class="fas fa-users"></i>
                                                    </a>
                                                    <a href="{{ route('docente.asistencias.index', ['curso_id' => $asignacion->curso_id]) }}" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Registrar asistencias">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </a>
                                                    <a href="{{ route('docente.notas.index', ['curso_id' => $asignacion->curso_id]) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Registrar notas">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No tienes cursos asignados en este momento.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tu perfil de docente no está completo. Por favor contacta al administrador.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            @if(session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif
        });
    </script>
@stop