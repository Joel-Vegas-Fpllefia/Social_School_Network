<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>SchoolGram | Acerca de Nosotros</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* INICIO DEL BLOQUE DE ESTILOS COPIADO DE INDEX.HTML */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root {
      --primary-color: #667eea;
      --secondary-color: #764ba2;
      --accent-color: #f093fb;
      --dark-color: #2d3561;
      --light-color: #f8f9fa;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      color: #333;
      overflow-x: hidden;
      padding-top: 88px;
      /* Ajuste para el navbar fijo */
    }

    /* Navbar */
    .navbar {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      padding: 20px 0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-size: 28px;
      font-weight: 700;
      color: white !important;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .navbar-brand i {
      font-size: 32px;
    }

    .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 500;
      margin: 0 15px;
      transition: all 0.3s;
    }

    .navbar-nav .nav-link:hover {
      color: white !important;
      transform: translateY(-2px);
    }

    /* Hero Section (Ajustado para páginas secundarias) */
    .page-hero {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      /* SOLUCIÓN: Aumentar el padding superior para bajar el contenido */
      padding: 100px 0 60px;
      text-align: center;
      position: relative;
      margin-top: -88px;
      /* Compensar padding-top del body */
    }

    .page-hero h1 {
      font-size: 48px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .page-hero p {
      font-size: 18px;
      opacity: 0.9;
    }

    /* Reutilización de estilos de título */
    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: 42px;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 15px;
    }

    .section-title p {
      font-size: 18px;
      color: #666;
    }

    /* Footer */
    footer {
      background: var(--dark-color);
      color: white;
      padding: 60px 0 30px;
    }

    .footer-logo {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .footer-links h5 {
      font-weight: 600;
      margin-bottom: 20px;
    }

    .footer-links ul {
      list-style: none;
      padding: 0;
    }

    .footer-links ul li {
      margin-bottom: 10px;
    }

    .footer-links ul li a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: all 0.3s;
    }

    .footer-links ul li a:hover {
      color: white;
      padding-left: 5px;
    }

    .social-icons {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .social-icons a {
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      transition: all 0.3s;
    }

    .social-icons a:hover {
      background: var(--primary-color);
      transform: translateY(-3px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 30px;
      margin-top: 30px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.5);
    }

    /* NUEVOS ESTILOS ESPECÍFICOS PARA ACERCA DE */
    .about-section {
      padding: 80px 0;
      background: white;
    }

    .vision-mission {
      background: var(--light-color);
      padding: 80px 0;
    }

    .vision-card {
      padding: 30px;
      border-radius: 15px;
      height: 100%;
      transition: all 0.3s;
    }

    .vision-card i {
      font-size: 36px;
      margin-bottom: 15px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .vision-card h3 {
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 15px;
    }

    .team-section {
      padding: 80px 0;
    }

    .team-member {
      text-align: center;
      padding: 20px;
      border-radius: 10px;
      transition: transform 0.3s;
    }

    .team-member:hover {
      transform: translateY(-5px);
    }

    .team-member img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 15px;
      border: 5px solid var(--light-color);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .team-member h4 {
      font-size: 20px;
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 5px;
    }

    .team-member p {
      color: var(--primary-color);
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .page-hero h1 {
        font-size: 36px;
      }

      .page-hero p {
        font-size: 16px;
      }

      .section-title h2 {
        font-size: 32px;
      }
    }

    /* FIN DEL BLOQUE DE ESTILOS COPIADO DE INDEX.HTML */
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.html">
        <i class="fas fa-graduation-cap"></i>
        SchoolGram
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.html">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="portfolio.html">Portfolio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="testimonios.html">Testimonios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="faqs.html">FAQs</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="acerca-de.html">Acerca de</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contacto.html">Contacto</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="page-hero">
    <div class="container">
      <h1>Nuestra Historia Educativa</h1>
      <p>Conoce la misión, la visión y el equipo detrás de SchoolGram.</p>
    </div>
  </section>

  <section class="about-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <img src="https://images.unsplash.com/photo-1543269664-7eef42226a2e?w=600" alt="Historia SchoolGram"
            class="img-fluid" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
        </div>
        <div class="col-lg-6">
          <span class="news-badge mb-3">#QuienesSomos</span>
          <h2>Revolucionando la Comunicación Escolar</h2>
          <p class="lead text-secondary">
            SchoolGram nació en 2020 de la necesidad de crear un puente seguro y eficaz entre la institución, los estudiantes y los padres de familia.
          </p>
          <p>
            Cansados de las herramientas de comunicación dispersas y poco atractivas, diseñamos una plataforma que centraliza noticias, logros y eventos en un entorno privado, exclusivo para la comunidad educativa. Hoy, somos la red social de confianza para cientos de colegios.
          </p>
          <ul>
            <li><i class="fas fa-check-circle text-primary mr-2"></i> Comunicación Centralizada</li>
            <li><i class="fas fa-check-circle text-primary mr-2"></i> Enfoque en la Seguridad del Estudiante</li>
            <li><i class="fas fa-check-circle text-primary mr-2"></i> Diseño Intuitivo y Moderno</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="vision-mission">
    <div class="container">
      <div class="section-title">
        <h2>Nuestra Visión y Misión</h2>
        <p>Guiados por el compromiso con la excelencia educativa y la seguridad.</p>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="vision-card bg-white shadow-sm">
            <i class="fas fa-bullseye"></i>
            <h3>Misión</h3>
            <p>Proveer la plataforma de comunicación más segura y eficiente para que las instituciones educativas fortalezcan su comunidad y compartan el valor de sus logros diarios.</p>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="vision-card bg-white shadow-sm">
            <i class="fas fa-lightbulb"></i>
            <h3>Visión</h3>
            <p>Ser la red social educativa líder a nivel global, unificando a millones de estudiantes, padres y educadores en una única plataforma de interacción positiva y enriquecedora.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="team-section">
    <div class="container">
      <div class="section-title">
        <h2>Conoce a Nuestro Equipo</h2>
        <p>Los profesionales que hacen posible SchoolGram</p>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="team-member">
            <img src="https://images.unsplash.com/photo-1507003211169-0a812380dd61?w=150" alt="Miembro del Equipo 1">
            <h4>Roberto Sáenz</h4>
            <p>Fundador & CEO</p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="team-member">
            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150" alt="Miembro del Equipo 2">
            <h4>Elena Gutiérrez</h4>
            <p>Directora de Producto</p>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="team-member">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150" alt="Miembro del Equipo 3">
            <h4>Marta Navarro</h4>
            <p>Jefa de Marketing</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="footer-logo">
            <i class="fas fa-graduation-cap"></i>
            SchoolGram
          </div>
          <p>La red social educativa que conecta colegios, estudiantes y familias.</p>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="footer-links">
            <h5>Enlaces Rápidos</h5>
            <ul>
              <li><a href="index.html">Inicio</a></li>
              <li><a href="portfolio.html">Portfolio</a></li>
              <li><a href="testimonios.html">Testimonios</a></li>
              <li><a href="faqs.html">Preguntas Frecuentes</a></li>
              <li><a href="acerca-de.html">Acerca de</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="footer-links">
            <h5>Contacto</h5>
            <ul>
              <li><i class="fas fa-map-marker-alt"></i> Barcelona, España</li>
              <li><i class="fas fa-phone"></i> +34 123 456 789</li>
              <li><i class="fas fa-envelope"></i> info@schoolgram.com</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2024 SchoolGram. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>