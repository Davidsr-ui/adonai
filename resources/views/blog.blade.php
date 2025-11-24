@extends('layouts.app')

@section('title', 'Colegio Adonai - Educación Cristiana de Excelencia')

@section('content')

<!-- SECCIÓN BLOG -->
<section class="blog-section">
    <div class="container">
        <div class="blog-header">
            <span class="section-tag">Blog Escolar</span>
            <h1>Noticias y Eventos</h1>
            <p>Mantente informado sobre actividades, concursos, premios y eventos.</p>
        </div>

        <!-- BUSCADOR -->
        <div class="blog-search">
            <input type="text" id="searchInput" placeholder="Buscar noticias, eventos, concursos...">
            <i class="bi bi-search"></i>
        </div>

        <!-- FILTROS -->
        <div class="blog-filters">
            <span class="filter-label"><i class="bi bi-funnel-fill"></i> Filtrar por:</span>
            <button class="filter-btn active" data-filter="todos"><i class="bi bi-grid-fill"></i> Todos</button>
            <button class="filter-btn" data-filter="concursos"><i class="bi bi-megaphone-fill"></i> Concursos</button>
            <button class="filter-btn" data-filter="premios"><i class="bi bi-trophy-fill"></i> Premios</button>
            <button class="filter-btn" data-filter="académico"><i class="bi bi-book-fill"></i> Académico</button>
            <button class="filter-btn" data-filter="eventos"><i class="bi bi-calendar-event-fill"></i> Eventos</button>
            <button class="filter-btn" data-filter="comunidad"><i class="bi bi-people-fill"></i> Comunidad</button>
        </div>

        <!-- GRID BLOG -->
        <div class="blog-grid" id="blogGrid">
            @forelse ($posts as $post)
                <div class="blog-card" data-category="{{ strtolower($post->categoria) }}">
                    <div class="blog-card-image">
                        <img src="{{ asset('storage/' . $post->portada) }}" alt="{{ $post->titulo }}">
                        <span class="blog-card-badge">
                            <i class="bi bi-bookmark-fill"></i> {{ $post->categoria }}
                        </span>
                    </div>
                    <div class="blog-card-content">
                        <h3 class="blog-card-title">{{ $post->titulo }}</h3>
                        <p class="blog-card-excerpt">{{ $post->descripcion_corta }}</p>
                        <div class="blog-card-footer">
                            <span class="blog-card-date">
                                <i class="bi bi-calendar-event"></i>
                                {{ $post->fecha->format('d M Y') }}
                            </span>
                            <span class="blog-card-author">
                                <i class="bi bi-person-fill"></i>
                                {{ $post->autor ?? 'Administrador' }}
                            </span>
                            <a href="{{ route('blog.detalle', $post->id) }}" class="blog-card-read">
                                Leer más <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted" style="grid-column: 1 / -1;">
                    No hay publicaciones aún.
                </p>
            @endforelse
        </div>

        @if(isset($posts) && method_exists($posts, 'links'))
            <div class="blog-pagination">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>

<a href="https://wa.me/51999999999" class="whatsapp-float" target="_blank">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".blog-card");
    const searchInput = document.getElementById("searchInput");
    const blogGrid = document.getElementById("blogGrid");

    function applyFilters() {
        const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        let hasVisibleCards = false;

        cards.forEach(card => {
            const category = card.dataset.category;
            const text = card.textContent.toLowerCase();
            
            const categoryMatch = activeFilter === 'todos' || category === activeFilter;
            const searchMatch = text.includes(searchTerm);
            
            if (categoryMatch && searchMatch) {
                card.style.display = 'block';
                hasVisibleCards = true;
            } else {
                card.style.display = 'none';
            }
        });

        if (!hasVisibleCards) {
            if (!document.querySelector('.no-results')) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.innerHTML = `
                    <i class="bi bi-search"></i>
                    <h3>No se encontraron resultados</h3>
                    <p>Intenta con otros términos de búsqueda o filtros</p>
                `;
                blogGrid.appendChild(noResults);
            }
        } else {
            const noResults = document.querySelector('.no-results');
            if (noResults) {
                noResults.remove();
            }
        }
    }

    filterButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            filterButtons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            applyFilters();
        });
    });

    let searchTimeout;
    searchInput.addEventListener("input", function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 300);
    });

    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>

@endsection