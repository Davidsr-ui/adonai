@extends('layouts.docente')

@section('title', 'Registrar Asistencias')
@section('page_title')Registrar <span>Asistencias</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    {{-- Header --}}
    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-clipboard-check"></i> Registrar Asistencias</h2>
            <p class="d-page-hdr__sub">Seleccione un curso y una fecha para registrar la asistencia de los estudiantes</p>
        </div>
    </div>

    {{-- Filtros de curso y fecha (estilo mis-alumnos) --}}
    <div class="d-filters-card" style="margin:16px 0;background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:16px 20px">
        <form method="GET" action="{{ route('docente.asistencias.create') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
            <div style="flex:2;min-width:180px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Curso</label>
                <select name="curso_id" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">-- Seleccione un curso --</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1.5;min-width:140px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Fecha</label>
                <input type="date" name="fecha" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem" value="{{ $fecha ?? date('Y-m-d') }}" required>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;cursor:pointer;font-size:.75rem">
                    <i class="fas fa-search"></i> Cargar estudiantes
                </button>
            </div>
        </form>
    </div>

    @if($cursoId && $estudiantes->count() > 0)
    <div class="d-card" style="margin-top:20px">
        <div class="d-card__hdr d-flex justify-content-between align-items-center">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-users"></i></span>Estudiantes del curso</div>
            <div style="display: flex; justify-content: flex-end; width: 100%;">
                <button type="button" class="d-btn d-btn--sky" id="marcarTodosPresente" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;font-size:.75rem">
                    <i class="fas fa-check-double"></i> Marcar todos como presentes
                </button>
            </div>
        </div>
        <form action="{{ route('docente.asistencias.store') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $cursoId }}">
            <input type="hidden" name="fecha" value="{{ $fecha }}">
            <div style="padding:0;overflow-x:auto">
                <table class="d-table">
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Código</th>
                            <th>Apellidos y Nombres</th>
                            <th style="width:150px">Asistencia</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $index => $e)
                        <tr>
                            <td style="text-align:center;color:var(--muted);font-size:.75rem">{{ $index + 1 }}</td>
                            <td class="d-mono">{{ $e->codigo_estudiante ?? '-' }}</td>
                            <td>
                                <div class="d-av" style="display:inline-flex;margin-right:8px">{{ strtoupper(substr($e->persona->apellidos ?? 'A', 0, 1)) }}</div>
                                <span>{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</span>
                            </td>
                            <td>
                                <select name="asistencias[{{ $loop->index }}][estado]" class="d-select" style="background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:6px 10px;color:var(--text);width:auto;min-width:120px" required>
                                    <option value="Presente" {{ old('asistencias.'.$loop->index.'.estado', $e->asistencia_estado ?? 'Presente') == 'Presente' ? 'selected' : '' }}>Presente</option>
                                    <option value="Ausente" {{ old('asistencias.'.$loop->index.'.estado', $e->asistencia_estado ?? '') == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                                    <option value="Tardanza" {{ old('asistencias.'.$loop->index.'.estado', $e->asistencia_estado ?? '') == 'Tardanza' ? 'selected' : '' }}>Tardanza</option>
                                    <option value="Justificado" {{ old('asistencias.'.$loop->index.'.estado', $e->asistencia_estado ?? '') == 'Justificado' ? 'selected' : '' }}>Justificado</option>
                                </select>
                                <input type="hidden" name="asistencias[{{ $loop->index }}][estudiante_id]" value="{{ $e->id }}">
                            </td>
                            <td>
                                <input type="text" name="asistencias[{{ $loop->index }}][observaciones]" class="d-input" style="background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:6px 10px;color:var(--text);width:100%;font-size:.75rem" placeholder="Observación" value="{{ old('asistencias.'.$loop->index.'.observaciones') }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-card__ftr" style="padding:12px 18px;border-top:1px solid var(--border);background:var(--surface2);text-align:right">
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600">
                    <i class="fas fa-save"></i> Guardar todas las asistencias
                </button>
            </div>
        </form>
    </div>
    @elseif($cursoId && $estudiantes->isEmpty())
    <div class="d-empty-state">
        <i class="fas fa-user-graduate"></i>
        <strong>Sin estudiantes en este curso</strong>
        <span>No se encontraron estudiantes matriculados en este curso.</span>
    </div>
    @endif
</div>
@endsection

@section('css')
<style>
    /* Estilos de tabla iguales a mis-alumnos */
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
        display: inline-flex;
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
    .d-select, .d-input {
        transition: all .2s;
    }
    .d-select:focus, .d-input:focus {
        outline: none;
        border-color: var(--brand);
    }
    .d-btn--sky {
        transition: all .2s;
    }
    .d-btn--sky:hover {
        background: var(--brand-d) !important;
        transform: translateY(-1px);
    }
    .d-card__ftr {
        background: var(--surface2);
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
</style>
@endsection

@section('js')
<script>
    document.getElementById('marcarTodosPresente')?.addEventListener('click', function() {
        var selects = document.querySelectorAll('select[name$="[estado]"]');
        selects.forEach(select => { select.value = 'Presente'; });
    });
    @if(session('mensaje'))
    Swal.fire({
        icon: '{{ session("icono") }}',
        title: '{{ session("mensaje") }}',
        showConfirmButton: true,
        timer: 3000
    });
    @endif
</script>
@endsection