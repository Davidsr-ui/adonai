@extends('layouts.docente')

@section('title', 'Mis Reportes Academicos')
@section('page_title')Reportes <span>Academicos</span>@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
/* ══════════════════════════════════════════════════
   TEMA OSCURO — default
   Las variables DEBEN estar en :root y en
   html[data-theme="dark"] para que el toggle funcione
══════════════════════════════════════════════════ */
:root,
html[data-theme="dark"] {
    --d-navy:     #0F172A;
    --d-card:     #1E293B;
    --d-card2:    #243044;
    --d-brd:      #334155;
    --d-brand:    #0EA5E9;
    --d-brand-d:  #0284C7;
    --d-brand-bg: rgba(14,165,233,.08);
    --d-green:    #10B981;
    --d-green-bg: rgba(16,185,129,.10);
    --d-amber:    #F59E0B;
    --d-amber-bg: rgba(245,158,11,.10);
    --d-rose:     #F43F5E;
    --d-rose-bg:  rgba(244,63,94,.10);
    --d-violet:   #8B5CF6;
    --d-slate:    #64748B;
    --d-slate-bg: rgba(100,116,139,.12);
    --d-text:     #F1F5F9;
    --d-text2:    #CBD5E1;
    --d-muted:    #94A3B8;
    --d-input-bg: rgba(15,23,42,.7);
    --d-shadow:   0 4px 24px rgba(0,0,0,.4);
    --d-radius:   10px;
}

/* ══════════════════════════════════════════════════
   TEMA CLARO
══════════════════════════════════════════════════ */
html[data-theme="light"] {
    --d-navy:     #f0f4fb;
    --d-card:     #ffffff;
    --d-card2:    #f7f9fc;
    --d-brd:      #e2e8f0;
    --d-brand:    #0284C7;
    --d-brand-d:  #0369A1;
    --d-brand-bg: rgba(2,132,199,.08);
    --d-green:    #059669;
    --d-green-bg: rgba(5,150,105,.09);
    --d-amber:    #B45309;
    --d-amber-bg: rgba(180,83,9,.09);
    --d-rose:     #BE123C;
    --d-rose-bg:  rgba(190,18,60,.09);
    --d-violet:   #7C3AED;
    --d-slate:    #475569;
    --d-slate-bg: rgba(71,85,105,.09);
    --d-text:     #111827;
    --d-text2:    #374151;
    --d-muted:    #6b7280;
    --d-input-bg: #f8fafc;
    --d-shadow:   0 4px 24px rgba(0,0,0,.08);
    --d-radius:   10px;
}

/* ══════════════════════════════════════════════════
   BASE
══════════════════════════════════════════════════ */
.d2-page {
    padding: 24px 28px 48px;
    background: var(--d-navy);
    min-height: 100vh;
    transition: background .25s, color .25s;
}

/* ══════════════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════════════ */
.d2-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
    padding: 0 0 20px;
    border-bottom: 1px solid var(--d-brd);
    margin-bottom: 24px;
}
.d2-header__title {
    font-size: 1.35rem; font-weight: 800; color: var(--d-text);
    display: flex; align-items: center; gap: 10px; margin: 0;
}
.d2-header__icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; font-size: .9rem;
}
.d2-header__icon--sky   { background: var(--d-brand-bg); color: var(--d-brand); }
.d2-header__icon--rose  { background: var(--d-rose-bg);  color: var(--d-rose);  }
.d2-header__icon--amber { background: var(--d-amber-bg); color: var(--d-amber); }
.d2-header__icon--green { background: var(--d-green-bg); color: var(--d-green); }
.d2-header__actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }

/* ══════════════════════════════════════════════════
   TOGGLE TEMA
══════════════════════════════════════════════════ */
.d2-theme-toggle {
    display: flex; align-items: center;
    background: var(--d-card);
    border: 1px solid var(--d-brd);
    border-radius: 50px;
    padding: 4px; gap: 2px;
}
.d2-theme-toggle button {
    width: 30px; height: 30px; border-radius: 50px;
    border: none; background: transparent; cursor: pointer;
    color: var(--d-muted); font-size: 14px; transition: all .15s;
    display: flex; align-items: center; justify-content: center;
}
.d2-theme-toggle button.active { background: var(--d-brand); color: #fff; }

/* ══════════════════════════════════════════════════
   KPI GRID
══════════════════════════════════════════════════ */
.d2-kpi-grid {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 14px; margin-bottom: 22px;
}
@media(max-width:900px){ .d2-kpi-grid{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:500px){ .d2-kpi-grid{ grid-template-columns: 1fr; } }

.d2-kpi {
    position: relative; overflow: hidden;
    background: var(--d-card); border: 1px solid var(--d-brd);
    border-radius: var(--d-radius); padding: 18px 16px;
    display: flex; align-items: center; gap: 14px;
    transition: transform .2s, box-shadow .2s;
}
.d2-kpi:hover { transform: translateY(-3px); box-shadow: var(--d-shadow); }
.d2-kpi__icon {
    width: 44px; height: 44px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.d2-kpi--sky   .d2-kpi__icon { background: var(--d-brand-bg); color: var(--d-brand); }
.d2-kpi--green .d2-kpi__icon { background: var(--d-green-bg); color: var(--d-green); }
.d2-kpi--amber .d2-kpi__icon { background: var(--d-amber-bg); color: var(--d-amber); }
.d2-kpi--rose  .d2-kpi__icon { background: var(--d-rose-bg);  color: var(--d-rose);  }
.d2-kpi--bar { height: 3px; position: absolute; bottom: 0; left: 0; right: 0; }
.d2-kpi--sky   .d2-kpi--bar { background: var(--d-brand); }
.d2-kpi--green .d2-kpi--bar { background: var(--d-green); }
.d2-kpi--amber .d2-kpi--bar { background: var(--d-amber); }
.d2-kpi--rose  .d2-kpi--bar { background: var(--d-rose);  }
.d2-kpi__label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--d-muted); margin-bottom: 4px; }
.d2-kpi__value { font-size: 1.8rem; font-weight: 800; color: var(--d-text); line-height: 1; }

/* ══════════════════════════════════════════════════
   CARD
══════════════════════════════════════════════════ */
.d2-card {
    background: var(--d-card); border: 1px solid var(--d-brd);
    border-radius: var(--d-radius); overflow: hidden; margin-bottom: 20px;
    transition: box-shadow .2s;
}
.d2-card:hover { box-shadow: var(--d-shadow); }
.d2-card__hdr {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px; border-bottom: 1px solid var(--d-brd);
    background: var(--d-card2);
}
.d2-card__title {
    display: flex; align-items: center; gap: 8px;
    font-size: .73rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: var(--d-text); margin: 0;
}
.d2-card__tag {
    font-size: .65rem; font-weight: 700; color: var(--d-muted);
    background: var(--d-slate-bg); border: 1px solid var(--d-brd);
    border-radius: 100px; padding: 3px 10px;
}
.d2-card__body { padding: 18px; }

/* ══════════════════════════════════════════════════
   FILTROS
══════════════════════════════════════════════════ */
.d2-filter-body { padding: 16px 18px; }
.d2-filter-body .form-control,
.d2-filter-body select {
    background: var(--d-input-bg) !important;
    border: 1px solid var(--d-brd) !important;
    color: var(--d-text) !important;
    border-radius: 8px !important;
    font-size: .82rem !important;
}
.d2-filter-body .form-control:focus,
.d2-filter-body select:focus {
    border-color: var(--d-brand) !important;
    box-shadow: 0 0 0 3px var(--d-brand-bg) !important;
    outline: none !important;
}
.d2-filter-label {
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em;
    color: var(--d-muted); display: block; margin-bottom: 5px;
}

/* ══════════════════════════════════════════════════
   TABLE
══════════════════════════════════════════════════ */
.d2-table { width: 100%; border-collapse: collapse; }
.d2-table thead th {
    background: var(--d-card2); color: var(--d-muted);
    font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
    padding: 10px 14px; text-align: left; border-bottom: 1px solid var(--d-brd);
}
.d2-table tbody tr { border-bottom: 1px solid var(--d-brd); transition: background .15s; }
.d2-table tbody tr:hover { background: var(--d-brand-bg); }
.d2-table tbody td { padding: 11px 14px; font-size: .82rem; color: var(--d-text2); vertical-align: middle; }
.d2-table tbody tr:last-child { border-bottom: none; }

/* DataTables */
.dataTables_wrapper { color: var(--d-text) !important; }
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    background: var(--d-input-bg) !important;
    border: 1px solid var(--d-brd) !important;
    color: var(--d-text) !important;
    border-radius: 6px !important; padding: 4px 8px !important;
}
.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_info { color: var(--d-muted) !important; font-size: .75rem !important; }
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: var(--d-card) !important; border: 1px solid var(--d-brd) !important;
    color: var(--d-muted) !important; border-radius: 6px !important; margin: 0 2px !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--d-brand) !important; border-color: var(--d-brand) !important; color: #fff !important;
}

/* ══════════════════════════════════════════════════
   AVATAR
══════════════════════════════════════════════════ */
.d2-av {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .8rem; flex-shrink: 0;
}
.d2-av--rose  { background: var(--d-rose-bg);  color: var(--d-rose);  }
.d2-av--sky   { background: var(--d-brand-bg); color: var(--d-brand); }
.d2-av--green { background: var(--d-green-bg); color: var(--d-green); }
.d2-av--amber { background: var(--d-amber-bg); color: var(--d-amber); }

/* ══════════════════════════════════════════════════
   BADGE
══════════════════════════════════════════════════ */
.d2-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .64rem; font-weight: 700; padding: 3px 9px; border-radius: 100px;
}
.d2-badge--sky    { background: var(--d-brand-bg); color: var(--d-brand); }
.d2-badge--green  { background: var(--d-green-bg); color: var(--d-green); }
.d2-badge--amber  { background: var(--d-amber-bg); color: var(--d-amber); }
.d2-badge--rose   { background: var(--d-rose-bg);  color: var(--d-rose);  }
.d2-badge--violet { background: rgba(139,92,246,.10); color: var(--d-violet); }
.d2-badge--slate  { background: var(--d-slate-bg); color: var(--d-slate); }
.d2-badge--success{ background: var(--d-green-bg); color: var(--d-green); }
.d2-badge--warning{ background: var(--d-amber-bg); color: var(--d-amber); }
.d2-badge--danger { background: var(--d-rose-bg);  color: var(--d-rose);  }
.d2-badge--info   { background: var(--d-brand-bg); color: var(--d-brand); }

/* ══════════════════════════════════════════════════
   SCORE
══════════════════════════════════════════════════ */
.d2-score {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 38px; padding: 4px 10px; border-radius: 8px;
    font-size: .9rem; font-weight: 800;
}
.d2-score--green { background: var(--d-green-bg); color: var(--d-green); }
.d2-score--amber { background: var(--d-amber-bg); color: var(--d-amber); }
.d2-score--rose  { background: var(--d-rose-bg);  color: var(--d-rose);  }

/* ══════════════════════════════════════════════════
   BTN
══════════════════════════════════════════════════ */
.d2-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 8px; font-size: .79rem; font-weight: 700;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .2s; font-family: inherit; white-space: nowrap;
}
.d2-btn--sky   { background: var(--d-brand); color: #fff !important; }
.d2-btn--sky:hover   { background: var(--d-brand-d); box-shadow: 0 4px 14px rgba(14,165,233,.4); text-decoration: none; }
.d2-btn--green { background: var(--d-green); color: #fff !important; }
.d2-btn--green:hover { box-shadow: 0 4px 14px rgba(16,185,129,.4); text-decoration: none; }
.d2-btn--rose  { background: var(--d-rose);  color: #fff !important; }
.d2-btn--rose:hover  { box-shadow: 0 4px 14px rgba(244,63,94,.4);  text-decoration: none; }
.d2-btn--amber { background: var(--d-amber); color: #fff !important; }
.d2-btn--amber:hover { box-shadow: 0 4px 14px rgba(245,158,11,.4); text-decoration: none; }
.d2-btn--ghost {
    background: var(--d-slate-bg); color: var(--d-muted) !important;
    border: 1px solid var(--d-brd);
}
.d2-btn--ghost:hover { background: var(--d-brd); color: var(--d-text) !important; text-decoration: none; }

/* ══════════════════════════════════════════════════
   INFO PILL
══════════════════════════════════════════════════ */
.d2-info-pill {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    background: var(--d-brand-bg); border: 1px solid rgba(14,165,233,.2);
    font-size: .8rem; color: var(--d-muted); margin-bottom: 16px; line-height: 1.5;
}
.d2-info-pill i { color: var(--d-brand); margin-top: 2px; flex-shrink: 0; }

/* ══════════════════════════════════════════════════
   MODAL — esto es lo que faltaba para el tema
══════════════════════════════════════════════════ */
.d2-modal .modal-content {
    background: var(--d-card) !important;
    border: 1px solid var(--d-brd) !important;
    color: var(--d-text) !important;
    border-radius: 14px !important;
    box-shadow: 0 24px 64px rgba(0,0,0,.5) !important;
}
.d2-modal .modal-header {
    background: var(--d-card2) !important;
    border-bottom: 1px solid var(--d-brd) !important;
    border-radius: 14px 14px 0 0 !important;
    padding: 16px 20px !important;
}
.d2-modal .modal-title {
    font-size: .95rem !important; font-weight: 800 !important; color: var(--d-text) !important;
}
.d2-modal .modal-body {
    background: var(--d-card) !important;
    padding: 20px !important;
}
.d2-modal .modal-footer {
    background: var(--d-card2) !important;
    border-top: 1px solid var(--d-brd) !important;
    border-radius: 0 0 14px 14px !important;
    padding: 14px 20px !important;
}
.d2-modal label {
    font-size: .72rem !important; font-weight: 700 !important;
    text-transform: uppercase !important; letter-spacing: .06em !important;
    color: var(--d-muted) !important; display: block !important; margin-bottom: 6px !important;
}
.d2-modal .form-control,
.d2-modal select,
.d2-modal textarea {
    background: var(--d-input-bg) !important;
    border: 1px solid var(--d-brd) !important;
    color: var(--d-text) !important;
    border-radius: 9px !important;
    font-size: .84rem !important;
    transition: border-color .15s !important;
}
.d2-modal .form-control:focus,
.d2-modal select:focus,
.d2-modal textarea:focus {
    border-color: var(--d-brand) !important;
    box-shadow: 0 0 0 3px var(--d-brand-bg) !important;
    outline: none !important;
}
.d2-modal .close {
    color: var(--d-muted) !important; opacity: 1 !important;
    font-size: 1.3rem !important; background: none !important; border: none !important;
}
.d2-modal .close:hover { color: var(--d-text) !important; }
.d2-modal .custom-control-label {
    color: var(--d-text2) !important;
    font-size: .84rem !important; text-transform: none !important;
    letter-spacing: 0 !important;
}
.d2-modal .form-control-file { color: var(--d-muted) !important; font-size: .82rem !important; }
/* backdrop más oscuro en claro */
html[data-theme="light"] .modal-backdrop { background-color: #1e293b; }

/* ══════════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════════ */
.d2-empty {
    padding: 52px 32px; text-align: center; color: var(--d-muted);
}
.d2-empty i { font-size: 2.5rem; display: block; margin-bottom: 12px; opacity: .3; }

/* ══════════════════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════════════════ */
@keyframes d2fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}
.d2-kpi  { animation: d2fadeUp .3s cubic-bezier(.22,1,.36,1) both; }
.d2-kpi:nth-child(1){ animation-delay:.03s }
.d2-kpi:nth-child(2){ animation-delay:.07s }
.d2-kpi:nth-child(3){ animation-delay:.11s }
.d2-kpi:nth-child(4){ animation-delay:.15s }
.d2-card { animation: d2fadeUp .35s cubic-bezier(.22,1,.36,1) .1s both; }
</style>
@endsection

@section('content')
<div class="d2-page">

    {{-- ╔══ HEADER ══╗ --}}
    <div class="d2-header">
        <h1 class="d2-header__title">
            <span class="d2-header__icon d2-header__icon--rose"><i class="fas fa-file-alt"></i></span>
            Reportes Académicos
        </h1>
        <div class="d2-header__actions">
            {{-- Toggle claro/oscuro --}}
            <div class="d2-theme-toggle" id="d2ThemeToggle">
                <button id="d2BtnLight" onclick="d2SetTheme('light')" title="Modo claro">
                    <i class="fas fa-sun"></i>
                </button>
                <button id="d2BtnDark" onclick="d2SetTheme('dark')" title="Modo oscuro">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
            <button class="d2-btn d2-btn--sky" data-toggle="modal" data-target="#createReporteModal">
                <i class="fas fa-plus"></i> Nuevo Reporte
            </button>
        </div>
    </div>

    {{-- ╔══ KPIs ══╗ --}}
    <div class="d2-kpi-grid">
        <div class="d2-kpi d2-kpi--sky">
            <div class="d2-kpi__icon"><i class="fas fa-file-alt"></i></div>
            <div>
                <div class="d2-kpi__label">Total Reportes</div>
                <div class="d2-kpi__value">{{ $reportes->count() }}</div>
            </div>
            <div class="d2-kpi--bar"></div>
        </div>
        <div class="d2-kpi d2-kpi--green">
            <div class="d2-kpi__icon"><i class="fas fa-eye"></i></div>
            <div>
                <div class="d2-kpi__label">Publicados</div>
                <div class="d2-kpi__value">{{ $reportes->where('visible_tutor', true)->count() }}</div>
            </div>
            <div class="d2-kpi--bar"></div>
        </div>
        <div class="d2-kpi d2-kpi--amber">
            <div class="d2-kpi__icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="d2-kpi__label">Pendientes</div>
                <div class="d2-kpi__value">{{ $reportes->where('visible_tutor', false)->count() }}</div>
            </div>
            <div class="d2-kpi--bar"></div>
        </div>
        <div class="d2-kpi d2-kpi--rose">
            <div class="d2-kpi__icon"><i class="fas fa-file-pdf"></i></div>
            <div>
                <div class="d2-kpi__label">Con PDF</div>
                <div class="d2-kpi__value">{{ $reportes->filter(fn($r) => $r->tienePdf())->count() }}</div>
            </div>
            <div class="d2-kpi--bar"></div>
        </div>
    </div>

    {{-- ╔══ FILTROS ══╗ --}}
    <div class="d2-card">
        <div class="d2-card__hdr" style="cursor:pointer" data-toggle="collapse" data-target="#filtrosCollapse">
            <h3 class="d2-card__title">
                <span class="d2-header__icon d2-header__icon--sky" style="width:24px;height:24px;border-radius:7px;font-size:.7rem">
                    <i class="fas fa-filter"></i>
                </span>
                Filtros de Búsqueda
            </h3>
            <button class="d2-btn d2-btn--ghost" style="padding:5px 10px;font-size:.75rem" type="button">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="filtrosCollapse">
            <div class="d2-filter-body">
                <form action="{{ route('docente.reportes.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="d2-filter-label">Estudiante</label>
                            <select name="estudiante_id" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                @foreach($estudiantes as $e)
                                    <option value="{{ $e->id }}" {{ request('estudiante_id')==$e->id?'selected':'' }}>
                                        {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="d2-filter-label">Periodo</label>
                            <select name="periodo_id" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                @foreach($periodos as $p)
                                    <option value="{{ $p->id }}" {{ request('periodo_id')==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="d2-filter-label">Tipo</label>
                            <select name="tipo" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                @foreach(['Bimestral','Trimestral','Anual'] as $t)
                                    <option value="{{ $t }}" {{ request('tipo')==$t?'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="d2-filter-label">Visible</label>
                            <select name="visible" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                <option value="1" {{ request('visible')==='1'?'selected':'' }}>Sí</option>
                                <option value="0" {{ request('visible')==='0'?'selected':'' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                            <button type="submit" class="d2-btn d2-btn--sky" style="padding:7px 14px">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('docente.reportes.index') }}" class="d2-btn d2-btn--ghost" style="padding:7px 12px">
                                <i class="fas fa-eraser"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ╔══ TABLA ══╗ --}}
    <div class="d2-card">
        <div class="d2-card__hdr">
            <h3 class="d2-card__title">
                <span class="d2-header__icon d2-header__icon--rose" style="width:24px;height:24px;border-radius:7px;font-size:.7rem">
                    <i class="fas fa-list"></i>
                </span>
                Reportes Generados
            </h3>
            <span class="d2-card__tag">{{ $reportes->count() }} registros</span>
        </div>
        <div style="overflow-x:auto">
            @if($reportes->count() > 0)
            <table id="reportesTable" class="d2-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Periodo</th>
                        <th>Gestión</th>
                        <th>Tipo</th>
                        <th style="text-align:center">Promedio</th>
                        <th style="text-align:center">Asistencia</th>
                        <th style="text-align:center">PDF</th>
                        <th style="text-align:center">Visible</th>
                        <th>Fecha</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportes as $reporte)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="d2-av d2-av--rose">
                                    {{ strtoupper(substr($reporte->estudiante->persona->apellidos,0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.83rem;color:var(--d-text)">
                                        {{ $reporte->estudiante->persona->apellidos }}, {{ $reporte->estudiante->persona->nombres }}
                                    </div>
                                    <div style="font-size:.7rem;color:var(--d-muted)">
                                        {{ $reporte->estudiante->codigo_estudiante }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><span class="d2-badge d2-badge--slate">{{ $reporte->periodo->nombre }}</span></td>
                        <td><span style="font-size:.82rem;color:var(--d-muted)">{{ $reporte->gestion->anio ?? $reporte->gestion->nombre }}</span></td>
                        <td><span class="d2-badge d2-badge--{{ $reporte->tipo_badge }}">{{ $reporte->tipo }}</span></td>
                        <td style="text-align:center">
                            @if($reporte->promedio_general)
                                @php $sc = $reporte->promedio_general >= 14 ? 'green' : ($reporte->promedio_general >= 11 ? 'amber' : 'rose'); @endphp
                                <span class="d2-score d2-score--{{ $sc }}">{{ number_format($reporte->promedio_general,2) }}</span>
                            @else <span style="color:var(--d-muted)">—</span> @endif
                        </td>
                        <td style="text-align:center">
                            @if($reporte->porcentaje_asistencia)
                                @php $asc = $reporte->porcentaje_asistencia >= 80 ? 'green' : ($reporte->porcentaje_asistencia >= 60 ? 'amber' : 'rose'); @endphp
                                <span class="d2-badge d2-badge--{{ $asc }}">{{ number_format($reporte->porcentaje_asistencia,1) }}%</span>
                            @else <span style="color:var(--d-muted)">—</span> @endif
                        </td>
                        <td style="text-align:center">
                            @if($reporte->tienePdf())
                                <a href="{{ route('docente.reportes.descargar-pdf',$reporte->id) }}"
                                   class="d2-btn d2-btn--rose" style="padding:5px 10px;font-size:.72rem">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @else <span style="color:var(--d-muted);font-size:.8rem">—</span> @endif
                        </td>
                        <td style="text-align:center">
                            @if($reporte->visible_tutor)
                                <span class="d2-badge d2-badge--green"><i class="fas fa-eye"></i> Sí</span>
                            @else
                                <span class="d2-badge d2-badge--slate"><i class="fas fa-eye-slash"></i> No</span>
                            @endif
                        </td>
                        <td style="font-size:.75rem;color:var(--d-muted)">{{ $reporte->fecha_generacion_formateada }}</td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:4px;justify-content:center">
                                <a href="{{ route('docente.reportes.show',$reporte->id) }}"
                                   class="d2-btn d2-btn--sky" style="padding:5px 9px;font-size:.72rem" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="d2-btn d2-btn--green" style="padding:5px 9px;font-size:.72rem"
                                        data-toggle="modal" data-target="#editReporteModal{{ $reporte->id }}" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="d2-btn d2-btn--ghost" style="padding:5px 9px;font-size:.72rem"
                                        data-toggle="modal" data-target="#deleteReporteModal{{ $reporte->id }}" title="Eliminar">
                                    <i class="fas fa-trash" style="color:var(--d-rose)"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- ╔═ MODAL EDITAR ═╗ --}}
                    <div class="modal fade d2-modal" id="editReporteModal{{ $reporte->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit" style="color:var(--d-green);margin-right:8px"></i>
                                        Editar Reporte
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <form action="{{ route('docente.reportes.update',$reporte->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="d2-info-pill">
                                            <i class="fas fa-user-graduate"></i>
                                            Editando reporte de <strong style="color:var(--d-text);margin-left:4px">
                                                {{ $reporte->estudiante->persona->nombres }} {{ $reporte->estudiante->persona->apellidos }}
                                            </strong>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Estudiante *</label>
                                                <select name="estudiante_id" class="form-control" required>
                                                    @foreach($estudiantes as $e)
                                                        <option value="{{ $e->id }}" {{ old('estudiante_id',$reporte->estudiante_id)==$e->id?'selected':'' }}>
                                                            {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Tipo *</label>
                                                <select name="tipo" class="form-control" required>
                                                    @foreach(['Bimestral','Trimestral','Anual'] as $t)
                                                        <option value="{{ $t }}" {{ old('tipo',$reporte->tipo)==$t?'selected':'' }}>{{ $t }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Periodo *</label>
                                                <select name="periodo_id" class="form-control" required>
                                                    @foreach($periodos as $p)
                                                        <option value="{{ $p->id }}" {{ old('periodo_id',$reporte->periodo_id)==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Gestión *</label>
                                                <select name="gestion_id" class="form-control" required>
                                                    @foreach($gestiones as $g)
                                                        <option value="{{ $g->id }}" {{ old('gestion_id',$reporte->gestion_id)==$g->id?'selected':'' }}>
                                                            {{ $g->anio ?? $g->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Archivo PDF</label>
                                                <input type="file" name="archivo_pdf" class="form-control-file" accept=".pdf">
                                                @if($reporte->tienePdf())
                                                    <small style="color:var(--d-muted)"><i class="fas fa-check-circle" style="color:var(--d-green)"></i> Ya tiene PDF</small>
                                                @endif
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Promedio General (0–20)</label>
                                                <input type="number" name="promedio_general" class="form-control"
                                                       step="0.01" min="0" max="20"
                                                       value="{{ old('promedio_general',$reporte->promedio_general) }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Asistencia % (0–100)</label>
                                                <input type="number" name="porcentaje_asistencia" class="form-control"
                                                       step="0.01" min="0" max="100"
                                                       value="{{ old('porcentaje_asistencia',$reporte->porcentaje_asistencia) }}">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           id="vt{{ $reporte->id }}" name="visible_tutor" value="1"
                                                           {{ old('visible_tutor',$reporte->visible_tutor)?'checked':'' }}>
                                                    <label class="custom-control-label" for="vt{{ $reporte->id }}">
                                                        Visible para Tutores
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label>Comentario Final</label>
                                                <textarea name="comentario_final" class="form-control" rows="3" maxlength="2000">{{ old('comentario_final',$reporte->comentario_final) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="d2-btn d2-btn--ghost" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="d2-btn d2-btn--green">
                                            <i class="fas fa-save"></i> Actualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- ╔═ MODAL ELIMINAR ═╗ --}}
                    <div class="modal fade d2-modal" id="deleteReporteModal{{ $reporte->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background:var(--d-rose-bg)!important;border-bottom:1px solid rgba(190,18,60,.2)!important">
                                    <h5 class="modal-title">
                                        <i class="fas fa-exclamation-triangle" style="color:var(--d-rose);margin-right:8px"></i>
                                        Confirmar Eliminación
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <form action="{{ route('docente.reportes.destroy',$reporte->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body">
                                        <div style="background:var(--d-rose-bg);border:1px solid rgba(190,18,60,.2);border-radius:10px;padding:14px 16px;margin-bottom:14px">
                                            <div style="font-weight:700;color:var(--d-text);font-size:.88rem;margin-bottom:3px">
                                                {{ $reporte->estudiante->persona->nombres }} {{ $reporte->estudiante->persona->apellidos }}
                                            </div>
                                            <div style="font-size:.78rem;color:var(--d-muted)">
                                                {{ $reporte->tipo }} &middot; {{ $reporte->periodo->nombre }}
                                            </div>
                                        </div>
                                        <p style="color:var(--d-muted);font-size:.84rem;margin:0">
                                            ¿Estás seguro? Esta acción no se puede deshacer.
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="d2-btn d2-btn--ghost" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="d2-btn d2-btn--rose">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
            @else
            <div class="d2-empty">
                <i class="fas fa-file-alt"></i>
                <div style="font-size:.88rem">No hay reportes registrados aún.</div>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ╔══ MODAL CREAR ══╗ --}}
<div class="modal fade d2-modal" id="createReporteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus" style="color:var(--d-brand);margin-right:8px"></i>
                    Generar Nuevo Reporte
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('docente.reportes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="d2-info-pill">
                        <i class="fas fa-lightbulb"></i>
                        Puedes dejar el promedio y asistencia en blanco; se calcularán automáticamente a partir de los registros existentes.
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Estudiante *</label>
                            <select name="estudiante_id_create" class="form-control" required>
                                <option value="">— Seleccione —</option>
                                @foreach($estudiantes as $e)
                                    <option value="{{ $e->id }}" {{ old('estudiante_id_create')==$e->id?'selected':'' }}>
                                        {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tipo de Reporte *</label>
                            <select name="tipo_create" class="form-control" required>
                                @foreach(['Bimestral','Trimestral','Anual'] as $t)
                                    <option value="{{ $t }}" {{ old('tipo_create','Bimestral')==$t?'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Periodo *</label>
                            <select name="periodo_id_create" class="form-control" required>
                                <option value="">— Seleccione —</option>
                                @foreach($periodos as $p) <option value="{{ $p->id }}">{{ $p->nombre }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Gestión *</label>
                            <select name="gestion_id_create" class="form-control" required>
                                <option value="">— Seleccione —</option>
                                @foreach($gestiones as $g) <option value="{{ $g->id }}">{{ $g->anio ?? $g->nombre }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Archivo PDF (opcional)</label>
                            <input type="file" name="archivo_pdf_create" class="form-control-file" accept=".pdf">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="vtc"
                                       name="visible_tutor_create" value="1"
                                       {{ old('visible_tutor_create')?'checked':'' }}>
                                <label class="custom-control-label" for="vtc">
                                    Publicar inmediatamente (visible para tutores)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Comentario Final del Docente</label>
                            <textarea name="comentario_final_create" class="form-control" rows="4"
                                      maxlength="2000"
                                      placeholder="Comentario sobre el desempeño general del estudiante...">{{ old('comentario_final_create') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="d2-btn d2-btn--ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="d2-btn d2-btn--sky">
                        <i class="fas fa-save"></i> Guardar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ════════════════════════════════════════
   TOGGLE TEMA — la clave es poner data-theme
   en document.documentElement (el <html>)
   porque ahí están definidas las variables CSS
════════════════════════════════════════ */
function d2SetTheme(mode) {
    // Aplicar en <html> — OBLIGATORIO para que las variables CSS funcionen
    document.documentElement.setAttribute('data-theme', mode);
    // También en body por si el layout base lo usa
    document.body.setAttribute('data-theme', mode);
    // Marcar botón activo
    document.getElementById('d2BtnLight').classList.toggle('active', mode === 'light');
    document.getElementById('d2BtnDark').classList.toggle('active',  mode === 'dark');
    // Persistir preferencia
    try { localStorage.setItem('docente-theme', mode); } catch(e) {}
}

// Restaurar tema al cargar la página
(function() {
    var saved = 'dark';
    try { saved = localStorage.getItem('docente-theme') || 'dark'; } catch(e) {}
    d2SetTheme(saved);
})();

/* ════════════════════════════════════════
   DATATABLE + ALERTAS
════════════════════════════════════════ */
$(document).ready(function(){
    @if($reportes->count() > 0)
    $('#reportesTable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
        responsive: true,
        autoWidth: false,
        order: [[0, 'asc']],
        columnDefs: [{ orderable: false, targets: [6, 9] }]
    });
    @endif

    @if(session('mensaje'))
    Swal.fire({
        icon: '{{ session("icono") }}',
        title: '{{ session("mensaje") }}',
        timer: 2500,
        showConfirmButton: false,
        background: 'var(--d-card)',
        color: 'var(--d-text)'
    });
    @endif

    @if($errors->has('estudiante_id_create') || $errors->has('tipo_create'))
    $('#createReporteModal').modal('show');
    @endif
});
</script>
@endsection