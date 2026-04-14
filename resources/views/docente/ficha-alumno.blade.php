@extends('layouts.docente')

@section('title', 'Ficha del Alumno')
@section('page_title')Ficha <span>Alumno</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div style="margin-bottom:16px">
        <a href="{{ route('docente.mis-alumnos') }}" class="d-btn-back"><i class="fas fa-arrow-left"></i> Volver a Mis Alumnos</a>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

        {{-- Columna izquierda --}}
        <div style="display:flex;flex-direction:column;gap:18px">

            {{-- Info Personal --}}
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-user"></i></span>Información Personal</div>
                </div>
                <div style="padding:20px">
                    <div class="d-info-grid">
                        <div class="d-info-item"><span class="d-info-lbl">DNI</span><span class="d-info-val">{{ $estudiante->persona->dni ?? 'N/A' }}</span></div>
                        <div class="d-info-item"><span class="d-info-lbl">Nombres</span><span class="d-info-val">{{ $estudiante->persona->nombres ?? 'N/A' }}</span></div>
                        <div class="d-info-item"><span class="d-info-lbl">Apellidos</span><span class="d-info-val">{{ $estudiante->persona->apellidos ?? 'N/A' }}</span></div>
                        <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $estudiante->codigo_estudiante ?? 'N/A' }}</span></div>
                        <div class="d-info-item"><span class="d-info-lbl">Grado</span><span class="d-info-val"><span class="d-badge d-badge--sky">{{ $estudiante->grado->nombre ?? 'N/A' }}</span></span></div>
                        <div class="d-info-item"><span class="d-info-lbl">Nivel</span><span class="d-info-val">{{ $estudiante->grado->nivel->nombre ?? 'N/A' }}</span></div>
                        <div class="d-info-item"><span class="d-info-lbl">F. Nacimiento</span>
                            <span class="d-info-val">@if($estudiante->persona && $estudiante->persona->fecha_nacimiento){{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}@else N/A @endif</span>
                        </div>
                        <div class="d-info-item"><span class="d-info-lbl">Género</span>
                            <span class="d-info-val">@if($estudiante->persona)@if($estudiante->persona->genero=='M')Masculino @elseif($estudiante->persona->genero=='F')Femenino @else Otro @endif @else N/A @endif</span>
                        </div>
                        <div class="d-info-item"><span class="d-info-lbl">Año Ingreso</span><span class="d-info-val">{{ $estudiante->año_ingreso ?? 'N/A' }}</span></div>
                        <div class="d-info-item"><span class="d-info-lbl">Condición</span>
                            <span class="d-info-val"><span class="d-badge d-badge--{{ $estudiante->condicion=='Regular'?'green':'amber' }}">{{ $estudiante->condicion ?? 'N/A' }}</span></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Asistencia --}}
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title"><span class="d-card__ico d-card__ico--amber"><i class="fas fa-calendar-check"></i></span>Resumen de Asistencia</div>
                    <span class="d-badge d-badge--{{ $porcentajeAsistencia>=75?'green':'rose' }}" style="font-size:.85rem;padding:6px 14px">{{ $porcentajeAsistencia }}%</span>
                </div>
                <div style="padding:16px 20px">
                    <div style="height:8px;background:var(--surface2);border-radius:100px;margin-bottom:18px">
                        <div style="height:100%;border-radius:100px;background:{{ $porcentajeAsistencia>=75?'var(--green)':'var(--rose)' }};width:{{ $porcentajeAsistencia }}%;transition:width 1s"></div>
                    </div>
                    @if($asistencias->count() > 0)
                    <div style="overflow-x:auto;margin-top:8px">
                        <table class="d-table">
                            <thead><tr><th>Fecha</th><th>Curso</th><th>Estado</th></tr></thead>
                            <tbody>
                                @foreach($asistencias as $a)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ $a->curso->nombre ?? 'N/A' }}</td>
                                    <td>
                                        @if($a->estado=='Presente')<span class="d-badge d-badge--green">Presente</span>
                                        @elseif($a->estado=='Falta')<span class="d-badge d-badge--rose">Falta</span>
                                        @elseif($a->estado=='Tardanza')<span class="d-badge d-badge--amber">Tardanza</span>
                                        @else<span class="d-badge d-badge--slate">{{ $a->estado }}</span>@endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Comportamiento --}}
            @if($comportamientos->count() > 0)
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title"><span class="d-card__ico d-card__ico--amber"><i class="fas fa-exclamation-circle"></i></span>Observaciones de Comportamiento</div>
                    <span class="d-card__tag">{{ $comportamientos->count() }}</span>
                </div>
                <div style="padding:16px 20px;display:flex;flex-direction:column;gap:10px">
                    @foreach($comportamientos as $c)
                    <div style="padding:12px 14px;border-radius:10px;border:1px solid var(--border);background:var(--surface2)">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                            <span class="d-badge d-badge--{{ $c->tipo=='Positivo'?'green':'amber' }}">{{ $c->tipo }}</span>
                            <span style="font-size:.72rem;color:var(--muted)">{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</span>
                        </div>
                        <p style="font-size:.82rem;color:var(--text);margin:0">{{ $c->descripcion }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Notas --}}
            @if(isset($notas) && $notas->count() > 0)
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title"><span class="d-card__ico d-card__ico--rose"><i class="fas fa-star"></i></span>Notas</div>
                </div>
                <div style="overflow-x:auto">
                    <table class="d-table">
                        <thead><tr><th>Curso</th><th>Periodo</th><th style="text-align:center">Nota</th></tr></thead>
                        <tbody>
                            @foreach($notas as $n)
                            <tr>
                                <td>{{ $n->curso->nombre ?? 'N/A' }}</td>
                                <td>{{ $n->periodo->nombre ?? 'N/A' }}</td>
                                <td style="text-align:center">
                                    <span class="d-score d-score--{{ $n->nota>=14?'green':($n->nota>=11?'sky':'rose') }}">{{ $n->nota }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- Columna derecha --}}
        <div style="display:flex;flex-direction:column;gap:18px">
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title"><span class="d-card__ico d-card__ico--amber"><i class="fas fa-users"></i></span>Tutores / Apoderados</div>
                </div>
                <div style="padding:16px">
                    @if($tutores->count() > 0)
                        @foreach($tutores as $t)
                        <div style="padding:14px;border:1px solid var(--border);border-radius:10px;margin-bottom:10px;background:var(--surface2)">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                                <div class="d-av">{{ strtoupper(substr($t->nombres,0,1)) }}</div>
                                <div>
                                    <div style="font-size:.83rem;font-weight:700;color:var(--text)">{{ $t->nombres }} {{ $t->apellidos }}</div>
                                    <span class="d-badge d-badge--{{ $t->tipo=='Principal'?'sky':'slate' }}" style="font-size:.62rem">{{ $t->tipo }}</span>
                                </div>
                            </div>
                            <div style="font-size:.77rem;color:var(--muted);line-height:1.8">
                                <div><strong>Relación:</strong> {{ $t->relacion_familiar }}</div>
                                <div><strong>Teléfono:</strong> {{ $t->telefono ?? 'N/A' }}</div>
                                @if($t->telefono_emergencia)<div><strong>Emergencia:</strong> {{ $t->telefono_emergencia }}</div>@endif
                            </div>
                            @if($t->user_id)
                            <a href="{{ route('docente.mensajeria') }}?destinatario_user_id={{ $t->user_id }}&estudiante_id={{ $estudiante->id }}" class="d-btn d-btn--sky" style="margin-top:10px;width:100%;justify-content:center">
                                <i class="fas fa-envelope"></i> Enviar Mensaje
                            </a>
                            @endif
                        </div>
                        @endforeach
                    @else
                    <div class="d-empty-state" style="padding:24px"><i class="fas fa-users"></i><span>No hay tutores registrados</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.d-btn-back{display:inline-flex;align-items:center;gap:6px;font-size:.79rem;font-weight:600;color:var(--muted);text-decoration:none;transition:color .2s}
.d-btn-back:hover{color:var(--brand)}
.d-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.d-info-item{display:flex;flex-direction:column;gap:2px}
.d-info-lbl{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted)}
.d-info-val{font-size:.84rem;font-weight:600;color:var(--text)}
.d-mono{font-family:monospace;font-size:.8rem}
.d-av{width:34px;height:34px;border-radius:9px;background:rgba(14,165,233,.12);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:10px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:10px 14px;color:var(--text)}
.d-score{display:inline-flex;align-items:center;justify-content:center;min-width:36px;padding:4px 10px;border-radius:7px;font-size:.88rem;font-weight:800}
.d-score--green{background:rgba(16,185,129,.1);color:var(--green)}
.d-score--sky{background:rgba(14,165,233,.1);color:var(--brand)}
.d-score--rose{background:rgba(244,63,94,.1);color:var(--rose)}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--sky{background:var(--brand);color:#fff}.d-btn--sky:hover{background:var(--brand-d);color:#fff}
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-empty-state{display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--muted);text-align:center}
.d-empty-state i{font-size:1.5rem;opacity:.2}
.d-empty-state span{font-size:.78rem}
@media(max-width:900px){div[style*="grid-template-columns:2fr 1fr"]{grid-template-columns:1fr!important}}
</style>
@endsection

