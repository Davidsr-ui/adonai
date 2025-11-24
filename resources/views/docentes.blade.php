@extends('layouts.app')

@section('title', 'Colegio Adonai - Educación Cristiana de Excelencia')

@section('content')
    <section class="teachers-section" id="docentes">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Nuestro Equipo</span>
                <h2 class="section-title">Docentes Calificados</h2>
                <p class="section-subtitle">Profesionales comprometidos con la excelencia educativa y el desarrollo integral de nuestros estudiantes</p>
            </div>

            <?php
                use App\Models\Docente;
                $docentes = Docente::with(['persona', 'persona.user'])
                    ->whereHas('persona', function ($query) {
                        $query->where('estado', 'Activo');
                    })
                    ->get();
            ?>

            <div class="teachers-grid">
                @foreach($docentes as $docente)
                <div class="teacher-card">
                    <div class="teacher-image">
                        @php
                            $fotoPath = $docente->persona->foto_perfil;
                            if ($fotoPath && file_exists(storage_path('app/public/' . $fotoPath))) {
                                $imagenUrl = asset('storage/' . $fotoPath);
                            } else {
                                $imagenUrl = asset('img/profesor-default.jpg');
                            }
                        @endphp
                        
                        <img src="{{ $imagenUrl }}"
                             alt="{{ $docente->persona->nombres }} {{ $docente->persona->apellidos }}"
                             onerror="handleImageError(this)">
                        
                        <div class="teacher-overlay">
                            <button class="btn-contact"
                                    data-email="{{ $docente->persona->user->email ?? 'info@colegioadonai.edu.pe' }}"
                                    data-name="{{ $docente->persona->nombres }} {{ $docente->persona->apellidos }}">
                                <i class="bi bi-envelope-fill"></i> Contactar
                            </button>
                        </div>
                    </div>
                    <div class="teacher-info">
                        <h4 class="teacher-name">{{ $docente->persona->nombres }} {{ $docente->persona->apellidos }}</h4>
                        <p class="teacher-specialty">{{ $docente->especialidad ?? 'Docente Calificado' }}</p>
                        <div class="teacher-details">
                            <div class="detail-item">
                                <i class="bi bi-award-fill"></i>
                                <span>
                                    @php
                                        if ($docente->fecha_contratacion) {
                                            $años = floor(\Carbon\Carbon::parse($docente->fecha_contratacion)->diffInYears(now()));
                                            $experiencia = $años . ' año' . ($años != 1 ? 's' : '') . ' de experiencia';
                                        } else {
                                            $experiencia = 'Amplia experiencia';
                                        }
                                    @endphp
                                    {{ $experiencia }}
                                </span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-mortarboard-fill"></i>
                                <span>{{ $docente->tipo_contrato ?? 'Profesional' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($docentes->count() == 0)
                <div class="teacher-card">
                    <div class="teacher-image">
                        <img src="{{ asset('img/profesor-default.jpg') }}" 
                             alt="Docente"
                             onerror="handleImageError(this)">
                        <div class="teacher-overlay">
                            <button class="btn-contact" data-email="contacto@colegioadonai.edu.pe">
                                <i class="bi bi-envelope-fill"></i> Contactar
                            </button>
                        </div>
                    </div>
                    <div class="teacher-info">
                        <h4 class="teacher-name">Nuestro Equipo</h4>
                        <p class="teacher-specialty">Profesionales en Educación</p>
                        <div class="teacher-details">
                            <div class="detail-item">
                                <i class="bi bi-award-fill"></i>
                                <span>Amplia experiencia</span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-mortarboard-fill"></i>
                                <span>Altamente calificados</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <div id="contact-modal" class="modal" aria-hidden="true">
        <div class="modal-backdrop"></div>
        <div class="modal-panel">
            <button class="modal-close">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="modal-content">
                <div class="modal-icon">
                    <i class="bi bi-envelope-heart-fill"></i>
                </div>
                <h3 class="modal-title">Contactar Docente</h3>
                <p class="modal-email" id="teacher-email"></p>
                <p class="modal-text">Puedes enviar un correo directamente o copiar la dirección para usarla en tu cliente de correo preferido.</p>
                <div class="modal-actions">
                    <a id="email-link" class="btn btn-primary" href="">
                        <i class="bi bi-send-fill"></i> Enviar Correo
                    </a>
                    <button class="btn btn-secondary" id="copy-email">
                        <i class="bi bi-clipboard-fill"></i> Copiar Email
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function handleImageError(img) {
            if (img.dataset.errorHandled) {
                img.src = 'data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27400%27%3E%3Crect fill=%27%23667eea%27 width=%27400%27 height=%27400%27/%3E%3Cg transform=%27translate(200,200)%27%3E%3Ccircle cx=%270%27 cy=%27-30%27 r=%2750%27 fill=%27white%27 opacity=%270.3%27/%3E%3Cpath d=%27M -60,40 Q 0,20 60,40%27 stroke=%27white%27 stroke-width=%2730%27 fill=%27none%27 opacity=%270.3%27/%3E%3C/g%3E%3Ctext x=%2750%25%27 y=%2775%25%27 text-anchor=%27middle%27 fill=%27white%27 font-family=%27Arial%27 font-size=%2724%27%3EDocente%3C/text%3E%3C/svg%3E';
                img.style.objectFit = 'contain';
                return;
            }
            img.dataset.errorHandled = 'true';
            img.src = '/img/profesor-default.jpg';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const contactButtons = document.querySelectorAll('.btn-contact');
            const modal = document.getElementById('contact-modal');
            const teacherEmail = document.getElementById('teacher-email');
            const emailLink = document.getElementById('email-link');
            const copyButton = document.getElementById('copy-email');
            const closeButton = document.querySelector('.modal-close');
            const backdrop = document.querySelector('.modal-backdrop');

            contactButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const email = this.getAttribute('data-email');
                    const name = this.getAttribute('data-name') || 'Docente';
                    teacherEmail.textContent = email;
                    emailLink.href = `mailto:${email}?subject=Consulta para ${name}`;
                    modal.style.display = 'block';
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                });
            });

            copyButton.addEventListener('click', function() {
                const email = teacherEmail.textContent;
                navigator.clipboard.writeText(email).then(() => {
                    const original = copyButton.innerHTML;
                    copyButton.innerHTML = '<i class="bi bi-check-lg"></i> ¡Copiado!';
                    setTimeout(() => copyButton.innerHTML = original, 2000);
                });
            });

            function closeModal() {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = 'auto';
            }

            closeButton.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
                    closeModal();
                }
            });
        });
    </script>
@endsection