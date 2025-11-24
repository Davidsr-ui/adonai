// ====================================
// JS LIMPIO SIN ANIME.JS - COLEGIO ADONAI
// ====================================
import '../css/style.css';
import '../css/docentes.css';
import '../css/cursos.css';
import '../css/talleres.css';

class AdonaiWebsite {
    constructor() {
        this.navbar      = document.getElementById('navbar');
        this.navMenu     = document.getElementById('nav-menu');
        this.menuToggle  = document.getElementById('menu-toggle');
        this.btnIntranet = document.querySelector('.btn-intranet');
        this.modalIntranet = document.getElementById('modal-intranet');
        this.modalClose  = document.getElementById('modal-close');
        this.loginForm   = document.getElementById('login-form');

        this.init();
    }

    init() {
        console.log('🎓 Iniciando Colegio Adonai (sin Anime.js)…');

        this.setupNavigation();
        this.setupScrollEffects();
        this.setupParallax();
        this.setupCounters();
        this.setupCarousel();
        this.setupSmoothScroll();
        this.setupModal();

        console.log('✅ Sistema inicializado sin Anime.js');
    }

    // ====================================
    // NAVEGACIÓN Y MENÚ
    // ====================================
    setupNavigation() {
        // Navbar al hacer scroll
        window.addEventListener('scroll', () => {
            if (!this.navbar) return;
            if (window.scrollY > 50) {
                this.navbar.classList.add('scrolled');
            } else {
                this.navbar.classList.remove('scrolled');
            }
        });

        // Toggle menú móvil
        if (this.menuToggle && this.navMenu) {
            this.menuToggle.addEventListener('click', () => {
                this.navMenu.classList.toggle('active');
                this.menuToggle.classList.toggle('open');
            });
        }

        // Cerrar menú al hacer clic en un link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (this.navMenu) this.navMenu.classList.remove('active');
                if (this.menuToggle) this.menuToggle.classList.remove('open');
            });
        });

        this.highlightActiveSection();
    }

    highlightActiveSection() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        if (!sections.length || !navLinks.length) return;

        window.addEventListener('scroll', () => {
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    }

    // ====================================
    // EFECTOS DE SCROLL (BÁSICOS)
    // ====================================
    setupScrollEffects() {
        if (!('IntersectionObserver' in window)) return;

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -80px 0px' });

        const animatedElements = document.querySelectorAll(
            '.about-card, .course-card, .teacher-card, .blog-card, .workshop-item'
        );

        animatedElements.forEach(el => observer.observe(el));
    }

    // ====================================
    // PARALLAX SENCILLO (NO TOCA hero-background)
    // ====================================
    setupParallax() {
        const heroContent = document.querySelector('.hero-content');

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (ticking) return;
            ticking = true;

            window.requestAnimationFrame(() => {
                const scrolled = window.scrollY;

                if (heroContent) {
                    heroContent.style.transform = `translateY(${scrolled * 0.3}px)`;
                    heroContent.style.opacity = String(Math.max(0, 1 - scrolled / 600));
                }

                const parallaxElements = document.querySelectorAll('[data-parallax]');
                parallaxElements.forEach(element => {
                    const speed = parseFloat(element.dataset.parallax) || 0.5;
                    const yPos = -(scrolled * speed);
                    element.style.transform = `translateY(${yPos}px)`;
                });

                ticking = false;
            });
        });
    }

    // ====================================
    // CONTADORES .counter (OTRAS PÁGINAS)
    // ====================================
    setupCounters() {
        const counters = document.querySelectorAll('.counter');
        if (!counters.length) return;

        const animateCounter = (el) => {
            if (el.dataset.animated === 'true') return;
            el.dataset.animated = 'true';

            const raw = el.getAttribute('data-count') || el.textContent;
            const finalValue = parseInt((raw || '0').replace(/\D/g, ''), 10) || 0;
            const duration = 2000;
            const start = performance.now();

            function update(now) {
                const progress = Math.min((now - start) / duration, 1);
                const value = Math.floor(progress * finalValue);
                el.textContent = value.toString();

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = finalValue.toString();
                }
            }

            requestAnimationFrame(update);
        };

        if (!('IntersectionObserver' in window)) {
            counters.forEach(animateCounter);
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        counters.forEach(c => observer.observe(c));
    }

    // ====================================
    // CARRUSEL DE TALLERES (DRAG)
    // ====================================
    setupCarousel() {
        const carousel = document.querySelector('.workshops-carousel');
        if (!carousel) return;

        let isDown = false;
        let startX = 0;
        let scrollLeft = 0;

        carousel.addEventListener('mousedown', (e) => {
            isDown = true;
            carousel.classList.add('dragging');
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });

        carousel.addEventListener('mouseleave', () => {
            isDown = false;
            carousel.classList.remove('dragging');
        });

        carousel.addEventListener('mouseup', () => {
            isDown = false;
            carousel.classList.remove('dragging');
        });

        carousel.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });
    }

    // ====================================
    // SMOOTH SCROLL
    // ====================================
    setupSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') return;

                const target = document.querySelector(href);
                if (!target) return;

                e.preventDefault();

                const offsetTop = target.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            });
        });
    }

    // ====================================
    // MODAL DE INTRANET (SIN ANIMACIONES)
    // ====================================
    setupModal() {
        if (!this.btnIntranet || !this.modalIntranet) return;

        this.btnIntranet.addEventListener('click', (e) => {
            // Si el botón es un <a href="login"> no evitamos la navegación
            if (this.btnIntranet.tagName.toLowerCase() === 'a' &&
                this.btnIntranet.getAttribute('href')) {
                return;
            }

            e.preventDefault();
            this.openModal();
        });

        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => this.closeModal());
        }

        this.modalIntranet.addEventListener('click', (e) => {
            if (e.target === this.modalIntranet) {
                this.closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modalIntranet.classList.contains('active')) {
                this.closeModal();
            }
        });

        if (this.loginForm) {
            this.loginForm.addEventListener('submit', (e) => {
                // aquí podrías manejar el submit vía AJAX si quisieras
            });
        }
    }

    openModal() {
        this.modalIntranet.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeModal() {
        this.modalIntranet.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// ====================================
// ESTILOS EXTRA MÍNIMOS (SIN ANIME)
// ====================================
const style = document.createElement('style');
style.textContent = `
    .workshops-carousel {
        cursor: grab;
        user-select: none;
    }
    .workshops-carousel.dragging {
        cursor: grabbing;
    }
`;
document.head.appendChild(style);

// ====================================
// INICIALIZACIÓN
// ====================================
document.addEventListener('DOMContentLoaded', () => {
    new AdonaiWebsite();
    console.log('🎓 Colegio Adonai: script.js cargado (sin Anime.js)');
});
