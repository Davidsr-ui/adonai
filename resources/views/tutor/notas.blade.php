@extends('adminlte::page')

@section('title', 'Notas de Mis Estudiantes')

@section('content_header')
    <h1><b>Notas de Mis Estudiantes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-star"></i> Calificaciones de Mis Estudiantes</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->persona && Auth::user()->persona->tutor)
                        @php
                            // Obtener los estudiantes del tutor
                            $estudiantes = Auth::user()->persona->tutor->estudiantes;
                            $estudiantesIds = $estudiantes->pluck('id');
                            
                            // Obtener las notas de esos estudiantes que están visibles para tutores
                            $notas = \App\Models\Nota::whereHas('matricula', function($q) use ($estudiantesIds) {
                                    $q->whereIn('estudiante_id', $estudiantesIds);
                                })
                                ->where('visible_tutor', true)
                                ->with(['matricula.estudiante.persona', 'matricula.curso', 'periodo', 'docente.persona'])
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp
                        
                        @if($notas->count() > 0)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Puedes ver las notas que los docentes han publicado para tutores.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm" id="tablaNotas">
                                    <thead class="bg-dark">
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Curso</th>
                                            <th>Periodo</th>
                                            <th>Tipo</th>
                                            <th class="text-center">N. Práctica</th>
                                            <th class="text-center">N. Teoría</th>
                                            <th class="text-center">N. Final</th>
                                            <th class="text-center">Estado</th>
                                            <th>Docente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notas as $nota)
                                            <tr>
                                                <td>
                                                    <strong>{{ $nota->matricula->estudiante->persona->apellidos }}, 
                                                    {{ $nota->matricula->estudiante->persona->nombres }}</strong>
                                                </td>
                                                <td>{{ $nota->matricula->curso->nombre }}</td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ $nota->periodo->nombre }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $nota->tipo_evaluacion_badge }}">
                                                        {{ $nota->tipo_evaluacion }}
                                                    </span>
                                                </td>
                                                <td class="text-center">{{ $nota->nota_practica ?? '-' }}</td>
                                                <td class="text-center">{{ $nota->nota_teoria ?? '-' }}</td>
                                                <td class="text-center">
                                                    <strong class="badge badge-{{ $nota->estado_nota_badge }} badge-lg">
                                                        {{ $nota->nota_final }}
                                                    </strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $nota->estado_nota_badge }}">
                                                        {{ $nota->estado_nota_texto }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($nota->docente)
                                                        {{ $nota->docente->persona->apellidos }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Estadísticas -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $notas->where('nota_final', '>=', 14)->count() }}</h3>
                                            <p>Aprobados</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $notas->where('nota_final', '<', 14)->count() }}</h3>
                                            <p>Desaprobados</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ number_format($notas->avg('nota_final'), 2) }}</h3>
                                            <p>Promedio General</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calculator"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $notas->count() }}</h3>
                                            <p>Total Notas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay notas publicadas para tus estudiantes en este momento.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tu perfil de tutor no está completo. Por favor contacta al administrador.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
            $('#tablaNotas').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                },
                responsive: true,
                autoWidth: false,
                order: [[0, 'asc'], [2, 'desc']]
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