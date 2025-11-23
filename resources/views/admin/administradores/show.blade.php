@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Administrador</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información del Administrador</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $administrador->cargo_badge }} badge-lg">
                            <i class="fas {{ $administrador->cargo_icon }}"></i>
                            {{ $administrador->cargo }}
                        </span>
                        <span class="badge badge-{{ $administrador->estado_persona_badge }} badge-lg">
                            {{ $administrador->estado_persona }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información Personal -->
                    <h5><i class="fas fa-user"></i> Datos Personales</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre Completo</label>
                                <p><strong>{{ $administrador->persona->nombres }} {{ $administrador->persona->apellidos }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>DNI</label>
                                <p><strong>{{ $administrador->persona->dni }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha de Nacimiento</label>
                                <p>{{ $administrador->persona->fecha_nacimiento ? \Carbon\Carbon::parse($administrador->persona->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Edad</label>
                                <p>
                                    @if($administrador->persona->fecha_nacimiento)
                                        {{ \Carbon\Carbon::parse($administrador->persona->fecha_nacimiento)->age }} años
                                    @else
                                        No disponible
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email</label>
                                <p>
                                    @if($administrador->persona->email)
                                        <a href="mailto:{{ $administrador->persona->email }}">
                                            <i class="fas fa-envelope"></i> {{ $administrador->persona->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">No registrado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <p>
                                    @if($administrador->persona->telefono)
                                        <i class="fas fa-phone"></i> {{ $administrador->persona->telefono }}
                                    @else
                                        <span class="text-muted">No registrado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Celular</label>
                                <p>
                                    @if($administrador->persona->celular)
                                        <i class="fas fa-mobile-alt"></i> {{ $administrador->persona->celular }}
                                    @else
                                        <span class="text-muted">No registrado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <p>{{ $administrador->persona->direccion ?? 'No registrada' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información Administrativa -->
                    <h5 class="mt-4"><i class="fas fa-briefcase"></i> Información Administrativa</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-{{ $administrador->cargo_badge }}">
                                <span class="info-box-icon">
                                    <i class="fas {{ $administrador->cargo_icon }}"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cargo</span>
                                    <span class="info-box-number">{{ $administrador->cargo }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon">
                                    <i class="fas fa-building"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Área</span>
                                    <span class="info-box-number" style="font-size: 1.2rem;">
                                        {{ $administrador->area ?? 'Sin asignar' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">F. Asignación</span>
                                    <span class="info-box-number" style="font-size: 1.2rem;">
                                        {{ $administrador->fecha_asignacion_formateada }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Antigüedad</span>
                                    <span class="info-box-number" style="font-size: 1.2rem;">
                                        {{ $administrador->antiguedad }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-{{ $administrador->cargo_badge }}">
                                <h5><i class="fas fa-info-circle"></i> Información del Cargo</h5>
                                <p>
                                    <strong>{{ $administrador->persona->nombres }} {{ $administrador->persona->apellidos }}</strong> 
                                    se desempeña como <strong>{{ $administrador->cargo }}</strong>
                                    @if($administrador->area)
                                        en el área de <strong>{{ $administrador->area }}</strong>
                                    @endif
                                    desde el <strong>{{ $administrador->fecha_asignacion_formateada }}</strong>,
                                    con una antigüedad de <strong>{{ $administrador->antiguedad }}</strong> en el cargo.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del Cargo -->
                    <h5 class="mt-4"><i class="fas fa-list-ul"></i> Descripción del Cargo</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            @if($administrador->cargo == 'Director')
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-user-tie"></i> <strong>Director</strong></h6>
                                <ul>
                                    <li>Máxima autoridad ejecutiva de la institución</li>
                                    <li>Responsable de la gestión académica y administrativa</li>
                                    <li>Toma de decisiones estratégicas</li>
                                    <li>Representación legal de la institución</li>
                                    <li>Supervisión general de todas las áreas</li>
                                </ul>
                            </div>
                            @elseif($administrador->cargo == 'Subdirector')
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-user-cog"></i> <strong>Subdirector</strong></h6>
                                <ul>
                                    <li>Apoyo directo a la dirección general</li>
                                    <li>Coordinación de áreas académicas y administrativas</li>
                                    <li>Supervisión del personal docente y administrativo</li>
                                    <li>Seguimiento de planes y programas educativos</li>
                                    <li>Reemplazo del Director en su ausencia</li>
                                </ul>
                            </div>
                            @elseif($administrador->cargo == 'Secretario')
                            <div class="alert alert-primary">
                                <h6><i class="fas fa-user-edit"></i> <strong>Secretario</strong></h6>
                                <ul>
                                    <li>Gestión de documentación oficial</li>
                                    <li>Atención a padres de familia y público en general</li>
                                    <li>Archivo y control de expedientes</li>
                                    <li>Elaboración de certificados y constancias</li>
                                    <li>Coordinación de comunicación interna y externa</li>
                                </ul>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <h6><i class="fas fa-user-shield"></i> <strong>Administrativo</strong></h6>
                                <ul>
                                    <li>Apoyo en tareas administrativas generales</li>
                                    <li>Gestión de recursos materiales</li>
                                    <li>Control de inventarios y suministros</li>
                                    <li>Apoyo logístico a las diferentes áreas</li>
                                    <li>Mantenimiento de instalaciones</li>
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Estado y Contacto -->
                    <h5 class="mt-4"><i class="fas fa-id-card"></i> Estado y Contacto</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado Actual</label>
                                <p>
                                    <span class="badge badge-{{ $administrador->estado_persona_badge }} badge-lg">
                                        <i class="fas fa-circle"></i> {{ $administrador->estado_persona }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Información de Contacto</label>
                                <p>
                                    @if($administrador->persona->email || $administrador->persona->celular)
                                        @if($administrador->persona->email)
                                            <a href="mailto:{{ $administrador->persona->email }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-envelope"></i> Enviar Email
                                            </a>
                                        @endif
                                        @if($administrador->persona->celular)
                                            <a href="tel:{{ $administrador->persona->celular }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-phone"></i> Llamar
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">No hay información de contacto</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Sistema -->
                    <h5 class="mt-4"><i class="fas fa-info-circle"></i> Información del Sistema</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de Registro en el Sistema</label>
                                <p>
                                    <i class="fas fa-calendar-plus"></i> 
                                    {{ \Carbon\Carbon::parse($administrador->created_at)->format('d/m/Y H:i:s') }}
                                    <br>
                                    <small class="text-muted">
                                        ({{ \Carbon\Carbon::parse($administrador->created_at)->diffForHumans() }})
                                    </small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Última Actualización</label>
                                <p>
                                    <i class="fas fa-sync-alt"></i> 
                                    {{ \Carbon\Carbon::parse($administrador->updated_at)->format('d/m/Y H:i:s') }}
                                    <br>
                                    <small class="text-muted">
                                        ({{ \Carbon\Carbon::parse($administrador->updated_at)->diffForHumans() }})
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.administradores.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                
                                @if($administrador->estaActivo())
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#desactivarModal">
                                    <i class="fas fa-ban"></i> Desactivar
                                </button>
                                @else
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#activarModal">
                                    <i class="fas fa-check"></i> Activar
                                </button>
                                @endif

                                <button type="button" class="btn btn-info" onclick="window.print()">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Desactivar -->
    <div class="modal fade" id="desactivarModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Desactivación
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.administradores.desactivar', $administrador->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>¿Está seguro de que desea desactivar a este administrador?</p>
                        <div class="alert alert-info">
                            <strong>Nombre:</strong> {{ $administrador->persona->nombres }} {{ $administrador->persona->apellidos }}<br>
                            <strong>Cargo:</strong> {{ $administrador->cargo }}<br>
                            <strong>Área:</strong> {{ $administrador->area ?? 'N/A' }}
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            El administrador no podrá acceder al sistema hasta que sea activado nuevamente.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-ban"></i> Desactivar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Activar -->
    <div class="modal fade" id="activarModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> Confirmar Activación
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.administradores.activar', $administrador->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>¿Está seguro de que desea activar a este administrador?</p>
                        <div class="alert alert-info">
                            <strong>Nombre:</strong> {{ $administrador->persona->nombres }} {{ $administrador->persona->apellidos }}<br>
                            <strong>Cargo:</strong> {{ $administrador->cargo }}<br>
                            <strong>Área:</strong> {{ $administrador->area ?? 'N/A' }}
                        </div>
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle"></i>
                            El administrador podrá acceder al sistema una vez activado.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Activar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        @media print {
            .btn, .card-header .card-tools, .modal {
                display: none !important;
            }
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        @if(session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                showConfirmButton: true,
                timer: 3000
            });
        @endif
    </script>
@stop