@extends('layouts.docente')

@section('title', 'Estudiantes del Curso')
@section('page_title')Estudiantes del <span>Curso</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    {{-- Header --}}
    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-users"></i> Estudiantes del Curso</h2>
            <p class="d-page-hdr__sub">Lista de estudiantes matriculados en este curso</p>
        </div>
        <span class="d-badge d-badge--sky" style="font-size:.85rem;padding:8px 18px">
            Total: {{ $estudiantes->count() }} estudiantes
        </span>
    </div>

    {{-- Botones rápidos del curso (con estilos del layout) --}}
    <div class="row" style="margin:16px 0 24px">
        <div class="col-md-6 mb-2 mb-md-0">
            <a href="{{ route('docente.asistencias.index', ['curso_id' => $curso->id]) }}" 
               class="d-btn d-btn--sky d-btn--block" style="background:var(--brand);color:#fff;border-radius:12px;padding:10px;font-weight:600;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px">
                <i class="fas fa-clipboard-check"></i> Registrar Asistencias (Curso)
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('docente.notas.index', ['curso_id' => $curso->id]) }}" 
               class="d-btn d-btn--green d-btn--block" style="background:var(--green);color:#fff;border-radius:12px;padding:10px;font-weight:600;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px">
                <i class="fas fa-star"></i> Registrar Notas (Curso)
            </a>
        </div>
    </div>

    @if($estudiantes->count() > 0)
    <div class="d-card" style="margin-top:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-users"></i></span>Listado de Alumnos</div>
        </div>
        <div style="padding:0;overflow-x:auto">
            <table id="tablaEstudiantesCurso" class="d-table">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Código</th>
                        <th>Apellidos y Nombres</th>
                        <th>DNI</th>
                        <th>Grado</th>
                        <th>Condición</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $c = 1; @endphp
                    @foreach($estudiantes as $e)
                    <tr>
                        <td style="text-align:center;color:var(--muted);font-size:.75rem">{{ $c++ }}</td>
                        <td><span class="d-mono">{{ $e->codigo_estudiante ?? '-' }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="d-av">{{ strtoupper(substr($e->persona->apellidos ?? 'A', 0, 1)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:.83rem;color:var(--text)">
                                        {{ $e->persona->apellidos }} {{ $e->persona->nombres }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="d-mono">{{ $e->persona->dni }}</td>
                        <td>{{ $e->grado->nombre ?? 'N/A' }}</td>
                        <td>
                            @if($e->condicion == 'Regular')
                                <span class="d-badge d-badge--green">Regular</span>
                            @else
                                <span class="d-badge d-badge--amber">{{ $e->condicion }}</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a href="{{ route('docente.estudiantes.show', $e->id) }}" class="d-btn-icon d-btn-icon--sky" title="Ver Ficha"><i class="fas fa-id-card"></i></a>
                                <button class="d-btn-icon d-btn-icon--green" onclick="enviarMensaje({{ $e->id }})" title="Enviar Mensaje"><i class="fas fa-envelope"></i></button>
                            </div>
                         </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="d-empty-state">
        <i class="fas fa-user-graduate"></i>
        <strong>Sin estudiantes en este curso</strong>
        <span>No hay estudiantes matriculados en este curso.</span>
    </div>
    @endif
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
/* Ajustes para que DataTables combine con tu diseño */
.d-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .82rem;
}
.d-table thead tr {
    border-bottom: 2px solid var(--border);
}
.d-table thead th {
    padding: 11px 14px;
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--muted);
    white-space: nowrap;
}
.d-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .15s;
}
.d-table tbody tr:last-child {
    border-bottom: none;
}
.d-table tbody tr:hover {
    background: var(--surface2);
}
.d-table tbody td {
    padding: 11px 14px;
    color: var(--text);
}
.d-av {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: rgba(14,165,233,.12);
    color: var(--brand);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: .8rem;
    flex-shrink: 0;
}
.d-mono {
    font-family: monospace;
    font-size: .79rem;
    color: var(--muted);
}
.d-btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .78rem;
    border: none;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
}
.d-btn-icon--sky {
    background: rgba(14,165,233,.1);
    color: var(--brand);
}
.d-btn-icon--sky:hover {
    background: var(--brand);
    color: #fff;
}
.d-btn-icon--green {
    background: rgba(16,185,129,.1);
    color: var(--green);
}
.d-btn-icon--green:hover {
    background: var(--green);
    color: #fff;
}
.d-badge--sky {
    background: rgba(14,165,233,.1);
    color: var(--brand);
}
.d-badge--green {
    background: rgba(16,185,129,.1);
    color: var(--green);
}
.d-badge--amber {
    background: rgba(245,158,11,.1);
    color: var(--amber);
}
.d-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 60px 20px;
    color: var(--muted);
    text-align: center;
}
.d-empty-state i {
    font-size: 2.5rem;
    opacity: .25;
}
.d-empty-state strong {
    font-size: .95rem;
    color: var(--text);
}
.d-empty-state span {
    font-size: .8rem;
}
/* Botones personalizados */
.d-btn--sky {
    background: var(--brand);
    color: #fff;
    transition: all .2s;
}
.d-btn--sky:hover {
    background: var(--brand-d);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(14,165,233,.3);
}
.d-btn--green {
    background: var(--green);
    color: #fff;
    transition: all .2s;
}
.d-btn--green:hover {
    background: #0b9e6e;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16,185,129,.3);
}
.d-btn--block {
    display: flex;
    width: 100%;
}
</style>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    $("#tablaEstudiantesCurso").DataTable({
        pageLength:10, responsive:true, autoWidth:false,
        language:{url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'}
    });
});
function enviarMensaje(id){window.location.href='{{ route("docente.mensajeria") }}?estudiante_id='+id;}
</script>
@endsection