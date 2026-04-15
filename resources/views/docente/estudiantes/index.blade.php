@extends('layouts.docente')

@section('title', 'Mis Estudiantes')
@section('page_title')Mis <span>Estudiantes</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-users"></i> Listado de Estudiantes</h2>
            <p class="d-page-hdr__sub">Todos los estudiantes de tus cursos asignados</p>
        </div>
    </div>

    @if(Auth::user()->persona && Auth::user()->persona->docente)
        @php
            $docente = Auth::user()->persona->docente;
            $asignaciones = \App\Models\DocenteCurso::where('docente_id',$docente->id)->with(['curso','grado'])->get();
            $cursosIds = $asignaciones->pluck('curso_id')->unique();
            $matriculas = \App\Models\Matricula::whereIn('curso_id',$cursosIds)->where('estado','Matriculado')->with(['estudiante.persona','estudiante.grado','curso','grado'])->get();
            $estudiantes = collect();
            foreach($matriculas as $m){
                if($m->estudiante && $m->estudiante->persona){
                    $estudiantes->push(['estudiante'=>$m->estudiante,'curso'=>$m->curso,'grado'=>$m->grado??$m->estudiante->grado,'matricula'=>$m]);
                }
            }
            $estudiantes = $estudiantes->unique(fn($i)=>$i['estudiante']->id);
        @endphp

        @if($estudiantes->count() > 0)
        {{-- Stats --}}
        <div class="d-kpi-grid4" style="margin:16px 0">
            <div class="d-stat-card d-stat-card--sky"><div class="d-stat-card__ico"><i class="fas fa-user-graduate"></i></div><div><div class="d-stat-card__val">{{ $estudiantes->count() }}</div><div class="d-stat-card__lbl">Estudiantes</div></div></div>
            <div class="d-stat-card d-stat-card--green"><div class="d-stat-card__ico"><i class="fas fa-chalkboard-teacher"></i></div><div><div class="d-stat-card__val">{{ $asignaciones->count() }}</div><div class="d-stat-card__lbl">Asignaciones</div></div></div>
            <div class="d-stat-card d-stat-card--amber"><div class="d-stat-card__ico"><i class="fas fa-book"></i></div><div><div class="d-stat-card__val">{{ $cursosIds->count() }}</div><div class="d-stat-card__lbl">Cursos</div></div></div>
            <div class="d-stat-card d-stat-card--rose"><div class="d-stat-card__ico"><i class="fas fa-layer-group"></i></div><div><div class="d-stat-card__val">{{ $estudiantes->pluck('estudiante.grado_id')->unique()->count() }}</div><div class="d-stat-card__lbl">Grados</div></div></div>
        </div>

        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-table"></i></span>Tabla de Estudiantes</div>
            </div>
            <div style="overflow-x:auto">
                <table id="tablaEstudiantes" class="d-table">
                    <thead><tr><th>#</th><th>Código</th><th>Estudiante</th><th>DNI</th><th>Curso</th><th>Grado</th><th>Estado</th><th style="text-align:center">Acc.</th></tr></thead>
                    <tbody>
                        @foreach($estudiantes as $i => $item)
                        <tr>
                            <td style="color:var(--muted);font-size:.75rem">{{ $i+1 }}</td>
                            <td class="d-mono">{{ $item['estudiante']->codigo_estudiante }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px">
                                    <div class="d-av">{{ strtoupper(substr($item['estudiante']->persona->apellidos,0,1)) }}</div>
                                    <div>
                                        <div style="font-weight:600;font-size:.83rem;color:var(--text)">{{ $item['estudiante']->persona->apellidos }}, {{ $item['estudiante']->persona->nombres }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-mono">{{ $item['estudiante']->persona->dni }}</td>
                            <td><span class="d-badge d-badge--sky">{{ $item['curso']->nombre }}</span></td>
                            <td><span class="d-badge d-badge--slate">{{ $item['grado']->nombre_completo ?? '—' }}</span></td>
                            <td><span class="d-badge d-badge--{{ $item['estudiante']->persona->estado=='Activo'?'green':'rose' }}">{{ $item['estudiante']->persona->estado }}</span></td>
                            <td style="text-align:center">
                                <div style="display:flex;gap:5px;justify-content:center">
                                    <a href="{{ route('docente.asistencias.index',['estudiante_id'=>$item['estudiante']->id]) }}" class="d-btn-icon d-btn-icon--amber" title="Asistencias"><i class="fas fa-clipboard-check"></i></a>
                                    <a href="{{ route('docente.notas.index',['estudiante_id'=>$item['estudiante']->id]) }}" class="d-btn-icon d-btn-icon--green" title="Notas"><i class="fas fa-star"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Agrupados por curso --}}
        <div class="d-card" style="margin-top:20px">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--green"><i class="fas fa-layer-group"></i></span>Agrupados por Curso</div>
            </div>
            <div style="padding:18px;display:flex;flex-direction:column;gap:14px">
                @php $porCurso = $estudiantes->groupBy('curso.id'); @endphp
                @foreach($porCurso as $cid => $group)
                @php $curso = $group->first()['curso']; @endphp
                <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden">
                    <div style="padding:12px 18px;background:var(--surface2);display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border)">
                        <span style="font-size:.84rem;font-weight:700;color:var(--text)"><i class="fas fa-book" style="color:var(--brand);margin-right:8px"></i>{{ $curso->nombre }}</span>
                        <span class="d-badge d-badge--sky">{{ $group->count() }} estudiantes</span>
                    </div>
                    <div style="padding:14px 18px;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:10px">
                        @foreach($group as $est)
                        <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--surface);border:1px solid var(--border);border-radius:9px">
                            <div class="d-av">{{ strtoupper(substr($est['estudiante']->persona->apellidos,0,1)) }}</div>
                            <div>
                                <div style="font-size:.8rem;font-weight:600;color:var(--text)">{{ $est['estudiante']->persona->apellidos }}, {{ $est['estudiante']->persona->nombres }}</div>
                                <div style="font-size:.7rem;color:var(--muted)">{{ $est['estudiante']->codigo_estudiante }} · {{ $est['grado']->nombre_completo ?? '—' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @else
        <div class="d-empty-state" style="padding:60px 20px">
            <i class="fas fa-user-graduate"></i>
            <strong>Sin estudiantes asignados</strong>
            <span>No se encontraron estudiantes en tus cursos.</span>
            @if(isset($matriculas))
            <div style="margin-top:12px;font-size:.75rem;color:var(--muted)">
                Asignaciones: {{ $asignaciones->count() }} · Cursos IDs: {{ $cursosIds->implode(', ') }} · Matrículas: {{ $matriculas->count() }}
            </div>
            @endif
        </div>
        @endif
    @else
    <div class="d-warn-box" style="margin-top:20px">
        <i class="fas fa-exclamation-triangle"></i>
        <div><strong>Perfil incompleto</strong><p>Tu perfil de docente no está completo. Contacta al administrador.</p></div>
    </div>
    @endif
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
.d-page-hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:24px 0 8px}
.d-page-hdr__title{font-size:1.1rem;font-weight:800;color:var(--text);margin:0;display:flex;align-items:center;gap:8px}
.d-page-hdr__title i{color:var(--brand)}
.d-page-hdr__sub{font-size:.78rem;color:var(--muted);margin:4px 0 0}
.d-kpi-grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
@media(max-width:700px){.d-kpi-grid4{grid-template-columns:repeat(2,1fr)}}
.d-stat-card{display:flex;align-items:center;gap:14px;padding:18px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius)}
.d-stat-card__ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.d-stat-card__val{font-size:1.6rem;font-weight:800;color:var(--text);line-height:1}
.d-stat-card__lbl{font-size:.67rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-top:2px}
.d-stat-card--sky .d-stat-card__ico{background:rgba(14,165,233,.1);color:var(--brand)}
.d-stat-card--green .d-stat-card__ico{background:rgba(16,185,129,.1);color:var(--green)}
.d-stat-card--amber .d-stat-card__ico{background:rgba(245,158,11,.1);color:var(--amber)}
.d-stat-card--rose .d-stat-card__ico{background:rgba(244,63,94,.1);color:var(--rose)}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:11px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:11px 14px;color:var(--text)}
.d-av{width:34px;height:34px;border-radius:9px;background:rgba(14,165,233,.12);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0}
.d-mono{font-family:monospace;font-size:.79rem;color:var(--muted)}
.d-btn-icon{width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
.d-btn-icon--amber{background:rgba(245,158,11,.1);color:var(--amber)}.d-btn-icon--amber:hover{background:var(--amber);color:#fff}
.d-btn-icon--green{background:rgba(16,185,129,.1);color:var(--green)}.d-btn-icon--green:hover{background:var(--green);color:#fff}
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-warn-box{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:var(--amber-bg);border:1px solid rgba(245,158,11,.25);border-radius:var(--radius);color:var(--amber)}
.d-warn-box p{font-size:.8rem;margin:0;opacity:.85}
.d-empty-state{display:flex;flex-direction:column;align-items:center;gap:10px;color:var(--muted);text-align:center}
.d-empty-state i{font-size:2.5rem;opacity:.2}
.d-empty-state strong{font-size:.95rem;color:var(--text)}
.d-empty-state span{font-size:.8rem}
</style>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    $('#tablaEstudiantes').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'},responsive:true,autoWidth:false,order:[[2,'asc']]});
    @if(session('mensaje'))Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});@endif
});
</script>
@endsection

