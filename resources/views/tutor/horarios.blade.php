@extends('adminlte::page')

@section('content_header')
    <h1><b>Horario de Clases</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Horario Semanal</h3>
                    <div class="card-tools">
                        @if($tutor->estudiantes->count() > 1)
                            <select id="estudianteSelect" class="form-control form-control-sm" onchange="cambiarEstudiante()">
                                @foreach($tutor->estudiantes as $est)
                                    <option value="{{ $est->id }}" {{ $estudiante && $estudiante->id == $est->id ? 'selected' : '' }}>
                                        {{ $est->persona->apellidos }} {{ $est->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($estudiante)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-user"></i> Estudiante:</strong> {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}<br>
                            <strong><i class="fas fa-graduation-cap"></i> Grado:</strong> {{ $estudiante->grado->nombre ?? 'N/A' }}<br>
                            <strong><i class="fas fa-layer-group"></i> Nivel:</strong> {{ $estudiante->grado->nivel->nombre ?? 'N/A' }}
                        </div>

                        @if(count($horarioSemanal) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 120px;">Hora</th>
                                            <th>Lunes</th>
                                            <th>Martes</th>
                                            <th>Miércoles</th>
                                            <th>Jueves</th>
                                            <th>Viernes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($horarioSemanal as $hora => $dias)
                                            <tr>
                                                <td><strong>{{ $hora }}</strong></td>
                                                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                                    <td>
                                                        @if(isset($dias[$dia]))
                                                            <div class="p-2" style="background-color: #e3f2fd; border-radius: 5px;">
                                                                <strong style="color: #1976d2;">{{ $dias[$dia]['curso'] }}</strong><br>
                                                                <small><i class="fas fa-chalkboard-teacher"></i> {{ $dias[$dia]['docente'] }}</small><br>
                                                                <small><i class="fas fa-door-open"></i> {{ $dias[$dia]['aula'] }}</small>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-muted">
                                                                <small>-</small>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <button onclick="imprimirHorario()" class="btn btn-primary">
                                    <i class="fas fa-print"></i> Imprimir Horario
                                </button>
                                <a href="{{ route('tutor.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay horario registrado para este grado.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No tiene estudiantes asignados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .table td {
        vertical-align: middle;
    }
    @media print {
        .card-tools, .btn, .alert-info { display: none; }
    }
</style>
@stop

@section('js')
<script>
    function cambiarEstudiante() {
        const estudianteId = document.getElementById('estudianteSelect').value;
        window.location.href = '{{ route("tutor.horarios") }}?estudiante_id=' + estudianteId;
    }

    function imprimirHorario() {
        window.print();
    }
</script>
@stop