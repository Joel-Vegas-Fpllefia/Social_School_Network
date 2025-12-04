<?php
session_start();
require_once('./db/config.php');
include('./querys/selects.php');
$noticas = select("SELECT nw.title,nw.descr,nw.imagen,nw.fecha_creacion,ct.category FROM news nw JOIN category ct ON nw.id_category = ct.id_category ORDER BY nw.fecha_creacion DESC LIMIT 3;", $mysqli);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>SchoolGram | Red Social para Colegios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="SchoolGram: La red social para colegios. Conecta estudiantes, padres y profesores de forma segura.">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    /* Definición de variables de color */
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
    }

    /* --- ESTILOS MEJORADOS PARA EL NAVBAR (Sticky Shrink) --- */
    .navbar {
      /* Mantenemos el degradado */
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      padding: 20px 0;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      transition: all 0.4s ease;
    }

    /* Estilo para la barra de navegación al hacer scroll */
    .navbar.scrolled {
      padding: 10px 0;
      background: var(--dark-color);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    .navbar-brand {
      font-size: 28px;
      font-weight: 700;
      color: white !important;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.4s ease;
    }

    .navbar.scrolled .navbar-brand {
      font-size: 24px;
    }

    .navbar-brand i {
      font-size: 32px;
      transition: all 0.4s ease;
    }

    .navbar.scrolled .navbar-brand i {
      font-size: 28px;
    }

    /* Estilos para los enlaces de navegación generales (Inicio, Noticias, etc.) */
    .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 500;
      margin: 0 15px;
      /* Espaciado horizontal mayor para verse más ancho */
      transition: all 0.3s;
      padding: 0.5rem 0.5rem;
      display: block;
    }

    .navbar-nav .nav-link:hover {
      color: white !important;
      transform: translateY(-2px);
    }

    /* --- ESTILOS ESPECÍFICOS PARA APILAMIENTO (LOGIN/SIGNUP) --- */
    .navbar-nav .nav-item.nav-stack .nav-link {
      /* Convierte el enlace en un contenedor flexbox para apilar */
      display: flex;
      flex-direction: column;
      /* Apila el ícono y el texto verticalmente */
      align-items: center;
      /* Centra el contenido horizontalmente */
      line-height: 1.2;
      font-size: 14px;
      /* Texto inferior más pequeño */
      font-weight: 400;
      padding: 0.5rem 10px;
      margin: 0 5px;
      /* Más compacto en los laterales */
    }

    .navbar-nav .nav-item.nav-stack .nav-link i {
      font-size: 1.2rem;
      /* Ícono un poco más grande */
      margin-bottom: 2px;
      /* Pequeña separación del texto */
    }

    /* --- FIN ESTILOS NAVBAR --- */

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 120px 0 80px;
      position: relative;
      overflow: hidden;
    }

    /* ... Estilos de Hero, Features, News, CTA y Footer (Mantenidos) ... */
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
      opacity: 0.5;
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero h1 {
      font-size: 56px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 20px;
      margin-bottom: 30px;
      opacity: 0.9;
    }

    .btn-primary-custom {
      background: white;
      color: var(--primary-color);
      padding: 15px 40px;
      border-radius: 50px;
      font-weight: 600;
      border: none;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      text-decoration: none;
      display: inline-block;
    }

    .btn-primary-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
      color: var(--primary-color);
    }

    /* Features Section */
    .features {
      padding: 80px 0;
      background: white;
    }

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

    .feature-card {
      text-align: center;
      padding: 40px 30px;
      border-radius: 20px;
      transition: all 0.3s;
      height: 100%;
      background: white;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 25px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 36px;
      color: white;
    }

    .feature-card h3 {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 15px;
      color: var(--dark-color);
    }

    .news-section {
      padding: 80px 0;
      background: var(--light-color);
    }

    .news-card {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.3s;
      background: white;
      height: 100%;
    }

    .news-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .news-image {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }

    .news-content {
      padding: 25px;
    }

    .news-badge {
      display: inline-block;
      padding: 5px 15px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    .news-card h3 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 10px;
      color: var(--dark-color);
    }

    .news-meta {
      color: #999;
      font-size: 14px;
      margin-bottom: 15px;
    }

    .cta-section {
      padding: 80px 0;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      text-align: center;
    }

    .cta-section h2 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .cta-section p {
      font-size: 20px;
      margin-bottom: 30px;
      opacity: 0.9;
    }

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


    @media (max-width: 768px) {
      .hero h1 {
        font-size: 36px;
      }

      .hero p {
        font-size: 16px;
      }
    }
  </style>
</head>

<body>
  <?php
  // Usa require_once o include_once para incluir el header.
  // Asegúrate de que la ruta 'header.php' sea correcta (ej: includes/header.php si lo pusiste en una carpeta).
  include './components/header.php';
  ?>

  <section class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 hero-content">
          <h1>Conecta tu Colegio con el Mundo</h1>
          <p>La red social diseñada para colegios. Comparte momentos, logros y noticias de tu comunidad educativa de forma **segura y privada**.</p>
          <a href="singup.php" class="btn-primary-custom" role="button">
            <i class="fas fa-rocket"></i> Únete Gratis
          </a>
        </div>
        <div class="col-lg-6">
          <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600"
            alt="Grupo de estudiantes sonriendo en un salón de clases, representando la comunidad educativa."
            class="img-fluid" style="border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        </div>
      </div>
    </div>
  </section>

  <section class="features">
    <div class="container">
      <div class="section-title">
        <h2>¿Por qué SchoolGram?</h2>
        <p>La plataforma perfecta para la comunicación escolar moderna</p>
      </div>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon" aria-hidden="true">
              <i class="fas fa-images"></i>
            </div>
            <h3>Galería Visual</h3>
            <p>Comparte fotos y videos de eventos, actividades y logros de tus estudiantes de forma segura.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon" aria-hidden="true">
              <i class="fas fa-newspaper"></i>
            </div>
            <h3>Noticias en Tiempo Real</h3>
            <p>Mantén informada a toda la comunidad educativa con actualizaciones instantáneas.</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card">
            <div class="feature-icon" aria-hidden="true">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h3>100% Seguro</h3>
            <p>Entorno privado y controlado solo para la comunidad de tu colegio.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="news-section" id="noticias" aria-labelledby="news-section-title">
    <div class="container">
      <div class="section-title">
        <h2 id="news-section-title">Últimas Noticias</h2>
        <p>Descubre lo que está pasando en nuestra comunidad</p>
      </div>
      <div class="row">
        <?php foreach ($noticas as $noticia): ?>
          <div class="col-md-4 mb-4">
            <div class="news-card">
              <img src="<?= $noticia['imagen'] ?>"
                alt="<?= $noticia['title'] ?>" class="news-image">
              <div class="news-content">
                <span class="news-badge"><?= $noticia['category'] ?></span>
                <h3><?= $noticia['title'] ?></h3>
                <p class="news-meta"><i class="far fa-calendar" aria-hidden="true"></i> <?= $noticia['fecha_creacion'] ?></p>
                <p><?= $noticia['descr'] ?></p>
              </div>
            </div>
          </div>
        <? endforeach; ?>

      </div>
      <div class="text-center mt-4">
        <a href="blog.php" class="btn-primary-custom" role="button">Ver Todas las Noticias</a>
      </div>
    </div>
  </section>

  <section class="cta-section" aria-labelledby="cta-title">
    <div class="container">
      <h2 id="cta-title">¿Listo para Transformar tu Colegio?</h2>
      <p>Únete a cientos de instituciones que ya confían en SchoolGram</p>
    </div>
  </section>

  <?php include './components/footer.php'; ?>
</body>

</html>