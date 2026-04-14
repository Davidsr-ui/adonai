@extends('layouts.docente')

@section('title', 'Detalle del Mensaje')
@section('page_title')Detalle <span>Mensaje</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div style="margin-bottom:16px">
        <a href="{{ route('docente.mensajeria') }}" class="d-btn-back"><i class="fas fa-arrow-left"></i> Volver a Mensajería</a>
    </div>

    <div class="d-card">
        <div class="d-card__hdr">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <span class="d-badge d-badge--{{ $mensaje->badge_prioridad }}"><i class="fas {{ $mensaje->icono_prioridad }}"></i> {{ $mensaje->prioridad }}</span>
                <span style="font-size:.9rem;font-weight:700;color:var(--text)">{{ $mensaje->asunto }}</span>
            </div>
            <a href="{{ route('docente.mensajeria') }}" class="d-btn-icon d-btn-icon--slate"><i class="fas fa-times"></i></a>
        </div>
        <div style="padding:24px">

            {{-- Meta --}}
            <div class="d-msg-meta">
                <div class="d-msg-meta__item">
                    <span class="d-msg-meta__lbl"><i class="fas fa-user"></i> De (Tutor)</span>
                    <span class="d-msg-meta__val">{{ $mensaje->remitente->persona->nombres ?? 'N/A' }} {{ $mensaje->remitente->persona->apellidos ?? '' }}</span>
                </div>
                <div class="d-msg-meta__item">
                    <span class="d-msg-meta__lbl"><i class="fas fa-calendar"></i> Fecha</span>
                    <span class="d-msg-meta__val">{{ $mensaje->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($mensaje->estudiante)
                <div class="d-msg-meta__item">
                    <span class="d-msg-meta__lbl"><i class="fas fa-user-graduate"></i> Estudiante</span>
                    <span class="d-msg-meta__val">{{ $mensaje->estudiante->persona->nombres }} {{ $mensaje->estudiante->persona->apellidos }}</span>
                </div>
                @endif
                @if($mensaje->destinatarios->first())
                <div class="d-msg-meta__item">
                    <span class="d-msg-meta__lbl"><i class="fas fa-user-check"></i> Para</span>
                    <span class="d-msg-meta__val">{{ $mensaje->destinatarios->first()->destinatario->persona->nombres ?? 'N/A' }} {{ $mensaje->destinatarios->first()->destinatario->persona->apellidos ?? '' }}</span>
                </div>
                @endif
            </div>

            <hr style="border:none;border-top:1px solid var(--border);margin:20px 0">

            {{-- Cuerpo --}}
            <div style="font-size:.86rem;font-weight:600;color:var(--muted);margin-bottom:8px"><i class="fas fa-envelope-open-text"></i> Mensaje</div>
            <div class="d-msg-body">{!! nl2br(e($mensaje->contenido)) !!}</div>

            {{-- Adjuntos --}}
            @if($mensaje->tiene_archivos)
            <div style="margin-top:20px">
                <div style="font-size:.86rem;font-weight:600;color:var(--muted);margin-bottom:10px"><i class="fas fa-paperclip"></i> Adjuntos ({{ $mensaje->cantidad_archivos }})</div>
                <div style="display:flex;flex-wrap:wrap;gap:8px">
                    @foreach($mensaje->archivos as $a)
                    <a href="{{ asset('storage/'.$a['path']) }}" download class="d-file-chip">
                        <i class="fas fa-file"></i>
                        <span>{{ $a['nombre'] }}</span>
                        <small>{{ number_format($a['tamaño']/1024,1) }} KB</small>
                        <i class="fas fa-download"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <hr style="border:none;border-top:1px solid var(--border);margin:20px 0">

            {{-- Acciones --}}
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                @if($mensaje->remitente_id != Auth::id())
                <button class="d-btn d-btn--sky" data-toggle="modal" data-target="#responderModal"><i class="fas fa-reply"></i> Responder</button>
                @endif
                @if($mensaje->estudiante)
                <a href="{{ route('docente.alumno.ficha', $mensaje->estudiante->id) }}" class="d-btn d-btn--outline"><i class="fas fa-id-card"></i> Ver Ficha del Estudiante</a>
                @endif
                <a href="{{ route('docente.mensajeria') }}" class="d-btn d-btn--ghost"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Responder --}}
<div class="modal fade" id="responderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:1px solid var(--border);background:var(--surface)">
            <form action="{{ route('docente.mensajeria.responder', $mensaje->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="border-bottom:1px solid var(--border);padding:18px 24px">
                    <h5 style="margin:0;font-size:.92rem;font-weight:700;color:var(--text)"><i class="fas fa-reply" style="color:var(--brand)"></i> Responder Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" style="color:var(--muted)">&times;</button>
                </div>
                <div class="modal-body" style="padding:20px 24px">
                    <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 16px;font-size:.81rem;color:var(--muted);margin-bottom:16px">
                        <strong style="color:var(--text)">Respondiendo a:</strong> {{ $mensaje->asunto }}<br>
                        <strong style="color:var(--text)">Remitente:</strong> {{ $mensaje->remitente->persona->nombres ?? 'N/A' }} {{ $mensaje->remitente->persona->apellidos ?? '' }}
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Mensaje <b class="d-req">*</b></label>
                        <textarea name="contenido" class="d-textarea" rows="5" required placeholder="Escriba su respuesta..."></textarea>
                    </div>
                    <div class="d-form-group" style="margin-top:12px">
                        <label class="d-label">Adjuntos <span style="color:var(--muted);font-weight:400">(Opcional)</span></label>
                        <input type="file" name="archivos[]" class="d-file" multiple>
                        <small style="color:var(--muted);font-size:.72rem">Máximo 10MB por archivo</small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 24px;gap:10px">
                    <button type="button" class="d-btn d-btn--ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="d-btn d-btn--sky"><i class="fas fa-paper-plane"></i> Enviar Respuesta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.d-btn-back{display:inline-flex;align-items:center;gap:6px;font-size:.79rem;font-weight:600;color:var(--muted);text-decoration:none;transition:color .2s}
.d-btn-back:hover{color:var(--brand)}
.d-msg-meta{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px}
.d-msg-meta__item{display:flex;flex-direction:column;gap:3px}
.d-msg-meta__lbl{font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);display:flex;align-items:center;gap:5px}
.d-msg-meta__val{font-size:.84rem;font-weight:600;color:var(--text)}
.d-msg-body{background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:16px 18px;font-size:.84rem;color:var(--text);line-height:1.7;white-space:pre-wrap}
.d-file-chip{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;font-size:.76rem;color:var(--text);text-decoration:none;transition:all .2s}
.d-file-chip:hover{border-color:var(--brand);color:var(--brand)}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--sky{background:var(--brand);color:#fff}.d-btn--sky:hover{background:var(--brand-d);color:#fff}
.d-btn--outline{background:transparent;color:var(--brand);border:2px solid var(--brand)}.d-btn--outline:hover{background:var(--brand-bg)}
.d-btn--ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.d-btn-icon{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
.d-btn-icon--slate{background:rgba(100,116,139,.1);color:var(--slate)}.d-btn-icon--slate:hover{background:var(--slate);color:#fff}
.d-form-group{display:flex;flex-direction:column;gap:5px}
.d-label{font-size:.73rem;font-weight:600;color:var(--text)}
.d-req{color:var(--rose)}
.d-textarea{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;font-size:.82rem;color:var(--text);outline:none;transition:border .2s;font-family:var(--ff);resize:vertical}
.d-textarea:focus{border-color:var(--brand)}
.d-file{font-size:.8rem;color:var(--text)}
</style>
@endsection

