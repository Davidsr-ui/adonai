@extends('layouts.docente')

@section('title', 'Registrar Notas')
@section('page_title')Registrar <span>Notas</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-star"></i> Registrar Notas por Curso</h2>
            <p class="d-page-hdr__sub">Seleccione un curso y un periodo para ingresar las notas de todos los estudiantes</p>
        </div>
    </div>

    {{-- Selector de curso y periodo --}}
    <div class="d-filters-card" style="margin:16px 0;background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:16px 20px">
        <form method="GET" action="{{ route('docente.notas.create') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
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
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Periodo</label>
                <select name="periodo_id" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">-- Seleccione un periodo --</option>
                    @foreach($periodos as $periodo)
                        <option value="{{ $periodo->id }}" {{ request('periodo_id') == $periodo->id ? 'selected' : '' }}>{{ $periodo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;cursor:pointer;font-size:.75rem">
                    <i class="fas fa-search"></i> Cargar estudiantes
                </button>
            </div>
        </form>
    </div>

    @if($cursoId && $periodoId && $estudiantes->count() > 0)
    <div class="d-card" style="margin-top:20px">
        <div class="d-card__hdr d-flex justify-content-between align-items-center">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--rose"><i class="fas fa-star"></i></span>Registro de Notas - {{ $cursos->firstWhere('id', $cursoId)->nombre }} / {{ $periodos->firstWhere('id', $periodoId)->nombre }}</div>
            <button type="button" class="d-btn d-btn--sky" id="calcularFinales" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;font-size:.75rem">
                <i class="fas fa-calculator"></i> Calcular finales (prác+teo)/2
            </button>
        </div>
        <form action="{{ route('docente.notas.store.multiple') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $cursoId }}">
            <input type="hidden" name="periodo_id" value="{{ $periodoId }}">
            <div style="overflow-x:auto; border-radius:12px;">
                <table class="d-table" style="width:100%; border-collapse:collapse; font-size:0.8rem;">
                    <thead style="background:var(--surface2); border-bottom:1px solid var(--border);">
                        <tr>
                            <th style="width:50px; padding:12px 8px;">#</th>
                            <th style="padding:12px 8px;">Código</th>
                            <th style="padding:12px 8px;">Apellidos y Nombres</th>
                            <th style="width:120px; padding:12px 8px;">Tipo</th>
                            <th style="width:100px; padding:12px 8px;">Práctica</th>
                            <th style="width:100px; padding:12px 8px;">Teoría</th>
                            <th style="width:100px; padding:12px 8px;">Final</th>
                            <th style="width:140px; padding:12px 8px;">Fecha Evaluación</th>
                            <th style="width:200px; padding:12px 8px;">Descripción</th>
                            <th style="width:200px; padding:12px 8px;">Observaciones</th>
                            <th style="width:110px; padding:12px 8px;">Visible</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $index => $e)
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="text-align:center; padding:10px 8px;">{{ $index + 1 }}</td>
                            <td style="padding:10px 8px;" class="d-mono">{{ $e->codigo_estudiante ?? '-' }}</td>
                            <td style="padding:10px 8px;">
                                <div class="d-av" style="display:inline-flex;margin-right:8px">{{ strtoupper(substr($e->persona->apellidos ?? 'A', 0, 1)) }}</div>
                                {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                <input type="hidden" name="notas[{{ $loop->index }}][matricula_id]" value="{{ $e->matricula_id }}">
                                <input type="hidden" name="notas[{{ $loop->index }}][nota_id]" value="{{ $e->nota_id }}">
                            </td>
                            <td style="padding:10px 8px;">
                                <select name="notas[{{ $loop->index }}][tipo_evaluacion]" class="d-select" style="width:100%; background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:6px 6px;">
                                    <option value="Parcial" {{ $e->tipo_evaluacion == 'Parcial' ? 'selected' : '' }}>Parcial</option>
                                    <option value="Final" {{ $e->tipo_evaluacion == 'Final' ? 'selected' : '' }}>Final</option>
                                    <option value="Práctica" {{ $e->tipo_evaluacion == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                                    <option value="Oral" {{ $e->tipo_evaluacion == 'Oral' ? 'selected' : '' }}>Oral</option>
                                    <option value="Trabajo" {{ $e->tipo_evaluacion == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                                </select>
                            </td>
                            <td style="padding:10px 8px;">
                                <input type="number" name="notas[{{ $loop->index }}][nota_practica]" class="d-input nota-practica" step="0.01" min="0" max="20" style="width:100%; background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:6px 8px;" value="{{ $e->nota_practica }}" placeholder="Práctica">
                            </td>
                            <td style="padding:10px 8px;">
                                <input type="number" name="notas[{{ $loop->index }}][nota_teoria]" class="d-input nota-teoria" step="0.01" min="0" max="20" style="width:100%; background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:6px 8px;" value="{{ $e->nota_teoria }}" placeholder="Teoría">
                            </td>
                            <td style="padding:10px 8px;">
                                <input type="number" name="notas[{{ $loop->index }}][nota_final]" class="d-input nota-final" step="0.01" min="0" max="20" style="width:100%; font-weight:bold; background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:6px 8px;" value="{{ $e->nota_final }}" required placeholder="Final">
                            </td>
                            <td style="padding:10px 8px;">
                                <input type="date" name="notas[{{ $loop->index }}][fecha_evaluacion]" class="d-input" style="width:100%; background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:6px 8px;" value="{{ $e->fecha_evaluacion ? \Carbon\Carbon::parse($e->fecha_evaluacion)->format('Y-m-d') : '' }}">
                            </td>
                            <td style="padding:10px 8px;">
                                <input type="text" name="notas[{{ $loop->index }}][descripcion]" class="d-input" style="width:100%; background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:6px 8px;" value="{{ $e->descripcion }}" placeholder="Descripción">
                            </td>
                            <td style="padding:10px 8px;">
                                <textarea name="notas[{{ $loop->index }}][observaciones]" class="d-input" rows="2" style="width:100%; background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:6px 8px;" placeholder="Observaciones">{{ $e->observaciones }}</textarea>
                            </td>
                            <td style="padding:10px 8px; text-align:center;">
                                <label style="display:flex;align-items:center;justify-content:center;gap:8px;cursor:pointer">
                                    <input type="checkbox" name="notas[{{ $loop->index }}][visible_tutor]" value="1" {{ $e->visible_tutor ? 'checked' : '' }} style="width:16px;height:16px;">
                                    <span style="font-size:.7rem">Visible</span>
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-card__ftr" style="padding:12px 18px; border-top:1px solid var(--border); background:var(--surface2); text-align:right; border-radius:0 0 12px 12px;">
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand); border:none; border-radius:30px; padding:8px 20px; color:#fff; font-weight:600; font-size:.8rem;">
                    <i class="fas fa-save"></i> Guardar todas las notas
                </button>
            </div>
        </form>
    </div>
    @elseif($cursoId && $periodoId && $estudiantes->isEmpty())
    <div class="d-empty-state" style="text-align:center; padding:40px 20px; background:var(--surface); border-radius:20px; border:1px solid var(--border);">
        <i class="fas fa-user-graduate" style="font-size:2.5rem; color:var(--muted); margin-bottom:12px; display:block;"></i>
        <strong style="display:block; margin-bottom:4px;">Sin estudiantes en este curso</strong>
        <span style="color:var(--text2); font-size:.8rem;">No se encontraron estudiantes matriculados en este curso.</span>
    </div>
    @endif
</div>
@endsection

@section('css')
<style>
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
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 2px rgba(14,165,233,.2);
    }
    .d-btn--sky {
        transition: all .2s;
    }
    .d-btn--sky:hover {
        background: var(--brand-d) !important;
        transform: translateY(-1px);
    }
    .d-table {
        background: var(--surface);
        border-radius: 12px;
    }
    .d-table th {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: var(--text2);
        text-align: left;
    }
    .d-table td {
        vertical-align: middle;
    }
    @media (max-width: 768px) {
        .d-table th, .d-table td {
            padding: 8px 6px !important;
        }
        .d-table th:nth-child(4), .d-table td:nth-child(4),
        .d-table th:nth-child(8), .d-table td:nth-child(8) {
            min-width: 110px;
        }
        .d-table input, .d-table select {
            font-size: .7rem;
        }
    }
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        opacity: 0.5;
    }
    .d-card {
        background: var(--surface);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    .d-card__hdr {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        background: var(--surface2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .d-card__title {
        font-weight: 800;
        font-size: .9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .d-card__ico {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 10px;
    }
    .d-card__ico--rose {
        background: var(--rose-bg);
        color: var(--rose);
    }
    .d-flex {
        display: flex;
    }
    .justify-content-between {
        justify-content: space-between;
    }
    .align-items-center {
        align-items: center;
    }
    #calcularFinales {
        cursor: pointer;
    }
</style>
@endsection

@section('js')
<script>
    document.getElementById('calcularFinales')?.addEventListener('click', function() {
        const practicas = document.querySelectorAll('.nota-practica');
        const teorias = document.querySelectorAll('.nota-teoria');
        const finales = document.querySelectorAll('.nota-final');
        for (let i = 0; i < practicas.length; i++) {
            let p = parseFloat(practicas[i].value) || 0;
            let t = parseFloat(teorias[i].value) || 0;
            let promedio = ((p + t) / 2).toFixed(2);
            finales[i].value = promedio;
        }
    });
</script>
@if(session('mensaje'))
<script>
    Swal.fire({
        icon: '{{ session("icono") }}',
        title: '{{ session("mensaje") }}',
        showConfirmButton: true,
        timer: 3000
    });
</script>
@endif
@endsection