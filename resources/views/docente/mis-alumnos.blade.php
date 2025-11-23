@extends('adminlte::page')

@section('content_header')
    <h1><b>Mis Alumnos</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Lista de Estudiantes Asignados</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary" style="font-size: 14px;">
                            Total: {{ $estudiantes->count() }} estudiantes
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($estudiantes->count() > 0)
                        <table id="tablaAlumnos" class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Código</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>Grado</th>
                                    <th>Nivel</th>
                                    <th>Condición</th>
                                    <th style="text-align: center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $contador = 1; @endphp
                                @foreach($estudiantes as $estudiante)
                                    <tr>
                                        <td style="text-align: center">{{ $contador++ }}</td>
                                        <td>{{ $estudiante->codigo_estudiante ?? '-' }}</td>
                                        <td>
                                            @if($estudiante->persona)
                                                {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $estudiante->grado->nombre ?? 'N/A' }}</td>
                                        <td>{{ $estudiante->grado->nivel->nombre ?? 'N/A' }}</td>
                                        <td>
                                            @if($estudiante->condicion == 'Regular')
                                                <span class="badge badge-success">Regular</span>
                                            @else
                                                <span class="badge badge-warning">{{ $estudiante->condicion }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('docente.alumno.ficha', $estudiante->id) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Ver Ficha">
                                                    <i class="fas fa-id-card"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm" 
                                                        onclick="enviarMensaje({{ $estudiante->id }})"
                                                        title="Enviar Mensaje">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No tiene estudiantes asignados actualmente.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function() {
        $("#tablaAlumnos").DataTable({
            "pageLength": 10,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Estudiantes",
                "infoEmpty": "Mostrando 0 a 0 de 0 Estudiantes",
                "infoFiltered": "(Filtrado de _MAX_ total Estudiantes)",
                "lengthMenu": "Mostrar _MENU_ Estudiantes",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });

    function enviarMensaje(estudianteId) {
        window.location.href = '{{ route("docente.mensajeria") }}?estudiante_id=' + estudianteId;
    }
</script>
@stop