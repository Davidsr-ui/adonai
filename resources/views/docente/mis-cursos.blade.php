@extends('layouts.docente')

@section('title', 'Mis Cursos')
@section('page_title')Mis <span>Cursos</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-book"></i> Cursos Asignados</h2>
            <p class="d-page-hdr__sub">Todos tus cursos activos en la gestión actual</p>
        </div>
    </div>

    <div class="d-card" style="margin-top:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-book"></i></span>Listado de Mis Cursos</div>
        </div>
        <div style="padding:0">
            @if(Auth::user()->persona && Auth::user()->persona->docente)
                @php
                    $docente = Auth::user()->persona->docente;
                    $asignaciones = \App\Models\DocenteCurso::where('docente_id', $docente->id)
                        ->with(['curso', 'grado', 'gestion'])->get();
                @endphp

                @if($asignaciones->count() > 0)
                <div style="overflow-x:auto">
                    <table class="d-table">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Curso</th>
                                <th>Grado</th>
                                <th>Gestión</th>
                                <th style="text-align:center">Tutor</th>
                                <th style="text-align:center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaciones as $i => $a)
                            <tr>
                                <td style="color:var(--muted);font-size:.75rem;text-align:center">{{ $i+1 }}</td>
                                <td>
                                    <div style="font-weight:700;color:var(--text);font-size:.85rem">{{ $a->curso->nombre }}</div>
                                    @if($a->curso->descripcion)<div style="font-size:.72rem;color:var(--muted);margin-top:2px">{{ $a->curso->descripcion }}</div>@endif
                                </td>
                                <td><span class="d-badge d-badge--sky">{{ $a->grado->nombre_completo }}</span></td>
                                <td><span class="d-badge d-badge--slate">{{ $a->gestion->nombre }}</span></td>
                                <td style="text-align:center">
                                    @if($a->es_tutor_aula)
                                        <span class="d-badge d-badge--green"><i class="fas fa-star"></i> Sí</span>
                                    @else
                                        <span style="color:var(--muted);font-size:.78rem">—</span>
                                    @endif
                                 </div>
                                <td style="text-align:center">
                                    <div style="display:flex;gap:6px;justify-content:center">
                                        <a href="{{ route('docente.estudiantes.index', ['curso_id'=>$a->curso_id,'grado_id'=>$a->grado_id]) }}" class="d-btn-icon d-btn-icon--sky" title="Estudiantes"><i class="fas fa-users"></i></a>
                                        <a href="{{ route('docente.asistencias.index', ['curso_id'=>$a->curso_id]) }}" class="d-btn-icon d-btn-icon--amber" title="Asistencias"><i class="fas fa-clipboard-check"></i></a>
                                        <a href="{{ route('docente.notas.index', ['curso_id'=>$a->curso_id]) }}" class="d-btn-icon d-btn-icon--green" title="Notas"><i class="fas fa-star"></i></a>
                                    </div>
                                 </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="d-empty-state" style="padding:48px">
                    <i class="fas fa-book-open"></i>
                    <strong>Sin cursos asignados</strong>
                    <span>No tienes cursos asignados en este momento.</span>
                </div>
                @endif
            @else
            <div style="padding:24px">
                <div class="d-warn-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div><strong>Perfil incompleto</strong><p>Tu perfil de docente no está completo. Contacta al administrador.</p></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.d-page-hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:24px 0 8px}
.d-page-hdr__title{font-size:1.1rem;font-weight:800;color:var(--text);margin:0;display:flex;align-items:center;gap:8px}
.d-page-hdr__title i{color:var(--brand)}
.d-page-hdr__sub{font-size:.78rem;color:var(--muted);margin:4px 0 0}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:11px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:11px 14px;color:var(--text)}
.d-btn-icon{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
.d-btn-icon--sky{background:rgba(14,165,233,.1);color:var(--brand)}.d-btn-icon--sky:hover{background:var(--brand);color:#fff}
.d-btn-icon--amber{background:rgba(245,158,11,.1);color:var(--amber)}.d-btn-icon--amber:hover{background:var(--amber);color:#fff}
.d-btn-icon--green{background:rgba(16,185,129,.1);color:var(--green)}.d-btn-icon--green:hover{background:var(--green);color:#fff}
.d-badge--sky{background:rgba(14,165,233,.1);color:var(--brand)}
.d-badge--green{background:rgba(16,185,129,.1);color:var(--green)}
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-warn-box{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:var(--amber-bg);border:1px solid rgba(245,158,11,.25);border-radius:var(--radius);color:var(--amber)}
.d-warn-box p{font-size:.8rem;margin:0;opacity:.85}
.d-empty-state{display:flex;flex-direction:column;align-items:center;gap:10px;padding:60px 20px;color:var(--muted);text-align:center}
.d-empty-state i{font-size:2.5rem;opacity:.25}
.d-empty-state strong{font-size:.95rem;color:var(--text)}
.d-empty-state span{font-size:.8rem}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
    @if(session('mensaje'))
    Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});
    @endif
});
</script>
@endsection