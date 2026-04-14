@extends('layouts.docente')

@section('title', 'Detalle Comportamiento')
@section('page_title')Detalle <span>Comportamiento</span>@endsection

@section('content')
<div style="padding:0 28px 32px">
    <div style="margin-bottom:16px">
        <a href="{{ route('docente.comportamientos.index') }}" class="d-btn-back"><i class="fas fa-arrow-left"></i> Volver al Listado</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        {{-- Estudiante --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-user-graduate"></i></span>Datos del Estudiante</div>
            </div>
            <div style="padding:20px">
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Nombre</span><span class="d-info-val" style="font-size:.95rem">{{ $comportamiento->estudiante->persona->nombres }} {{ $comportamiento->estudiante->persona->apellidos }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">DNI</span><span class="d-info-val d-mono">{{ $comportamiento->estudiante->persona->dni }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $comportamiento->estudiante->codigo_estudiante }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Grado</span>
                        <span class="d-info-val">@if($comportamiento->estudiante->grado)<span class="d-badge d-badge--sky">{{ $comportamiento->estudiante->grado->nombre_completo }}</span>@else —@endif</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Docente --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-chalkboard-teacher"></i></span>Datos del Docente</div>
            </div>
            <div style="padding:20px">
                @if($comportamiento->docente)
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Nombre</span><span class="d-info-val" style="font-size:.95rem">{{ $comportamiento->docente->persona->nombres }} {{ $comportamiento->docente->persona->apellidos }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">DNI</span><span class="d-info-val d-mono">{{ $comportamiento->docente->persona->dni }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $comportamiento->docente->codigo_docente }}</span></div>
                </div>
                @else
                <div class="d-empty-state" style="padding:20px"><i class="fas fa-user-slash"></i><span>Sin docente asignado</span></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Detalle --}}
    <div class="d-card" style="margin-bottom:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--{{ $comportamiento->tipo_badge }}"><i class="fas {{ $comportamiento->tipo_icon }}"></i></span>Detalle del Comportamiento</div>
            <div style="display:flex;gap:8px">
                <span class="d-badge d-badge--{{ $comportamiento->tipo_badge }}"><i class="fas {{ $comportamiento->tipo_icon }}"></i> {{ $comportamiento->tipo }}</span>
                @if($comportamiento->notificado_tutor)<span class="d-badge d-badge--green"><i class="fas fa-bell"></i> Notificado</span>@endif
            </div>
        </div>
        <div style="padding:20px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px">
                <div class="d-info-item"><span class="d-info-lbl">Fecha</span><span class="d-info-val">{{ $comportamiento->fecha_formateada }}</span><span style="font-size:.7rem;color:var(--muted)">{{ $comportamiento->dia_semana }}</span></div>
                <div class="d-info-item"><span class="d-info-lbl">Tipo</span><span class="d-info-val"><span class="d-badge d-badge--{{ $comportamiento->tipo_badge }}">{{ $comportamiento->tipo }}</span></span></div>
                <div class="d-info-item"><span class="d-info-lbl">Notificado</span><span class="d-info-val"><span class="d-badge d-badge--{{ $comportamiento->notificado_tutor?'green':'slate' }}">{{ $comportamiento->notificado_tutor?'Sí':'No' }}</span></span></div>
                <div class="d-info-item"><span class="d-info-lbl">F. Notificación</span><span class="d-info-val">{{ $comportamiento->fecha_notificacion_formateada }}</span></div>
            </div>
            <div class="d-info-item" style="margin-bottom:14px">
                <span class="d-info-lbl">Descripción</span>
                <div style="margin-top:6px;background:var(--surface2);border:1px solid var(--border);border-left:4px solid var(--{{ $comportamiento->tipo_badge=='success'?'green':($comportamiento->tipo_badge=='danger'?'rose':'amber') }});border-radius:0 10px 10px 0;padding:14px 16px;font-size:.84rem;color:var(--text);line-height:1.7">{{ $comportamiento->descripcion }}</div>
            </div>
        </div>
    </div>
</div>
@endsection