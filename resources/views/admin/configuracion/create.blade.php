{{-- Esta vista sirve tanto para create como para edit --}}
@extends('adminlte::page')

@section('title', isset($configuracion) ? 'Editar Configuración' : 'Nueva Configuración')

@section('content_header')
    <h1>
        <i class="fas fa-cog"></i> 
        {{ isset($configuracion) ? 'Editar Configuración' : 'Nueva Configuración' }}
    </h1>
@stop

@section('content')
    <div class="card">
        <form action="{{ isset($configuracion) ? route('admin.configuracion.update', $configuracion->id) : route('admin.configuracion.store') }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @if(isset($configuracion))
                @method('PUT')
            @endif

            <div class="card-body">
                {{-- Errores de validación --}}
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
                    {{-- Información Básica --}}
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-info-circle"></i> Información Básica</h5>

                        <div class="form-group">
                            <label for="clave">Clave <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('clave') is-invalid @enderror" 
                                   id="clave" 
                                   name="clave" 
                                   value="{{ old('clave', $configuracion->clave ?? '') }}"
                                   {{ isset($configuracion) && !$configuracion->editable ? 'readonly' : '' }}
                                   required>
                            <small class="text-muted">Identificador único (ej: NOTA_MINIMA_APROBACION)</small>
                            @error('clave')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $configuracion->nombre ?? '') }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3">{{ old('descripcion', $configuracion->descripcion ?? '') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo <span class="text-danger">*</span></label>
                            <select class="form-control @error('tipo') is-invalid @enderror" 
                                    id="tipo" 
                                    name="tipo" 
                                    required 
                                    onchange="cambiarTipo(this.value)">
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo }}" 
                                            {{ old('tipo', $configuracion->tipo ?? '') == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="categoria">Categoría <span class="text-danger">*</span></label>
                            <select class="form-control @error('categoria') is-invalid @enderror" 
                                    id="categoria" 
                                    name="categoria" 
                                    required>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat }}" 
                                            {{ old('categoria', $configuracion->categoria ?? '') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="editable" 
                                       name="editable" 
                                       value="1"
                                       {{ old('editable', $configuracion->editable ?? true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="editable">
                                    Editable
                                </label>
                            </div>
                            <small class="text-muted">Si está marcado, la configuración puede ser modificada</small>
                        </div>
                    </div>

                    {{-- Valor --}}
                    <div class="col-md-6">
                        <h5 class="mb-3"><i class="fas fa-edit"></i> Valor</h5>

                        {{-- Valor Texto --}}
                        <div class="form-group tipo-valor" id="valor-texto">
                            <label for="valor_texto">Valor</label>
                            <textarea class="form-control @error('valor') is-invalid @enderror" 
                                      name="valor" 
                                      id="valor_texto_input" 
                                      rows="4">{{ old('valor', $configuracion->valor ?? '') }}</textarea>
                            @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Valor Número --}}
                        <div class="form-group tipo-valor" id="valor-numero" style="display: none;">
                            <label for="valor_numero">Valor Numérico</label>
                            <input type="number" 
                                   class="form-control" 
                                   name="valor_numero" 
                                   id="valor_numero_input" 
                                   step="0.01"
                                   value="{{ old('valor', $configuracion->valor ?? '') }}">
                        </div>

                        {{-- Valor Fecha --}}
                        <div class="form-group tipo-valor" id="valor-fecha" style="display: none;">
                            <label for="valor_fecha">Valor Fecha</label>
                            <input type="date" 
                                   class="form-control" 
                                   name="valor_fecha" 
                                   id="valor_fecha_input"
                                   value="{{ old('valor', $configuracion->valor ?? '') }}">
                        </div>

                        {{-- Valor Boolean --}}
                        <div class="form-group tipo-valor" id="valor-boolean" style="display: none;">
                            <label>Valor Booleano</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" 
                                           id="valor_si" 
                                           name="valor_boolean" 
                                           class="custom-control-input" 
                                           value="1"
                                           {{ old('valor', $configuracion->valor ?? '') == '1' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="valor_si">Sí / Verdadero</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" 
                                           id="valor_no" 
                                           name="valor_boolean" 
                                           class="custom-control-input" 
                                           value="0"
                                           {{ old('valor', $configuracion->valor ?? '') == '0' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="valor_no">No / Falso</label>
                                </div>
                            </div>
                        </div>

                        {{-- Valor JSON --}}
                        <div class="form-group tipo-valor" id="valor-json" style="display: none;">
                            <label for="valor_json">Valor JSON</label>
                            <textarea class="form-control" 
                                      name="valor_json" 
                                      id="valor_json_input" 
                                      rows="8">{{ old('valor', $configuracion->valor ?? '') }}</textarea>
                            <small class="text-muted">Formato JSON válido</small>
                        </div>

                        <hr>

                        <h5 class="mb-3"><i class="fas fa-building"></i> Datos Institucionales (Opcional)</h5>

                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="{{ old('direccion', $configuracion->direccion ?? '') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="telefono" 
                                           name="telefono" 
                                           value="{{ old('telefono', $configuracion->telefono ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="divisa">Divisa</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="divisa" 
                                           name="divisa" 
                                           placeholder="PEN, USD, EUR..."
                                           value="{{ old('divisa', $configuracion->divisa ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $configuracion->email ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label for="web">Sitio Web</label>
                            <input type="url" 
                                   class="form-control" 
                                   id="web" 
                                   name="web" 
                                   placeholder="https://..."
                                   value="{{ old('web', $configuracion->web ?? '') }}">
                        </div>

                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('logo') is-invalid @enderror" 
                                       id="logo" 
                                       name="logo"
                                       accept="image/*"
                                       onchange="previewLogo(this)">
                                <label class="custom-file-label" for="logo">Seleccionar logo...</label>
                            </div>
                            <small class="text-muted">Formatos: JPG, PNG, GIF. Máximo 2MB</small>
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            @if(isset($configuracion) && $configuracion->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $configuracion->logo) }}" 
                                         alt="Logo actual" 
                                         class="img-thumbnail"
                                         style="max-height: 100px;">
                                </div>
                            @endif
                            
                            <div id="preview-logo" class="mt-2" style="display: none;">
                                <img id="img-preview" src="" alt="Vista previa" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> 
                    {{ isset($configuracion) ? 'Actualizar' : 'Guardar' }}
                </button>
                <a href="{{ route('admin.configuracion.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
    // Cambiar tipo de valor
    function cambiarTipo(tipo) {
        // Ocultar todos
        document.querySelectorAll('.tipo-valor').forEach(el => {
            el.style.display = 'none';
            // Deshabilitar inputs
            el.querySelectorAll('input, textarea').forEach(input => {
                input.disabled = true;
                input.removeAttribute('name');
            });
        });

        // Mostrar el correspondiente
        let elemento;
        switch(tipo) {
            case 'Numero':
                elemento = document.getElementById('valor-numero');
                document.getElementById('valor_numero_input').setAttribute('name', 'valor');
                break;
            case 'Fecha':
                elemento = document.getElementById('valor-fecha');
                document.getElementById('valor_fecha_input').setAttribute('name', 'valor');
                break;
            case 'Boolean':
                elemento = document.getElementById('valor-boolean');
                document.querySelectorAll('input[name="valor_boolean"]').forEach(input => {
                    input.setAttribute('name', 'valor');
                });
                break;
            case 'JSON':
                elemento = document.getElementById('valor-json');
                document.getElementById('valor_json_input').setAttribute('name', 'valor');
                break;
            default: // Texto
                elemento = document.getElementById('valor-texto');
                document.getElementById('valor_texto_input').setAttribute('name', 'valor');
        }

        if (elemento) {
            elemento.style.display = 'block';
            elemento.querySelectorAll('input, textarea').forEach(input => {
                input.disabled = false;
            });
        }
    }

    // Preview de logo
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-preview').src = e.target.result;
                document.getElementById('preview-logo').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
            
            // Actualizar label del custom-file
            let fileName = input.files[0].name;
            let label = input.nextElementSibling;
            label.innerText = fileName;
        }
    }

    // Al cargar la página, mostrar el tipo correcto
    document.addEventListener('DOMContentLoaded', function() {
        let tipoActual = document.getElementById('tipo').value;
        cambiarTipo(tipoActual);
    });
</script>
@stop