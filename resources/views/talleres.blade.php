<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talleres - Colegio Adonai</title>
    <!-- CSS y JS con Vite -->
    @vite(['resources/css/style.css', 'resources/js/script.js'])
    @vite(['resources/css/talleres.css', 'resources/js/talleres.js'])
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <!-- Navbar Corregido -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <!-- Logo como botón -->
            <div class="logo">
                <a href="/" class="logo-link">
                    <img src="/img/logoad.png" alt="Colegio Adonai" class="logo-img">
                </a>
            </div>

            <ul class="nav-menu" id="nav-menu">
                <li class="nav-item"><a href="/" class="nav-link">Inicio</a></li>

                <!-- Dropdown corregido -->
                <li class="nav-item dropdown">
                    <a href="#nosotros" class="nav-link dropdown-toggle">
                        Sobre Nosotros <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#mision" class="dropdown-link">Misión</a></li>
                        <li><a href="#vision" class="dropdown-link">Visión</a></li>
                        <li><a href="#valores" class="dropdown-link">Valores</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a href="{{ route('cursos') }}" class="nav-link">Cursos</a></li>
                <li class="nav-item"><a href="{{ route('talleres') }}" class="nav-link">Talleres</a></li>
                <li class="nav-item"><a href="{{ route('docentes') }}" class="nav-link">Profesores</a></li>
                <li class="nav-item"><a href="{{ route('blog') }}" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="{{ route('tour') }}" class="nav-link">Visita Guiada</a></li>
            </ul>

            <div class="nav-actions">
                <!-- Botón Intranet -->
                <a href="{{ route('login') }}" class="btn-intranet">
                    <span>Intranet</span>
                    <i class="bi bi-person-circle"></i>
                </a>

                <!-- Hamburguesa -->
                <button class="menu-toggle" id="menu-toggle">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Contenido de talleres -->
    <br><br>
    <section class="workshops-section" id="talleres">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">TALLERES EXTRACURRICULARES</h2>
                <p class="section-sub">Espacios formativos para potenciar talentos. Filtra por categoría o haz clic en "Más info".</p>
            </div>

            <!-- Filtros -->
            <div class="filter-row" role="tablist" aria-label="Filtrar talleres">
                <button class="filter-chip active" data-filter="all" role="tab">Todos</button>
                <button class="filter-chip" data-filter="musica" role="tab">Música</button>
                <button class="filter-chip" data-filter="deportes" role="tab">Deportes</button>
                <button class="filter-chip" data-filter="arte" role="tab">Arte</button>
                <button class="filter-chip" data-filter="robotica" role="tab">Robótica</button>
                <button class="filter-chip" data-filter="teatro" role="tab">Teatro</button>
                <button class="filter-chip" data-filter="oratoria" role="tab">Oratoria</button>
            </div>

            <!-- Cuadrícula de talleres DINÁMICA -->
            <div class="workshops-grid" aria-live="polite">

                @php
                    use App\Models\Taller;
                    use Carbon\Carbon;

                    $talleres = Taller::where('activo', true)->get();

                    function contains_any($text, $needles) {
                        foreach ($needles as $n) {
                            if (str_contains($text, $n)) {
                                return true;
                            }
                        }
                        return false;
                    }
                @endphp

                @foreach($talleres as $taller)
                    @php
                        // Determinar categoría basada en el nombre del taller
                        $categoria = 'general';
                        $nombreLower = strtolower($taller->nombre ?? '');

                        if (contains_any($nombreLower, ['música', 'musica', 'piano', 'guitarra', 'canto'])) {
                            $categoria = 'musica';
                        } elseif (contains_any($nombreLower, ['deporte', 'fútbol', 'futbol', 'vóley', 'vole', 'básquet', 'basquet'])) {
                            $categoria = 'deportes';
                        } elseif (contains_any($nombreLower, ['arte', 'pintura', 'dibujo', 'manualidad'])) {
                            $categoria = 'arte';
                        } elseif (contains_any($nombreLower, ['robótica', 'robotica', 'programación', 'programacion'])) {
                            $categoria = 'robotica';
                        } elseif (contains_any($nombreLower, ['teatro', 'drama', 'actuación', 'actuacion'])) {
                            $categoria = 'teatro';
                        } elseif (contains_any($nombreLower, ['oratoria', 'hablar público', 'comunicación', 'comunicacion'])) {
                            $categoria = 'oratoria';
                        }

                        // Determinar etiqueta para mostrar
                        $etiquetas = [
                            'musica' => 'Música',
                            'deportes' => 'Deportes',
                            'arte' => 'Arte',
                            'robotica' => 'Robótica',
                            'teatro' => 'Teatro',
                            'oratoria' => 'Oratoria',
                            'general' => 'Taller'
                        ];

                        $etiqueta = $etiquetas[$categoria] ?? 'Taller';

                        // Duración: SOLO fechas
                        $duracionTexto = ($taller->duracion_inicio && $taller->duracion_fin)
                            ? Carbon::parse($taller->duracion_inicio)->format('d/m/Y') . ' al ' . Carbon::parse($taller->duracion_fin)->format('d/m/Y')
                            : 'Duración por definir';

                        // Horario: SOLO horas HH:MM
                        $horarioTexto = ($taller->horario_inicio && $taller->horario_fin)
                            ? substr($taller->horario_inicio, 0, 5) . ' - ' . substr($taller->horario_fin, 0, 5)
                            : 'Horario por definir';

                        // WhatsApp message
                        $whatsappMessage = "Estoy interesado en el taller: " . urlencode($taller->nombre);
                    @endphp

                    <article class="workshop-card" data-category="{{ $categoria }}"
                        data-title="{{ $taller->nombre }}"
                        data-image="{{ $taller->imagen ? asset('storage/'.$taller->imagen) : '/img/taller-default.jpg' }}"
                        data-desc="{{ $taller->descripcion ?? 'Taller formativo para el desarrollo de habilidades.' }}"
                        data-instructor="{{ $taller->instructor }}"
                        data-schedule="{{ $horarioTexto }}"
                        data-duration="{{ $duracionTexto }}"
                        data-cost="{{ $taller->costo ? 'S/ ' . $taller->costo : 'Gratuito' }}"
                        data-slots="{{ $taller->cupos_maximos }} cupos disponibles"
                        data-category-label="{{ $etiqueta }}">
                        <div class="workshop-image">
                            <img src="{{ $taller->imagen ? asset('storage/'.$taller->imagen) : '/img/taller-default.jpg' }}"
                                alt="{{ $taller->nombre }}"
                                onerror="this.src='/img/taller-default.jpg'">
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
                                {{ $taller->descripcion ? \Illuminate\Support\Str::limit($taller->descripcion, 100) : 'Taller formativo para desarrollo de habilidades.' }}
                            </p>

                            <div class="workshop-details">
                                <div class="detail-item">
                                    <i class="bi bi-person-fill"></i>
                                    <span>{{ $taller->instructor }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="bi bi-calendar-event-fill"></i>
                                    <span>{{ $duracionTexto }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="bi bi-clock-fill"></i>
                                    <span>{{ $horarioTexto }}</span>
                                </div>
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

                <!-- Mensaje si no hay talleres -->
                @if($talleres->count() == 0)
                <div class="no-workshops">
                    <i class="bi bi-inbox" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h3>No hay talleres disponibles</h3>
                    <p>Próximamente anunciaremos nuestros nuevos talleres extracurriculares.</p>
                </div>
                @endif

            </div>
        </div>

        <!-- Modal de detalle -->
        <div id="workshop-modal" class="modal" aria-hidden="true" role="dialog" aria-modal="true">
            <div class="modal-backdrop" tabindex="-1" data-close></div>
            <div class="modal-panel" role="document" style="max-width: 720px; margin: 40px auto;">
                <button class="modal-close" aria-label="Cerrar" data-close>
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="modal-grid">
                    <div class="modal-image">
                        <img id="modal-image" src="" alt="">
                    </div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="modal-tag" id="modal-tag"></span>
                            <span class="modal-price" id="modal-price"></span>
                        </div>
                        <h3 id="modal-title"></h3>
                        <p id="modal-desc"></p>

                        <div class="modal-info-grid">
                            <div class="info-item">
                                <i class="bi bi-person-fill"></i>
                                <div>
                                    <strong>Instructor:</strong>
                                    <span id="modal-instructor"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-calendar-event-fill"></i>
                                <div>
                                    <strong>Duración:</strong>
                                    <span id="modal-duration"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-clock-fill"></i>
                                <div>
                                    <strong>Horario:</strong>
                                    <span id="modal-schedule"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-people-fill"></i>
                                <div>
                                    <strong>Cupos:</strong>
                                    <span id="modal-slots"></span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-actions">
                            <a id="modal-join" class="btn btn-primary" href="#" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp"></i> Inscribirme por WhatsApp
                            </a>
                            <button class="btn btn-outline" data-close>
                                <i class="bi bi-x-circle"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer" id="footer">
        <div class="container">
            <div class="footer-content">

                <!-- COLUMNA 1 -->
                <div class="footer-column">
                    <div class="footer-logo">
                        <div class="logo-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <span>Colegio Adonai</span>
                    </div>
                    <p>Formando vidas con propósito desde 2009</p>

                    <h4>Ubícanos</h4>
                    <div class="footer-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3893.1234567890123!2d-79.125678901234!3d-7.860123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9108abcdef12345%3A0xabcdef1234567890!2sChicama%2C%20Per%C3%BA!5e0!3m2!1ses!2spe!4v1695700000000!5m2!1ses!2spe"
                            loading="lazy"
                            allowfullscreen=""></iframe>
                    </div>
                </div>

                <!-- COLUMNA 2 -->
                <div class="footer-column">
                    <h4>Contacto</h4>
                    <p><i class="bi bi-geo-alt-fill"></i> Av. Principal 123, Lima</p>
                    <p><i class="bi bi-telephone-fill"></i> (01) 234-5678</p>
                    <p><i class="bi bi-envelope-fill"></i> info@colegioadonai.edu.pe</p>
                </div>

                <!-- COLUMNA 3 -->
                <div class="footer-column">
                    <h4>Enlaces</h4>
                    <a href="/#nosotros"><i class="bi bi-chevron-right"></i> Sobre Nosotros</a>
                    <a href="/#cursos"><i class="bi bi-chevron-right"></i> Cursos</a>
                    <a href="/blog"><i class="bi bi-chevron-right"></i> Blog</a>
                    <a href="/#visita"><i class="bi bi-chevron-right"></i> Visita Guiada</a>
                </div>

                <!-- COLUMNA 4 -->
                <div class="footer-column">
                    <h4>Síguenos</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/colegiocristiano.mgsa?locale=es_LA" target="_blank" class="social-icon" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center footer-bottom">
                <p>&copy; 2025 Colegio Adonai. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/51999999999" class="whatsapp-float" target="_blank" aria-label="Contactar por WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- JavaScript MEJORADO para el modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const workshopCards = document.querySelectorAll('.workshop-card');
            const modal = document.getElementById('workshop-modal');
            const closeButtons = document.querySelectorAll('[data-close]');

            // Filtros
            const filterChips = document.querySelectorAll('.filter-chip');

            filterChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    filterChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');

                    workshopCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Configurar modal para cada card
            workshopCards.forEach(card => {
                const moreBtn = card.querySelector('.btn-more');
                if (!moreBtn) return;

                moreBtn.addEventListener('click', function() {
                    const title = card.getAttribute('data-title');
                    const image = card.getAttribute('data-image');
                    const desc = card.getAttribute('data-desc');
                    const instructor = card.getAttribute('data-instructor');
                    const schedule = card.getAttribute('data-schedule');
                    const duration = card.getAttribute('data-duration');
                    const cost = card.getAttribute('data-cost');
                    const slots = card.getAttribute('data-slots');
                    const categoryLabel = card.getAttribute('data-category-label');
                    const category = card.getAttribute('data-category');

                    // Llenar modal
                    document.getElementById('modal-title').textContent = title;
                    document.getElementById('modal-image').src = image;
                    document.getElementById('modal-image').alt = title;
                    document.getElementById('modal-desc').textContent = desc;
                    document.getElementById('modal-instructor').textContent = instructor;
                    document.getElementById('modal-schedule').textContent = schedule;
                    document.getElementById('modal-duration').textContent = duration;
                    document.getElementById('modal-slots').textContent = slots;

                    document.getElementById('modal-tag').textContent =
                        categoryLabel || (category ? category.charAt(0).toUpperCase() + category.slice(1) : 'Taller');

                    document.getElementById('modal-price').textContent = cost;

                    // WhatsApp desde el modal
                    const joinBtn = document.getElementById('modal-join');
                    if (joinBtn) {
                        const whatsappMessage = `Estoy interesado en el taller: ${title}`;
                        joinBtn.href = `https://wa.me/51999999999?text=${encodeURIComponent(whatsappMessage)}`;
                    }

                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });

            // Cerrar modal
            function closeModal() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            closeButtons.forEach(button => {
                button.addEventListener('click', closeModal);
            });

            const backdrop = modal.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.addEventListener('click', closeModal);
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
        });
    </script>
</body>

</html>
