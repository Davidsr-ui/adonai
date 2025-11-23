@extends('adminlte::page')

@section('title', 'Configuraciones')

@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1><i class="fas fa-cog"></i> Configuraciones del Sistema</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.configuracion.global') }}" class="btn btn-info">
                <i class="fas fa-globe"></i> Configuración Global
            </a>
            <a href="{{ route('admin.configuracion.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Configuración
            </a>
        </div>
    </div>
@stop

@section('content')
    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Estadísticas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total Configuraciones</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['editables'] }}</h3>
                    <p>Editables</p>
                </div>
                <div class="icon">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['no_editables'] }}</h3>
                    <p>No Editables</p>
                </div>
                <div class="icon">
                    <i class="fas fa-lock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $estadisticas['por_categoria']->count() }}</h3>
                    <p>Categorías</p>
                </div>
                <div class="icon">
                    <i class="fas fa-th-list"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.configuracion.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Buscar</label>
                            <input type="text" name="buscar" class="form-control" placeholder="Nombre, descripción, clave..." value="{{ request('buscar') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Categoría</label>
                            <select name="categoria" class="form-control">
                                <option value="">Todas</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat }}" {{ request('categoria') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo" class="form-control">
                                <option value="">Todos</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Editable</label>
                            <select name="editable" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('editable') === '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ request('editable') === '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de configuraciones --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> Lista de Configuraciones</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-secondary" onclick="restaurarDefecto()">
                    <i class="fas fa-undo"></i> Restaurar Defecto
                </button>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Editable</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($configuraciones as $config)
                        <tr>
                            <td>{{ $config->id }}</td>
                            <td>
                                <code>{{ $config->clave }}</code>
                            </td>
                            <td>{{ $config->nombre ?? 'N/A' }}</td>
                            <td>
                                @if($config->tipo == 'Boolean')
                                    <span class="badge badge-{{ $config->valor == '1' ? 'success' : 'danger' }}">
                                        {{ $config->valor == '1' ? 'Sí' : 'No' }}
                                    </span>
                                @else
                                    <span class="text-muted">{{ \Str::limit($config->valor_formateado, 50) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $config->tipo }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $config->badge_categoria }}">
                                    <i class="fas {{ $config->icono_categoria }}"></i>
                                    {{ $config->categoria }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($config->editable)
                                    <i class="fas fa-check text-success"></i>
                                @else
                                    <i class="fas fa-lock text-danger"></i>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <a href="{{ route('admin.configuracion.show', $config->id) }}" 
                                       class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($config->editable)
                                        <a href="{{ route('admin.configuracion.edit', $config->id) }}" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmarEliminacion({{ $config->id }})"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="No editable">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <p class="text-muted my-3">No hay configuraciones registradas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($configuraciones->hasPages())
            <div class="card-footer">
                {{ $configuraciones->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- Form de eliminación oculto --}}
    <form id="formEliminar" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@stop

@section('js')
<script>
    function confirmarEliminacion(id) {
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
                let form = document.getElementById('formEliminar');
                form.action = "{{ url('admin/configuracion') }}/" + id;
                form.submit();
            }
        });
    }

    function restaurarDefecto() {
        Swal.fire({
            title: '¿Restaurar configuraciones por defecto?',
            text: "Se crearán/actualizarán las configuraciones básicas del sistema",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('admin.configuracion.restaurar-defecto') }}";
            }
        });
    }

    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@stop