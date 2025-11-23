@extends('adminlte::page')

@section('title', 'Mis Estudiantes')

@section('content_header')
    <h1><b>Mis Estudiantes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-users"></i> Listado de Estudiantes</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Aquí podrás ver todos los estudiantes de tus cursos asignados.
                    </div>

                    @if(Auth::user()->persona && Auth::user()->persona->docente)
                        @php
                            // ✅ Obtener docente
                            $docente = Auth::user()->persona->docente;
                            
                            // ✅ Obtener asignaciones del docente (cursos con grados)
                            $asignaciones = \App\Models\DocenteCurso::where('docente_id', $docente->id)
                                ->with(['curso', 'grado'])
                                ->get();
                            
                            // ✅ Obtener IDs de cursos únicos
                            $cursosIds = $asignaciones->pluck('curso_id')->unique();
                            
                            // ✅ SIMPLIFICADO: Buscar SOLO por curso_id, NO por grado_id
                            // Esto encuentra todos los estudiantes matriculados en los cursos del docente
                            $matriculas = \App\Models\Matricula::whereIn('curso_id', $cursosIds)
                                ->where('estado', 'Matriculado')  // ← CAMBIADO: de 'Activa' a 'Matriculado'
                                ->with(['estudiante.persona', 'estudiante.grado', 'curso', 'grado'])
                                ->get();
                            
                            // Crear colección de estudiantes únicos
                            $estudiantes = collect();
                            foreach($matriculas as $matricula) {
                                if($matricula->estudiante && $matricula->estudiante->persona) {
                                    // Buscar la asignación que corresponde a este curso
                                    $asignacionDelCurso = $asignaciones->where('curso_id', $matricula->curso_id)->first();
                                    
                                    $estudiantes->push([
                                        'estudiante' => $matricula->estudiante,
                                        'curso' => $matricula->curso,
                                        'grado' => $matricula->grado ?? $matricula->estudiante->grado,
                                        'matricula' => $matricula
                                    ]);
                                }
                            }
                            
                            // Eliminar duplicados por ID de estudiante
                            $estudiantes = $estudiantes->unique(function($item) {
                                return $item['estudiante']->id;
                            });
                        @endphp
                        
                        @if($estudiantes->count() > 0)
                            <!-- Estadísticas -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $estudiantes->count() }}</h3>
                                            <p>Total Estudiantes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $asignaciones->count() }}</h3>
                                            <p>Asignaciones</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $cursosIds->count() }}</h3>
                                            <p>Cursos</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            @php
                                                $gradosUnicos = $estudiantes->pluck('estudiante.grado_id')->unique()->count();
                                            @endphp
                                            <h3>{{ $gradosUnicos }}</h3>
                                            <p>Grados</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="tablaEstudiantes">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Estudiante</th>
                                            <th>DNI</th>
                                            <th>Curso</th>
                                            <th>Grado</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($estudiantes as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $item['estudiante']->codigo_estudiante }}</strong>
                                                </td>
                                                <td>
                                                    <i class="fas fa-user-graduate text-primary"></i>
                                                    <strong>{{ $item['estudiante']->persona->apellidos }}</strong>, 
                                                    {{ $item['estudiante']->persona->nombres }}
                                                </td>
                                                <td>{{ $item['estudiante']->persona->dni }}</td>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        {{ $item['curso']->nombre }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $item['grado']->nombre_completo ?? 'Sin grado' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $item['estudiante']->persona->estado == 'Activo' ? 'success' : 'danger' }}">
                                                        {{ $item['estudiante']->persona->estado }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-info" title="Ver información">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <a href="{{ route('docente.asistencias.index', ['estudiante_id' => $item['estudiante']->id]) }}" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="Ver asistencias">
                                                            <i class="fas fa-clipboard-check"></i>
                                                        </a>
                                                        <a href="{{ route('docente.notas.index', ['estudiante_id' => $item['estudiante']->id]) }}" 
                                                           class="btn btn-sm btn-success" 
                                                           title="Ver notas">
                                                            <i class="fas fa-star"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>No tienes estudiantes asignados en tus cursos.</strong>
                                <hr>
                                <p><strong>Debug Info:</strong></p>
                                <ul>
                                    <li>Asignaciones encontradas: {{ $asignaciones->count() }}</li>
                                    <li>Cursos IDs: {{ $cursosIds->implode(', ') }}</li>
                                    <li>Matrículas encontradas: {{ isset($matriculas) ? $matriculas->count() : 0 }}</li>
                                </ul>
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

    @if(isset($estudiantes) && $estudiantes->count() > 0)
    <!-- Agrupación por Curso -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-layer-group"></i> Estudiantes Agrupados por Curso</h3>
                </div>
                <div class="card-body">
                    @php
                        $estudiantesPorCurso = $estudiantes->groupBy('curso.id');
                    @endphp
                    @foreach($estudiantesPorCurso as $cursoId => $estudiantesCurso)
                        @php
                            $curso = $estudiantesCurso->first()['curso'];
                        @endphp
                        <div class="callout callout-success">
                            <h5>
                                <i class="fas fa-book"></i>
                                <strong>{{ $curso->nombre }}</strong>
                                <span class="badge badge-primary">{{ $estudiantesCurso->count() }} estudiantes</span>
                            </h5>
                            <div class="row">
                                @foreach($estudiantesCurso as $est)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="small-box bg-light">
                                            <div class="inner" style="padding: 10px;">
                                                <p style="margin-bottom: 5px;">
                                                    <strong>{{ $est['estudiante']->persona->apellidos }}, {{ $est['estudiante']->persona->nombres }}</strong>
                                                </p>
                                                <small>
                                                    <i class="fas fa-id-card"></i> {{ $est['estudiante']->codigo_estudiante }}
                                                    | 
                                                    <i class="fas fa-users"></i> {{ $est['grado']->nombre_completo ?? 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#tablaEstudiantes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                },
                responsive: true,
                autoWidth: false,
                order: [[2, 'asc']] // Ordenar por nombre de estudiante
            });

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