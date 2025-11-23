@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de Gestiones Educativas Adonai</b></h1>
    <hr>
    <a href="{{url('/admin/gestiones/create')}}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Crear nueva gestión
    </a>
@stop

@section('content')
<div class="row">
    @foreach ($gestiones as $gestion)
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box zoomP">
            <img src="{{ asset('img/calendario.gif') }}" width="70px" alt="">
            <div class="info-box-content">
                <span class="info-box-text"><b>{{ $gestion->nombre }}</b></span>
                
                @if($gestion->año)
                    <span class="info-box-number" style="color: rgb(10, 6, 248); font-size: 20pt">
                        {{ $gestion->año }}
                    </span>
                @endif
                
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> 
                    {{ $gestion->fecha_inicio?->format('d/m/Y') }} - 
                    {{ $gestion->fecha_fin?->format('d/m/Y') }}
                </small>
                
                <div class="mt-2">
                    @if($gestion->estado == 'Activo')
                        <span class="badge badge-success">{{ $gestion->estado }}</span>
                    @elseif($gestion->estado == 'Finalizado')
                        <span class="badge badge-secondary">{{ $gestion->estado }}</span>
                    @else
                        <span class="badge badge-warning">{{ $gestion->estado }}</span>
                    @endif
                </div>
                
                <div class="d-flex gap-2 mt-2">
                    <a href="{{url('/admin/gestiones/'.$gestion->id.'/edit')}}" 
                       class="btn btn-success btn-sm" title="Editar">
                        <i class="fas fa-pencil-alt"></i> Editar
                    </a>
                    
                    {{-- ✅ FORMULARIO CORREGIDO --}}
                    <form action="{{ url('/admin/gestiones/'.$gestion->id)}}" 
                          method="POST" 
                          id="deleteForm{{$gestion->id}}" 
                          style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                class="btn btn-danger btn-sm" 
                                onclick="confirmarEliminacion({{$gestion->id}})"
                                title="Eliminar">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@stop

@section('css')
<style>
    .gap-2 {
        gap: 0.5rem;
    }
    .zoomP {
        transition: transform 0.3s;
    }
    .zoomP:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>
@stop

@section('js')
{{-- ✅ SCRIPT CORREGIDO - UNA SOLA FUNCIÓN --}}
<script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Deseas eliminar esta gestión educativa?',
            text: "Esta acción no se puede deshacer",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#a5161d',
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                // ✅ Enviar el formulario
                document.getElementById('deleteForm' + id).submit();
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
@stop