import React, { useState, useEffect } from "react";

const HeroCarousel = () => {
  const images = [
    "/img/carrusel2.png",
    "/img/carrusel3.png",
    "/img/carrusel4.png",
  ];

  const [current, setCurrent] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrent((prev) => (prev + 1) % images.length);
    }, 5000);
    return () => clearInterval(interval);
  }, [images.length]);

  return (
    <section className="hero" id="inicio">
      {/* Capas del carrusel */}
      {images.map((img, index) => (
        <div
          key={index}
          className={`hero-slide ${index === current ? "active" : ""}`}
          style={{ backgroundImage: `url(${img})` }}
        >
          <div className="hero-overlay"></div>
        </div>
      ))}

      {/* Contenido */}
      <div className="hero-content">
        <div className="hero-text">
          <h1 className="hero-title">
            <span className="word">Formando</span>
            <span className="word">Vidas</span>
            <span className="word highlight">con</span>
            <span className="word highlight">Propósito</span>
          </h1>
          <p className="hero-subtitle">
            Educación cristiana de excelencia que transforma corazones y mentes
          </p>
          <div className="hero-buttons">
            <button className="btn btn-primary">Conoce más</button>
            <button className="btn btn-secondary">Admisiones 2025</button>
          </div>
        </div>

        <div className="hero-stats">
          <div className="stat-card">
            <div className="stat-number">15+</div>
            <div className="stat-label">Años de experiencia</div>
          </div>
          <div className="stat-card">
            <div className="stat-number">500+</div>
            <div className="stat-label">Estudiantes</div>
          </div>
          <div className="stat-card">
            <div className="stat-number">98%</div>
            <div className="stat-label">Satisfacción</div>
          </div>
        </div>
      </div>

      <div className="scroll-indicator">
        <span>Explora</span>
        <div className="mouse-icon"></div>
      </div>
    </section>
  );
};

export default HeroCarousel;
