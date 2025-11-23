@extends('adminlte::page')

@section('title', 'Configuración Global')

@section('content_header')
    <h1><i class="fas fa-globe"></i> Configuración Global del Sistema</h1>
@stop

@section('content')
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

    <div class="card">
        <form action="{{ route('admin.configuracion.actualizar-global') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Errores:</h5>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    {{-- Información de la Institución --}}
                    <div class="col-md-8">
                        <h5 class="mb-3"><i class="fas fa-building"></i> Información de la Institución</h5>

                        <div class="form-group">
                            <label for="nombre">Nombre de la Institución <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $configuracionGlobal->nombre) }}"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3">{{ old('descripcion', $configuracionGlobal->descripcion) }}</textarea>
                            <small class="text-muted">Breve descripción de la institución</small>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" 
                                   class="form-control @error('direccion') is-invalid @enderror" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="{{ old('direccion', $configuracionGlobal->direccion) }}">
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" 
                                           class="form-control @error('telefono') is-invalid @enderror" 
                                           id="telefono" 
                                           name="telefono" 
                                           placeholder="+51 999 999 999"
                                           value="{{ old('telefono', $configuracionGlobal->telefono) }}">
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="divisa">Divisa</label>
                                    <input type="text" 
                                           class="form-control @error('divisa') is-invalid @enderror" 
                                           id="divisa" 
                                           name="divisa" 
                                           placeholder="PEN, USD, EUR..."
                                           value="{{ old('divisa', $configuracionGlobal->divisa ?? 'PEN') }}"
                                           maxlength="10">
                                    <small class="text-muted">Código de moneda (3 letras)</small>
                                    @error('divisa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   placeholder="contacto@institucion.edu"
                                   value="{{ old('email', $configuracionGlobal->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="web">Sitio Web</label>
                            <input type="url" 
                                   class="form-control @error('web') is-invalid @enderror" 
                                   id="web" 
                                   name="web" 
                                   placeholder="https://www.institucion.edu"
                                   value="{{ old('web', $configuracionGlobal->web) }}">
                            @error('web')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Logo --}}
                    <div class="col-md-4">
                        <h5 class="mb-3"><i class="fas fa-image"></i> Logo de la Institución</h5>

                        <div class="text-center mb-3">
                            @if($configuracionGlobal->logo)
                                <img src="{{ asset('storage/' . $configuracionGlobal->logo) }}" 
                                     alt="Logo actual" 
                                     id="logo-preview"
                                     class="img-thumbnail"
                                     style="max-height: 250px; max-width: 100%;">
                            @else
                                <div class="border p-5 mb-3" id="logo-preview">
                                    <i class="fas fa-image fa-5x text-muted"></i>
                                    <p class="text-muted mt-2">Sin logo</p>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="logo">Cambiar Logo</label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('logo') is-invalid @enderror" 
                                       id="logo" 
                                       name="logo"
                                       accept="image/*"
                                       onchange="previewLogo(this)">
                                <label class="custom-file-label" for="logo">Seleccionar imagen...</label>
                            </div>
                            <small class="text-muted">JPG, PNG, GIF. Máximo 2MB</small>
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Información</h6>
                            <small>
                                Esta configuración aparecerá en:
                                <ul class="mb-0 pl-3">
                                    <li>Encabezado del sistema</li>
                                    <li>Reportes y documentos</li>
                                    <li>Comunicaciones oficiales</li>
                                </ul>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Configuración
                </button>
                <a href="{{ route('admin.configuracion.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Información Adicional --}}
    <div class="row">
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Configuración</span>
                    <span class="info-box-number">Global del Sistema</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Estado</span>
                    <span class="info-box-number">Activa</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Última Actualización</span>
                    <span class="info-box-number">{{ $configuracionGlobal->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    #logo-preview {
        transition: all 0.3s ease;
    }
    
    #logo-preview:hover {
        transform: scale(1.02);
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
</style>
@stop

@section('js')
<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const preview = document.getElementById('logo-preview');
            
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 250px; max-width: 100%;">';
            };
            
            reader.readAsDataURL(input.files[0]);
            
            // Actualizar label del custom-file
            let fileName = input.files[0].name;
            let label = input.nextElementSibling;
            label.innerText = fileName;
        }
    }

    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@stop