@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de periodos académicos</b></h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Periodos registrados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreatePeriodo">
                        <i class="fas fa-plus"></i> Crear nuevo periodo
                    </button>

                    <!-- Modal Create -->
                    <div class="modal fade" id="ModalCreatePeriodo" tabindex="-1" aria-labelledby="ModalCreatePeriodoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #007bff; color: white;">
                                    <h5 class="modal-title" id="ModalCreatePeriodoLabel">
                                        <i class="fas fa-calendar-plus"></i> Registro de un nuevo periodo
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="color: white;">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.periodos.store') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gestion_id_create">Gestión</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                        </div>
                                                        <select class="form-control" name="gestion_id_create" id="gestion_id_create" required>
                                                            <option value="">Seleccione una gestión</option>
                                                            @foreach ($gestiones as $gestion)
                                                                <option value="{{ $gestion->id }}" {{ old('gestion_id_create') == $gestion->id ? 'selected' : '' }}>
                                                                    {{ $gestion->nombre }} - {{ $gestion->año }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('gestion_id_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="numero_create">Número de Periodo</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        </div>
                                                        <input type="number" class="form-control" name="numero_create" 
                                                            value="{{ old('numero_create') }}" placeholder="Ej: 1, 2, 3..." 
                                                            min="1" required>
                                                    </div>
                                                    @error('numero_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nombre_create">Nombre del periodo</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" name="nombre_create" 
                                                            value="{{ old('nombre_create') }}" 
                                                            placeholder="Ej: Primer Trimestre" required>
                                                    </div>
                                                    @error('nombre_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fecha_inicio_create">Fecha de Inicio</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        </div>
                                                        <input type="date" class="form-control" name="fecha_inicio_create" 
                                                            value="{{ old('fecha_inicio_create') }}" required>
                                                    </div>
                                                    @error('fecha_inicio_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fecha_fin_create">Fecha de Fin</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                        </div>
                                                        <input type="date" class="form-control" name="fecha_fin_create" 
                                                            value="{{ old('fecha_fin_create') }}" required>
                                                    </div>
                                                    @error('fecha_fin_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="estado_create">Estado</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                                        </div>
                                                        <select class="form-control" name="estado_create" required>
                                                            <option value="">Seleccione un estado</option>
                                                            <option value="Planificado" {{ old('estado_create') == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                                                            <option value="Activo" {{ old('estado_create') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                            <option value="Finalizado" {{ old('estado_create') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                                        </select>
                                                    </div>
                                                    @error('estado_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table id="example" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th style="text-align: center; width: 50px;">Nro</th>
                            <th>Gestión</th>
                            <th>Periodos</th>
                            <th style="text-align: center; width: 200px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gestiones as $gestion)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $gestion->nombre }}</strong>
                                    @if($gestion->año)
                                        <br><small class="text-muted">Año: {{ $gestion->año }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($gestion->periodos->count() > 0)
                                        @foreach ($gestion->periodos->sortBy('numero') as $periodo)
                                            <div class="mb-2">
                                                <span class="badge badge-light" style="font-size: 13px; padding: 8px 12px;">
                                                    <strong>{{ $periodo->numero }}.</strong> {{ $periodo->nombre }}
                                                </span>
                                                
                                                @if($periodo->estado == 'Activo')
                                                    <span class="badge badge-success">{{ $periodo->estado }}</span>
                                                @elseif($periodo->estado == 'Finalizado')
                                                    <span class="badge badge-secondary">{{ $periodo->estado }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ $periodo->estado }}</span>
                                                @endif
                                                
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar"></i> 
                                                    {{ $periodo->fecha_inicio?->format('d/m/Y') }} - 
                                                    {{ $periodo->fecha_fin?->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Sin periodos registrados</span>
                                    @endif
                                </td>
                                <td>
                                    @if($gestion->periodos->count() > 0)
                                        @foreach ($gestion->periodos->sortBy('numero') as $periodo)
                                        <div class="d-flex justify-content-center gap-2 mb-2">
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" 
                                                data-target="#ModalUpdatePeriodo{{ $periodo->id }}" title="Editar">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>

                                            <form action="{{ url('/admin/periodos/'.$periodo->id) }}" 
                                                method="POST" id="miFormularioPeriodo{{ $periodo->id }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="preguntar{{ $periodo->id }}(event)" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <script>
                                            function preguntar{{ $periodo->id }}(event) {
                                                event.preventDefault();
                                                Swal.fire({
                                                    title: '¿Deseas eliminar este periodo?',
                                                    text: "Esta acción no se puede deshacer",
                                                    icon: 'question',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Sí, eliminar',
                                                    confirmButtonColor: '#a5161d',
                                                    cancelButtonText: 'Cancelar',
                                                    cancelButtonColor: '#6c757d'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('miFormularioPeriodo{{ $periodo->id }}').submit();
                                                    }
                                                });
                                            }
                                        </script>

                                        <!-- Modal Update -->
                                        <div class="modal fade" id="ModalUpdatePeriodo{{ $periodo->id }}" tabindex="-1"
                                             aria-labelledby="ModalUpdatePeriodoLabel{{ $periodo->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color: #28a745; color: white;">
                                                        <h5 class="modal-title" id="ModalUpdatePeriodoLabel{{ $periodo->id }}">
                                                            <i class="fas fa-edit"></i> Actualizar periodo
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true" style="color: white;">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form action="{{ url('/admin/periodos/'.$periodo->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="gestion_id">Gestión</label> <b>(*)</b>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                                            </div>
                                                                            <select class="form-control" name="gestion_id" required>
                                                                                <option value="">Seleccione una gestión</option>
                                                                                @foreach ($gestiones as $gest)
                                                                                    <option value="{{ $gest->id }}"
                                                                                        {{ old('gestion_id', $periodo->gestion_id) == $gest->id ? 'selected' : '' }}>
                                                                                        {{ $gest->nombre }} - {{ $gest->año }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        @error('gestion_id')
                                                                            <small style="color: red;">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="numero">Número de Periodo</label> <b>(*)</b>
                                                                        <div class="input-group mb-3">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                                            </div>
                                                                            <input type="number" class="form-control" name="numero" 
                                                                                value="{{ old('numero', $periodo->numero) }}" 
                                                                                placeholder="Ej: 1, 2, 3..." min="1" required>
                                                                        </div>
                                                                        @error('numero')
                                                                            <small style="color: red;">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Nombre del periodo</label> <b>(*)</b>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="nombre" 
                                                                        value="{{ old('nombre', $periodo->nombre) }}" 
                                                                        placeholder="Ej: Primer Trimestre" required>
                                                                </div>
                                                                @error('nombre')
                                                                    <small style="color: red;">{{ $message }}</small>
                                                                @enderror
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
                                                                                value="{{ old('fecha_inicio', $periodo->fecha_inicio?->format('Y-m-d')) }}" required>
                                                                        </div>
                                                                        @error('fecha_inicio')
                                                                            <small style="color: red;">{{ $message }}</small>
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
                                                                                value="{{ old('fecha_fin', $periodo->fecha_fin?->format('Y-m-d')) }}" required>
                                                                        </div>
                                                                        @error('fecha_fin')
                                                                            <small style="color: red;">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="estado">Estado</label> <b>(*)</b>
                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                                                    </div>
                                                                    <select class="form-control" name="estado" required>
                                                                        <option value="">Seleccione un estado</option>
                                                                        <option value="Planificado" {{ old('estado', $periodo->estado) == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                                                                        <option value="Activo" {{ old('estado', $periodo->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                                        <option value="Finalizado" {{ old('estado', $periodo->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                                                    </select>
                                                                </div>
                                                                @error('estado')
                                                                    <small style="color: red;">{{ $message }}</small>
                                                                @enderror
                                                            </div>

                                                            <hr>

                                                            <div class="d-flex justify-content-end">
                                                                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                                                                    <i class="fas fa-times"></i> Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-save"></i> Actualizar
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@stop

@section('js')
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

    @if($errors->any())
        <script>
            $(document).ready(function(){
                @if (session('modal_id'))
                    $("#ModalUpdatePeriodo{{ session('modal_id') }}").modal("show");
                @elseif(session('modal_open') == 'create')
                    $("#ModalCreatePeriodo").modal("show");
                @endif
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "order": [[0, "asc"]]
            });
        });
    </script>
@stop