document.addEventListener('DOMContentLoaded', function() {
    // Base de datos de posts
    const posts = [
        {
            id: 1,
            category: 'premios',
            badge: 'Premios',
            icon: 'bi-trophy-fill',
            badgeColor: 'linear-gradient(135deg, #d35400 0%, #e67e22 100%)',
            date: '15 Enero 2025',
            title: 'Gran Premio en Olimpiadas de Matemáticas',
            excerpt: 'Nuestro equipo obtuvo el primer lugar en las Olimpiadas Nacionales de Matemáticas, destacando entre más de 150 instituciones participantes.',
            content: 'Es con inmensa alegría que anunciamos que nuestro equipo de matemáticas ha obtenido el primer lugar en las Olimpiadas Nacionales de Matemáticas 2025. Este logro es el resultado del esfuerzo constante, dedicación y preparación rigurosa de nuestros estudiantes. El equipo estuvo conformado por 5 estudiantes excepcionales que demostraron no solo conocimiento matemático avanzado, sino también trabajo en equipo y perseverancia. Este triunfo nos llena de orgullo y refuerza nuestro compromiso con la excelencia académica.',
            image: 'https://images.unsplash.com/photo-1596496050827-8299e0220de1?w=800',
            author: 'Dirección Académica',
            tags: ['Matemáticas', 'Olimpiadas', 'Logros', 'Excelencia']
        },
        {
            id: 2,
            category: 'concursos',
            badge: 'Concursos',
            icon: 'bi-megaphone-fill',
            badgeColor: 'linear-gradient(135deg, #e67e22 0%, #f39c12 100%)',
            date: '12 Enero 2025',
            title: 'Convocatoria: Concurso de Oratoria 2025',
            excerpt: 'Invitamos a todos los estudiantes de 4to a 6to grado a participar en nuestro tradicional concurso de oratoria.',
            content: 'La Escuela Adonai convoca al tradicional Concurso de Oratoria 2025. Este evento busca desarrollar las habilidades comunicativas de nuestros estudiantes y fomentar la expresión oral efectiva. Los participantes podrán elegir entre diversos temas de actualidad, valores o experiencias personales. Las inscripciones están abiertas hasta el 30 de enero. El evento se realizará el 15 de febrero en el auditorio principal. Premios para los tres primeros lugares y reconocimiento especial a todos los participantes.',
            image: 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800',
            author: 'Coord. Académica',
            tags: ['Oratoria', 'Concurso', 'Inscripciones', 'Comunicación']
        },
        {
            id: 3,
            category: 'academico',
            badge: 'Académico',
            icon: 'bi-book-fill',
            badgeColor: 'linear-gradient(135deg, #c0392b 0%, #e74c3c 100%)',
            date: '10 Enero 2025',
            title: 'Inicio del Programa de Lectura 2025',
            excerpt: 'Lanzamos nuestro nuevo programa "Leer para Crecer" con más de 500 títulos disponibles para todos los grados.',
            content: 'Con gran entusiasmo inauguramos el programa "Leer para Crecer" 2025. Este programa incluye una biblioteca renovada con más de 500 nuevos títulos, clubes de lectura por grado, encuentros con autores y concursos mensuales. Nuestro objetivo es fomentar el amor por la lectura desde temprana edad. Cada estudiante recibirá una credencial de biblioteca personalizada y podrá llevar hasta 3 libros por semana. Además, implementamos un sistema de puntos que reconoce a los lectores más destacados.',
            image: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=800',
            author: 'Biblioteca Escolar',
            tags: ['Lectura', 'Programa', 'Educación', 'Biblioteca']
        },
        {
            id: 4,
            category: 'eventos',
            badge: 'Eventos',
            icon: 'bi-calendar-event-fill',
            badgeColor: 'linear-gradient(135deg, #d35400 0%, #e67e22 100%)',
            date: '08 Enero 2025',
            title: 'Festival Cultural Intercultural',
            excerpt: 'Celebramos la diversidad con danzas, música y gastronomía de diferentes regiones del país.',
            content: 'El próximo 20 de febrero realizaremos nuestro Festival Cultural Intercultural anual. Este evento celebra la riqueza cultural de nuestro país con presentaciones de danzas típicas, música folklórica, exposición gastronómica y muestras artesanales. Cada grado representará una región diferente del Perú. Invitamos a todas las familias a participar de esta celebración que promueve el respeto, la tolerancia y el orgullo por nuestras raíces. Entrada libre para toda la comunidad educativa.',
            image: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800',
            author: 'Coordinación Cultural',
            tags: ['Cultura', 'Festival', 'Diversidad', 'Tradiciones']
        },
        {
            id: 5,
            category: 'premios',
            badge: 'Premios',
            icon: 'bi-award-fill',
            badgeColor: 'linear-gradient(135deg, #d35400 0%, #e67e22 100%)',
            date: '05 Enero 2025',
            title: 'Reconocimiento a Estudiantes Destacados',
            excerpt: 'Felicitamos a los 15 estudiantes que obtuvieron el cuadro de honor del primer trimestre 2025.',
            content: 'Es un honor reconocer a los 15 estudiantes que han sido incluidos en el Cuadro de Honor del primer trimestre 2025. Estos jóvenes han demostrado excelencia académica, valores ejemplares y compromiso con su aprendizaje. El reconocimiento incluye un certificado especial, una medalla de honor y un libro seleccionado para cada estudiante. La ceremonia de premiación se realizará el viernes 18 de enero en presencia de toda la comunidad educativa. ¡Felicitaciones a todos los galardonados!',
            image: 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800',
            author: 'Dirección General',
            tags: ['Reconocimiento', 'Estudiantes', 'Excelencia', 'Valores']
        },
        {
            id: 6,
            category: 'comunidad',
            badge: 'Comunidad',
            icon: 'bi-people-fill',
            badgeColor: 'linear-gradient(135deg, #c0392b 0%, #e74c3c 100%)',
            date: '03 Enero 2025',
            title: 'Proyecto de Solidaridad Comunitaria',
            excerpt: 'Lanzamos nuestro proyecto anual de ayuda a familias vulnerables de nuestra comunidad local.',
            content: 'Iniciamos nuestro Proyecto de Solidaridad Comunitaria 2025 con el objetivo de apoyar a familias en situación de vulnerabilidad. El proyecto incluye recolección de víveres, ropa y útiles escolares. También organizaremos jornadas de voluntariado donde nuestros estudiantes podrán participar activamente. Este proyecto forma parte de nuestra formación en valores y responsabilidad social. Invitamos a toda la comunidad educativa a sumarse a esta noble causa. Las donaciones pueden realizarse en la recepción del colegio hasta el 30 de enero.',
            image: 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800',
            author: 'Depto. Pastoral',
            tags: ['Solidaridad', 'Comunidad', 'Valores', 'Voluntariado']
        },
        {
            id: 7,
            category: 'academico',
            badge: 'Académico',
            icon: 'bi-laptop-fill',
            badgeColor: 'linear-gradient(135deg, #c0392b 0%, #e74c3c 100%)',
            date: '28 Diciembre 2024',
            title: 'Talleres de Robótica y Programación',
            excerpt: 'Nuevos talleres extracurriculares de tecnología para despertar vocaciones en ciencias.',
            content: 'Anunciamos el inicio de nuestros talleres de Robótica y Programación dirigidos a estudiantes de 3ro a 6to grado. Los talleres incluyen programación básica con Scratch, robótica educativa con LEGO Mindstorms y diseño 3D. Las clases se realizarán dos veces por semana en horario extracurricular. Nuestro objetivo es desarrollar el pensamiento lógico, la creatividad y habilidades del siglo XXI. Cupos limitados. Inscripciones abiertas hasta el 15 de enero.',
            image: 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800',
            author: 'Depto. Tecnología',
            tags: ['Robótica', 'Programación', 'Tecnología', 'Innovación']
        },
        {
            id: 8,
            category: 'eventos',
            badge: 'Eventos',
            icon: 'bi-music-note-beamed',
            badgeColor: 'linear-gradient(135deg, #d35400 0%, #e67e22 100%)',
            date: '22 Diciembre 2024',
            title: 'Concierto Navideño Escolar',
            excerpt: 'Exitoso concierto navideño con la participación de coro, banda y ballet de la escuela.',
            content: 'Nuestro tradicional Concierto Navideño fue un éxito rotundo con más de 400 asistentes. El evento contó con presentaciones del coro escolar, la banda musical, el ballet y grupos de teatro. Los estudiantes deleitaron al público con villancicos tradicionales y modernos, demostrando el talento artístico de nuestra institución. Agradecemos a todos los padres de familia por su presencia y apoyo. Este tipo de eventos fortalece nuestra comunidad educativa y celebra los logros artísticos de nuestros estudiantes.',
            image: 'https://images.unsplash.com/photo-1478147427282-58a87a120781?w=800',
            author: 'Coordinación Artística',
            tags: ['Música', 'Navidad', 'Arte', 'Presentación']
        }
    ];

    // Elementos del DOM
    const blogGrid = document.getElementById('blogGrid');
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const modal = document.getElementById('blogModal');
    const modalClose = document.getElementById('modalClose');

    // Función para renderizar posts
    function renderPosts(postsToRender) {
        if (postsToRender.length === 0) {
            blogGrid.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 80px 20px;">
                    <i class="bi bi-inbox" style="font-size: 4.5rem; color: #e67e22; margin-bottom: 24px; display: block;"></i>
                    <h3 style="color: #5d4e37; margin-bottom: 12px; font-size: 1.8rem;">No se encontraron resultados</h3>
                    <p style="color: #8a7a68; font-size: 1.05rem;">Intenta con otros términos de búsqueda o filtros</p>
                </div>
            `;
            return;
        }

        blogGrid.innerHTML = '';
        postsToRender.forEach(post => {
            const card = document.createElement('div');
            card.className = 'blog-card';
            card.dataset.id = post.id;
            
            card.innerHTML = `
                <div class="blog-card-image">
                    <img src="${post.image}" alt="${post.title}" loading="lazy">
                    <div class="blog-card-badge" style="background: ${post.badgeColor}">
                        <i class="bi ${post.icon}"></i> ${post.badge}
                    </div>
                    <div class="blog-card-date">
                        <i class="bi bi-calendar3"></i> ${post.date}
                    </div>
                </div>
                <div class="blog-card-content">
                    <h3 class="blog-card-title">${post.title}</h3>
                    <p class="blog-card-excerpt">${post.excerpt}</p>
                    <div class="blog-card-tags">
                        ${post.tags.map(tag => `<span class="blog-tag"><i class="bi bi-hash"></i>${tag}</span>`).join('')}
                    </div>
                    <div class="blog-card-footer">
                        <span class="blog-card-author">
                            <i class="bi bi-person-circle"></i> ${post.author}
                        </span>
                        <span class="blog-card-read">
                            Leer más <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </div>
            `;
            
            blogGrid.appendChild(card);
            
            // Event listener individual para cada card
            card.addEventListener('click', () => openModal(post.id));
        });
    }

    // Función para abrir el modal
    function openModal(postId) {
        const post = posts.find(p => p.id === postId);
        if (!post) return;
        
        const modalBody = document.getElementById('modalBody');
        
        modalBody.innerHTML = `
            <img src="${post.image}" alt="${post.title}" class="modal-image">
            <div class="modal-body">
                <div class="modal-header">
                    <div class="blog-card-badge" style="background: ${post.badgeColor}">
                        <i class="bi ${post.icon}"></i> ${post.badge}
                    </div>
                    <div class="blog-card-date">
                        <i class="bi bi-calendar3"></i> ${post.date}
                    </div>
                </div>
                <h2 class="modal-title">${post.title}</h2>
                <div class="modal-author">
                    <div class="modal-author-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="modal-author-info">
                        <h4>${post.author}</h4>
                        <p><i class="bi bi-clock"></i> Publicado el ${post.date}</p>
                    </div>
                </div>
                <div class="modal-text">
                    <p>${post.content}</p>
                </div>
                <div class="modal-tags-section">
                    <h4><i class="bi bi-tags-fill"></i> Etiquetas:</h4>
                    <div class="blog-card-tags">
                        ${post.tags.map(tag => `<span class="blog-tag"><i class="bi bi-hash"></i>${tag}</span>`).join('')}
                    </div>
                </div>
            </div>
        `;
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Función para cerrar el modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Event listeners para filtros
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            if (filter === 'todos') {
                renderPosts(posts);
            } else {
                const filtered = posts.filter(p => p.category === filter);
                renderPosts(filtered);
            }
            
            searchInput.value = '';
        });
    });

    // Event listener para búsqueda con debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            const term = this.value.toLowerCase().trim();
            
            if (term === '') {
                const activeFilter = document.querySelector('.filter-btn.active');
                const filter = activeFilter ? activeFilter.dataset.filter : 'todos';
                
                if (filter === 'todos') {
                    renderPosts(posts);
                } else {
                    const filtered = posts.filter(p => p.category === filter);
                    renderPosts(filtered);
                }
            } else {
                const filtered = posts.filter(p => 
                    p.title.toLowerCase().includes(term) ||
                    p.excerpt.toLowerCase().includes(term) ||
                    p.content.toLowerCase().includes(term) ||
                    p.tags.some(tag => tag.toLowerCase().includes(term)) ||
                    p.author.toLowerCase().includes(term)
                );
                renderPosts(filtered);
            }
        }, 300);
    });

    // Event listeners para cerrar modal
    modalClose.addEventListener('click', closeModal);
    
    const modalOverlay = modal.querySelector('.modal-overlay');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeModal);
    }

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Renderizado inicial
    renderPosts(posts);
    
    // Animación de entrada suave
    setTimeout(() => {
        const cards = document.querySelectorAll('.blog-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.4s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 80);
        });
    }, 100);
});