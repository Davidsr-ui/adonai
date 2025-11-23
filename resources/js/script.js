// ====================================
// SISTEMA DE ANIMACIONES MEJORADAS - COLEGIO ADONAI
// JavaScript con Anime.js para efectos profesionales
// ====================================
import '../css/style.css';
import '../css/docentes.css';
import '../css/cursos.css';
import '../css/talleres.css';
import anime from 'animejs';

class AdonaiWebsite {
    constructor() {
        this.navbar = document.getElementById('navbar');
        this.navMenu = document.getElementById('nav-menu');
        this.menuToggle = document.getElementById('menu-toggle');
        this.btnIntranet = document.querySelector('.btn-intranet');
        this.modalIntranet = document.getElementById('modal-intranet');
        this.modalClose = document.getElementById('modal-close');
        this.loginForm = document.getElementById('login-form');
        
        this.init();
    }

    init() {
        console.log('🎓 Iniciando Colegio Adonai con Anime.js...');
        
        this.setupNavigation();
        this.setupScrollEffects();
        this.setupAnimations();
        this.setupModal();
        this.setupParallax();
        this.setupCounters();      // ⬅ CONTADORES
        this.setupCarousel();
        this.setupSmoothScroll();
        this.initPageLoadAnimation();
        
        console.log('✅ Sistema inicializado correctamente');
    }

    // ====================================
    // ANIMACIÓN INICIAL DE CARGA
    // ====================================
    initPageLoadAnimation() {
        // Animar el logo del navbar
        anime({
            targets: '.navbar .logo',
            scale: [0, 1],
            rotate: ['-180deg', '0deg'],
            opacity: [0, 1],
            duration: 1200,
            easing: 'easeOutElastic(1, .8)'
        });

        // Animar los links del navbar
        anime({
            targets: '.nav-link',
            translateY: [-50, 0],
            opacity: [0, 1],
            delay: anime.stagger(100, {start: 300}),
            duration: 800,
            easing: 'easeOutExpo'
        });

        // Animar el botón de intranet
        anime({
            targets: '.btn-intranet',
            scale: [0, 1],
            opacity: [0, 1],
            delay: 800,
            duration: 600,
            easing: 'easeOutBack'
        });
    }

    // ====================================
    // NAVEGACIÓN Y MENÚ
    // ====================================
    setupNavigation() {
        // Cambio de estilo del navbar al hacer scroll con animación
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                this.navbar.classList.add('scrolled');
                anime({
                    targets: this.navbar,
                    backgroundColor: ['rgba(255, 255, 255, 0)', 'rgba(255, 255, 255, 0.98)'],
                    boxShadow: ['0 0 0 rgba(0,0,0,0)', '0 4px 20px rgba(0,0,0,0.1)'],
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            } else {
                this.navbar.classList.remove('scrolled');
            }
        });

        // Toggle del menú móvil
        if (this.menuToggle) {
            this.menuToggle.addEventListener('click', () => {
                const isActive = this.navMenu.classList.contains('active');
                this.navMenu.classList.toggle('active');
                this.animateMenuToggle(isActive);
            });
        }

        // Cerrar menú al hacer clic en un link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                this.navMenu.classList.remove('active');
            });
        });

        // Resaltar link activo según sección visible
        this.highlightActiveSection();
    }

    animateMenuToggle(isActive) {
        const spans = this.menuToggle.querySelectorAll('span');
        
        if (!isActive) {
            // Abrir menú
            anime({
                targets: spans[0],
                rotate: '45deg',
                translateY: 10,
                duration: 300,
                easing: 'easeOutQuad'
            });
            anime({
                targets: spans[1],
                opacity: 0,
                duration: 200,
                easing: 'linear'
            });
            anime({
                targets: spans[2],
                rotate: '-45deg',
                translateY: -10,
                duration: 300,
                easing: 'easeOutQuad'
            });

            // Animar items del menú
            anime({
                targets: '.nav-link',
                translateX: [-50, 0],
                opacity: [0, 1],
                delay: anime.stagger(50),
                duration: 400,
                easing: 'easeOutExpo'
            });
        } else {
            // Cerrar menú
            anime({
                targets: spans,
                rotate: 0,
                translateY: 0,
                opacity: 1,
                duration: 300,
                easing: 'easeOutQuad'
            });
        }
    }

    highlightActiveSection() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

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
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                    anime({
                        targets: link,
                        scale: [1, 1.1, 1],
                        duration: 300,
                        easing: 'easeOutQuad'
                    });
                }
            });
        });
    }

    // ====================================
    // EFECTOS DE SCROLL CON ANIME.JS
    // ====================================
    setupScrollEffects() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    entry.target.dataset.animated = 'true';
                    
                    // Animación con Anime.js
                    anime({
                        targets: entry.target,
                        translateY: [100, 0],
                        opacity: [0, 1],
                        scale: [0.8, 1],
                        duration: 1000,
                        easing: 'easeOutExpo',
                        complete: () => {
                            // Animar elementos hijos
                            const children = entry.target.querySelectorAll('.animate-child, h3, p, .btn');
                            if (children.length > 0) {
                                anime({
                                    targets: children,
                                    translateY: [30, 0],
                                    opacity: [0, 1],
                                    delay: anime.stagger(100),
                                    duration: 600,
                                    easing: 'easeOutQuad'
                                });
                            }
                        }
                    });
                }
            });
        }, observerOptions);

        // Observar elementos
        const animatedElements = document.querySelectorAll('.about-card, .course-card, .teacher-card, .blog-card, .workshop-item');
        animatedElements.forEach(el => observer.observe(el));
    }

    // ====================================
    // ANIMACIONES PERSONALIZADAS CON ANIME.JS
    // ====================================
    setupAnimations() {
        this.animateHeroWords();
        this.setupCardAnimations();
        this.setupImageParallax();
        this.animateButtons();
    }

    animateHeroWords() {
        const heroTitle = document.querySelector('.hero-title');
        if (!heroTitle) return;

        const text = heroTitle.textContent;
        heroTitle.innerHTML = '';
        
        // Dividir en palabras
        const words = text.split(' ');
        words.forEach(word => {
            const span = document.createElement('span');
            span.className = 'word';
            span.style.display = 'inline-block';
            span.style.opacity = '0';
            span.textContent = word + ' ';
            heroTitle.appendChild(span);
        });

        // Animar palabras
        anime({
            targets: '.hero-title .word',
            translateY: [100, 0],
            opacity: [0, 1],
            rotateX: [-90, 0],
            delay: anime.stagger(150, {start: 500}),
            duration: 1200,
            easing: 'easeOutExpo'
        });

        // Animar subtítulo
        const heroSubtitle = document.querySelector('.hero-subtitle');
        if (heroSubtitle) {
            anime({
                targets: heroSubtitle,
                translateY: [50, 0],
                opacity: [0, 1],
                delay: 1500,
                duration: 1000,
                easing: 'easeOutQuad'
            });
        }

        // Animar botones del hero
        const heroButtons = document.querySelectorAll('.hero-buttons .btn');
        anime({
            targets: heroButtons,
            scale: [0, 1],
            opacity: [0, 1],
            delay: anime.stagger(200, {start: 2000}),
            duration: 800,
            easing: 'easeOutElastic(1, .6)'
        });
    }

    setupCardAnimations() {
        const cards = document.querySelectorAll('.about-card, .course-card, .teacher-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', (e) => {
                this.animateCardHover(e.currentTarget, true);
            });
            
            card.addEventListener('mouseleave', (e) => {
                this.animateCardHover(e.currentTarget, false);
            });
        });
    }

    animateCardHover(card, isEnter) {
        if (isEnter) {
            anime({
                targets: card,
                scale: 1.05,
                boxShadow: '0 20px 60px rgba(0,0,0,0.3)',
                duration: 400,
                easing: 'easeOutQuad'
            });

            // Animar icono o imagen dentro de la tarjeta
            const icon = card.querySelector('.icon, .course-image, .teacher-image');
            if (icon) {
                anime({
                    targets: icon,
                    rotate: '5deg',
                    scale: 1.1,
                    duration: 400,
                    easing: 'easeOutQuad'
                });
            }

            // Crear partículas brillantes
            this.createSparkles(card);
        } else {
            anime({
                targets: card,
                scale: 1,
                boxShadow: '0 10px 30px rgba(0,0,0,0.1)',
                duration: 400,
                easing: 'easeOutQuad'
            });

            const icon = card.querySelector('.icon, .course-image, .teacher-image');
            if (icon) {
                anime({
                    targets: icon,
                    rotate: '0deg',
                    scale: 1,
                    duration: 400,
                    easing: 'easeOutQuad'
                });
            }
        }
    }

    createSparkles(element) {
        const sparkleCount = 8;
        
        for (let i = 0; i < sparkleCount; i++) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            sparkle.style.cssText = `
                position: absolute;
                width: 6px;
                height: 6px;
                background: linear-gradient(45deg, #FFD700, #FFA500);
                border-radius: 50%;
                pointer-events: none;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
                z-index: 10;
            `;
            
            element.appendChild(sparkle);
            
            anime({
                targets: sparkle,
                translateY: [0, -50],
                translateX: [0, (Math.random() - 0.5) * 40],
                scale: [0, 1, 0],
                opacity: [1, 1, 0],
                duration: 1000,
                easing: 'easeOutQuad',
                complete: () => sparkle.remove()
            });
        }
    }

    setupImageParallax() {
        const images = document.querySelectorAll('.course-image, .blog-image, .teacher-image');
        
        images.forEach(img => {
            img.addEventListener('mousemove', (e) => {
                const rect = img.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width - 0.5) * 20;
                const y = ((e.clientY - rect.top) / rect.height - 0.5) * 20;
                
                anime({
                    targets: img,
                    translateX: x,
                    translateY: y,
                    scale: 1.1,
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });
            
            img.addEventListener('mouseleave', () => {
                anime({
                    targets: img,
                    translateX: 0,
                    translateY: 0,
                    scale: 1,
                    duration: 500,
                    easing: 'easeOutElastic(1, .6)'
                });
            });
        });
    }

    animateButtons() {
        const buttons = document.querySelectorAll('.btn, button');
        
        buttons.forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                anime({
                    targets: btn,
                    scale: 1.08,
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });
            
            btn.addEventListener('mouseleave', () => {
                anime({
                    targets: btn,
                    scale: 1,
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });

            btn.addEventListener('click', function() {
                anime({
                    targets: this,
                    scale: [1, 0.95, 1.05, 1],
                    duration: 400,
                    easing: 'easeOutQuad'
                });
            });
        });
    }

    // ====================================
    // MODAL DE INTRANET CON ANIME.JS
    // ====================================
    setupModal() {
        if (!this.btnIntranet || !this.modalIntranet) return;

        this.btnIntranet.addEventListener('click', () => {
            this.openModal();
        });

        if (this.modalClose) {
            this.modalClose.addEventListener('click', () => {
                this.closeModal();
            });
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
                e.preventDefault();
                this.handleLogin();
            });
        }
    }

    openModal() {
        this.modalIntranet.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        const modalContent = this.modalIntranet.querySelector('.modal-content');
        
        // Animar fondo del modal
        anime({
            targets: this.modalIntranet,
            opacity: [0, 1],
            duration: 300,
            easing: 'linear'
        });

        // Animar contenido del modal
        anime({
            targets: modalContent,
            scale: [0.7, 1],
            opacity: [0, 1],
            duration: 500,
            easing: 'easeOutBack'
        });

        // Animar campos del formulario
        const formGroups = this.modalIntranet.querySelectorAll('.form-group');
        anime({
            targets: formGroups,
            translateY: [30, 0],
            opacity: [0, 1],
            delay: anime.stagger(100, {start: 400}),
            duration: 600,
            easing: 'easeOutQuad'
        });

        // Animar botón
        const loginBtn = this.modalIntranet.querySelector('.btn-login');
        if (loginBtn) {
            anime({
                targets: loginBtn,
                scale: [0, 1],
                opacity: [0, 1],
                delay: 800,
                duration: 500,
                easing: 'easeOutElastic(1, .6)'
            });
        }
    }

    closeModal() {
        const modalContent = this.modalIntranet.querySelector('.modal-content');
        
        anime({
            targets: modalContent,
            scale: [1, 0.7],
            opacity: [1, 0],
            duration: 300,
            easing: 'easeInQuad'
        });

        anime({
            targets: this.modalIntranet,
            opacity: [1, 0],
            duration: 300,
            easing: 'linear',
            complete: () => {
                this.modalIntranet.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    handleLogin() {
        const button = this.loginForm.querySelector('.btn-login');
        const originalText = button.textContent;
        
        button.textContent = 'Iniciando sesión...';
        button.disabled = true;
        
        // Animación de loading en el botón
        anime({
            targets: button,
            scale: [1, 0.95, 1],
            duration: 1000,
            easing: 'easeInOutQuad',
            loop: true
        });
        
        setTimeout(() => {
            this.showNotification('¡Bienvenido! Redirigiendo...', 'success');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
                this.closeModal();
            }, 1500);
        }, 2000);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: -300px;
            background: ${type === 'success' ? '#4CAF50' : '#FFD700'};
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            z-index: 3000;
        `;
        
        document.body.appendChild(notification);
        
        anime({
            targets: notification,
            right: ['-300px', '20px'],
            opacity: [0, 1],
            duration: 500,
            easing: 'easeOutBack',
            complete: () => {
                setTimeout(() => {
                    anime({
                        targets: notification,
                        right: ['20px', '-300px'],
                        opacity: [1, 0],
                        duration: 500,
                        easing: 'easeInQuad',
                        complete: () => notification.remove()
                    });
                }, 3000);
            }
        });
    }

    // ====================================
    // EFECTO PARALLAX
    // ====================================
    setupParallax() {
        let ticking = false;
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const scrolled = window.scrollY;
                    
                    const heroContent = document.querySelector('.hero-content');
                    if (heroContent) {
                        heroContent.style.transform = `translateY(${scrolled * 0.3}px)`;
                        heroContent.style.opacity = Math.max(0, 1 - (scrolled / 600));
                    }
                    
                    const parallaxElements = document.querySelectorAll('[data-parallax]');
                    parallaxElements.forEach(element => {
                        const speed = parseFloat(element.dataset.parallax) || 0.5;
                        const yPos = -(scrolled * speed);
                        element.style.transform = `translateY(${yPos}px)`;
                    });
                    
                    ticking = false;
                });
                
                ticking = true;
            }
        });
    }

    // ====================================
    // CONTADORES ANIMADOS CON ANIME.JS
    // ====================================
    setupCounters() {
        // Soportar tanto .stat-number como .counter
        // Solo para otras páginas, NO tocar los contadores del home
        const counters = document.querySelectorAll('.counter');
        if (!counters.length) return;

        // Si no hay IntersectionObserver, animar directo
        if (!('IntersectionObserver' in window)) {
            counters.forEach(counter => this.animateCounter(counter));
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    entry.target.dataset.animated = 'true';
                    this.animateCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        counters.forEach(counter => observer.observe(counter));
    }

    animateCounter(counterElement) {
        // data-count siempre manda; si no, toma el texto
        const rawValue = counterElement.getAttribute('data-count') || counterElement.textContent;
        const finalValue = parseInt(rawValue.toString().replace(/\D/g, ''), 10) || 0;

        // empezar visualmente en 0
        counterElement.textContent = '0';

        const counterObject = { current: 0 };

        anime({
            targets: counterObject,
            current: finalValue,
            duration: 2000,
            easing: 'easeOutExpo',
            round: 1,
            update: () => {
                counterElement.textContent = counterObject.current;
            }
        });
    }

    // ====================================
    // CARRUSEL DE TALLERES CON ANIME.JS
    // ====================================
    setupCarousel() {
        const carousel = document.querySelector('.workshops-carousel');
        if (!carousel) return;
        
        let isDown = false;
        let startX;
        let scrollLeft;
        
        carousel.addEventListener('mousedown', (e) => {
            isDown = true;
            carousel.style.cursor = 'grabbing';
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });
        
        carousel.addEventListener('mouseleave', () => {
            isDown = false;
            carousel.style.cursor = 'grab';
        });
        
        carousel.addEventListener('mouseup', () => {
            isDown = false;
            carousel.style.cursor = 'grab';
        });
        
        carousel.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });

        // Animar items del taller cuando entren en vista
        const workshopItems = document.querySelectorAll('.workshop-item');
        const workshopObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animated) {
                    entry.target.dataset.animated = 'true';
                    anime({
                        targets: entry.target,
                        scale: [0.8, 1],
                        opacity: [0, 1],
                        duration: 600,
                        easing: 'easeOutQuad'
                    });
                }
            });
        }, { threshold: 0.3 });

        workshopItems.forEach(item => workshopObserver.observe(item));
    }

    // ====================================
    // SMOOTH SCROLL
    // ====================================
    setupSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href === '#') return;

                const target = document.querySelector(href);
                if (!target) return;

                e.preventDefault();
                
                const offsetTop = target.offsetTop - 80;
                
                anime({
                    targets: [document.documentElement, document.body],
                    scrollTop: offsetTop,
                    duration: 1000,
                    easing: 'easeInOutQuad'
                });
            });
        });
    }

    // ====================================
    // EFECTOS DE PARTÍCULAS FLOTANTES
    // ====================================
    createParticles() {
        const hero = document.querySelector('.hero');
        if (!hero) return;
        
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'particles-container';
        particlesContainer.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        `;
        
        hero.style.position = 'relative';
        hero.appendChild(particlesContainer);
        
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.cssText = `
                position: absolute;
                width: ${Math.random() * 8 + 3}px;
                height: ${Math.random() * 8 + 3}px;
                background: radial-gradient(circle, rgba(255,215,0,0.8) 0%, rgba(255,215,0,0) 70%);
                border-radius: 50%;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
            `;
            
            particlesContainer.appendChild(particle);
            
            // Animar partículas
            anime({
                targets: particle,
                translateY: [0, -100],
                translateX: () => anime.random(-50, 50),
                opacity: [0, 0.8, 0],
                scale: [0.5, 1.2, 0.5],
                duration: () => anime.random(3000, 6000),
                easing: 'easeInOutQuad',
                loop: true,
                delay: () => anime.random(0, 3000)
            });
        }
    }
}

// ====================================
// ANIMACIONES CSS ADICIONALES
// ====================================
const style = document.createElement('style');
style.textContent = `
    .workshops-carousel {
        cursor: grab;
        user-select: none;
    }
    
    .workshops-carousel:active {
        cursor: grabbing;
    }

    .word {
        display: inline-block;
        transform-origin: center;
    }

    * {
        scroll-behavior: smooth;
    }
`;
document.head.appendChild(style);

// ====================================
// INICIALIZACIÓN
// ====================================
document.addEventListener('DOMContentLoaded', () => {
    const website = new AdonaiWebsite();
    
    // Crear partículas decorativas
    website.createParticles();
    
    // Easter egg: Konami Code
    let konamiCode = [];
    const konamiPattern = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
    
    document.addEventListener('keydown', (e) => {
        konamiCode.push(e.key);
        konamiCode = konamiCode.slice(-10);
        
        if (konamiCode.join(',') === konamiPattern.join(',')) {
            website.showNotification('🎉 ¡Código secreto activado! 🎓', 'success');
            
            // Efecto arcoíris en todo el sitio
            anime({
                targets: 'body',
                filter: ['hue-rotate(0deg)', 'hue-rotate(360deg)'],
                duration: 3000,
                easing: 'linear',
                loop: 3
            });
        }
    });
    
    console.log('🎓 Colegio Adonai - Sistema completamente cargado con Anime.js');
});

// ====================================
// PERFORMANCE OPTIMIZATION
// ====================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    
                    // Animar imagen al cargar
                    anime({
                        targets: img,
                        opacity: [0, 1],
                        scale: [0.9, 1],
                        duration: 800,
                        easing: 'easeOutQuad'
                    });
                }
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}
