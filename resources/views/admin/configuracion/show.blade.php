@extends('adminlte::page')

@section('title', 'Detalle de Configuración')

@section('content_header')
    <h1><i class="fas fa-cog"></i> Detalle de Configuración</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <code>{{ $configuracion->clave }}</code>
            </h3>
            <div class="card-tools">
                @if($configuracion->editable)
                    <a href="{{ route('admin.configuracion.edit', $configuracion->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endif
                <a href="{{ route('admin.configuracion.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-info-circle"></i> Información General</h5>
                    
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $configuracion->id }}</td>
                        </tr>
                        <tr>
                            <th>Clave</th>
                            <td><code>{{ $configuracion->clave }}</code></td>
                        </tr>
                        <tr>
                            <th>Nombre</th>
                            <td>{{ $configuracion->nombre ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td>{{ $configuracion->descripcion ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo</th>
                            <td><span class="badge badge-secondary">{{ $configuracion->tipo }}</span></td>
                        </tr>
                        <tr>
                            <th>Categoría</th>
                            <td>
                                <span class="badge badge-{{ $configuracion->badge_categoria }}">
                                    <i class="fas {{ $configuracion->icono_categoria }}"></i>
                                    {{ $configuracion->categoria }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Editable</th>
                            <td>
                                @if($configuracion->editable)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Sí
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-lock"></i> No
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <h5 class="mt-4 mb-3"><i class="fas fa-edit"></i> Valor</h5>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            @if($configuracion->tipo == 'Boolean')
                                <h4>
                                    <span class="badge badge-{{ $configuracion->valor == '1' ? 'success' : 'danger' }}">
                                        {{ $configuracion->valor == '1' ? 'Verdadero / Sí' : 'Falso / No' }}
                                    </span>
                                </h4>
                            @elseif($configuracion->tipo == 'JSON')
                                <pre><code>{{ json_encode(json_decode($configuracion->valor), JSON_PRETTY_PRINT) }}</code></pre>
                            @else
                                <h4 class="mb-0">{{ $configuracion->valor_formateado }}</h4>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3"><i class="fas fa-building"></i> Datos Institucionales</h5>
                    
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Dirección</th>
                            <td>{{ $configuracion->direccion ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td>{{ $configuracion->telefono ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $configuracion->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Web</th>
                            <td>
                                @if($configuracion->web)
                                    <a href="{{ $configuracion->web }}" target="_blank">
                                        {{ $configuracion->web }}
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Divisa</th>
                            <td>{{ $configuracion->divisa ?? 'N/A' }}</td>
                        </tr>
                    </table>

                    @if($configuracion->logo)
                        <h5 class="mt-4 mb-3"><i class="fas fa-image"></i> Logo</h5>
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $configuracion->logo) }}" 
                                 alt="Logo" 
                                 class="img-thumbnail"
                                 style="max-height: 200px;">
                        </div>
                    @endif

                    <h5 class="mt-4 mb-3"><i class="fas fa-clock"></i> Fechas</h5>
                    
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Creado</th>
                            <td>{{ $configuracion->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Actualizado</th>
                            <td>{{ $configuracion->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Última modificación</th>
                            <td>{{ $configuracion->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @if($configuracion->editable)
                <a href="{{ route('admin.configuracion.edit', $configuracion->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmarEliminacion()">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            @endif
            <a href="{{ route('admin.configuracion.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    {{-- Form de eliminación oculto --}}
    <form id="formEliminar" method="POST" action="{{ route('admin.configuracion.destroy', $configuracion->id) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@stop

@section('js')
<script>
    function confirmarEliminacion() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEliminar').submit();
            }
        });
    }
</script>
@stop