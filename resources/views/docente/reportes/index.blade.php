@extends('layouts.docente')

@section('title', 'Mis Reportes Academicos')
@section('page_title')Reportes <span>Academicos</span>@endsection

@section('content')
<div class="d2-page">

    {{-- Header --}}
    <div class="d2-header">
        <h1 class="d2-header__title">
            <span class="d2-header__icon d2-header__icon--rose"><i class="fas fa-file-alt"></i></span>
            Reportes Academicos
        </h1>
        <div class="d2-header__actions">
            <button class="d2-btn d2-btn--sky" data-toggle="modal" data-target="#createReporteModal">
                <i class="fas fa-plus"></i> Nuevo Reporte
            </button>
        </div>
    </div>

    {{-- KPIs --}}
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

    {{-- Filtros --}}
    <div class="d2-card">
        <div class="d2-card__hdr">
            <h3 class="d2-card__title"><span class="d2-header__icon d2-header__icon--sky" style="width:22px;height:22px;border-radius:6px;font-size:.65rem"><i class="fas fa-filter"></i></span>Filtros</h3>
            <button class="d2-btn d2-btn--ghost" type="button" data-toggle="collapse" data-target="#filtrosCollapse">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="filtrosCollapse">
            <div class="d2-card__body d2-filters">
                <form action="{{ route('docente.reportes.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="d2-kpi__label d-block mb-1">Estudiante</label>
                            <select name="estudiante_id" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                @foreach($estudiantes as $e)
                                    <option value="{{ $e->id }}" {{ request('estudiante_id')==$e->id?'selected':'' }}>
                                        {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="d2-kpi__label d-block mb-1">Periodo</label>
                            <select name="periodo_id" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                @foreach($periodos as $p)
                                    <option value="{{ $p->id }}" {{ request('periodo_id')==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="d2-kpi__label d-block mb-1">Tipo</label>
                            <select name="tipo" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                @foreach(['Bimestral','Trimestral','Anual'] as $t)
                                    <option value="{{ $t }}" {{ request('tipo')==$t?'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="d2-kpi__label d-block mb-1">Visible</label>
                            <select name="visible" class="form-control form-control-sm">
                                <option value="">Todos</option>
                                <option value="1" {{ request('visible')==='1'?'selected':'' }}>Si</option>
                                <option value="0" {{ request('visible')==='0'?'selected':'' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end gap-2">
                            <button type="submit" class="d2-btn d2-btn--sky btn-sm"><i class="fas fa-search"></i> Buscar</button>
                            <a href="{{ route('docente.reportes.index') }}" class="d2-btn d2-btn--ghost btn-sm"><i class="fas fa-eraser"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="d2-card">
        <div class="d2-card__hdr">
            <h3 class="d2-card__title"><span class="d2-header__icon d2-header__icon--rose" style="width:22px;height:22px;border-radius:6px;font-size:.65rem"><i class="fas fa-list"></i></span>Reportes Generados</h3>
            <span class="d2-card__tag">{{ $reportes->count() }}</span>
        </div>
        <div style="overflow-x:auto">
            <table id="reportesTable" class="d2-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Periodo</th>
                        <th>Gestion</th>
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
                                <div class="d2-av d2-av--rose">{{ strtoupper(substr($reporte->estudiante->persona->apellidos,0,1)) }}</div>
                                <div>
                                    <div style="font-weight:700;font-size:.83rem">{{ $reporte->estudiante->persona->apellidos }}, {{ $reporte->estudiante->persona->nombres }}</div>
                                    <div style="font-size:.7rem;color:var(--d-muted)">{{ $reporte->estudiante->codigo_estudiante }}</div>
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
                            @else <span style="color:var(--d-muted)">-</span> @endif
                        </td>
                        <td style="text-align:center">
                            @if($reporte->porcentaje_asistencia)
                                @php $asc = $reporte->porcentaje_asistencia >= 80 ? 'green' : ($reporte->porcentaje_asistencia >= 60 ? 'amber' : 'rose'); @endphp
                                <span class="d2-badge d2-badge--{{ $asc }}">{{ number_format($reporte->porcentaje_asistencia,1) }}%</span>
                            @else <span style="color:var(--d-muted)">-</span> @endif
                        </td>
                        <td style="text-align:center">
                            @if($reporte->tienePdf())
                                <a href="{{ route('docente.reportes.descargar-pdf',$reporte->id) }}" class="d2-btn d2-btn--rose" style="padding:5px 10px;font-size:.72rem"><i class="fas fa-file-pdf"></i></a>
                            @else <span style="color:var(--d-muted);font-size:.8rem">-</span> @endif
                        </td>
                        <td style="text-align:center">
                            @if($reporte->visible_tutor)
                                <span class="d2-badge d2-badge--green"><i class="fas fa-eye"></i> Si</span>
                            @else
                                <span class="d2-badge d2-badge--slate"><i class="fas fa-eye-slash"></i> No</span>
                            @endif
                        </td>
                        <td style="font-size:.75rem;color:var(--d-muted)">{{ $reporte->fecha_generacion_formateada }}</td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:4px;justify-content:center">
                                <a href="{{ route('docente.reportes.show',$reporte->id) }}" class="d2-btn d2-btn--sky" style="padding:5px 9px;font-size:.72rem" title="Ver"><i class="fas fa-eye"></i></a>
                                <button class="d2-btn d2-btn--green" style="padding:5px 9px;font-size:.72rem" data-toggle="modal" data-target="#editReporteModal{{ $reporte->id }}" title="Editar"><i class="fas fa-edit"></i></button>
                                <button class="d2-btn d2-btn--rose" style="padding:5px 9px;font-size:.72rem" data-toggle="modal" data-target="#deleteReporteModal{{ $reporte->id }}" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade d2-modal" id="editReporteModal{{ $reporte->id }}" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header" style="background:var(--d-card)">
                                    <h5 class="modal-title" style="color:var(--d-green)"><i class="fas fa-edit"></i> Editar Reporte</h5>
                                    <button type="button" class="close" data-dismiss="modal" style="color:var(--d-muted)"><span>&times;</span></button>
                                </div>
                                <form action="{{ route('docente.reportes.update',$reporte->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Estudiante *</label>
                                                <select name="estudiante_id" class="form-control" required>
                                                    @foreach($estudiantes as $e)
                                                        <option value="{{ $e->id }}" {{ old('estudiante_id',$reporte->estudiante_id)==$e->id?'selected':'' }}>{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</option>
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
                                                <label>Gestion *</label>
                                                <select name="gestion_id" class="form-control" required>
                                                    @foreach($gestiones as $g)
                                                        <option value="{{ $g->id }}" {{ old('gestion_id',$reporte->gestion_id)==$g->id?'selected':'' }}>{{ $g->anio ?? $g->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Archivo PDF</label>
                                                <input type="file" name="archivo_pdf" class="form-control-file" accept=".pdf">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Promedio General (0-20)</label>
                                                <input type="number" name="promedio_general" class="form-control" step="0.01" min="0" max="20" value="{{ old('promedio_general',$reporte->promedio_general) }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Asistencia % (0-100)</label>
                                                <input type="number" name="porcentaje_asistencia" class="form-control" step="0.01" min="0" max="100" value="{{ old('porcentaje_asistencia',$reporte->porcentaje_asistencia) }}">
                                            </div>
                                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="vt{{ $reporte->id }}" name="visible_tutor" value="1" {{ old('visible_tutor',$reporte->visible_tutor)?'checked':'' }}>
                                                    <label class="custom-control-label" for="vt{{ $reporte->id }}">Visible para Tutores</label>
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
                                        <button type="submit" class="d2-btn d2-btn--green"><i class="fas fa-save"></i> Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Eliminar --}}
                    <div class="modal fade d2-modal" id="deleteReporteModal{{ $reporte->id }}" tabindex="-1">
                        <div class="modal-dialog"><div class="modal-content">
                            <div class="modal-header" style="background:rgba(244,63,94,.1);border-bottom:1px solid rgba(244,63,94,.2)">
                                <h5 class="modal-title" style="color:var(--d-rose)"><i class="fas fa-exclamation-triangle"></i> Confirmar Eliminacion</h5>
                                <button type="button" class="close" data-dismiss="modal" style="color:var(--d-muted)"><span>&times;</span></button>
                            </div>
                            <form action="{{ route('docente.reportes.destroy',$reporte->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <div class="modal-body">
                                    <div class="d2-info-pill"><i class="fas fa-info-circle"></i>
                                        <div>
                                            <strong>{{ $reporte->estudiante->persona->nombres }} {{ $reporte->estudiante->persona->apellidos }}</strong><br>
                                            Tipo: {{ $reporte->tipo }} &middot; Periodo: {{ $reporte->periodo->nombre }}
                                        </div>
                                    </div>
                                    <p style="color:var(--d-muted);font-size:.85rem">Esta accion no se puede deshacer.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="d2-btn d2-btn--ghost" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="d2-btn d2-btn--rose"><i class="fas fa-trash"></i> Eliminar</button>
                                </div>
                            </form>
                        </div></div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Crear --}}
<div class="modal fade d2-modal" id="createReporteModal" tabindex="-1">
    <div class="modal-dialog modal-xl"><div class="modal-content">
        <div class="modal-header" style="background:var(--d-card)">
            <h5 class="modal-title" style="color:var(--d-brand)"><i class="fas fa-plus"></i> Generar Nuevo Reporte</h5>
            <button type="button" class="close" data-dismiss="modal" style="color:var(--d-muted)"><span>&times;</span></button>
        </div>
        <form action="{{ route('docente.reportes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="d2-info-pill"><i class="fas fa-lightbulb"></i> Puedes dejar el promedio y asistencia en blanco, se calcularan automaticamente.</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Estudiante *</label>
                        <select name="estudiante_id_create" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($estudiantes as $e)
                                <option value="{{ $e->id }}" {{ old('estudiante_id_create')==$e->id?'selected':'' }}>{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tipo *</label>
                        <select name="tipo_create" class="form-control" required>
                            @foreach(['Bimestral','Trimestral','Anual'] as $t)
                                <option value="{{ $t }}" {{ old('tipo_create','Bimestral')==$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Periodo *</label>
                        <select name="periodo_id_create" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($periodos as $p) <option value="{{ $p->id }}">{{ $p->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Gestion *</label>
                        <select name="gestion_id_create" class="form-control" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($gestiones as $g) <option value="{{ $g->id }}">{{ $g->anio ?? $g->nombre }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Archivo PDF</label>
                        <input type="file" name="archivo_pdf_create" class="form-control-file" accept=".pdf">
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="vtc" name="visible_tutor_create" value="1" {{ old('visible_tutor_create')?'checked':'' }}>
                            <label class="custom-control-label" for="vtc">Visible para Tutores (publicar automaticamente)</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Comentario Final del Docente</label>
                        <textarea name="comentario_final_create" class="form-control" rows="3" maxlength="2000" placeholder="Comentario sobre el desempeno del estudiante...">{{ old('comentario_final_create') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="d2-btn d2-btn--ghost" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="d2-btn d2-btn--sky"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div></div>
</div>
@endsection

@section('css')
<style>
:root {
    --d-navy:    #0F172A;
    --d-card:    #1E293B;
    --d-brd:     #334155;
    --d-brand:   #0EA5E9;
    --d-brand-d: #0284C7;
    --d-brand-bg:rgba(14,165,233,.08);
    --d-green:   #10B981;
    --d-amber:   #F59E0B;
    --d-rose:    #F43F5E;
    --d-violet:  #8B5CF6;
    --d-slate:   #64748B;
    --d-text:    #F1F5F9;
    --d-muted:   #94A3B8;
    --d-radius:  10px;
    --d-shadow:  0 4px 24px rgba(0,0,0,.35);
}

/* ---- Layout base ---- */
.d2-page { padding: 24px 28px; background: var(--d-navy); min-height: 100vh; }

/* ---- Header de pagina ---- */
.d2-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
    padding: 0 0 20px; border-bottom: 1px solid var(--d-brd); margin-bottom: 24px;
}
.d2-header__title {
    font-size: 1.35rem; font-weight: 800; color: var(--d-text);
    display: flex; align-items: center; gap: 10px; margin: 0;
}
.d2-header__icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; font-size: .9rem;
}
.d2-header__icon--sky   { background: rgba(14,165,233,.15); color: var(--d-brand); }
.d2-header__icon--rose  { background: rgba(244,63,94,.15);  color: var(--d-rose); }
.d2-header__icon--amber { background: rgba(245,158,11,.15); color: var(--d-amber); }
.d2-header__icon--green { background: rgba(16,185,129,.15); color: var(--d-green); }
.d2-header__actions { display: flex; gap: 8px; flex-wrap: wrap; }

/* ---- KPI grid ---- */
.d2-kpi-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 22px; }
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
.d2-kpi--sky   .d2-kpi__icon { background: rgba(14,165,233,.12); color: var(--d-brand); }
.d2-kpi--green .d2-kpi__icon { background: rgba(16,185,129,.10); color: var(--d-green); }
.d2-kpi--amber .d2-kpi__icon { background: rgba(245,158,11,.10); color: var(--d-amber); }
.d2-kpi--rose  .d2-kpi__icon { background: rgba(244,63,94,.10);  color: var(--d-rose); }
.d2-kpi--bar { height: 3px; position: absolute; bottom: 0; left: 0; right: 0; }
.d2-kpi--sky  .d2-kpi--bar { background: var(--d-brand); }
.d2-kpi--green .d2-kpi--bar { background: var(--d-green); }
.d2-kpi--amber .d2-kpi--bar { background: var(--d-amber); }
.d2-kpi--rose  .d2-kpi--bar { background: var(--d-rose); }
.d2-kpi__label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--d-muted); margin-bottom: 4px; }
.d2-kpi__value { font-size: 1.8rem; font-weight: 800; color: var(--d-text); line-height: 1; }

/* ---- Card ---- */
.d2-card {
    background: var(--d-card); border: 1px solid var(--d-brd);
    border-radius: var(--d-radius); overflow: hidden; margin-bottom: 20px;
    transition: box-shadow .2s;
}
.d2-card:hover { box-shadow: var(--d-shadow); }
.d2-card__hdr {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px; border-bottom: 1px solid var(--d-brd);
}
.d2-card__title {
    display: flex; align-items: center; gap: 8px;
    font-size: .73rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: var(--d-text); margin: 0;
}
.d2-card__tag {
    font-size: .65rem; font-weight: 700; color: var(--d-muted);
    background: rgba(100,116,139,.15); border: 1px solid var(--d-brd);
    border-radius: 100px; padding: 3px 10px;
}
.d2-card__body { padding: 18px; }
.d2-card__ftr  { padding: 12px 18px; border-top: 1px solid var(--d-brd); background: rgba(15,23,42,.4); }

/* ---- Table ---- */
.d2-table { width: 100%; border-collapse: collapse; }
.d2-table thead th {
    background: var(--d-navy); color: var(--d-muted);
    font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
    padding: 10px 14px; text-align: left; border-bottom: 1px solid var(--d-brd);
}
.d2-table tbody tr { border-bottom: 1px solid var(--d-brd); transition: background .15s; }
.d2-table tbody tr:hover { background: rgba(255,255,255,.03); }
.d2-table tbody td { padding: 11px 14px; font-size: .82rem; color: var(--d-text); vertical-align: middle; }
.d2-table tbody tr:last-child { border-bottom: none; }

/* ---- Avatar ---- */
.d2-av {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .8rem; flex-shrink: 0;
}
.d2-av--sky   { background: rgba(14,165,233,.15); color: var(--d-brand); }
.d2-av--green { background: rgba(16,185,129,.12); color: var(--d-green); }
.d2-av--amber { background: rgba(245,158,11,.12); color: var(--d-amber); }
.d2-av--rose  { background: rgba(244,63,94,.12);  color: var(--d-rose); }

/* ---- Badge ---- */
.d2-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .64rem; font-weight: 700; padding: 3px 9px; border-radius: 100px;
}
.d2-badge--sky    { background: rgba(14,165,233,.12); color: var(--d-brand); }
.d2-badge--green  { background: rgba(16,185,129,.10); color: var(--d-green); }
.d2-badge--amber  { background: rgba(245,158,11,.10); color: var(--d-amber); }
.d2-badge--rose   { background: rgba(244,63,94,.10);  color: var(--d-rose); }
.d2-badge--violet { background: rgba(139,92,246,.10); color: var(--d-violet); }
.d2-badge--slate  { background: rgba(100,116,139,.12);color: var(--d-slate); }
.d2-badge--success{ background: rgba(16,185,129,.10); color: var(--d-green); }
.d2-badge--warning{ background: rgba(245,158,11,.10); color: var(--d-amber); }
.d2-badge--danger { background: rgba(244,63,94,.10);  color: var(--d-rose); }
.d2-badge--info   { background: rgba(14,165,233,.12); color: var(--d-brand); }

/* ---- Score ---- */
.d2-score {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 38px; padding: 4px 10px; border-radius: 8px;
    font-size: .9rem; font-weight: 800;
}
.d2-score--green { background: rgba(16,185,129,.12); color: var(--d-green); }
.d2-score--amber { background: rgba(245,158,11,.12); color: var(--d-amber); }
.d2-score--rose  { background: rgba(244,63,94,.12);  color: var(--d-rose); }

/* ---- Btn ---- */
.d2-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 8px; font-size: .79rem; font-weight: 700;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .2s; font-family: inherit;
}
.d2-btn--sky    { background: var(--d-brand); color: #fff; }
.d2-btn--sky:hover { background: var(--d-brand-d); box-shadow: 0 4px 14px rgba(14,165,233,.4); color: #fff; }
.d2-btn--green  { background: var(--d-green); color: #fff; }
.d2-btn--green:hover { box-shadow: 0 4px 14px rgba(16,185,129,.4); color: #fff; }
.d2-btn--rose   { background: var(--d-rose); color: #fff; }
.d2-btn--rose:hover  { box-shadow: 0 4px 14px rgba(244,63,94,.4); color: #fff; }
.d2-btn--amber  { background: var(--d-amber); color: #fff; }
.d2-btn--amber:hover { box-shadow: 0 4px 14px rgba(245,158,11,.4); color: #fff; }
.d2-btn--outline {
    background: transparent; color: var(--d-brand);
    border: 1.5px solid rgba(14,165,233,.4);
}
.d2-btn--outline:hover { background: var(--d-brand-bg); }
.d2-btn--ghost  {
    background: rgba(100,116,139,.1); color: var(--d-muted);
    border: 1px solid var(--d-brd);
}
.d2-btn--ghost:hover { background: rgba(100,116,139,.18); }
.d2-btn--block  { width: 100%; justify-content: center; }

/* ---- Info pill ---- */
.d2-info-pill {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    background: rgba(14,165,233,.07); border: 1px solid rgba(14,165,233,.2);
    font-size: .8rem; color: var(--d-muted); margin-bottom: 16px;
}
.d2-info-pill i { color: var(--d-brand); }

/* ---- Error / Empty state ---- */
.d2-state-card {
    background: var(--d-card); border: 1px solid var(--d-brd);
    border-radius: 16px; padding: 48px 32px; text-align: center; max-width: 560px; margin: 40px auto;
}
.d2-state-card__orb {
    width: 100px; height: 100px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.8rem; margin: 0 auto 24px;
}
.d2-state-card__orb--amber { background: rgba(245,158,11,.12); }
.d2-state-card__orb--rose  { background: rgba(244,63,94,.12); }
.d2-state-card__title { font-size: 1.25rem; font-weight: 800; color: var(--d-text); margin: 0 0 10px; }
.d2-state-card__sub   { font-size: .88rem; color: var(--d-muted); margin: 0 0 28px; line-height: 1.6; }
.d2-steps { list-style: none; padding: 0; margin: 0 0 28px; text-align: left; }
.d2-steps li {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid var(--d-brd); font-size: .85rem; color: var(--d-muted);
}
.d2-steps li:last-child { border-bottom: none; }
.d2-steps__num {
    width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 800;
}
.d2-steps__num--amber { background: rgba(245,158,11,.15); color: var(--d-amber); }
.d2-steps__num--rose  { background: rgba(244,63,94,.15);  color: var(--d-rose); }
.d2-user-pill {
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
    padding: 12px 16px; border-radius: 10px;
    background: rgba(15,23,42,.6); border: 1px solid var(--d-brd);
    font-size: .78rem; color: var(--d-muted); justify-content: center;
}

/* ---- Metric row (show) ---- */
.d2-metrics { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px; }
@media(max-width:700px){ .d2-metrics{ grid-template-columns: repeat(2,1fr); } }
.d2-metric {
    background: rgba(15,23,42,.6); border: 1px solid var(--d-brd);
    border-radius: 10px; padding: 14px 12px; text-align: center;
}
.d2-metric__bar { height: 3px; border-radius: 2px; margin-bottom: 10px; }
.d2-metric__val { font-size: 1.5rem; font-weight: 800; color: var(--d-text); line-height: 1; margin-bottom: 4px; }
.d2-metric__lbl { font-size: .65rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--d-muted); }

/* ---- Hero estudiante ---- */
.d2-hero-student {
    display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    padding: 18px; background: rgba(15,23,42,.5); border-radius: 12px;
    border: 1px solid var(--d-brd); margin-bottom: 18px;
}
.d2-hero-student__av {
    width: 56px; height: 56px; border-radius: 14px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 800;
}
.d2-hero-student__name { font-size: 1.05rem; font-weight: 800; color: var(--d-text); margin: 0 0 6px; }
.d2-hero-student__tags { display: flex; gap: 6px; flex-wrap: wrap; }

/* ---- Filtros ---- */
.d2-filters {
    background: var(--d-card); border: 1px solid var(--d-brd);
    border-radius: var(--d-radius); padding: 16px 18px; margin-bottom: 20px;
}
.d2-filters .form-control, .d2-filters .custom-select {
    background: rgba(15,23,42,.7) !important;
    border: 1px solid var(--d-brd) !important;
    color: var(--d-text) !important; border-radius: 8px !important;
}
.d2-filters .btn-primary   { background: var(--d-brand) !important; border-color: var(--d-brand) !important; }
.d2-filters .btn-secondary { background: rgba(100,116,139,.2) !important; border-color: var(--d-brd) !important; color: var(--d-muted) !important; }

/* ---- Modal overrides ---- */
.d2-modal .modal-content {
    background: var(--d-card) !important; border: 1px solid var(--d-brd) !important; color: var(--d-text) !important;
}
.d2-modal .modal-header { border-bottom: 1px solid var(--d-brd) !important; }
.d2-modal .modal-footer { border-top: 1px solid var(--d-brd) !important; }
.d2-modal .form-control, .d2-modal select, .d2-modal textarea {
    background: rgba(15,23,42,.7) !important; border: 1px solid var(--d-brd) !important;
    color: var(--d-text) !important; border-radius: 8px !important;
}
.d2-modal label { color: var(--d-muted) !important; font-size: .8rem !important; font-weight: 600 !important; }
.d2-modal .alert-info { background: rgba(14,165,233,.08) !important; border-color: rgba(14,165,233,.2) !important; color: var(--d-brand) !important; }

@keyframes d2fadeUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.d2-card, .d2-kpi, .d2-state-card { animation: d2fadeUp .35s cubic-bezier(.22,1,.36,1) both; }
</style>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
    $('#reportesTable').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'},responsive:true,autoWidth:false,order:[[0,'asc']]});
    @if(session('mensaje')) Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',timer:2500,showConfirmButton:false}); @endif
    @if($errors->has('estudiante_id_create')||$errors->has('tipo_create')) $('#createReporteModal').modal('show'); @endif
});
</script>
@endsection