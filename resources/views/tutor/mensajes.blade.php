@extends('layouts.tutor')
@section('title', 'Mensajeria')
@section('page_title')Mensajeria con <span>Docentes</span>@endsection
@section('content')

{{-- HERO --}}
<div class="msg-hero">
    <div class="msg-hero__orb"></div>
    <div class="msg-hero__content">
        <div class="msg-hero__left">
            <div class="msg-eyebrow"><span class="msg-eyebrow__dot"></span>Comunicacion Directa</div>
            <h1 class="msg-hero__title">Mensajeria con <span class="msg-hero__accent">Docentes</span></h1>
            <p class="msg-hero__sub">Comunicate directamente con los profesores de tus hijos.</p>
        </div>
        <div class="msg-hero__right">
            @if($mensajesNoLeidos > 0)
            <div class="msg-badge-noLeidos">
                <i class="fas fa-bell"></i> {{ $mensajesNoLeidos }} mensaje{{ $mensajesNoLeidos > 1 ? 's' : '' }} sin leer
            </div>
            @endif
            <button onclick="abrirModal()" class="msg-btn-nuevo">
                <i class="fas fa-pen"></i> Nuevo Mensaje
            </button>
        </div>
    </div>
</div>

{{-- CONTENIDO PRINCIPAL --}}
<div class="msg-wrapper">

    {{-- TABS --}}
    <div class="msg-tabs">
        <button id="tab-rec" onclick="switchTab('rec')" class="msg-tab msg-tab--active">
            <i class="fas fa-inbox"></i>
            <span>Recibidos</span>
            <span class="msg-tab__count">{{ $mensajesRecibidos->count() }}</span>
        </button>
        <button id="tab-env" onclick="switchTab('env')" class="msg-tab">
            <i class="fas fa-paper-plane"></i>
            <span>Enviados</span>
            <span class="msg-tab__count">{{ $mensajesEnviados->count() }}</span>
        </button>
    </div>

    {{-- PANEL RECIBIDOS --}}
    <div id="panel-rec">
        @if($mensajesRecibidos->count() > 0)
        <div class="msg-list">
            @foreach($mensajesRecibidos as $m)
            @php $leido = $m->destinatarios->where('destinatario_id', Auth::id())->first()->leido ?? false; @endphp
            <div class="msg-item {{ !$leido ? 'msg-item--nuevo' : '' }}">
                <div class="msg-item__avatar">
                    {{ strtoupper(substr($m->remitente->persona->nombres ?? 'D', 0, 1)) }}
                </div>
                <div class="msg-item__body">
                    <div class="msg-item__top">
                        <span class="msg-item__nombre">{{ $m->remitente->persona->nombres ?? 'N/A' }} {{ $m->remitente->persona->apellidos ?? '' }}</span>
                        @if(!$leido)<span class="msg-item__nuevo-badge">Nuevo</span>@endif
                    </div>
                    <div class="msg-item__asunto">{{ $m->asunto }}</div>
                    <div class="msg-item__meta">
                        <span><i class="fas fa-user-graduate"></i> {{ $m->estudiante?->persona->nombres ?? '-' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $m->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <a href="{{ route('tutor.mensajeria.ver', $m->id) }}" class="msg-item__btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="msg-empty">
            <div class="msg-empty__icon"><i class="fas fa-inbox"></i></div>
            <div class="msg-empty__title">Bandeja vacia</div>
            <div class="msg-empty__sub">No tienes mensajes recibidos por el momento.</div>
        </div>
        @endif
    </div>

    {{-- PANEL ENVIADOS --}}
    <div id="panel-env" style="display:none">
        @if($mensajesEnviados->count() > 0)
        <div class="msg-list">
            @foreach($mensajesEnviados as $m)
            <div class="msg-item">
                <div class="msg-item__avatar msg-item__avatar--sent">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="msg-item__body">
                    <div class="msg-item__top">
                        <span class="msg-item__nombre">Para: {{ $m->destinatarios->first()?->destinatario->persona->nombres ?? 'N/A' }}</span>
                    </div>
                    <div class="msg-item__asunto">{{ $m->asunto }}</div>
                    <div class="msg-item__meta">
                        <span><i class="fas fa-user-graduate"></i> {{ $m->estudiante?->persona->nombres ?? '-' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $m->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <a href="{{ route('tutor.mensajeria.ver', $m->id) }}" class="msg-item__btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="msg-empty">
            <div class="msg-empty__icon"><i class="fas fa-paper-plane"></i></div>
            <div class="msg-empty__title">Sin mensajes enviados</div>
            <div class="msg-empty__sub">Aun no has enviado ningun mensaje a los docentes.</div>
            <button onclick="abrirModal()" class="msg-btn-nuevo" style="margin-top:16px">
                <i class="fas fa-pen"></i> Escribir mensaje
            </button>
        </div>
        @endif
    </div>

</div>

{{-- MODAL NUEVO MENSAJE --}}
<div id="modal-nuevo" class="msg-modal-overlay">
    <div class="msg-modal-box">

        <div class="msg-modal-header">
            <div class="msg-modal-header__icon"><i class="fas fa-envelope"></i></div>
            <div>
                <div class="msg-modal-header__title">Nuevo Mensaje</div>
                <div class="msg-modal-header__sub">Escribe al docente de tu hijo</div>
            </div>
            <button onclick="cerrarModal()" class="msg-modal-close"><i class="fas fa-times"></i></button>
        </div>

        <form action="{{ route('tutor.mensajeria.enviar') }}" method="POST" enctype="multipart/form-data" class="msg-form">
            @csrf

            <div class="msg-form__row msg-form__row--2">
                <div class="msg-form__group">
                    <label class="msg-form__label"><i class="fas fa-user-graduate"></i> Estudiante *</label>
                    <select name="estudiante_id" id="est_id" required onchange="cargarDocentes()" class="msg-form__select">
                        <option value="">Seleccione un estudiante</option>
                        @foreach($tutor->estudiantes as $est)
                        <option value="{{ $est->id }}">{{ $est->persona->apellidos }} {{ $est->persona->nombres }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="msg-form__group">
                    <label class="msg-form__label"><i class="fas fa-chalkboard-teacher"></i> Docente *</label>
                    <select name="destinatario_user_id" id="doc_id" required class="msg-form__select">
                        <option value="">Primero seleccione un estudiante</option>
                    </select>
                </div>
            </div>

            <div class="msg-form__row msg-form__row--2">
                <div class="msg-form__group">
                    <label class="msg-form__label"><i class="fas fa-flag"></i> Prioridad</label>
                    <select name="prioridad" class="msg-form__select">
                        <option value="Normal">Normal</option>
                        <option value="Alta">Alta</option>
                        <option value="Urgente">Urgente</option>
                    </select>
                </div>
                <div class="msg-form__group">
                    <label class="msg-form__label"><i class="fas fa-tag"></i> Asunto *</label>
                    <input type="text" name="asunto" required maxlength="255" class="msg-form__input" placeholder="Tema del mensaje">
                </div>
            </div>

            <div class="msg-form__group">
                <label class="msg-form__label"><i class="fas fa-comment-alt"></i> Mensaje *</label>
                <textarea name="contenido" required rows="5" class="msg-form__input" placeholder="Escribe aqui tu mensaje para el docente..."></textarea>
            </div>

            <div class="msg-form__group">
                <label class="msg-form__label"><i class="fas fa-paperclip"></i> Archivos adjuntos <span style="font-weight:400;color:#9ca3af">(Opcional)</span></label>
                <div class="msg-form__file-area">
                    <input type="file" name="archivos[]" multiple id="file-input" style="display:none" onchange="mostrarArchivos(this)">
                    <button type="button" onclick="document.getElementById('file-input').click()" class="msg-form__file-btn">
                        <i class="fas fa-upload"></i> Elegir archivos
                    </button>
                    <span id="file-label" class="msg-form__file-label">Sin archivos seleccionados</span>
                </div>
            </div>

            <div class="msg-form__actions">
                <button type="button" onclick="cerrarModal()" class="msg-form__btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="msg-form__btn-send">
                    <i class="fas fa-paper-plane"></i> Enviar Mensaje
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('css')<style>

/* ===== HERO ===== */
.msg-hero{position:relative;overflow:hidden;padding:30px 36px 26px;background:var(--t-hero-bg);border-bottom:1px solid var(--t-border)}
.msg-hero__orb{position:absolute;width:300px;height:300px;border-radius:50%;top:-80px;right:3%;background:var(--t-orb1);filter:blur(60px);pointer-events:none;opacity:.7}
.msg-hero__content{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.msg-eyebrow{display:inline-flex;align-items:center;gap:7px;font-size:.67rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:var(--t-amber);margin-bottom:7px}
.msg-eyebrow__dot{width:6px;height:6px;border-radius:50%;background:var(--t-amber)}
.msg-hero__title{font-size:1.9rem;font-weight:800;color:var(--t-text);margin:0 0 5px;line-height:1.1}
.msg-hero__accent{color:var(--t-amber)}
.msg-hero__sub{font-size:.83rem;color:var(--t-muted);margin:0}
.msg-hero__right{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
.msg-badge-noLeidos{display:inline-flex;align-items:center;gap:7px;padding:8px 14px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:100px;font-size:.75rem;font-weight:700;color:#dc2626}
.msg-btn-nuevo{display:inline-flex;align-items:center;gap:8px;padding:11px 22px;background:#d97706;color:#fff;border:none;border-radius:10px;font-size:.83rem;font-weight:700;cursor:pointer;transition:all .2s;font-family:var(--t-font);box-shadow:0 4px 14px rgba(217,119,6,.35)}
.msg-btn-nuevo:hover{background:#b45309;box-shadow:0 6px 20px rgba(217,119,6,.45);transform:translateY(-1px)}

/* ===== WRAPPER ===== */
.msg-wrapper{padding:28px 36px 40px}

/* ===== TABS ===== */
.msg-tabs{display:flex;gap:8px;margin-bottom:24px}
.msg-tab{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#ffffff;border:1.5px solid #d1d5db;border-radius:10px;font-size:.8rem;font-weight:700;color:#6b7280;cursor:pointer;transition:all .2s;font-family:var(--t-font)}
.msg-tab:hover{border-color:#d97706;color:#d97706;background:#fffbeb}
.msg-tab--active{background:#d97706;border-color:#d97706;color:#fff;box-shadow:0 4px 12px rgba(217,119,6,.35)}
.msg-tab--active .msg-tab__count{background:rgba(255,255,255,.3);color:#fff}
.msg-tab__count{display:inline-flex;align-items:center;justify-content:center;min-width:22px;height:22px;padding:0 6px;background:#e5e7eb;border-radius:100px;font-size:.7rem;font-weight:800;color:#6b7280;transition:all .2s}

/* ===== LISTA ===== */
.msg-list{display:flex;flex-direction:column;gap:10px}
.msg-item{display:flex;align-items:center;gap:14px;padding:16px 18px;background:#ffffff;border:1.5px solid #e5e7eb;border-radius:12px;transition:all .2s}
.msg-item:hover{border-color:#d97706;box-shadow:0 4px 16px rgba(0,0,0,.08);transform:translateY(-1px)}
.msg-item--nuevo{border-left:3px solid #d97706;background:#fffbeb}
.msg-item__avatar{width:44px;height:44px;border-radius:12px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0}
.msg-item__avatar--sent{background:#ede9fe;color:#7c3aed}
.msg-item__body{flex:1;min-width:0}
.msg-item__top{display:flex;align-items:center;gap:8px;margin-bottom:3px}
.msg-item__nombre{font-size:.86rem;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.msg-item__nuevo-badge{flex-shrink:0;padding:2px 9px;background:#d97706;color:#fff;border-radius:100px;font-size:.64rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em}
.msg-item__asunto{font-size:.82rem;color:#374151;margin-bottom:5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.msg-item__meta{display:flex;gap:14px;flex-wrap:wrap}
.msg-item__meta span{display:inline-flex;align-items:center;gap:5px;font-size:.72rem;color:#9ca3af}
.msg-item__btn{flex-shrink:0;width:36px;height:36px;background:#f3f4f6;border:1.5px solid #e5e7eb;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#9ca3af;text-decoration:none;transition:all .2s}
.msg-item__btn:hover{background:#d97706;border-color:#d97706;color:#fff}

/* ===== VACIO ===== */
.msg-empty{text-align:center;padding:60px 20px}
.msg-empty__icon{width:72px;height:72px;border-radius:18px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.9rem;margin:0 auto 16px}
.msg-empty__title{font-size:1rem;font-weight:800;color:#111827;margin-bottom:6px}
.msg-empty__sub{font-size:.83rem;color:#6b7280}

/* ===== MODAL ===== */
.msg-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(3px)}
.msg-modal-overlay.activo{display:flex !important}
.msg-modal-box{background:#ffffff;border-radius:18px;width:100%;max-width:600px;position:relative;max-height:92vh;overflow-y:auto;box-shadow:0 30px 80px rgba(0,0,0,.2)}
.msg-modal-header{display:flex;align-items:center;gap:14px;padding:22px 28px 20px;border-bottom:1px solid #f3f4f6}
.msg-modal-header__icon{width:44px;height:44px;border-radius:12px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.05rem;flex-shrink:0}
.msg-modal-header__title{font-size:1rem;font-weight:800;color:#111827;line-height:1.2}
.msg-modal-header__sub{font-size:.75rem;color:#9ca3af;margin-top:2px}
.msg-modal-close{margin-left:auto;width:34px;height:34px;background:#f3f4f6;border:none;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#6b7280;font-size:.85rem;transition:all .2s}
.msg-modal-close:hover{background:#e5e7eb;color:#111827}

/* ===== FORM ===== */
.msg-form{padding:24px 28px 28px;display:grid;gap:18px}
.msg-form__row{display:grid;gap:14px}
.msg-form__row--2{grid-template-columns:1fr 1fr}
.msg-form__group{display:flex;flex-direction:column;gap:6px}
.msg-form__label{font-size:.78rem;font-weight:700;color:#374151;display:flex;align-items:center;gap:6px}
.msg-form__label i{color:#d97706;font-size:.72rem}
.msg-form__select,.msg-form__input{width:100%;padding:10px 13px;border:1.5px solid #e5e7eb;border-radius:9px;background:#f9fafb;color:#111827;font-size:.83rem;font-family:inherit;box-sizing:border-box;transition:all .2s}
.msg-form__select:focus,.msg-form__input:focus{outline:none;border-color:#d97706;background:#fff;box-shadow:0 0 0 3px rgba(217,119,6,.1)}
textarea.msg-form__input{resize:vertical;min-height:110px}
.msg-form__file-area{display:flex;align-items:center;gap:12px;padding:10px 14px;border:1.5px dashed #e5e7eb;border-radius:9px;background:#f9fafb}
.msg-form__file-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:#fff;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.77rem;font-weight:700;color:#374151;cursor:pointer;transition:all .2s;white-space:nowrap}
.msg-form__file-btn:hover{border-color:#d97706;color:#d97706}
.msg-form__file-label{font-size:.77rem;color:#9ca3af;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.msg-form__actions{display:flex;justify-content:flex-end;gap:10px;padding-top:8px;border-top:1px solid #f3f4f6;margin-top:2px}
.msg-form__btn-cancel{display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#f3f4f6;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.82rem;font-weight:700;color:#6b7280;cursor:pointer;transition:all .2s;font-family:inherit}
.msg-form__btn-cancel:hover{background:#e5e7eb;color:#374151}
.msg-form__btn-send{display:inline-flex;align-items:center;gap:7px;padding:10px 22px;background:#d97706;border:none;border-radius:9px;font-size:.82rem;font-weight:700;color:#fff;cursor:pointer;transition:all .2s;font-family:inherit;box-shadow:0 4px 12px rgba(217,119,6,.3)}
.msg-form__btn-send:hover{background:#b45309;box-shadow:0 6px 18px rgba(217,119,6,.4);transform:translateY(-1px)}

@media(max-width:560px){
    .msg-form__row--2{grid-template-columns:1fr}
    .msg-wrapper{padding:20px 18px 32px}
    .msg-hero{padding:22px 18px 18px}
}
</style>@endsection

@section('js')<script>
function abrirModal(){
    document.getElementById('modal-nuevo').classList.add('activo');
    document.body.style.overflow = 'hidden';
}
function cerrarModal(){
    document.getElementById('modal-nuevo').classList.remove('activo');
    document.body.style.overflow = '';
}
document.getElementById('modal-nuevo').addEventListener('click', function(e){
    if(e.target === this) cerrarModal();
});
function mostrarArchivos(input){
    const label = document.getElementById('file-label');
    if(input.files.length === 0){
        label.textContent = 'Sin archivos seleccionados';
    } else if(input.files.length === 1){
        label.textContent = input.files[0].name;
    } else {
        label.textContent = input.files.length + ' archivos seleccionados';
    }
}
const docentesPorEst = @json($docentes->groupBy('estudiante_id'));
function cargarDocentes(){
    const id = document.getElementById('est_id').value;
    const sel = document.getElementById('doc_id');
    sel.innerHTML = '<option value="">Seleccione un docente</option>';
    if(id && docentesPorEst[id]){
        docentesPorEst[id].forEach(d=>{
            const o = document.createElement('option');
            o.value = d.user_id;
            o.textContent = d.nombres+' '+d.apellidos+' ('+d.curso+')';
            sel.appendChild(o);
        });
    }
}
function switchTab(t){
    document.getElementById('panel-rec').style.display = t==='rec'?'block':'none';
    document.getElementById('panel-env').style.display = t==='env'?'block':'none';
    document.getElementById('tab-rec').className = 'msg-tab'+(t==='rec'?' msg-tab--active':'');
    document.getElementById('tab-env').className = 'msg-tab'+(t==='env'?' msg-tab--active':'');
}
</script>@endsection