@extends('layouts.app')

@section('title', 'Colegio Adonai - Educación Cristiana de Excelencia')

@section('content')

<br><br>
<section class="workshops-section" id="talleres">
    <div class="container">

        <div class="section-header">
            <h2 class="section-title">TALLERES EXTRACURRICULARES</h2>
            <p class="section-sub">Espacios formativos para potenciar talentos. Filtra por categoría o haz clic en "Más info".</p>
        </div>

        <!-- Filtros -->
        <div class="filter-row" role="tablist" aria-label="Filtrar talleres">
            <button class="filter-chip active" data-filter="all">Todos</button>
            <button class="filter-chip" data-filter="musica">Música</button>
            <button class="filter-chip" data-filter="deportes">Deportes</button>
            <button class="filter-chip" data-filter="arte">Arte</button>
            <button class="filter-chip" data-filter="robotica">Robótica</button>
            <button class="filter-chip" data-filter="teatro">Teatro</button>
            <button class="filter-chip" data-filter="oratoria">Oratoria</button>
        </div>

        @php
            use App\Models\Taller;
            use Carbon\Carbon;
            use Illuminate\Support\Facades\Storage;

            $talleres = Taller::where('activo', true)->get();
        @endphp

        <!-- GRID -->
        <div class="workshops-grid" aria-live="polite">

            @foreach($talleres as $taller)
                @php
                    /* ===========================
                       SOLO CAMBIO DE CATEGORÍA
                       =========================== */

                    // Categoría real desde el admin
                    $categoria = strtolower($taller->categoria ?? 'general');

                    // Etiqueta visible tal cual la escribió el admin
                    $etiqueta = $taller->categoria ?? 'Taller';

                    /* ===========================
                       FIN CAMBIO SOLO CATEGORÍA
                       =========================== */

                    $duracionTexto = ($taller->duracion_inicio && $taller->duracion_fin)
                        ? Carbon::parse($taller->duracion_inicio)->format('d/m/Y') . ' al ' .
                          Carbon::parse($taller->duracion_fin)->format('d/m/Y')
                        : 'Duración por definir';

                    $horarioTexto = ($taller->horario_inicio && $taller->horario_fin)
                        ? Carbon::parse($taller->horario_inicio)->format('H:i') . ' - ' .
                          Carbon::parse($taller->horario_fin)->format('H:i')
                        : 'Horario por definir';

                    $imagePath = $taller->imagen ?: 'talleres/default.jpg';
                    $imageUrl  = Storage::url($imagePath);

                    $whatsappMessage = "Estoy interesado en el taller: " . urlencode($taller->nombre);
                @endphp

                <article class="workshop-card"
                    data-category="{{ $categoria }}"
                    data-title="{{ $taller->nombre }}"
                    data-image="{{ $imageUrl }}"
                    data-desc="{{ $taller->descripcion ?? 'Taller formativo para el desarrollo de habilidades.' }}"
                    data-instructor="{{ $taller->instructor }}"
                    data-schedule="{{ $horarioTexto }}"
                    data-duration="{{ $duracionTexto }}"
                    data-cost="{{ $taller->costo ? 'S/ '.$taller->costo : 'Gratuito' }}"
                    data-slots="{{ $taller->cupos_maximos }} cupos disponibles"
                    data-category-label="{{ $etiqueta }}">
                    
                    <div class="workshop-image">
                        <img src="{{ $imageUrl }}"
                             alt="{{ $taller->nombre }}"
                             style="width:100%; height:220px; object-fit:cover; border-radius:16px 16px 0 0;">
                    </div>

                    <div class="workshop-body">

                        <div class="workshop-meta">
                            <small class="workshop-tag">{{ $etiqueta }}</small>
                            @if($taller->costo)
                                <small class="workshop-price">S/ {{ $taller->costo }}</small>
                            @else
                                <small class="workshop-price-free">Gratuito</small>
                            @endif
                        </div>

                        <h3 class="workshop-title">{{ $taller->nombre }}</h3>
                        <p class="workshop-excerpt">
                            {{ $taller->descripcion ? \Illuminate\Support\Str::limit($taller->descripcion, 100) : 'Taller formativo.' }}
                        </p>

                        <div class="workshop-details">
                            <div class="detail-item"><i class="bi bi-person-fill"></i><span>{{ $taller->instructor }}</span></div>
                            <div class="detail-item"><i class="bi bi-calendar-event-fill"></i><span>{{ $duracionTexto }}</span></div>
                            <div class="detail-item"><i class="bi bi-clock-fill"></i><span>{{ $horarioTexto }}</span></div>
                        </div>

                        <div class="workshop-actions">
                            <button class="btn btn-outline btn-more">Más info</button>

                            <a class="btn btn-primary"
                               href="https://wa.me/51999999999?text={{ $whatsappMessage }}"
                               target="_blank"
                               rel="noopener">
                                Inscribirme
                            </a>
                        </div>
                    </div>
                </article>

            @endforeach

            @if($talleres->count() == 0)
                <div class="no-workshops">
                    <i class="bi bi-inbox" style="font-size:3rem;margin-bottom:1rem;"></i>
                    <h3>No hay talleres disponibles</h3>
                    <p>Próximamente anunciaremos nuestros nuevos talleres extracurriculares.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL -->
    <div id="workshop-modal" class="modal">
        <div class="modal-backdrop" data-close></div>

        <div class="modal-panel" style="max-width:720px;margin:40px auto;">
            <button class="modal-close" data-close><i class="bi bi-x-lg"></i></button>

            <div class="modal-grid">
                <div class="modal-image">
                    <img id="modal-image" src="" alt="">
                </div>

                <div class="modal-content">
                    <div class="modal-header">
                        <span id="modal-tag" class="modal-tag"></span>
                        <span id="modal-price" class="modal-price"></span>
                    </div>

                    <h3 id="modal-title"></h3>
                    <p id="modal-desc"></p>

                    <div class="modal-info-grid">
                        <div class="info-item"><i class="bi bi-person-fill"></i><strong> Instructor:</strong> <span id="modal-instructor"></span></div>
                        <div class="info-item"><i class="bi bi-calendar-event-fill"></i><strong> Duración:</strong> <span id="modal-duration"></span></div>
                        <div class="info-item"><i class="bi bi-clock-fill"></i><strong> Horario:</strong> <span id="modal-schedule"></span></div>
                        <div class="info-item"><i class="bi bi-people-fill"></i><strong> Cupos:</strong> <span id="modal-slots"></span></div>
                    </div>

                    <div class="modal-actions">
                        <a id="modal-join" class="btn btn-primary" href="#" target="_blank">
                            <i class="bi bi-whatsapp"></i> Inscribirme por WhatsApp
                        </a>
                        <button class="btn btn-outline" data-close><i class="bi bi-x-circle"></i> Cerrar</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    const cards = document.querySelectorAll('.workshop-card');
    const modal = document.getElementById('workshop-modal');
    const closeBtns = document.querySelectorAll('[data-close]');
    const filterChips = document.querySelectorAll('.filter-chip');

    // Filtros
    filterChips.forEach(chip => {
        chip.addEventListener('click', () => {
            filterChips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');

            const filter = chip.dataset.filter;

            cards.forEach(card => {
                card.style.display = (filter === 'all' || card.dataset.category === filter)
                    ? 'block'
                    : 'none';
            });
        });
    });

    // Abrir modal
    cards.forEach(card => {
        const btn = card.querySelector('.btn-more');
        if (!btn) return;

        btn.addEventListener('click', () => {
            document.getElementById('modal-title').textContent = card.dataset.title;
            document.getElementById('modal-image').src = card.dataset.image;
            document.getElementById('modal-desc').textContent = card.dataset.desc;
            document.getElementById('modal-instructor').textContent = card.dataset.instructor;
            document.getElementById('modal-duration').textContent = card.dataset.duration;
            document.getElementById('modal-schedule').textContent = card.dataset.schedule;
            document.getElementById('modal-slots').textContent = card.dataset.slots;
            document.getElementById('modal-tag').textContent = card.dataset.categoryLabel;
            document.getElementById('modal-price').textContent = card.dataset.cost;

            document.getElementById('modal-join').href =
                "https://wa.me/51999999999?text=Estoy interesado en el taller: " +
                encodeURIComponent(card.dataset.title);

            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    });

    // Cerrar modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    modal.querySelector('.modal-backdrop').addEventListener('click', closeModal);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });

});
</script>

@endsection
