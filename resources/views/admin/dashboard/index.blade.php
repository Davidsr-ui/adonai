@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

{{-- HERO HEADER --}}
<div class="dash-hero">
    <div class="dash-hero__orb dash-hero__orb--1"></div>
    <div class="dash-hero__orb dash-hero__orb--2"></div>
    <div class="dash-hero__orb dash-hero__orb--3"></div>
    <div class="dash-hero__content">
        <div class="dash-hero__left">
            <div class="dash-eyebrow">
                <span class="dash-eyebrow__dot"></span>
                Sistema Académico Activo
            </div>
            <h1 class="dash-hero__title">Panel de <span class="dash-hero__accent">Control</span></h1>
            <p class="dash-hero__sub">Resumen ejecutivo · Gestión académica y administrativa</p>
        </div>
        <div class="dash-hero__right">
            @if($gestionActiva)
            <div class="dash-pill dash-pill--green">
                <i class="fas fa-calendar-check"></i>
                {{ $gestionActiva->nombre }}
            </div>
            @endif
            @if($periodoActivo)
            <div class="dash-pill dash-pill--blue">
                <i class="fas fa-clock"></i>
                {{ $periodoActivo->nombre }}
            </div>
            @endif
            <div class="dash-pill dash-pill--muted">
                <i class="fas fa-circle-dot"></i>
                {{ now()->format('d M Y') }}
            </div>
        </div>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="dash-kpi-grid">
    <a href="{{ route('admin.usuarios.index') }}" class="dash-kpi dash-kpi--blue" style="animation-delay:.05s">
        <div class="dash-kpi__icon"><i class="fas fa-users"></i></div>
        <div class="dash-kpi__body">
            <div class="dash-kpi__label">Usuarios del Sistema</div>
            <div class="dash-kpi__value" data-count="{{ $estadisticas['usuarios']['total'] }}">0</div>
            <div class="dash-kpi__sub">
                <span class="dash-badge dash-badge--green"><i class="fas fa-circle"></i> {{ $estadisticas['usuarios']['activos'] }} activos</span>
            </div>
        </div>
        <div class="dash-kpi__glow"></div>
        <div class="dash-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('admin.estudiantes.index') }}" class="dash-kpi dash-kpi--emerald" style="animation-delay:.1s">
        <div class="dash-kpi__icon"><i class="fas fa-user-graduate"></i></div>
        <div class="dash-kpi__body">
            <div class="dash-kpi__label">Estudiantes</div>
            <div class="dash-kpi__value" data-count="{{ $estadisticas['estudiantes']['total'] }}">0</div>
            <div class="dash-kpi__sub">
                <span class="dash-badge dash-badge--green"><i class="fas fa-circle"></i> {{ $estadisticas['estudiantes']['activos'] }} activos</span>
            </div>
        </div>
        <div class="dash-kpi__glow"></div>
        <div class="dash-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('admin.docentes.index') }}" class="dash-kpi dash-kpi--amber" style="animation-delay:.15s">
        <div class="dash-kpi__icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="dash-kpi__body">
            <div class="dash-kpi__label">Docentes</div>
            <div class="dash-kpi__value" data-count="{{ $estadisticas['docentes']['total'] }}">0</div>
            <div class="dash-kpi__sub">
                <span class="dash-badge dash-badge--green"><i class="fas fa-circle"></i> {{ $estadisticas['docentes']['activos'] }} activos</span>
            </div>
        </div>
        <div class="dash-kpi__glow"></div>
        <div class="dash-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
    <a href="{{ route('admin.tutores.index') }}" class="dash-kpi dash-kpi--violet" style="animation-delay:.2s">
        <div class="dash-kpi__icon"><i class="fas fa-user-tie"></i></div>
        <div class="dash-kpi__body">
            <div class="dash-kpi__label">Tutores</div>
            <div class="dash-kpi__value" data-count="{{ $estadisticas['tutores']['total'] ?? 0 }}">0</div>
            <div class="dash-kpi__sub">
                <span class="dash-badge dash-badge--green"><i class="fas fa-circle"></i> {{ $estadisticas['tutores']['activos'] ?? 0 }} activos</span>
            </div>
        </div>
        <div class="dash-kpi__glow"></div>
        <div class="dash-kpi__arrow"><i class="fas fa-arrow-up-right-from-square"></i></div>
    </a>
</div>

{{-- MÉTRICAS SECUNDARIAS --}}
<div class="dash-metrics-row" style="animation-delay:.25s">
    <div class="dash-metric">
        <div class="dash-metric__icon dash-metric__icon--blue"><i class="fas fa-graduation-cap"></i></div>
        <div class="dash-metric__text">
            <div class="dash-metric__val">{{ $estadisticas['academico']['grados'] }}</div>
            <div class="dash-metric__lbl">Grados Activos</div>
        </div>
    </div>
    <div class="dash-metric-divider"></div>
    <div class="dash-metric">
        <div class="dash-metric__icon dash-metric__icon--rose"><i class="fas fa-book-open"></i></div>
        <div class="dash-metric__text">
            <div class="dash-metric__val">{{ $estadisticas['academico']['cursos'] }}</div>
            <div class="dash-metric__lbl">Cursos</div>
        </div>
    </div>
    <div class="dash-metric-divider"></div>
    <div class="dash-metric">
        <div class="dash-metric__icon dash-metric__icon--green"><i class="fas fa-clipboard-check"></i></div>
        <div class="dash-metric__text">
            <div class="dash-metric__val">{{ $estadisticas['academico']['matriculas_activas'] }}</div>
            <div class="dash-metric__lbl">Matrículas Activas</div>
        </div>
    </div>
    <div class="dash-metric-divider"></div>
    <div class="dash-metric">
        <div class="dash-metric__icon dash-metric__icon--amber"><i class="fas fa-file-pen"></i></div>
        <div class="dash-metric__text">
            <div class="dash-metric__val">{{ $estadisticas['registros']['notas'] ?? 0 }}</div>
            <div class="dash-metric__lbl">Notas Registradas</div>
        </div>
    </div>
    <div class="dash-metric-divider"></div>
    <div class="dash-metric">
        <div class="dash-metric__icon dash-metric__icon--teal"><i class="fas fa-calendar-day"></i></div>
        <div class="dash-metric__text">
            <div class="dash-metric__val">{{ $estadisticas['registros']['asistencias_hoy'] ?? 0 }}</div>
            <div class="dash-metric__lbl">Asistencias Hoy</div>
        </div>
    </div>
    <div class="dash-metric-divider"></div>
    <div class="dash-metric">
        <div class="dash-metric__icon dash-metric__icon--indigo"><i class="fas fa-layer-group"></i></div>
        <div class="dash-metric__text">
            <div class="dash-metric__val">{{ $estadisticas['academico']['matriculas'] }}</div>
            <div class="dash-metric__lbl">Matrículas Totales</div>
        </div>
    </div>
</div>

{{-- ALERTAS --}}
@php
    $hayAlertas =
        ($alertas['estudiantes_sin_matricula']->count() > 0) ||
        ($alertas['cursos_sin_docente']->count() > 0) ||
        (isset($alertas['estudiantes_bajo_rendimiento']) && $alertas['estudiantes_bajo_rendimiento']->count() > 0) ||
        (isset($alertas['estudiantes_inasistencias']) && $alertas['estudiantes_inasistencias']->count() > 0);
@endphp

@if($hayAlertas)
<div class="dash-alerts-wrapper" style="animation-delay:.35s">
    <div class="dash-alerts-header">
        <span class="dash-alerts-pulse"></span>
        <span class="dash-alerts-title">Alertas del Sistema</span>
    </div>
    <div class="dash-alerts-grid">
        @if($alertas['estudiantes_sin_matricula']->count() > 0)
        <button class="dash-alert dash-alert--amber" data-bs-toggle="modal" data-bs-target="#modalSinMatricula">
            <div class="dash-alert__icon"><i class="fas fa-user-xmark"></i></div>
            <div class="dash-alert__body">
                <div class="dash-alert__count">{{ $alertas['estudiantes_sin_matricula']->count() }}</div>
                <div class="dash-alert__text">Sin Matrícula</div>
            </div>
            <i class="fas fa-chevron-right dash-alert__caret"></i>
        </button>
        @endif
        @if($alertas['cursos_sin_docente']->count() > 0)
        <button class="dash-alert dash-alert--blue" data-bs-toggle="modal" data-bs-target="#modalSinDocente">
            <div class="dash-alert__icon"><i class="fas fa-chalkboard"></i></div>
            <div class="dash-alert__body">
                <div class="dash-alert__count">{{ $alertas['cursos_sin_docente']->count() }}</div>
                <div class="dash-alert__text">Cursos sin Docente</div>
            </div>
            <i class="fas fa-chevron-right dash-alert__caret"></i>
        </button>
        @endif
        @if(isset($alertas['estudiantes_bajo_rendimiento']) && $alertas['estudiantes_bajo_rendimiento']->count() > 0)
        <button class="dash-alert dash-alert--red" data-bs-toggle="modal" data-bs-target="#modalBajoRendimiento">
            <div class="dash-alert__icon"><i class="fas fa-arrow-trend-down"></i></div>
            <div class="dash-alert__body">
                <div class="dash-alert__count">{{ $alertas['estudiantes_bajo_rendimiento']->count() }}</div>
                <div class="dash-alert__text">Bajo Rendimiento</div>
            </div>
            <i class="fas fa-chevron-right dash-alert__caret"></i>
        </button>
        @endif
        @if(isset($alertas['estudiantes_inasistencias']) && $alertas['estudiantes_inasistencias']->count() > 0)
        <button class="dash-alert dash-alert--slate" data-bs-toggle="modal" data-bs-target="#modalInasistencias">
            <div class="dash-alert__icon"><i class="fas fa-calendar-xmark"></i></div>
            <div class="dash-alert__body">
                <div class="dash-alert__count">{{ $alertas['estudiantes_inasistencias']->count() }}</div>
                <div class="dash-alert__text">Inasistencias (5+)</div>
            </div>
            <i class="fas fa-chevron-right dash-alert__caret"></i>
        </button>
        @endif
    </div>
</div>
@endif

{{-- GRÁFICOS FILA 1 --}}
<div class="dash-charts-row">
    <div class="dash-card" style="animation-delay:.45s">
        <div class="dash-card__header">
            <div class="dash-card__title">
                <span class="dash-card__icon dash-card__icon--blue"><i class="fas fa-chart-column"></i></span>
                Estudiantes por Nivel
            </div>
        </div>
        <div class="dash-card__body">
            @if($graficos['estudiantes_por_nivel']->count() > 0)
                <canvas id="chartNivel" class="dash-canvas"></canvas>
            @else
                <div class="dash-empty"><i class="fas fa-chart-simple"></i><span>Sin datos disponibles</span></div>
            @endif
        </div>
    </div>
    <div class="dash-card" style="animation-delay:.5s">
        <div class="dash-card__header">
            <div class="dash-card__title">
                <span class="dash-card__icon dash-card__icon--emerald"><i class="fas fa-chart-pie"></i></span>
                Top 10 Grados
            </div>
        </div>
        <div class="dash-card__body">
            @if($graficos['estudiantes_por_grado']->count() > 0)
                <canvas id="chartGrado" class="dash-canvas"></canvas>
            @else
                <div class="dash-empty"><i class="fas fa-chart-simple"></i><span>Sin datos disponibles</span></div>
            @endif
        </div>
    </div>
</div>

{{-- GRÁFICOS FILA 2 --}}
<div class="dash-charts-row">
    <div class="dash-card" style="animation-delay:.55s">
        <div class="dash-card__header">
            <div class="dash-card__title">
                <span class="dash-card__icon dash-card__icon--teal"><i class="fas fa-chart-line"></i></span>
                Matrículas por Mes
            </div>
            <span class="dash-card__tag">Gestión actual</span>
        </div>
        <div class="dash-card__body">
            @if($graficos['matriculas_por_mes']->count() > 0)
                <canvas id="chartMatriculas" class="dash-canvas"></canvas>
            @else
                <div class="dash-empty"><i class="fas fa-chart-simple"></i><span>Sin datos disponibles</span></div>
            @endif
        </div>
    </div>
    <div class="dash-card" style="animation-delay:.6s">
        <div class="dash-card__header">
            <div class="dash-card__title">
                <span class="dash-card__icon dash-card__icon--amber"><i class="fas fa-user-check"></i></span>
                Asistencias — Últimos 7 días
            </div>
        </div>
        <div class="dash-card__body">
            @if($graficos['asistencias_ultimos_7_dias']->count() > 0)
                <canvas id="chartAsistencias" class="dash-canvas"></canvas>
            @else
                <div class="dash-empty"><i class="fas fa-chart-simple"></i><span>Sin datos disponibles</span></div>
            @endif
        </div>
    </div>
</div>

{{-- FILA INFERIOR --}}
<div class="dash-bottom-row">
    <div class="dash-card" style="animation-delay:.65s">
        <div class="dash-card__header">
            <div class="dash-card__title">
                <span class="dash-card__icon dash-card__icon--violet"><i class="fas fa-circle-half-stroke"></i></span>
                Distribución de Notas
            </div>
            <span class="dash-card__tag">Periodo actual</span>
        </div>
        <div class="dash-card__body">
            @if(isset($graficos['notas_distribucion']) && $graficos['notas_distribucion']->count() > 0)
                <canvas id="chartNotas" class="dash-canvas"></canvas>
            @else
                <div class="dash-empty"><i class="fas fa-chart-simple"></i><span>Sin datos disponibles</span></div>
            @endif
        </div>
    </div>

    @if(isset($rankings['mejores_estudiantes']) && $rankings['mejores_estudiantes']->count() > 0)
    <div class="dash-card" style="animation-delay:.7s">
        <div class="dash-card__header">
            <div class="dash-card__title">
                <span class="dash-card__icon dash-card__icon--amber"><i class="fas fa-trophy"></i></span>
                Top 10 Estudiantes
            </div>
        </div>
        <div class="dash-card__body dash-card__body--scroll">
            <div class="dash-ranking">
                @foreach($rankings['mejores_estudiantes'] as $i => $est)
                <div class="dash-rank-item">
                    <div class="dash-rank-pos dash-rank-pos--{{ $i < 3 ? ['gold','silver','bronze'][$i] : 'default' }}">
                        @if($i < 3)<i class="fas fa-medal"></i>@else{{ $i+1 }}@endif
                    </div>
                    <div class="dash-rank-name">{{ $est->nombres }} {{ $est->apellidos }}</div>
                    <div class="dash-rank-score">
                        <span class="dash-score dash-score--{{ $est->promedio >= 15 ? 'green' : ($est->promedio >= 11 ? 'blue' : 'red') }}">
                            {{ number_format($est->promedio, 1) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if(isset($actividadReciente))
    <div class="dash-card" style="animation-delay:.75s">
        <div class="dash-card__header">
            <div class="dash-card__title">
                <span class="dash-card__icon dash-card__icon--rose"><i class="fas fa-wave-square"></i></span>
                Actividad Reciente
            </div>
        </div>
        <div class="dash-tabs">
            <button class="dash-tab dash-tab--active" data-target="tab-matriculas">
                <i class="fas fa-clipboard-list"></i> Matrículas
            </button>
            <button class="dash-tab" data-target="tab-notas">
                <i class="fas fa-file-alt"></i> Notas
            </button>
        </div>
        <div class="dash-tab-content" id="tab-matriculas">
            @if(isset($actividadReciente['ultimas_matriculas']) && $actividadReciente['ultimas_matriculas']->count() > 0)
                @foreach($actividadReciente['ultimas_matriculas'] as $m)
                <div class="dash-activity-item">
                    <div class="dash-activity-avatar">
                        {{ strtoupper(substr($m->estudiante->persona->nombres,0,1)) }}{{ strtoupper(substr($m->estudiante->persona->apellidos,0,1)) }}
                    </div>
                    <div class="dash-activity-info">
                        <div class="dash-activity-name">{{ $m->estudiante->persona->nombres }} {{ $m->estudiante->persona->apellidos }}</div>
                        <div class="dash-activity-meta">{{ $m->curso->nombre }}@if($m->grado) · {{ $m->grado->nombre }}@endif</div>
                    </div>
                    <div class="dash-activity-time">{{ $m->created_at->diffForHumans() }}</div>
                </div>
                @endforeach
            @else
                <div class="dash-empty dash-empty--sm"><span>Sin matrículas recientes</span></div>
            @endif
        </div>
        <div class="dash-tab-content dash-tab-content--hidden" id="tab-notas">
            @if(isset($actividadReciente['ultimas_notas']) && $actividadReciente['ultimas_notas']->count() > 0)
                @foreach($actividadReciente['ultimas_notas'] as $n)
                <div class="dash-activity-item">
                    <div class="dash-activity-avatar dash-activity-avatar--violet">
                        {{ strtoupper(substr($n->matricula->estudiante->persona->nombres,0,1)) }}{{ strtoupper(substr($n->matricula->estudiante->persona->apellidos,0,1)) }}
                    </div>
                    <div class="dash-activity-info">
                        <div class="dash-activity-name">{{ $n->matricula->estudiante->persona->nombres }} {{ $n->matricula->estudiante->persona->apellidos }}</div>
                        <div class="dash-activity-meta">Nota: <strong>{{ $n->nota_final }}</strong> · {{ $n->docente->persona->nombres }} {{ $n->docente->persona->apellidos }}</div>
                    </div>
                    <div class="dash-activity-time">{{ \Carbon\Carbon::parse($n->fecha_publicacion)->diffForHumans() }}</div>
                </div>
                @endforeach
            @else
                <div class="dash-empty dash-empty--sm"><span>Sin notas recientes</span></div>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- MODALES --}}
<div class="modal modal-blur fade" id="modalSinMatricula" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-user-xmark me-2"></i>Estudiantes Sin Matrícula</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead><tr><th>DNI</th><th>Estudiante</th><th>Estado</th></tr></thead>
                    <tbody>
                        @foreach($alertas['estudiantes_sin_matricula'] as $est)
                        <tr>
                            <td class="text-muted">{{ $est->persona->dni }}</td>
                            <td>{{ $est->persona->nombres }} {{ $est->persona->apellidos }}</td>
                            <td><span class="badge {{ $est->persona->estado=='Activo' ? 'bg-success-lt text-success' : 'bg-danger-lt text-danger' }}">{{ $est->persona->estado }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modalSinDocente" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-chalkboard me-2"></i>Cursos Sin Docente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead><tr><th>Curso</th><th>Descripción</th></tr></thead>
                    <tbody>
                        @foreach($alertas['cursos_sin_docente'] as $curso)
                        <tr><td class="fw-semibold">{{ $curso->nombre }}</td><td class="text-muted">{{ $curso->descripcion }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(isset($alertas['estudiantes_bajo_rendimiento']) && $alertas['estudiantes_bajo_rendimiento']->count() > 0)
<div class="modal modal-blur fade" id="modalBajoRendimiento" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-arrow-trend-down me-2"></i>Bajo Rendimiento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead><tr><th>Estudiante</th><th class="text-center">Promedio</th></tr></thead>
                    <tbody>
                        @foreach($alertas['estudiantes_bajo_rendimiento'] as $est)
                        <tr>
                            <td>{{ $est->nombres }} {{ $est->apellidos }}</td>
                            <td class="text-center"><span class="badge bg-danger-lt text-danger">{{ number_format($est->promedio,2) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($alertas['estudiantes_inasistencias']) && $alertas['estudiantes_inasistencias']->count() > 0)
<div class="modal modal-blur fade" id="modalInasistencias" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="fas fa-calendar-xmark me-2"></i>Estudiantes con Inasistencias</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Estudiantes con 5 o más ausencias en los últimos 30 días.</p>
                <table class="table table-hover table-sm">
                    <thead><tr><th>Estudiante</th><th class="text-center">Ausencias</th></tr></thead>
                    <tbody>
                        @foreach($alertas['estudiantes_inasistencias'] as $est)
                        <tr>
                            <td>{{ $est->nombres }} {{ $est->apellidos }}</td>
                            <td class="text-center"><span class="badge bg-danger-lt text-danger">{{ $est->total_ausencias }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>

/* ════════════════════════════════════════
   VARIABLES — Se adaptan automáticamente
   al tema claro/oscuro de Tabler
════════════════════════════════════════ */

/* MODO CLARO (por defecto) */
:root,
[data-bs-theme="light"] {
    --d-bg:        #f0f2f5;
    --d-surface:   #ffffff;
    --d-surf2:     #f4f6fb;
    --d-border:    rgba(0,0,0,.08);
    --d-border2:   rgba(0,0,0,.15);
    --d-text:      #1a202c;
    --d-text2:     #2d3748;
    --d-muted:     #718096;
    --d-shadow:    0 4px 20px rgba(0,0,0,.10);
    --d-shadowlg:  0 12px 40px rgba(0,0,0,.14);
    --d-hero-bg:   linear-gradient(135deg,#eef2ff 0%,#f0f9ff 60%,#fefce8 100%);
    --d-hero-orb1: rgba(59,130,246,.12);
    --d-hero-orb2: rgba(16,185,129,.10);
    --d-hero-orb3: rgba(139,92,246,.09);

    /* Colores de acento (iguales en ambos modos) */
    --d-blue:    #2563eb;
    --d-emerald: #059669;
    --d-amber:   #d97706;
    --d-violet:  #7c3aed;
    --d-teal:    #0891b2;
    --d-rose:    #e11d48;
    --d-red:     #dc2626;
    --d-indigo:  #4338ca;
    --d-slate:   #64748b;
    --d-gold:    #b45309;
    --d-r:       14px;
    --d-rlg:     18px;
    --font:      'Sora', system-ui, sans-serif;
    --mono:      'JetBrains Mono', monospace;
}

/* MODO OSCURO */
[data-bs-theme="dark"] {
    --d-bg:        #0d1117;
    --d-surface:   #161b22;
    --d-surf2:     #1c2333;
    --d-border:    rgba(255,255,255,.07);
    --d-border2:   rgba(255,255,255,.13);
    --d-text:      #e6edf3;
    --d-text2:     #c9d1d9;
    --d-muted:     #7d8590;
    --d-shadow:    0 8px 32px rgba(0,0,0,.45);
    --d-shadowlg:  0 24px 64px rgba(0,0,0,.55);
    --d-hero-bg:   linear-gradient(135deg,#0d1117 0%,#161b22 60%,#1a1f2e 100%);
    --d-hero-orb1: rgba(88,166,255,.13);
    --d-hero-orb2: rgba(63,185,80,.10);
    --d-hero-orb3: rgba(188,140,255,.09);

    --d-blue:    #58a6ff;
    --d-emerald: #3fb950;
    --d-amber:   #d29922;
    --d-violet:  #bc8cff;
    --d-teal:    #39c5cf;
    --d-rose:    #f47067;
    --d-red:     #f85149;
    --d-indigo:  #6e76ff;
    --d-slate:   #8b949e;
    --d-gold:    #e3b341;
}

/* Override Tabler layout */
.content-header { display:none !important; }
.content { padding:0 !important; }

/* Animaciones */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes pulseRing {
    0%  { transform:scale(1);   opacity:.9; }
    70% { transform:scale(1.8); opacity:0; }
    100%{ transform:scale(1.8); opacity:0; }
}
@keyframes orbFloat {
    0%,100%{ transform:translateY(0) scale(1); }
    50%    { transform:translateY(-24px) scale(1.03); }
}
.dash-kpi, .dash-card, .dash-alerts-wrapper, .dash-metrics-row {
    animation: fadeUp .5s cubic-bezier(.22,1,.36,1) both;
}

/* ══ HERO ══ */
.dash-hero {
    position:relative; overflow:hidden;
    padding:44px 40px 40px;
    background: var(--d-hero-bg);
    border-bottom:1px solid var(--d-border);
    font-family:var(--font);
}
.dash-hero__orb {
    position:absolute; border-radius:50%;
    filter:blur(55px); pointer-events:none;
    animation:orbFloat 8s ease-in-out infinite;
}
.dash-hero__orb--1{width:320px;height:320px;top:-80px;right:8%; background:var(--d-hero-orb1);}
.dash-hero__orb--2{width:200px;height:200px;bottom:-40px;right:24%;background:var(--d-hero-orb2);animation-delay:2.2s;}
.dash-hero__orb--3{width:160px;height:160px;top:20px;right:37%; background:var(--d-hero-orb3);animation-delay:4.5s;}

.dash-hero__content{
    position:relative;z-index:1;
    display:flex;align-items:center;justify-content:space-between;
    gap:24px;flex-wrap:wrap;
}
.dash-eyebrow{
    display:flex;align-items:center;gap:8px;
    font-size:.7rem;font-weight:600;
    letter-spacing:.13em;text-transform:uppercase;
    color:var(--d-emerald);margin-bottom:10px;
}
.dash-eyebrow__dot{
    width:7px;height:7px;border-radius:50%;
    background:var(--d-emerald);
    animation:pulseRing 2.2s cubic-bezier(.455,.03,.515,.955) infinite;
}
.dash-hero__title{
    font-size:2.4rem;font-weight:800;
    color:var(--d-text);margin:0 0 8px;line-height:1.08;letter-spacing:-.03em;
}
.dash-hero__accent{
    background:linear-gradient(135deg,var(--d-blue) 0%,var(--d-violet) 100%);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;
}
.dash-hero__sub{ font-size:.84rem;color:var(--d-muted);margin:0; }
.dash-hero__right{ display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end; }

.dash-pill{
    display:inline-flex;align-items:center;gap:7px;
    padding:7px 14px;border-radius:100px;
    font-size:.77rem;font-weight:600;
    border:1px solid transparent;white-space:nowrap;
    backdrop-filter:blur(12px);
}
.dash-pill--green { background:rgba(5,150,105,.12); border-color:rgba(5,150,105,.25);  color:var(--d-emerald); }
.dash-pill--blue  { background:rgba(37,99,235,.12); border-color:rgba(37,99,235,.25);  color:var(--d-blue); }
.dash-pill--muted { background:var(--d-surf2);       border-color:var(--d-border2);     color:var(--d-muted); }
[data-bs-theme="dark"] .dash-pill--green { background:rgba(63,185,80,.15);  border-color:rgba(63,185,80,.3); }
[data-bs-theme="dark"] .dash-pill--blue  { background:rgba(88,166,255,.15); border-color:rgba(88,166,255,.3); }

/* ══ KPI GRID ══ */
.dash-kpi-grid{
    display:grid;grid-template-columns:repeat(4,1fr);
    gap:16px;padding:28px 40px 0;
}
@media(max-width:1100px){.dash-kpi-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:600px) {.dash-kpi-grid{grid-template-columns:1fr;}}

.dash-kpi{
    position:relative;overflow:hidden;
    display:flex;align-items:center;gap:18px;
    padding:22px 20px;
    border-radius:var(--d-rlg);
    border:1px solid var(--d-border);
    background:var(--d-surface);
    text-decoration:none !important;cursor:pointer;
    transition:transform .25s cubic-bezier(.22,1,.36,1), box-shadow .25s ease, border-color .25s ease;
}
.dash-kpi:hover{ transform:translateY(-4px); box-shadow:var(--d-shadowlg); border-color:var(--d-border2); }
.dash-kpi:hover .dash-kpi__glow { opacity:1; }
.dash-kpi:hover .dash-kpi__arrow{ opacity:1; transform:translate(0,0); }

.dash-kpi__glow{
    position:absolute;inset:0;opacity:0;pointer-events:none;
    transition:opacity .3s ease;border-radius:inherit;
}
.dash-kpi__icon{
    width:52px;height:52px;border-radius:13px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.25rem;flex-shrink:0;
}
.dash-kpi__label{ font-size:.68rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--d-muted);margin-bottom:4px; }
.dash-kpi__value{ font-size:2rem;font-weight:800;color:var(--d-text);font-family:var(--mono);line-height:1; }
.dash-kpi__sub  { margin-top:6px; }
.dash-kpi__arrow{
    position:absolute;top:14px;right:14px;font-size:.72rem;
    opacity:0;transform:translate(-4px,-4px);
    transition:all .25s ease;color:var(--d-muted);
}

.dash-kpi--blue    .dash-kpi__icon{ background:rgba(37,99,235,.12);   color:var(--d-blue); }
.dash-kpi--blue    .dash-kpi__glow{ background:radial-gradient(ellipse at 0% 0%,rgba(37,99,235,.12),transparent 70%); }
.dash-kpi--emerald .dash-kpi__icon{ background:rgba(5,150,105,.12);   color:var(--d-emerald); }
.dash-kpi--emerald .dash-kpi__glow{ background:radial-gradient(ellipse at 0% 0%,rgba(5,150,105,.12),transparent 70%); }
.dash-kpi--amber   .dash-kpi__icon{ background:rgba(217,119,6,.12);   color:var(--d-amber); }
.dash-kpi--amber   .dash-kpi__glow{ background:radial-gradient(ellipse at 0% 0%,rgba(217,119,6,.10),transparent 70%); }
.dash-kpi--violet  .dash-kpi__icon{ background:rgba(124,58,237,.12);  color:var(--d-violet); }
.dash-kpi--violet  .dash-kpi__glow{ background:radial-gradient(ellipse at 0% 0%,rgba(124,58,237,.10),transparent 70%); }

[data-bs-theme="dark"] .dash-kpi--blue    .dash-kpi__icon{ background:rgba(88,166,255,.15); }
[data-bs-theme="dark"] .dash-kpi--emerald .dash-kpi__icon{ background:rgba(63,185,80,.15);  }
[data-bs-theme="dark"] .dash-kpi--amber   .dash-kpi__icon{ background:rgba(210,153,34,.15); }
[data-bs-theme="dark"] .dash-kpi--violet  .dash-kpi__icon{ background:rgba(188,140,255,.15);}

.dash-badge{ display:inline-flex;align-items:center;gap:5px;font-size:.69rem;font-weight:600;padding:3px 9px;border-radius:100px; }
.dash-badge i{ font-size:.45rem; }
.dash-badge--green{ background:rgba(5,150,105,.12);  color:var(--d-emerald); }
[data-bs-theme="dark"] .dash-badge--green{ background:rgba(63,185,80,.15); }

/* ══ METRICS ROW ══ */
.dash-metrics-row{
    display:flex;align-items:center;flex-wrap:wrap;
    margin:20px 40px 0;padding:18px 24px;
    background:var(--d-surface);border:1px solid var(--d-border);
    border-radius:var(--d-rlg);
}
.dash-metric{ display:flex;align-items:center;gap:12px;flex:1;min-width:100px;padding:4px 12px; }
.dash-metric-divider{ width:1px;height:36px;background:var(--d-border2);flex-shrink:0; }
.dash-metric__icon{
    width:36px;height:36px;border-radius:9px;
    display:flex;align-items:center;justify-content:center;
    font-size:.85rem;flex-shrink:0;
}
.dash-metric__icon--blue  { background:rgba(37,99,235,.1);  color:var(--d-blue); }
.dash-metric__icon--rose  { background:rgba(225,29,72,.1);   color:var(--d-rose); }
.dash-metric__icon--green { background:rgba(5,150,105,.1);   color:var(--d-emerald); }
.dash-metric__icon--amber { background:rgba(217,119,6,.1);   color:var(--d-amber); }
.dash-metric__icon--teal  { background:rgba(8,145,178,.1);   color:var(--d-teal); }
.dash-metric__icon--indigo{ background:rgba(67,56,202,.1);   color:var(--d-indigo); }
[data-bs-theme="dark"] .dash-metric__icon--blue  { background:rgba(88,166,255,.15);  }
[data-bs-theme="dark"] .dash-metric__icon--rose  { background:rgba(244,112,103,.15); }
[data-bs-theme="dark"] .dash-metric__icon--green { background:rgba(63,185,80,.15);   }
[data-bs-theme="dark"] .dash-metric__icon--amber { background:rgba(210,153,34,.15);  }
[data-bs-theme="dark"] .dash-metric__icon--teal  { background:rgba(57,197,207,.15);  }
[data-bs-theme="dark"] .dash-metric__icon--indigo{ background:rgba(110,118,255,.15); }

.dash-metric__val{ font-size:1.4rem;font-weight:800;color:var(--d-text);font-family:var(--mono);line-height:1; }
.dash-metric__lbl{ font-size:.67rem;font-weight:600;color:var(--d-muted);letter-spacing:.04em;text-transform:uppercase;margin-top:2px; }

/* ══ ALERTS ══ */
.dash-alerts-wrapper{
    margin:20px 40px 0;padding:18px 22px;
    background:var(--d-surface);
    border:1px solid rgba(220,38,38,.2);
    border-radius:var(--d-rlg);
}
[data-bs-theme="dark"] .dash-alerts-wrapper{
    border-color:rgba(248,81,73,.22);
}
.dash-alerts-header{ display:flex;align-items:center;gap:10px;margin-bottom:14px; }
.dash-alerts-pulse{
    width:8px;height:8px;border-radius:50%;background:var(--d-rose);
    animation:pulseRing 1.8s cubic-bezier(.455,.03,.515,.955) infinite;
}
.dash-alerts-title{ font-size:.77rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--d-rose); }
.dash-alerts-grid{ display:flex;flex-wrap:wrap;gap:12px; }

.dash-alert{
    display:flex;align-items:center;gap:14px;
    padding:13px 16px;border-radius:11px;
    border:1px solid var(--d-border);
    background:var(--d-surf2);
    cursor:pointer;flex:1;min-width:190px;
    transition:all .2s ease;
}
.dash-alert:hover{ transform:translateY(-2px);box-shadow:var(--d-shadow); }
.dash-alert__icon{ font-size:1.2rem;width:34px;text-align:center; }
.dash-alert__count{ font-size:1.5rem;font-weight:800;font-family:var(--mono);line-height:1; }
.dash-alert__text{ font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--d-muted);margin-top:2px; }
.dash-alert__caret{ margin-left:auto;font-size:.68rem;color:var(--d-muted); }
.dash-alert--amber{ border-color:rgba(217,119,6,.3);  color:var(--d-amber);   background:rgba(217,119,6,.07); }
.dash-alert--blue { border-color:rgba(37,99,235,.3);  color:var(--d-blue);    background:rgba(37,99,235,.07); }
.dash-alert--red  { border-color:rgba(220,38,38,.3);  color:var(--d-red);     background:rgba(220,38,38,.07); }
.dash-alert--slate{ border-color:rgba(100,116,139,.3);color:var(--d-slate);   background:rgba(100,116,139,.07); }
[data-bs-theme="dark"] .dash-alert--amber{ background:rgba(210,153,34,.08); }
[data-bs-theme="dark"] .dash-alert--blue { background:rgba(88,166,255,.08); }
[data-bs-theme="dark"] .dash-alert--red  { background:rgba(248,81,73,.08);  }
[data-bs-theme="dark"] .dash-alert--slate{ background:rgba(139,148,158,.08);}

/* ══ CARDS ══ */
.dash-charts-row{ display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:20px 40px 0; }
.dash-bottom-row{ display:grid;grid-template-columns:280px 1fr 1fr;gap:16px;padding:20px 40px 48px; }
@media(max-width:1200px){.dash-bottom-row{grid-template-columns:1fr 1fr;}}
@media(max-width:900px) {.dash-charts-row,.dash-bottom-row{grid-template-columns:1fr;}}

.dash-card{
    background:var(--d-surface);
    border:1px solid var(--d-border);
    border-radius:var(--d-rlg);
    overflow:hidden;
    transition:box-shadow .25s ease,border-color .25s ease;
}
.dash-card:hover{ box-shadow:var(--d-shadow);border-color:var(--d-border2); }

.dash-card__header{ display:flex;align-items:center;justify-content:space-between;padding:16px 18px 0; }
.dash-card__title{
    display:flex;align-items:center;gap:9px;
    font-size:.77rem;font-weight:700;letter-spacing:.04em;
    text-transform:uppercase;color:var(--d-text);
}
.dash-card__icon{
    width:26px;height:26px;border-radius:7px;
    display:flex;align-items:center;justify-content:center;font-size:.72rem;
}
.dash-card__icon--blue   { background:rgba(37,99,235,.12);  color:var(--d-blue); }
.dash-card__icon--emerald{ background:rgba(5,150,105,.12);  color:var(--d-emerald); }
.dash-card__icon--teal   { background:rgba(8,145,178,.12);  color:var(--d-teal); }
.dash-card__icon--amber  { background:rgba(217,119,6,.12);  color:var(--d-amber); }
.dash-card__icon--violet { background:rgba(124,58,237,.12); color:var(--d-violet); }
.dash-card__icon--rose   { background:rgba(225,29,72,.12);  color:var(--d-rose); }
[data-bs-theme="dark"] .dash-card__icon--blue   { background:rgba(88,166,255,.15);  }
[data-bs-theme="dark"] .dash-card__icon--emerald{ background:rgba(63,185,80,.15);   }
[data-bs-theme="dark"] .dash-card__icon--teal   { background:rgba(57,197,207,.15);  }
[data-bs-theme="dark"] .dash-card__icon--amber  { background:rgba(210,153,34,.15);  }
[data-bs-theme="dark"] .dash-card__icon--violet { background:rgba(188,140,255,.15); }
[data-bs-theme="dark"] .dash-card__icon--rose   { background:rgba(244,112,103,.15); }

.dash-card__tag{
    font-size:.62rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;
    color:var(--d-muted);background:var(--d-surf2);
    border-radius:100px;padding:3px 9px;border:1px solid var(--d-border);
}
.dash-card__body{ padding:14px 18px 18px;position:relative; }
.dash-card__body--scroll{ max-height:320px;overflow-y:auto;padding:8px 0 0; }
.dash-card__body--scroll::-webkit-scrollbar{width:4px;}
.dash-card__body--scroll::-webkit-scrollbar-thumb{background:var(--d-border2);border-radius:4px;}
.dash-canvas{ max-height:220px; }

.dash-empty{ display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;padding:40px 0;color:var(--d-muted);font-size:.82rem; }
.dash-empty i{ font-size:1.8rem;opacity:.3; }
.dash-empty--sm{ padding:20px 16px;font-size:.78rem; }

/* ══ RANKING ══ */
.dash-ranking{ padding:8px 16px; }
.dash-rank-item{ display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--d-border); }
.dash-rank-item:last-child{ border-bottom:none; }
.dash-rank-pos{
    width:28px;height:28px;border-radius:8px;
    display:flex;align-items:center;justify-content:center;
    font-size:.68rem;font-weight:800;flex-shrink:0;font-family:var(--mono);
}
.dash-rank-pos--gold  { background:rgba(180,83,9,.12);   color:var(--d-gold); }
.dash-rank-pos--silver{ background:rgba(100,116,139,.12);color:var(--d-slate); }
.dash-rank-pos--bronze{ background:rgba(217,119,6,.12);  color:var(--d-amber); }
.dash-rank-pos--default{ background:var(--d-surf2);color:var(--d-muted);font-size:.62rem; }
[data-bs-theme="dark"] .dash-rank-pos--gold  { background:rgba(227,179,65,.2);  color:#e3b341; }
[data-bs-theme="dark"] .dash-rank-pos--silver{ background:rgba(139,148,158,.2); color:#b1bac4; }
[data-bs-theme="dark"] .dash-rank-pos--bronze{ background:rgba(210,153,34,.2);  color:#cd7f32; }

.dash-rank-name{ flex:1;font-size:.8rem;font-weight:500;color:var(--d-text); }
.dash-score{ font-family:var(--mono);font-size:.78rem;font-weight:700;padding:3px 9px;border-radius:100px; }
.dash-score--green{ background:rgba(5,150,105,.12);  color:var(--d-emerald); }
.dash-score--blue { background:rgba(37,99,235,.12);  color:var(--d-blue); }
.dash-score--red  { background:rgba(220,38,38,.12);  color:var(--d-red); }
[data-bs-theme="dark"] .dash-score--green{ background:rgba(63,185,80,.15);  }
[data-bs-theme="dark"] .dash-score--blue { background:rgba(88,166,255,.15); }
[data-bs-theme="dark"] .dash-score--red  { background:rgba(248,81,73,.15);  }

/* ══ TABS ══ */
.dash-tabs{ display:flex;border-bottom:1px solid var(--d-border);padding:0 18px; }
.dash-tab{
    padding:10px 14px;background:none;border:none;
    font-size:.71rem;font-weight:600;
    color:var(--d-muted);cursor:pointer;position:relative;
    letter-spacing:.04em;text-transform:uppercase;transition:color .2s;
}
.dash-tab::after{
    content:'';position:absolute;bottom:-1px;left:0;right:0;
    height:2px;background:var(--d-blue);border-radius:2px 2px 0 0;
    opacity:0;transition:opacity .2s;
}
.dash-tab--active{ color:var(--d-blue); }
.dash-tab--active::after{ opacity:1; }
.dash-tab-content--hidden{ display:none; }

/* ══ ACTIVITY ══ */
.dash-activity-item{
    display:flex;align-items:center;gap:12px;
    padding:11px 18px;border-bottom:1px solid var(--d-border);
    transition:background .15s;
}
.dash-activity-item:last-child{ border-bottom:none; }
.dash-activity-item:hover{ background:var(--d-surf2); }
.dash-activity-avatar{
    width:32px;height:32px;border-radius:9px;
    background:rgba(37,99,235,.12);color:var(--d-blue);
    display:flex;align-items:center;justify-content:center;
    font-size:.7rem;font-weight:700;flex-shrink:0;font-family:var(--mono);
}
.dash-activity-avatar--violet{ background:rgba(124,58,237,.12);color:var(--d-violet); }
[data-bs-theme="dark"] .dash-activity-avatar         { background:rgba(88,166,255,.2); }
[data-bs-theme="dark"] .dash-activity-avatar--violet { background:rgba(188,140,255,.2); }
.dash-activity-name{ font-size:.79rem;font-weight:600;color:var(--d-text); }
.dash-activity-meta{ font-size:.71rem;color:var(--d-muted);margin-top:2px; }
.dash-activity-time{ font-size:.66rem;color:var(--d-muted);white-space:nowrap;margin-left:auto; }

/* ══ BADGES modo oscuro en tablas de modales ══ */
[data-bs-theme="dark"] .badge.bg-success-lt {
    background-color: #1a3329 !important; color:#6edbb4 !important;
    border:1px solid rgba(70,200,140,.25) !important;
}
[data-bs-theme="dark"] .badge.bg-danger-lt {
    background-color: #3d1f1f !important; color:#ff8a8a !important;
    border:1px solid rgba(255,100,100,.25) !important;
}
[data-bs-theme="dark"] .modal-content {
    background-color: #161b22 !important;
    border-color: rgba(255,255,255,.1) !important;
    color: #e6edf3 !important;
}
[data-bs-theme="dark"] .table { color:#c9d1d9 !important; }
[data-bs-theme="dark"] .table th { color:#7d8590 !important; border-color:rgba(255,255,255,.08) !important; }
[data-bs-theme="dark"] .table td { border-color:rgba(255,255,255,.06) !important; }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
$(document).ready(function () {

    /* Detectar modo oscuro */
    var isDark = document.body.getAttribute('data-bs-theme') === 'dark';
    var gridColor  = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.06)';
    var tickColor  = isDark ? '#7d8590' : '#94a3b8';
    var tooltipBg  = isDark ? '#1c2333' : '#ffffff';
    var tooltipBorder = isDark ? 'rgba(255,255,255,.1)' : 'rgba(0,0,0,.1)';
    var tooltipTitle  = isDark ? '#e6edf3' : '#1a202c';
    var tooltipBody   = isDark ? '#8b949e' : '#64748b';
    var doughnutBorder= isDark ? '#161b22' : '#ffffff';

    Chart.defaults.font.family = "'Sora', system-ui, sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = tickColor;
    Chart.defaults.plugins.legend.labels.color        = tickColor;
    Chart.defaults.plugins.legend.labels.padding      = 16;
    Chart.defaults.plugins.legend.labels.boxWidth     = 10;
    Chart.defaults.plugins.legend.labels.borderRadius = 3;
    Chart.defaults.plugins.legend.labels.usePointStyle= true;
    Chart.defaults.plugins.tooltip.backgroundColor    = tooltipBg;
    Chart.defaults.plugins.tooltip.borderColor        = tooltipBorder;
    Chart.defaults.plugins.tooltip.borderWidth        = 1;
    Chart.defaults.plugins.tooltip.titleColor         = tooltipTitle;
    Chart.defaults.plugins.tooltip.bodyColor          = tooltipBody;
    Chart.defaults.plugins.tooltip.padding            = 12;
    Chart.defaults.plugins.tooltip.cornerRadius       = 10;
    Chart.defaults.scale.grid.color   = gridColor;
    Chart.defaults.scale.ticks.color  = tickColor;
    Chart.defaults.scale.border.color = 'transparent';

    /* Contadores animados */
    document.querySelectorAll('.dash-kpi__value[data-count]').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-count')) || 0;
        var delay  = parseFloat(el.closest('.dash-kpi').style.animationDelay || 0) * 1000 + 450;
        setTimeout(function() {
            var start = 0, step = target / 55;
            (function tick() {
                start = Math.min(start + step, target);
                el.textContent = Math.round(start).toLocaleString();
                if (start < target) requestAnimationFrame(tick);
            })();
        }, delay);
    });

    /* Tabs */
    document.querySelectorAll('.dash-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            var card = this.closest('.dash-card');
            card.querySelectorAll('.dash-tab').forEach(function(t){ t.classList.remove('dash-tab--active'); });
            card.querySelectorAll('.dash-tab-content').forEach(function(c){ c.classList.add('dash-tab-content--hidden'); });
            this.classList.add('dash-tab--active');
            var tgt = document.getElementById(this.getAttribute('data-target'));
            if (tgt) tgt.classList.remove('dash-tab-content--hidden');
        });
    });

    var pal  = isDark
        ? ['#58a6ff','#3fb950','#d29922','#bc8cff','#39c5cf','#f47067','#6e76ff','#e3b341','#f85149','#a5d6ff']
        : ['#2563eb','#059669','#d97706','#7c3aed','#0891b2','#e11d48','#4338ca','#b45309','#dc2626','#3b82f6'];
    var anim = { duration:900, easing:'easeOutQuart' };

    /* Chart: Estudiantes por Nivel */
    @if(isset($graficos['estudiantes_por_nivel']) && $graficos['estudiantes_por_nivel']->count() > 0)
    (function(){
        var ctx = document.getElementById('chartNivel'); if(!ctx) return;
        new Chart(ctx,{
            type:'bar',
            data:{
                labels:{!! json_encode($graficos['estudiantes_por_nivel']->pluck('nombre')) !!},
                datasets:[{
                    label:'Estudiantes',
                    data:{!! json_encode($graficos['estudiantes_por_nivel']->pluck('total')) !!},
                    backgroundColor:pal.map(function(c){return c+'33';}),
                    borderColor:pal, borderWidth:2,
                    borderRadius:8, borderSkipped:false,
                }]
            },
            options:{
                responsive:true,maintainAspectRatio:false,animation:anim,
                plugins:{legend:{display:false}},
                scales:{x:{grid:{display:false}},y:{beginAtZero:true,ticks:{precision:0}}}
            }
        });
    })();
    @endif

    /* Chart: Top Grados */
    @if(isset($graficos['estudiantes_por_grado']) && $graficos['estudiantes_por_grado']->count() > 0)
    (function(){
        var ctx = document.getElementById('chartGrado'); if(!ctx) return;
        new Chart(ctx,{
            type:'doughnut',
            data:{
                labels:{!! json_encode($graficos['estudiantes_por_grado']->pluck('nombre')) !!},
                datasets:[{
                    data:{!! json_encode($graficos['estudiantes_por_grado']->pluck('total')) !!},
                    backgroundColor:pal,borderColor:doughnutBorder,borderWidth:3,hoverOffset:8,
                }]
            },
            options:{
                responsive:true,maintainAspectRatio:false,animation:anim,cutout:'68%',
                plugins:{legend:{position:'right',labels:{font:{size:10}}}}
            }
        });
    })();
    @endif

    /* Chart: Matrículas por Mes */
    @if(isset($graficos['matriculas_por_mes']) && $graficos['matriculas_por_mes']->count() > 0)
    (function(){
        var ctx = document.getElementById('chartMatriculas'); if(!ctx) return;
        var lineColor = isDark ? '#39c5cf' : '#0891b2';
        new Chart(ctx,{
            type:'line',
            data:{
                labels:{!! json_encode($graficos['matriculas_por_mes']->pluck('mes')) !!},
                datasets:[{
                    label:'Matrículas',
                    data:{!! json_encode($graficos['matriculas_por_mes']->pluck('total')) !!},
                    fill:true,
                    backgroundColor: isDark ? 'rgba(57,197,207,.12)' : 'rgba(8,145,178,.08)',
                    borderColor:lineColor,borderWidth:2.5,tension:0.4,
                    pointRadius:5,pointBackgroundColor:lineColor,
                    pointBorderColor: isDark ? '#161b22' : '#ffffff',
                    pointBorderWidth:2,pointHoverRadius:7,
                }]
            },
            options:{
                responsive:true,maintainAspectRatio:false,animation:anim,
                plugins:{legend:{display:false}},
                scales:{x:{grid:{display:false}},y:{beginAtZero:true,ticks:{precision:0}}}
            }
        });
    })();
    @endif

    /* Chart: Asistencias 7 días */
    @if(isset($graficos['asistencias_ultimos_7_dias']) && $graficos['asistencias_ultimos_7_dias']->count() > 0)
    (function(){
        var ctx = document.getElementById('chartAsistencias'); if(!ctx) return;
        new Chart(ctx,{
            type:'bar',
            data:{
                labels:{!! json_encode($graficos['asistencias_ultimos_7_dias']->pluck('fecha')) !!},
                datasets:[
                    {label:'Presentes',data:{!! json_encode($graficos['asistencias_ultimos_7_dias']->pluck('presentes')) !!},backgroundColor: isDark ? 'rgba(63,185,80,.75)' : 'rgba(5,150,105,.7)'},
                    {label:'Tardanzas',data:{!! json_encode($graficos['asistencias_ultimos_7_dias']->pluck('tardanzas')) !!},backgroundColor: isDark ? 'rgba(210,153,34,.75)' : 'rgba(217,119,6,.7)'},
                    {label:'Ausentes', data:{!! json_encode($graficos['asistencias_ultimos_7_dias']->pluck('ausentes')) !!}, backgroundColor: isDark ? 'rgba(248,81,73,.75)'  : 'rgba(220,38,38,.7)', borderRadius:{topLeft:6,topRight:6}},
                ]
            },
            options:{
                responsive:true,maintainAspectRatio:false,animation:anim,
                plugins:{legend:{position:'bottom'}},
                scales:{x:{stacked:true,grid:{display:false}},y:{stacked:true,beginAtZero:true,ticks:{precision:0}}}
            }
        });
    })();
    @endif

    /* Chart: Distribución de Notas */
    @if(isset($graficos['notas_distribucion']) && $graficos['notas_distribucion']->count() > 0)
    (function(){
        var ctx = document.getElementById('chartNotas'); if(!ctx) return;
        new Chart(ctx,{
            type:'pie',
            data:{
                labels:{!! json_encode($graficos['notas_distribucion']->pluck('rango')) !!},
                datasets:[{
                    data:{!! json_encode($graficos['notas_distribucion']->pluck('cantidad')) !!},
                    backgroundColor: isDark
                        ? ['#3fb950','#58a6ff','#d29922','#f85149']
                        : ['#059669','#2563eb','#d97706','#dc2626'],
                    borderColor:doughnutBorder,borderWidth:3,hoverOffset:6,
                }]
            },
            options:{
                responsive:true,maintainAspectRatio:false,animation:anim,
                plugins:{legend:{position:'bottom',labels:{font:{size:10}}}}
            }
        });
    })();
    @endif

    /* SweetAlert */
    @if(session('mensaje'))
    Swal.fire({
        icon:'{{ session('icono') }}',
        title:'{{ session('mensaje') }}',
        showConfirmButton:false,timer:2500,
        background: isDark ? '#161b22' : '#ffffff',
        color: isDark ? '#e6edf3' : '#1a202c',
    });
    @endif
});
</script>
@stop