@extends('layouts.tutor')

@section('title', 'Inicio – Portal Familiar')

@section('page_title')
    Portal <span>Familiar</span>
@endsection

@section('content')

@if(!Auth::user()->persona || !Auth::user()->persona->tutor)
    <div class="t-alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Perfil incompleto</strong>
            <p>Tu perfil de tutor no está asociado correctamente. Contacta al administrador del colegio.</p>
        </div>
    </div>
@else

{{-- HERO --}}
<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__orb t-hero__orb--2"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Portal Familiar Activo</div>
            <h1 class="t-hero__title">¡Hola, <span class="t-hero__accent">{{ Auth::user()->nombre_completo }}</span>!</h1>
            <p class="t-hero__sub">Aquí puedes seguir el progreso y bienestar de tus hijos en el Colegio Adonai.</p>
        </div>
        <div class="t-hero__right">
            <div class="t-pill t-pill--amber"><i class="fas fa-calendar-check"></i>{{ now()->format('d M Y') }}</div>
            <div class="t-pill t-pill--green"><i class="fas fa-circle"></i>En línea</div>
        </div>
    </div>
</div>

{{-- Alertas --}}
@if(count($alertas) > 0)
<div class="t-alerts-wrap">
    <div class="t-alerts-header"><span class="t-alerts-pulse"></span><span class="t-alerts-title">Alertas – Requieren tu atención</span></div>
    <div class="t-alerts-grid">
        @foreach($alertas as $alerta)
        <div class="t-alert-item">
            <i class="{{ $alerta['icono'] }}"></i>
            <span>{{ $alerta['mensaje'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- KPI CARDS --}}
<div class="t-kpi-grid">
    <a href="{{ route('tutor.mis-estudiantes') }}" class="t-kpi t-kpi--amber" style="animation-delay:.05s">
        <div class="t-kpi__icon"><i class="fas fa-child"></i></div>
        <div class="t-kpi__body">
            <div class="t-kpi__label">Mis Estudiantes</div>
            <div class="t-kpi__value" data-count="{{ $estudiantes->count() }}">0</div>
            <div class="t-kpi__sub"><span class="t-badge t-badge--amber"><i class="fas fa-circle"></i> A mi cargo</span></div>
        </div>
        <div class="t-kpi__glow"></div>
        <div class="t-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('tutor.reportes.index') }}" class="t-kpi t-kpi--blue" style="animation-delay:.1s">
        <div class="t-kpi__icon"><i class="fas fa-file-alt"></i></div>
        <div class="t-kpi__body">
            <div class="t-kpi__label">Reportes</div>
            <div class="t-kpi__value" data-count="{{ $totalReportes }}">0</div>
            <div class="t-kpi__sub"><span class="t-badge t-badge--blue"><i class="fas fa-circle"></i> Disponibles</span></div>
        </div>
        <div class="t-kpi__glow"></div>
        <div class="t-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('tutor.comportamientos') }}" class="t-kpi t-kpi--rose" style="animation-delay:.15s">
        <div class="t-kpi__icon"><i class="fas fa-bell"></i></div>
        <div class="t-kpi__body">
            <div class="t-kpi__label">Notificaciones</div>
            <div class="t-kpi__value" data-count="{{ $ultimosComportamientos->count() }}">0</div>
            <div class="t-kpi__sub"><span class="t-badge t-badge--rose"><i class="fas fa-circle"></i> Recientes</span></div>
        </div>
        <div class="t-kpi__glow"></div>
        <div class="t-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('tutor.asistencias') }}" class="t-kpi t-kpi--green" style="animation-delay:.2s">
        <div class="t-kpi__icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="t-kpi__body">
            <div class="t-kpi__label">Asistencias</div>
            <div class="t-kpi__value">–</div>
            <div class="t-kpi__sub"><span class="t-badge t-badge--green"><i class="fas fa-circle"></i> Ver detalle</span></div>
        </div>
        <div class="t-kpi__glow"></div>
        <div class="t-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
</div>

{{-- FILA PRINCIPAL --}}
<div class="t-row-2">
    {{-- Mis Estudiantes --}}
    <div class="t-card" style="animation-delay:.25s">
        <div class="t-card__header">
            <div class="t-card__title"><span class="t-card__icon t-card__icon--amber"><i class="fas fa-child"></i></span>Mis Estudiantes</div>
            <span class="t-card__tag">{{ $estudiantes->count() }} registrados</span>
        </div>
        <div class="t-card__body" style="padding:0">
            @if($estudiantes->count() > 0)
                @foreach($estudiantes as $est)
                <div class="t-student-item">
                    <div class="t-student-avatar">{{ strtoupper(substr($est->persona->nombres,0,1)) }}</div>
                    <div class="t-student-info">
                        <div class="t-student-name">{{ $est->persona->apellidos }}, {{ $est->persona->nombres }}</div>
                        <div class="t-student-meta">DNI: {{ $est->persona->dni }}</div>
                    </div>
                    @if($est->grado)
                        <span class="t-badge t-badge--amber">{{ $est->grado->nombre }}</span>
                    @endif
                </div>
                @endforeach
            @else
                <div class="t-empty"><i class="fas fa-user-graduate"></i><span>No tienes estudiantes asignados aún</span></div>
            @endif
        </div>
        <div class="t-card__footer">
            <a href="{{ route('tutor.mis-estudiantes') }}" class="t-btn t-btn--amber t-btn--block"><i class="fas fa-eye"></i> Ver todos mis estudiantes</a>
        </div>
    </div>

    {{-- Accesos Rápidos --}}
    <div class="t-card" style="animation-delay:.3s">
        <div class="t-card__header">
            <div class="t-card__title"><span class="t-card__icon t-card__icon--amber"><i class="fas fa-bolt"></i></span>Accesos Rápidos</div>
        </div>
        <div class="t-card__body">
            <div class="t-quick-grid">
                <a href="{{ route('tutor.mis-estudiantes') }}" class="t-quick t-quick--amber"><i class="fas fa-child"></i><span>Mis Hijos</span></a>
                <a href="{{ route('tutor.notas') }}" class="t-quick t-quick--blue"><i class="fas fa-star"></i><span>Ver Notas</span></a>
                <a href="{{ route('tutor.asistencias') }}" class="t-quick t-quick--green"><i class="fas fa-clipboard-check"></i><span>Asistencias</span></a>
                <a href="{{ route('tutor.comportamientos') }}" class="t-quick t-quick--rose"><i class="fas fa-user-check"></i><span>Comportamiento</span></a>
                <a href="{{ route('tutor.reportes.index') }}" class="t-quick t-quick--violet"><i class="fas fa-chart-line"></i><span>Reportes</span></a>
                <a href="{{ route('tutor.horarios') }}" class="t-quick t-quick--slate"><i class="fas fa-calendar-alt"></i><span>Horarios</span></a>
            </div>
        </div>
    </div>
</div>

{{-- FILA INFERIOR --}}
<div class="t-row-2" style="margin-top:16px">
    {{-- Últimas Notificaciones --}}
    <div class="t-card" style="animation-delay:.35s">
        <div class="t-card__header">
            <div class="t-card__title"><span class="t-card__icon t-card__icon--rose"><i class="fas fa-bell"></i></span>Últimas Notificaciones</div>
            <span class="t-card__tag">{{ $ultimosComportamientos->count() }}</span>
        </div>
        <div class="t-card__body" style="padding:0">
            @if($ultimosComportamientos->count() > 0)
                @foreach($ultimosComportamientos as $comp)
                <div class="t-activity-item">
                    <div class="t-activity-avatar t-activity-avatar--amber">
                        {{ strtoupper(substr($comp->estudiante->persona->nombres,0,1)) }}{{ strtoupper(substr($comp->estudiante->persona->apellidos,0,1)) }}
                    </div>
                    <div class="t-activity-info">
                        <div class="t-activity-name">{{ $comp->estudiante->persona->apellidos }}, {{ $comp->estudiante->persona->nombres }}</div>
                        <div class="t-activity-meta">{{ \Str::limit($comp->descripcion, 70) }}</div>
                    </div>
                    <div class="t-activity-time">{{ $comp->fecha_formateada ?? '' }}</div>
                </div>
                @endforeach
            @else
                <div class="t-empty"><i class="fas fa-bell-slash"></i><span>Sin notificaciones recientes</span></div>
            @endif
        </div>
        <div class="t-card__footer">
            <a href="{{ route('tutor.comportamientos') }}" class="t-btn t-btn--outline t-btn--block"><i class="fas fa-eye"></i> Ver todos</a>
        </div>
    </div>

    {{-- Últimos Reportes --}}
    <div class="t-card" style="animation-delay:.4s">
        <div class="t-card__header">
            <div class="t-card__title"><span class="t-card__icon t-card__icon--blue"><i class="fas fa-file-alt"></i></span>Últimos Reportes</div>
            <span class="t-card__tag">{{ $ultimosReportes->count() }}</span>
        </div>
        <div class="t-card__body" style="padding:0">
            @if($ultimosReportes->count() > 0)
                @foreach($ultimosReportes as $rep)
                <div class="t-activity-item">
                    <div class="t-activity-avatar">
                        {{ strtoupper(substr($rep->estudiante->persona->nombres,0,1)) }}{{ strtoupper(substr($rep->estudiante->persona->apellidos,0,1)) }}
                    </div>
                    <div class="t-activity-info">
                        <div class="t-activity-name">{{ $rep->estudiante->persona->apellidos }}</div>
                        <div class="t-activity-meta">{{ $rep->periodo->nombre }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        @if($rep->promedio_general)
                            <span class="t-score t-score--{{ $rep->promedio_general >= 14 ? 'green' : ($rep->promedio_general >= 11 ? 'blue' : 'red') }}">
                                {{ number_format($rep->promedio_general,1) }}
                            </span>
                        @endif
                        <a href="{{ route('tutor.reportes.show', $rep->id) }}" class="t-btn t-btn--sm t-btn--blue"><i class="fas fa-eye"></i></a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="t-empty"><i class="fas fa-folder-open"></i><span>No hay reportes disponibles aún</span></div>
            @endif
        </div>
        <div class="t-card__footer">
            <a href="{{ route('tutor.reportes.index') }}" class="t-btn t-btn--outline t-btn--block"><i class="fas fa-eye"></i> Ver todos</a>
        </div>
    </div>
</div>

@endif
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>

/* ── VARIABLES – modo claro ── */
:root {
    --t-bg:       #faf7f2;
    --t-surface:  #ffffff;
    --t-surf2:    #fdf8f0;
    --t-border:   rgba(0,0,0,.08);
    --t-border2:  rgba(0,0,0,.14);
    --t-text:     #1c1917;
    --t-muted:    #78716c;
    --t-shadow:   0 4px 20px rgba(0,0,0,.08);
    --t-shadowlg: 0 12px 40px rgba(0,0,0,.12);
    --t-hero-bg:  linear-gradient(135deg,#fefce8 0%,#fff7ed 60%,#fef3c7 100%);
    --t-orb1:     rgba(245,158,11,.15);
    --t-orb2:     rgba(234,88,12,.10);
    --t-amber:    #d97706;
    --t-blue:     #2563eb;
    --t-green:    #059669;
    --t-rose:     #e11d48;
    --t-violet:   #7c3aed;
    --t-slate:    #64748b;
    --t-r:        14px;
    --t-font:     'Sora', system-ui, sans-serif;
}

/* ── VARIABLES – modo oscuro ── */
[data-theme="dark"] {
    --t-bg:       #1a1200;
    --t-surface:  #231a00;
    --t-surf2:    #2c2200;
    --t-border:   rgba(255,255,255,.07);
    --t-border2:  rgba(255,255,255,.13);
    --t-text:     #fef3c7;
    --t-muted:    #a8966a;
    --t-shadow:   0 4px 20px rgba(0,0,0,.5);
    --t-shadowlg: 0 12px 40px rgba(0,0,0,.6);
    --t-hero-bg:  linear-gradient(135deg,#1a1200 0%,#231a00 60%,#2c1d00 100%);
    --t-orb1:     rgba(245,158,11,.12);
    --t-orb2:     rgba(251,191,36,.08);
    --t-amber:    #fbbf24;
    --t-blue:     #60a5fa;
    --t-green:    #34d399;
    --t-rose:     #fb7185;
    --t-violet:   #c084fc;
    --t-slate:    #94a3b8;
}

body { font-family: var(--t-font); background: var(--t-bg); color: var(--t-text); }

/* Animaciones */
@keyframes fadeUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
@keyframes pulseRing { 0%{transform:scale(1);opacity:.9} 70%{transform:scale(1.8);opacity:0} 100%{transform:scale(1.8);opacity:0} }
@keyframes orbFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
.t-kpi, .t-card, .t-alerts-wrap { animation: fadeUp .5s cubic-bezier(.22,1,.36,1) both; }

/* ── HERO ── */
.t-hero {
    position:relative; overflow:hidden;
    padding:36px 32px 32px;
    background: var(--t-hero-bg);
    border-bottom: 1px solid var(--t-border);
}
.t-hero__orb { position:absolute; border-radius:50%; filter:blur(50px); pointer-events:none; animation:orbFloat 7s ease-in-out infinite; }
.t-hero__orb--1 { width:280px;height:280px;top:-60px;right:5%;  background:var(--t-orb1); }
.t-hero__orb--2 { width:180px;height:180px;bottom:-30px;right:20%;background:var(--t-orb2);animation-delay:3s; }
.t-hero__content { position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap; }
.t-eyebrow { display:flex;align-items:center;gap:8px;font-size:.68rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--t-amber);margin-bottom:8px; }
.t-eyebrow__dot { width:7px;height:7px;border-radius:50%;background:var(--t-amber);animation:pulseRing 2s infinite; }
.t-hero__title { font-size:2.1rem;font-weight:800;color:var(--t-text);margin:0 0 6px;line-height:1.1;letter-spacing:-.02em; }
.t-hero__accent { color:var(--t-amber); }
.t-hero__sub { font-size:.83rem;color:var(--t-muted);margin:0; }
.t-hero__right { display:flex;flex-wrap:wrap;gap:8px; }
.t-pill { display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:100px;font-size:.74rem;font-weight:600;border:1px solid transparent;backdrop-filter:blur(10px); }
.t-pill--amber { background:rgba(217,119,6,.1);border-color:rgba(217,119,6,.2);color:var(--t-amber); }
.t-pill--green  { background:rgba(5,150,105,.1);border-color:rgba(5,150,105,.2);color:var(--t-green); }

/* ── ALERTS ── */
.t-alerts-wrap { margin:0;padding:16px 32px;background:rgba(220,38,38,.05);border-bottom:1px solid rgba(220,38,38,.15); }
.t-alerts-header { display:flex;align-items:center;gap:8px;margin-bottom:10px; }
.t-alerts-pulse { width:7px;height:7px;border-radius:50%;background:var(--t-rose);animation:pulseRing 1.8s infinite; }
.t-alerts-title { font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--t-rose); }
.t-alerts-grid { display:flex;flex-wrap:wrap;gap:8px; }
.t-alert-item { display:flex;align-items:center;gap:8px;padding:8px 14px;border-radius:9px;background:rgba(220,38,38,.07);border:1px solid rgba(220,38,38,.2);font-size:.78rem;font-weight:600;color:var(--t-rose); }
.t-alert-warning { display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:rgba(217,119,6,.08);border:1px solid rgba(217,119,6,.25);border-radius:var(--t-r);margin:20px 32px;color:var(--t-amber); }
.t-alert-warning strong { display:block;margin-bottom:4px; }
.t-alert-warning p { font-size:.82rem;margin:0;opacity:.85; }

/* ── KPI GRID ── */
.t-kpi-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:14px;padding:24px 32px 0; }
@media(max-width:1100px){.t-kpi-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px){.t-kpi-grid{grid-template-columns:1fr;}}

.t-kpi {
    position:relative;overflow:hidden;
    display:flex;align-items:center;gap:16px;
    padding:20px 18px;border-radius:var(--t-r);
    border:1px solid var(--t-border);background:var(--t-surface);
    text-decoration:none !important;cursor:pointer;
    transition:transform .25s cubic-bezier(.22,1,.36,1), box-shadow .25s ease, border-color .25s;
}
.t-kpi:hover { transform:translateY(-4px);box-shadow:var(--t-shadowlg);border-color:var(--t-border2); }
.t-kpi:hover .t-kpi__glow { opacity:1; }
.t-kpi:hover .t-kpi__arrow { opacity:1;transform:translate(0,0); }
.t-kpi__glow { position:absolute;inset:0;opacity:0;pointer-events:none;transition:opacity .3s;border-radius:inherit; }
.t-kpi__icon { width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0; }
.t-kpi__label { font-size:.65rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--t-muted);margin-bottom:3px; }
.t-kpi__value { font-size:1.9rem;font-weight:800;color:var(--t-text);line-height:1; }
.t-kpi__sub { margin-top:5px; }
.t-kpi__arrow { position:absolute;top:12px;right:12px;font-size:.7rem;opacity:0;transform:translate(-4px,-4px);transition:all .25s;color:var(--t-muted); }

.t-kpi--amber .t-kpi__icon { background:rgba(217,119,6,.12);color:var(--t-amber); }
.t-kpi--amber .t-kpi__glow { background:radial-gradient(ellipse at 0% 0%,rgba(217,119,6,.12),transparent 70%); }
.t-kpi--blue  .t-kpi__icon { background:rgba(37,99,235,.1); color:var(--t-blue); }
.t-kpi--blue  .t-kpi__glow { background:radial-gradient(ellipse at 0% 0%,rgba(37,99,235,.1),transparent 70%); }
.t-kpi--rose  .t-kpi__icon { background:rgba(225,29,72,.1); color:var(--t-rose); }
.t-kpi--rose  .t-kpi__glow { background:radial-gradient(ellipse at 0% 0%,rgba(225,29,72,.1),transparent 70%); }
.t-kpi--green .t-kpi__icon { background:rgba(5,150,105,.1); color:var(--t-green); }
.t-kpi--green .t-kpi__glow { background:radial-gradient(ellipse at 0% 0%,rgba(5,150,105,.1),transparent 70%); }

[data-theme="dark"] .t-kpi--amber .t-kpi__icon { background:rgba(251,191,36,.15); }
[data-theme="dark"] .t-kpi--blue  .t-kpi__icon { background:rgba(96,165,250,.15); }
[data-theme="dark"] .t-kpi--rose  .t-kpi__icon { background:rgba(251,113,133,.15);}
[data-theme="dark"] .t-kpi--green .t-kpi__icon { background:rgba(52,211,153,.15); }

/* ── BADGES ── */
.t-badge { display:inline-flex;align-items:center;gap:4px;font-size:.66rem;font-weight:600;padding:3px 9px;border-radius:100px; }
.t-badge i { font-size:.4rem; }
.t-badge--amber { background:rgba(217,119,6,.1); color:var(--t-amber); }
.t-badge--blue  { background:rgba(37,99,235,.1);  color:var(--t-blue); }
.t-badge--green { background:rgba(5,150,105,.1);  color:var(--t-green); }
.t-badge--rose  { background:rgba(225,29,72,.1);  color:var(--t-rose); }
[data-theme="dark"] .t-badge--amber { background:rgba(251,191,36,.15); }
[data-theme="dark"] .t-badge--blue  { background:rgba(96,165,250,.15); }
[data-theme="dark"] .t-badge--green { background:rgba(52,211,153,.15); }
[data-theme="dark"] .t-badge--rose  { background:rgba(251,113,133,.15);}

/* ── CARDS ── */
.t-row-2 { display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:20px 32px 0; }
@media(max-width:900px){ .t-row-2 { grid-template-columns:1fr; } }

.t-card { background:var(--t-surface);border:1px solid var(--t-border);border-radius:var(--t-r);overflow:hidden;transition:box-shadow .25s,border-color .25s; }
.t-card:hover { box-shadow:var(--t-shadow);border-color:var(--t-border2); }
.t-card__header { display:flex;align-items:center;justify-content:space-between;padding:16px 18px;border-bottom:1px solid var(--t-border); }
.t-card__title { display:flex;align-items:center;gap:8px;font-size:.74rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--t-text); }
.t-card__icon { width:24px;height:24px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.7rem; }
.t-card__icon--amber { background:rgba(217,119,6,.12);color:var(--t-amber); }
.t-card__icon--blue  { background:rgba(37,99,235,.1); color:var(--t-blue); }
.t-card__icon--rose  { background:rgba(225,29,72,.1); color:var(--t-rose); }
[data-theme="dark"] .t-card__icon--amber { background:rgba(251,191,36,.15); }
[data-theme="dark"] .t-card__icon--blue  { background:rgba(96,165,250,.15); }
[data-theme="dark"] .t-card__icon--rose  { background:rgba(251,113,133,.15);}
.t-card__tag { font-size:.62rem;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--t-muted);background:var(--t-surf2);border:1px solid var(--t-border);border-radius:100px;padding:3px 9px; }
.t-card__body { padding:18px; }
.t-card__footer { padding:12px 18px;border-top:1px solid var(--t-border); }

/* ── STUDENTS ── */
.t-student-item { display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid var(--t-border);transition:background .15s; }
.t-student-item:last-child { border-bottom:none; }
.t-student-item:hover { background:var(--t-surf2); }
.t-student-avatar { width:36px;height:36px;border-radius:10px;background:rgba(217,119,6,.12);color:var(--t-amber);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0; }
[data-theme="dark"] .t-student-avatar { background:rgba(251,191,36,.15); }
.t-student-name { font-size:.82rem;font-weight:600;color:var(--t-text); }
.t-student-meta { font-size:.7rem;color:var(--t-muted);margin-top:2px; }

/* ── QUICK GRID ── */
.t-quick-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:10px; }
.t-quick { display:flex;flex-direction:column;align-items:center;gap:7px;padding:16px 8px;border-radius:11px;text-decoration:none;font-size:.73rem;font-weight:700;transition:all .2s;text-align:center;border:2px solid transparent; }
.t-quick i { font-size:1.25rem; }
.t-quick--amber { background:rgba(217,119,6,.08); color:var(--t-amber); }
.t-quick--blue  { background:rgba(37,99,235,.08); color:var(--t-blue); }
.t-quick--green { background:rgba(5,150,105,.08); color:var(--t-green); }
.t-quick--rose  { background:rgba(225,29,72,.08); color:var(--t-rose); }
.t-quick--violet{ background:rgba(124,58,237,.08);color:var(--t-violet); }
.t-quick--slate { background:rgba(100,116,139,.08);color:var(--t-slate); }
.t-quick:hover  { border-color:currentColor;transform:translateY(-2px);box-shadow:var(--t-shadow); }
[data-theme="dark"] .t-quick--amber { background:rgba(251,191,36,.1); }
[data-theme="dark"] .t-quick--blue  { background:rgba(96,165,250,.1); }
[data-theme="dark"] .t-quick--green { background:rgba(52,211,153,.1); }
[data-theme="dark"] .t-quick--rose  { background:rgba(251,113,133,.1);}
[data-theme="dark"] .t-quick--violet{ background:rgba(192,132,252,.1);}
[data-theme="dark"] .t-quick--slate { background:rgba(148,163,184,.1);}

/* ── ACTIVITY ── */
.t-activity-item { display:flex;align-items:center;gap:12px;padding:11px 18px;border-bottom:1px solid var(--t-border);transition:background .15s; }
.t-activity-item:last-child { border-bottom:none; }
.t-activity-item:hover { background:var(--t-surf2); }
.t-activity-avatar { width:32px;height:32px;border-radius:9px;background:rgba(37,99,235,.1);color:var(--t-blue);display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:700;flex-shrink:0; }
.t-activity-avatar--amber { background:rgba(217,119,6,.1);color:var(--t-amber); }
[data-theme="dark"] .t-activity-avatar { background:rgba(96,165,250,.15); }
[data-theme="dark"] .t-activity-avatar--amber { background:rgba(251,191,36,.15); }
.t-activity-name { font-size:.79rem;font-weight:600;color:var(--t-text); }
.t-activity-meta { font-size:.7rem;color:var(--t-muted);margin-top:2px; }
.t-activity-time { font-size:.65rem;color:var(--t-muted);white-space:nowrap;margin-left:auto; }

/* ── SCORE ── */
.t-score { font-size:.76rem;font-weight:700;padding:3px 9px;border-radius:100px; }
.t-score--green { background:rgba(5,150,105,.1); color:var(--t-green); }
.t-score--blue  { background:rgba(37,99,235,.1);  color:var(--t-blue); }
.t-score--red   { background:rgba(220,38,38,.1);  color:var(--t-rose); }

/* ── BUTTONS ── */
.t-btn { display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s;font-family:var(--t-font); }
.t-btn--amber   { background:var(--t-amber);color:#fff; }
.t-btn--amber:hover { background:#b45309;box-shadow:0 4px 12px rgba(217,119,6,.35);color:#fff; }
.t-btn--blue    { background:var(--t-blue);color:#fff; }
.t-btn--blue:hover { color:#fff; }
.t-btn--outline { background:transparent;color:var(--t-amber);border:2px solid var(--t-amber); }
.t-btn--outline:hover { background:rgba(217,119,6,.08); }
.t-btn--block   { width:100%; }
.t-btn--sm      { padding:5px 11px;font-size:.72rem; }

/* ── EMPTY ── */
.t-empty { text-align:center;padding:28px 16px;color:var(--t-muted); }
.t-empty i { font-size:2rem;margin-bottom:8px;display:block;opacity:.3; }
.t-empty span { font-size:.8rem;font-weight:600; }

/* Padding bottom final */
.t-row-2:last-child { padding-bottom: 32px; }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* Contadores animados */
    document.querySelectorAll('.t-kpi__value[data-count]').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-count')) || 0;
        var delay  = parseFloat(el.closest('.t-kpi').style.animationDelay || 0) * 1000 + 400;
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
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    Swal.fire({
        icon: '{{ session('icono') }}',
        title: '{{ session('mensaje') }}',
        showConfirmButton: false,
        timer: 2500,
        background: isDark ? '#231a00' : '#ffffff',
        color: isDark ? '#fef3c7' : '#1c1917',
    });
    @endif
});
</script>
@endsection