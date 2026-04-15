@extends('layouts.docente')

@section('title', 'Mis Alumnos')
@section('page_title')Mis <span>Alumnos</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    {{-- Header con total a la derecha --}}
    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-user-graduate"></i> Lista de Estudiantes</h2>
            <p class="d-page-hdr__sub">Todos los alumnos asignados a tus cursos</p>
        </div>
        <span class="d-badge d-badge--sky" style="font-size:.85rem;padding:8px 18px">
            Total: {{ $estudiantes->count() }} estudiantes
        </span>
    </div>

    {{-- Filtros compactos con botón limpiar pequeño --}}
    <div class="d-filters-card" style="margin:16px 0;background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:16px 20px">
        <form method="GET" action="{{ route('docente.mis-alumnos') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
            <div style="flex:2;min-width:180px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Buscar</label>
                <div style="display:flex;align-items:center;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:4px 8px">
                    <i class="fas fa-search text-muted" style="margin-right:8px;font-size:.75rem"></i>
                    <input type="text" name="search" style="border:none;background:transparent;padding:6px 0;color:var(--text);width:100%;font-size:.8rem" placeholder="Nombre, apellido o DNI..." value="{{ request('search') }}">
                </div>
            </div>
            <div style="flex:1.5;min-width:140px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Curso</label>
                <select name="curso_id" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">Todos los cursos</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1.5;min-width:140px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Grado</label>
                <select name="grado_id" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">Todos los grados</option>
                    @foreach($grados as $grado)
                        <option value="{{ $grado->id }}" {{ request('grado_id') == $grado->id ? 'selected' : '' }}>{{ $grado->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;cursor:pointer;font-size:.75rem">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <a href="{{ route('docente.mis-alumnos') }}" class="d-btn" style="background:var(--surface2);border:1px solid var(--border);border-radius:30px;padding:5px 12px;color:var(--text);font-weight:500;font-size:.7rem;text-decoration:none">
                    <i class="fas fa-eraser"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    @if($estudiantes->count() > 0)
    <div class="d-card">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-users"></i></span>Alumnos Asignados</div>
        </div>
        <div style="padding:0;overflow-x:auto">
            <table id="tablaAlumnos" class="d-table">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Código</th>
                        <th>Apellidos y Nombres</th>
                        <th>Grado</th>
                        <th>Curso(s)</th>
                        <th>Condición</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $c = 1; @endphp
                    @foreach($estudiantes as $e)
                    @php
                        $cursosDelEstudiante = $e->matriculas->where('estado', 'Matriculado')->pluck('curso.nombre')->filter()->implode(', ');
                    @endphp
                    <tr>
                        <td style="text-align:center;color:var(--muted);font-size:.75rem">{{ $c++ }}</td>
                        <td><span class="d-mono">{{ $e->codigo_estudiante ?? '-' }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div class="d-av">{{ strtoupper(substr($e->persona->apellidos ?? 'A', 0, 1)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:.83rem;color:var(--text)">
                                        @if($e->persona) {{ $e->persona->apellidos }} {{ $e->persona->nombres }} @else N/A @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $e->grado->nombre ?? 'N/A' }}</td>
                        <td>{{ $cursosDelEstudiante ?: '—' }}</td>
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
        <strong>Sin alumnos asignados</strong>
        <span>No tienes estudiantes asignados actualmente.</span>
    </div>
    @endif
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
/* Mismos estilos que en la vista anterior, más ajustes de filtros */
.d-filters-card .d-btn {
    transition: all .2s;
}
.d-filters-card .d-btn:hover {
    transform: translateY(-1px);
}
/* El resto de estilos son los mismos que en la vista anterior, se heredan */
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
</style>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    $("#tablaAlumnos").DataTable({
        pageLength:10, responsive:true, autoWidth:false,
        language:{url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'}
    });
});
function enviarMensaje(id){window.location.href='{{ route("docente.mensajeria") }}?estudiante_id='+id;}
</script>
@endsection