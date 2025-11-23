@extends('adminlte::page')
@section('content')
    <div class="row">
        <h1 class="m-2">Horarios</h1>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Horarios registrados</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createHorarioModal">
                            <i class="fa fa-plus"></i> Crear nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Gestión</th>
                                <th>Curso</th>
                                <th>Grado</th>
                                <th>Docente</th>
                                <th>Día</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Aula</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = 1;
                            @endphp
                            @foreach ($horarios as $horario)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td>{{ $horario->gestion->nombre }}</td>
                                    <td>{{ $horario->curso->nombre }}</td>
                                    <td>{{ $horario->grado->nombre }}</td>
                                    <td>{{ $horario->docente ? $horario->docente->persona->apellidos . ' ' . $horario->docente->persona->nombres : 'Sin asignar' }}</td>
                                    <td>{{ $horario->dia_semana }}</td>
                                    <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                                    <td>{{ $horario->aula ?? '-' }}</td>
                                    <td style="text-align: center">
                                        {{-- ✅ BOTONES MEJORADOS --}}
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.horarios.show', $horario->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    data-toggle="modal"
                                                    data-target="#editHorarioModal{{ $horario->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminacionHorario({{ $horario->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        {{-- Formulario oculto para eliminación --}}
                                        <form id="deleteFormHorario{{ $horario->id }}" 
                                              action="{{ route('admin.horarios.destroy', $horario->id) }}"
                                              method="POST" 
                                              style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal CREATE-->
    <div class="modal fade" id="createHorarioModal" tabindex="-1" role="dialog" aria-labelledby="createHorarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.horarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createHorarioModalLabel">Crear Nuevo Horario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    <li>{{ $error }}</li>
                                </div>
                            @endforeach
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Gestión <b>*</b></label>
                                    <select name="gestion_id" class="form-control" required>
                                        <option value="">Seleccione una gestión...</option>
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}"
                                                {{ old('gestion_id') == $gestion->id ? 'selected' : '' }}>
                                                {{ $gestion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gestion_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Curso <b>*</b></label>
                                    <select name="curso_id" class="form-control" required>
                                        <option value="">Seleccione un curso...</option>
                                        @foreach ($cursos as $curso)
                                            <option value="{{ $curso->id }}"
                                                {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                                {{ $curso->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('curso_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Grado <b>*</b></label>
                                    <select name="grado_id" class="form-control" required>
                                        <option value="">Seleccione un grado...</option>
                                        @foreach ($grados as $grado)
                                            <option value="{{ $grado->id }}"
                                                {{ old('grado_id') == $grado->id ? 'selected' : '' }}>
                                                {{ $grado->nombre }} {{ $grado->seccion }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grado_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Docente</label>
                                    <select name="docente_id" class="form-control">
                                        <option value="">Sin asignar...</option>
                                        @foreach ($docentes as $docente)
                                            <option value="{{ $docente->id }}"
                                                {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                                {{ $docente->persona->apellidos }} {{ $docente->persona->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('docente_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Día <b>*</b></label>
                                    <select name="dia_semana" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Lunes" {{ old('dia_semana') == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                                        <option value="Martes" {{ old('dia_semana') == 'Martes' ? 'selected' : '' }}>Martes</option>
                                        <option value="Miércoles" {{ old('dia_semana') == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                        <option value="Jueves" {{ old('dia_semana') == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                                        <option value="Viernes" {{ old('dia_semana') == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                                        <option value="Sábado" {{ old('dia_semana') == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                                    </select>
                                    @error('dia_semana')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Hora Inicio <b>*</b></label>
                                    <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}"
                                        class="form-control" required>
                                    @error('hora_inicio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Hora Fin <b>*</b></label>
                                    <input type="time" name="hora_fin" value="{{ old('hora_fin') }}"
                                        class="form-control" required>
                                    @error('hora_fin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Aula</label>
                                    <input type="text" name="aula" value="{{ old('aula') }}"
                                        class="form-control" maxlength="20" placeholder="Ej: A-101">
                                    @error('aula')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal EDIT-->
    @foreach ($horarios as $horario)
        <div class="modal fade" id="editHorarioModal{{ $horario->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editHorarioModalLabel{{ $horario->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.horarios.update', $horario->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editHorarioModalLabel{{ $horario->id }}">Editar Horario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if (session('modal_id') == $horario->id && $errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">
                                        <li>{{ $error }}</li>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Gestión <b>*</b></label>
                                        <select name="gestion_id" class="form-control" required>
                                            <option value="">Seleccione una gestión...</option>
                                            @foreach ($gestiones as $gestion)
                                                <option value="{{ $gestion->id }}"
                                                    {{ $horario->gestion_id == $gestion->id ? 'selected' : '' }}>
                                                    {{ $gestion->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Curso <b>*</b></label>
                                        <select name="curso_id" class="form-control" required>
                                            <option value="">Seleccione un curso...</option>
                                            @foreach ($cursos as $curso)
                                                <option value="{{ $curso->id }}"
                                                    {{ $horario->curso_id == $curso->id ? 'selected' : '' }}>
                                                    {{ $curso->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Grado <b>*</b></label>
                                        <select name="grado_id" class="form-control" required>
                                            <option value="">Seleccione un grado...</option>
                                            @foreach ($grados as $grado)
                                                <option value="{{ $grado->id }}"
                                                    {{ $horario->grado_id == $grado->id ? 'selected' : '' }}>
                                                    {{ $grado->nombre }} {{ $grado->seccion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Docente</label>
                                        <select name="docente_id" class="form-control">
                                            <option value="">Sin asignar...</option>
                                            @foreach ($docentes as $docente)
                                                <option value="{{ $docente->id }}"
                                                    {{ $horario->docente_id == $docente->id ? 'selected' : '' }}>
                                                    {{ $docente->persona->apellidos }} {{ $docente->persona->nombres }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Día <b>*</b></label>
                                        <select name="dia_semana" class="form-control" required>
                                            <option value="">Seleccione...</option>
                                            <option value="Lunes" {{ $horario->dia_semana == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                                            <option value="Martes" {{ $horario->dia_semana == 'Martes' ? 'selected' : '' }}>Martes</option>
                                            <option value="Miércoles" {{ $horario->dia_semana == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                            <option value="Jueves" {{ $horario->dia_semana == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                                            <option value="Viernes" {{ $horario->dia_semana == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                                            <option value="Sábado" {{ $horario->dia_semana == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Hora Inicio <b>*</b></label>
                                        {{-- ✅ CORREGIDO: Formatear hora sin segundos --}}
                                        <input type="time" name="hora_inicio" 
                                               value="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Hora Fin <b>*</b></label>
                                        {{-- ✅ CORREGIDO: Formatear hora sin segundos --}}
                                        <input type="time" name="hora_fin" 
                                               value="{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}"
                                               class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Aula</label>
                                        <input type="text" name="aula" value="{{ $horario->aula }}"
                                            class="form-control" maxlength="20">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if (session('modal_id') == $horario->id && $errors->any())
            <script>
                $(document).ready(function() {
                    $('#editHorarioModal{{ $horario->id }}').modal('show');
                });
            </script>
        @endif
    @endforeach
@endsection

@section('js')
    {{-- ✅ FUNCIÓN ÚNICA DE ELIMINACIÓN --}}
    <script>
        function confirmarEliminacionHorario(id) {
            Swal.fire({
                title: "¿Seguro que quiere eliminar este registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Eliminar!",
                confirmButtonColor: '#a5161d',
                cancelButtonText: "Cancelar",
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteFormHorario' + id).submit();
                }
            });
        }
    </script>

    {{-- Mensajes de éxito/error --}}
    @if(session('mensaje'))
    <script>
        Swal.fire({
            icon: '{{ session('icono') }}',
            title: '{{ session('mensaje') }}',
            showConfirmButton: false,
            timer: 2500
        });
    </script>
    @endif

    {{-- DataTable --}}
    <script>
        $(function() {
            $("#example1").DataTable({
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No hay información",
                    "decimal": "",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Horarios",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Horarios",
                    "infoFiltered": "(Filtrado de _MAX_ total Horarios)",
                    "thousands": ".",
                    "lengthMenu": "Mostrar _MENU_ Horarios",
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
                },
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection