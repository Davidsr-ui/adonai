@extends('layouts.docente')

@section('title', 'Dashboard Docente')
@section('page_title')Panel <span>Docente</span>@endsection

@section('content')

@if(!Auth::user()->persona || !Auth::user()->persona->docente)
<div style="margin:24px 28px">
    <div class="d-warn-box">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Perfil Incompleto</strong>
            <p>Tu perfil de docente no esta completo. Contacta al administrador.</p>
        </div>
    </div>
</div>
@else

<div class="d-hero">
    <div class="d-hero__orb d-hero__orb--1"></div>
    <div class="d-hero__orb d-hero__orb--2"></div>
    <div class="d-hero__content">
        <div>
            <div class="d-eyebrow"><span class="d-eyebrow__dot"></span>Panel Docente Activo</div>
            <h1 class="d-hero__title">Hola, <span class="d-hero__accent">{{ Auth::user()->nombre_completo }}</span>!</h1>
            <p class="d-hero__sub">Gestiona tus cursos, asistencias y calificaciones desde aqui.</p>
        </div>
        <div class="d-hero__pills">
            <div class="d-pill d-pill--sky"><i class="fas fa-calendar-check"></i>{{ now()->format('d M Y') }}</div>
            <div class="d-pill d-pill--green"><i class="fas fa-circle"></i>En linea</div>
        </div>
    </div>
</div>

<div class="d-kpi-grid">
    <a href="{{ route('docente.mis-cursos') }}" class="d-kpi d-kpi--sky" style="animation-delay:.05s">
        <div class="d-kpi__icon"><i class="fas fa-book"></i></div>
        <div class="d-kpi__body">
            <div class="d-kpi__label">Asignaciones</div>
            <div class="d-kpi__value" data-count="{{ $asignaciones->count() }}">0</div>
            <div class="d-kpi__sub"><span class="d-badge d-badge--sky"><i class="fas fa-circle"></i> Totales</span></div>
        </div>
        <div class="d-kpi__glow"></div>
        <div class="d-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('docente.mis-alumnos') }}" class="d-kpi d-kpi--green" style="animation-delay:.1s">
        <div class="d-kpi__icon"><i class="fas fa-user-graduate"></i></div>
        <div class="d-kpi__body">
            <div class="d-kpi__label">Estudiantes</div>
            <div class="d-kpi__value" data-count="{{ $estudiantes->count() }}">0</div>
            <div class="d-kpi__sub"><span class="d-badge d-badge--green"><i class="fas fa-circle"></i> Activos</span></div>
        </div>
        <div class="d-kpi__glow"></div>
        <div class="d-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('docente.asistencias.index') }}" class="d-kpi d-kpi--amber" style="animation-delay:.15s">
        <div class="d-kpi__icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="d-kpi__body">
            <div class="d-kpi__label">Asistencias</div>
            <div class="d-kpi__value" data-count="{{ $totalAsistencias }}">0</div>
            <div class="d-kpi__sub"><span class="d-badge d-badge--amber"><i class="fas fa-circle"></i> Ultimos 7 dias</span></div>
        </div>
        <div class="d-kpi__glow"></div>
        <div class="d-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('docente.notas.index') }}" class="d-kpi d-kpi--rose" style="animation-delay:.2s">
        <div class="d-kpi__icon"><i class="fas fa-star"></i></div>
        <div class="d-kpi__body">
            <div class="d-kpi__label">Notas</div>
            <div class="d-kpi__value" data-count="{{ $notasRegistradas }}">0</div>
            <div class="d-kpi__sub"><span class="d-badge d-badge--rose"><i class="fas fa-circle"></i> Registradas</span></div>
        </div>
        <div class="d-kpi__glow"></div>
        <div class="d-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
</div>

<div class="d-stats-row">
    <div class="d-stat"><span class="d-stat__icon d-stat__icon--green"><i class="fas fa-check"></i></span><div><div class="d-stat__val">{{ $presentes }}</div><div class="d-stat__lbl">Presentes</div></div></div>
    <div class="d-stat"><span class="d-stat__icon d-stat__icon--rose"><i class="fas fa-times"></i></span><div><div class="d-stat__val">{{ $ausentes }}</div><div class="d-stat__lbl">Ausentes</div></div></div>
    <div class="d-stat"><span class="d-stat__icon d-stat__icon--sky"><i class="fas fa-smile"></i></span><div><div class="d-stat__val">{{ $comportamientosPositivos }}</div><div class="d-stat__lbl">Comportam. +</div></div></div>
    <div class="d-stat"><span class="d-stat__icon d-stat__icon--amber"><i class="fas fa-frown"></i></span><div><div class="d-stat__val">{{ $comportamientosNegativos }}</div><div class="d-stat__lbl">Comportam. -</div></div></div>
    <div class="d-stat"><span class="d-stat__icon d-stat__icon--violet"><i class="fas fa-eye"></i></span><div><div class="d-stat__val">{{ $notasPublicadas }}</div><div class="d-stat__lbl">Notas publ.</div></div></div>
    <div class="d-stat"><span class="d-stat__icon d-stat__icon--sky"><i class="fas fa-file-alt"></i></span><div><div class="d-stat__val">{{ $reportesPublicados }}</div><div class="d-stat__lbl">Reportes publ.</div></div></div>
</div>

<div class="d-row2">
    <div class="d-card" style="animation-delay:.25s">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-book"></i></span>Mis Cursos Asignados</div>
            <span class="d-card__tag">{{ $asignaciones->count() }}</span>
        </div>
        <div style="padding:0">
            @if($asignaciones->count() > 0)
                @foreach($asignaciones->take(5) as $asignacion)
                <div class="d-list-item">
                    <div class="d-list-av">{{ strtoupper(substr($asignacion->curso->nombre,0,1)) }}</div>
                    <div class="d-list-info">
                        <div class="d-list-name">{{ $asignacion->curso->nombre }}</div>
                        <div class="d-list-meta"><i class="fas fa-users"></i> {{ $asignacion->grado->nombre_completo }}</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px">
                        <span class="d-badge d-badge--sky">{{ $asignacion->curso->creditos }} creditos</span>
                        @if($asignacion->es_tutor_aula)<span class="d-badge d-badge--green"><i class="fas fa-star"></i> Tutor</span>@endif
                    </div>
                </div>
                @endforeach
                @if($asignaciones->count() > 5)<div style="padding:8px 18px;font-size:.72rem;color:var(--muted)">Mostrando 5 de {{ $asignaciones->count() }} asignaciones</div>@endif
            @else
            <div class="d-empty"><i class="fas fa-book-open"></i><span>No tienes cursos asignados aun</span></div>
            @endif
        </div>
        <div class="d-card__ftr">
            <a href="{{ route('docente.mis-cursos') }}" class="d-btn d-btn--sky d-btn--block"><i class="fas fa-eye"></i> Ver todos mis cursos</a>
        </div>
    </div>

    <div class="d-card" style="animation-delay:.3s">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-bolt"></i></span>Accesos Rapidos</div>
        </div>
        <div style="padding:18px">
            <div class="d-quick-grid">
                <a href="{{ route('docente.mis-alumnos') }}"           class="d-quick d-quick--sky">   <i class="fas fa-users"></i><span>Mis Alumnos</span></a>
                <a href="{{ route('docente.asistencias.index') }}"     class="d-quick d-quick--amber"> <i class="fas fa-clipboard-check"></i><span>Asistencias</span></a>
                <a href="{{ route('docente.notas.index') }}"           class="d-quick d-quick--rose">  <i class="fas fa-star"></i><span>Notas</span></a>
                <a href="{{ route('docente.comportamientos.index') }}" class="d-quick d-quick--violet"><i class="fas fa-user-check"></i><span>Comportamientos</span></a>
                <a href="{{ route('docente.reportes.index') }}"        class="d-quick d-quick--slate"> <i class="fas fa-file-alt"></i><span>Reportes</span></a>
                <a href="{{ route('docente.mensajeria') }}"            class="d-quick d-quick--green"> <i class="fas fa-envelope"></i><span>Mensajeria</span></a>
            </div>
        </div>
    </div>
</div>

<div class="d-row2" style="margin-top:0">
    <div class="d-card" style="animation-delay:.35s">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--amber"><i class="fas fa-clipboard-check"></i></span>Ultimas Asistencias</div>
            @if($ultimasAsistencias)<span class="d-card__tag">{{ $ultimasAsistencias->count() }}</span>@endif
        </div>
        <div style="padding:0">
            @if($ultimasAsistencias && $ultimasAsistencias->count() > 0)
                @foreach($ultimasAsistencias as $asistencia)
                <div class="d-list-item">
                    <div class="d-list-av d-list-av--amber">{{ strtoupper(substr($asistencia->estudiante->persona->nombres,0,1)) }}</div>
                    <div class="d-list-info">
                        <div class="d-list-name">{{ $asistencia->estudiante->persona->apellidos }}, {{ $asistencia->estudiante->persona->nombres }}</div>
                        <div class="d-list-meta">{{ $asistencia->curso->nombre }}</div>
                    </div>
                    <div style="text-align:right">
                        <span class="d-badge d-badge--{{ $asistencia->estado_badge }}">{{ $asistencia->estado }}</span>
                        <div style="font-size:.65rem;color:var(--muted);margin-top:2px">{{ $asistencia->fecha_formateada }}</div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="d-empty"><i class="fas fa-clipboard"></i><span>No hay asistencias recientes</span></div>
            @endif
        </div>
        <div class="d-card__ftr">
            <a href="{{ route('docente.asistencias.index') }}" class="d-btn d-btn--outline d-btn--block"><i class="fas fa-plus"></i> Registrar Asistencias</a>
        </div>
    </div>

    <div class="d-card" style="animation-delay:.4s">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--rose"><i class="fas fa-star"></i></span>Ultimas Notas</div>
            @if($ultimasNotas)<span class="d-card__tag">{{ $ultimasNotas->count() }}</span>@endif
        </div>
        <div style="padding:0">
            @if($ultimasNotas && $ultimasNotas->count() > 0)
                @foreach($ultimasNotas as $nota)
                <div class="d-list-item">
                    <div class="d-list-av d-list-av--rose">{{ strtoupper(substr($nota->matricula->estudiante->persona->nombres,0,1)) }}</div>
                    <div class="d-list-info">
                        <div class="d-list-name">{{ $nota->matricula->estudiante->persona->apellidos }}, {{ $nota->matricula->estudiante->persona->nombres }}</div>
                        <div class="d-list-meta">{{ $nota->matricula->curso->nombre }}</div>
                    </div>
                    <div style="text-align:right">
                        <span class="d-score d-score--{{ $nota->nota_final >= 14 ? 'green' : ($nota->nota_final >= 11 ? 'sky' : 'rose') }}">{{ $nota->nota_final }}</span>
                        <div style="font-size:.65rem;color:var(--muted);margin-top:2px">{{ $nota->tipo_evaluacion }}</div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="d-empty"><i class="fas fa-star"></i><span>No hay notas recientes</span></div>
            @endif
        </div>
        <div class="d-card__ftr">
            <a href="{{ route('docente.notas.index') }}" class="d-btn d-btn--outline d-btn--block"><i class="fas fa-plus"></i> Registrar Notas</a>
        </div>
    </div>
</div>

@if($gestionActual)
<div style="padding:0 28px 32px">
    <div class="d-card" style="animation-delay:.45s">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-calendar-alt"></i></span>Gestion y Periodo Actual</div>
        </div>
        <div style="padding:16px 18px;display:flex;flex-wrap:wrap;gap:24px">
            <div style="display:flex;align-items:center;gap:12px">
                <span class="d-card__ico d-card__ico--sky" style="width:36px;height:36px;border-radius:9px;flex-shrink:0"><i class="fas fa-calendar"></i></span>
                <div>
                    <div style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);margin-bottom:2px">Gestion Actual</div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px">{{ $gestionActual->nombre }} <span class="d-badge d-badge--green">Activo</span></div>
                </div>
            </div>
            @if($periodoActual)
            <div style="display:flex;align-items:center;gap:12px">
                <span class="d-card__ico d-card__ico--sky" style="width:36px;height:36px;border-radius:9px;flex-shrink:0"><i class="fas fa-calendar-week"></i></span>
                <div>
                    <div style="font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);margin-bottom:2px">Periodo Actual</div>
                    <div style="font-size:.88rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px">{{ $periodoActual->nombre }} <span class="d-badge d-badge--sky">Activo</span></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

@endif
@endsection

@section('css')
<style>
@keyframes fadeUp    { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
@keyframes pulseRing { 0%{transform:scale(1);opacity:.9} 70%{transform:scale(1.9);opacity:0} 100%{opacity:0} }
@keyframes orbFloat  { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-18px)} }
.d-kpi,.d-card,.d-stats-row{animation:fadeUp .45s cubic-bezier(.22,1,.36,1) both}

.d-warn-box{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:var(--amber-bg);border:1px solid rgba(245,158,11,.25);border-radius:var(--radius);color:var(--amber)}
.d-warn-box i{font-size:1.2rem;margin-top:2px}
.d-warn-box strong{display:block;margin-bottom:4px;font-size:.88rem}
.d-warn-box p{font-size:.8rem;margin:0;opacity:.85}

.d-hero{position:relative;overflow:hidden;padding:32px 28px 28px;background:linear-gradient(135deg,#e0f2fe 0%,#f0f9ff 55%,#bae6fd 100%);border-bottom:1px solid var(--border)}
[data-theme="dark"] .d-hero{background:linear-gradient(135deg,#071223 0%,#0d1f35 55%,#071223 100%)}
.d-hero__orb{position:absolute;border-radius:50%;filter:blur(55px);pointer-events:none;animation:orbFloat 7s ease-in-out infinite}
.d-hero__orb--1{width:260px;height:260px;top:-70px;right:4%;background:rgba(14,165,233,.15)}
.d-hero__orb--2{width:160px;height:160px;bottom:-30px;right:22%;background:rgba(56,189,248,.1);animation-delay:3.5s}
.d-hero__content{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.d-eyebrow{display:flex;align-items:center;gap:8px;font-size:.67rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--brand);margin-bottom:8px}
.d-eyebrow__dot{width:7px;height:7px;border-radius:50%;background:var(--brand);animation:pulseRing 2s infinite}
.d-hero__title{font-size:1.95rem;font-weight:800;color:var(--text);margin:0 0 6px;line-height:1.1;letter-spacing:-.02em}
.d-hero__accent{color:var(--brand)}
.d-hero__sub{font-size:.82rem;color:var(--text2);margin:0}
.d-hero__pills{display:flex;flex-wrap:wrap;gap:8px}
.d-pill{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:100px;font-size:.73rem;font-weight:600;border:1px solid transparent}
.d-pill--sky  {background:rgba(14,165,233,.1);border-color:rgba(14,165,233,.25);color:var(--brand)}
.d-pill--green{background:rgba(16,185,129,.1);border-color:rgba(16,185,129,.2);color:var(--green)}

.d-kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;padding:24px 28px 0}
@media(max-width:1100px){.d-kpi-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.d-kpi-grid{grid-template-columns:1fr}}

.d-kpi{position:relative;overflow:hidden;display:flex;align-items:center;gap:16px;padding:20px 18px;border-radius:var(--radius);border:1px solid var(--border);background:var(--surface);text-decoration:none!important;transition:transform .25s cubic-bezier(.22,1,.36,1),box-shadow .25s,border-color .25s}
.d-kpi:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--border2)}
.d-kpi:hover .d-kpi__glow{opacity:1}
.d-kpi:hover .d-kpi__arrow{opacity:1;transform:translate(0,0)}
.d-kpi__glow{position:absolute;inset:0;opacity:0;pointer-events:none;transition:opacity .3s;border-radius:inherit}
.d-kpi__icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;flex-shrink:0}
.d-kpi__label{font-size:.64rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--muted);margin-bottom:3px}
.d-kpi__value{font-size:1.85rem;font-weight:800;color:var(--text);line-height:1}
.d-kpi__sub{margin-top:5px}
.d-kpi__arrow{position:absolute;top:12px;right:12px;font-size:.7rem;opacity:0;transform:translate(-4px,-4px);transition:all .25s;color:var(--muted)}
.d-kpi--sky  .d-kpi__icon{background:rgba(14,165,233,.12);color:var(--brand)}
.d-kpi--sky  .d-kpi__glow{background:radial-gradient(ellipse at 0% 0%,rgba(14,165,233,.12),transparent 70%)}
.d-kpi--green .d-kpi__icon{background:rgba(16,185,129,.1);color:var(--green)}
.d-kpi--green .d-kpi__glow{background:radial-gradient(ellipse at 0% 0%,rgba(16,185,129,.1),transparent 70%)}
.d-kpi--amber .d-kpi__icon{background:rgba(245,158,11,.1);color:var(--amber)}
.d-kpi--amber .d-kpi__glow{background:radial-gradient(ellipse at 0% 0%,rgba(245,158,11,.1),transparent 70%)}
.d-kpi--rose  .d-kpi__icon{background:rgba(244,63,94,.1);color:var(--rose)}
.d-kpi--rose  .d-kpi__glow{background:radial-gradient(ellipse at 0% 0%,rgba(244,63,94,.1),transparent 70%)}

.d-stats-row{display:flex;flex-wrap:wrap;gap:12px;padding:16px 28px 0}
.d-stat{display:flex;align-items:center;gap:10px;padding:12px 16px;background:var(--surface);border:1px solid var(--border);border-radius:10px;flex:1;min-width:120px}
.d-stat__icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0}
.d-stat__icon--sky   {background:rgba(14,165,233,.12);color:var(--brand)}
.d-stat__icon--green {background:rgba(16,185,129,.1); color:var(--green)}
.d-stat__icon--rose  {background:rgba(244,63,94,.1);  color:var(--rose)}
.d-stat__icon--amber {background:rgba(245,158,11,.1); color:var(--amber)}
.d-stat__icon--violet{background:rgba(139,92,246,.1); color:var(--violet)}
.d-stat__val{font-size:1.1rem;font-weight:800;color:var(--text);line-height:1}
.d-stat__lbl{font-size:.67rem;font-weight:500;color:var(--muted);margin-top:2px}

.d-badge{display:inline-flex;align-items:center;gap:4px;font-size:.66rem;font-weight:700;padding:3px 9px;border-radius:100px}
.d-badge i{font-size:.4rem}
.d-badge--sky    {background:rgba(14,165,233,.1); color:var(--brand)}
.d-badge--green  {background:rgba(16,185,129,.1); color:var(--green)}
.d-badge--amber  {background:rgba(245,158,11,.1); color:var(--amber)}
.d-badge--rose   {background:rgba(244,63,94,.1);  color:var(--rose)}
.d-badge--success{background:rgba(16,185,129,.1); color:var(--green)}
.d-badge--warning{background:rgba(245,158,11,.1); color:var(--amber)}
.d-badge--danger {background:rgba(244,63,94,.1);  color:var(--rose)}
.d-badge--info   {background:rgba(14,165,233,.1); color:var(--brand)}

.d-row2{display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:16px 28px}
@media(max-width:900px){.d-row2{grid-template-columns:1fr}}

.d-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:box-shadow .25s,border-color .25s}
.d-card:hover{box-shadow:var(--shadow);border-color:var(--border2)}
.d-card__hdr{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border)}
.d-card__title{display:flex;align-items:center;gap:8px;font-size:.75rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:var(--text)}
.d-card__ico{width:26px;height:26px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0}
.d-card__ico--sky  {background:rgba(14,165,233,.12);color:var(--brand)}
.d-card__ico--amber{background:rgba(245,158,11,.1); color:var(--amber)}
.d-card__ico--rose {background:rgba(244,63,94,.1);  color:var(--rose)}
.d-card__tag{font-size:.65rem;font-weight:700;color:var(--muted);background:var(--surface2);border:1px solid var(--border);border-radius:100px;padding:3px 10px}
.d-card__ftr{padding:12px 18px;border-top:1px solid var(--border);background:var(--surface2)}

.d-list-item{display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--border);transition:background .15s}
.d-list-item:last-child{border-bottom:none}
.d-list-item:hover{background:var(--surface2)}
.d-list-av{width:36px;height:36px;border-radius:9px;background:rgba(14,165,233,.12);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.82rem;flex-shrink:0}
.d-list-av--amber{background:rgba(245,158,11,.1);color:var(--amber)}
.d-list-av--rose {background:rgba(244,63,94,.1); color:var(--rose)}
.d-list-name{font-size:.82rem;font-weight:600;color:var(--text)}
.d-list-meta{font-size:.7rem;color:var(--muted);margin-top:2px;display:flex;align-items:center;gap:4px}
.d-list-meta i{color:var(--brand);font-size:.65rem}

.d-quick-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.d-quick{display:flex;flex-direction:column;align-items:center;gap:7px;padding:16px 8px;border-radius:10px;text-decoration:none;font-size:.73rem;font-weight:700;transition:all .2s;text-align:center;border:2px solid transparent}
.d-quick i{font-size:1.2rem}
.d-quick:hover{border-color:currentColor;transform:translateY(-2px);box-shadow:var(--shadow)}
.d-quick--sky   {background:rgba(14,165,233,.08);color:var(--brand)}
.d-quick--amber {background:rgba(245,158,11,.08);color:var(--amber)}
.d-quick--rose  {background:rgba(244,63,94,.08); color:var(--rose)}
.d-quick--violet{background:rgba(139,92,246,.08);color:var(--violet)}
.d-quick--slate {background:rgba(100,116,139,.08);color:var(--slate)}
.d-quick--green {background:rgba(16,185,129,.08);color:var(--green)}

.d-score{display:inline-flex;align-items:center;justify-content:center;min-width:36px;padding:4px 10px;border-radius:7px;font-size:.88rem;font-weight:800}
.d-score--green{background:rgba(16,185,129,.1); color:var(--green)}
.d-score--sky  {background:rgba(14,165,233,.1); color:var(--brand)}
.d-score--rose {background:rgba(244,63,94,.1);  color:var(--rose)}

.d-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s;font-family:var(--ff)}
.d-btn--block{width:100%}
.d-btn--sky    {background:var(--brand);color:#fff}
.d-btn--sky:hover{background:var(--brand-d);box-shadow:0 4px 14px rgba(14,165,233,.35);color:#fff}
.d-btn--outline{background:transparent;color:var(--brand);border:2px solid var(--brand)}
.d-btn--outline:hover{background:var(--brand-bg)}

.d-empty{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;padding:32px 16px;color:var(--muted)}
.d-empty i{font-size:1.8rem;opacity:.3}
.d-empty span{font-size:.8rem;font-weight:600}

@media(max-width:700px){
    .d-hero{padding:20px 16px}
    .d-kpi-grid,.d-stats-row,.d-row2{padding-left:16px;padding-right:16px}
}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.d-kpi__value[data-count]').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-count')) || 0;
        var delay  = parseFloat(el.closest('.d-kpi').style.animationDelay || 0) * 1000 + 400;
        setTimeout(function() {
            var start = 0, step = Math.max(target / 55, 1);
            (function tick() {
                start = Math.min(start + step, target);
                el.textContent = Math.round(start).toLocaleString();
                if (start < target) requestAnimationFrame(tick);
            })();
        }, delay);
    });
    @if(session('mensaje'))
    Swal.fire({
        icon: '{{ session("icono") }}',
        title: '{{ session("mensaje") }}',
        showConfirmButton: false,
        timer: 2500,
    });
    @endif
});
</script>
@endsection