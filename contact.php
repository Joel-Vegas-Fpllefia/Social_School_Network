<?php
session_start();
require_once('./db/config.php');
include('./querys/selects.php');
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit();
}
$categorias = select("SELECT opt.option_name, opt.id_options_task FROM options_task opt", $mysqli);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Contacto | SchoolGram</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root {
      --primary-color: #667eea;
      --secondary-color: #764ba2;
    }

    body {
      font-family: 'Poppins', sans-serif;
      padding-top: 80px;
    }

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

    .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 500;
      margin: 0 15px;
    }

    .page-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 80px 0 40px;
      text-align: center;
    }

    .page-header h1 {
      font-size: 48px;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .contact-section {
      padding: 80px 0;
      background: #f8f9fa;
    }

    .contact-info {
      background: white;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
      height: 100%;
    }

    .contact-info h3 {
      color: var(--primary-color);
      font-weight: 700;
      margin-bottom: 30px;
    }

    .contact-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 30px;
    }

    .contact-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
      margin-right: 20px;
      flex-shrink: 0;
    }

    .contact-details h5 {
      font-weight: 600;
      color: #2d3561;
      margin-bottom: 5px;
    }

    .contact-details p {
      color: #666;
      margin: 0;
    }

    .contact-form {
      background: white;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
    }

    .contact-form h3 {
      color: var(--primary-color);
      font-weight: 700;
      margin-bottom: 30px;
    }

    .form-group label {
      font-weight: 600;
      color: #2d3561;
      margin-bottom: 10px;
    }

    .form-control {
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      padding: 12px 20px;
      transition: all 0.3s;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    textarea.form-control {
      min-height: 150px;
      resize: vertical;
    }

    .btn-submit {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border: none;
      padding: 15px 50px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s;
      width: 100%;
    }

    .btn-submit:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .map-section {
      height: 500px;
      width: 100%;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
      margin-top: 60px;
    }

    .map-section iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    .social-connect {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 60px 0;
      text-align: center;
      margin-top: 80px;
    }

    .social-connect h3 {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 30px;
    }

    .social-icons-large {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .social-icons-large a {
      width: 60px;
      height: 60px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      transition: all 0.3s;
      text-decoration: none;
    }

    .social-icons-large a:hover {
      background: white;
      color: var(--primary-color);
      transform: translateY(-5px);
    }

    footer {
      background: #2d3561;
      color: white;
      padding: 40px 0 20px;
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      margin-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.5);
    }

    @media (max-width: 768px) {

      .contact-info,
      .contact-form {
        margin-bottom: 30px;
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

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <h1><i class="fas fa-envelope"></i> Contáctanos</h1>
      <p>Estamos aquí para ayudarte a transformar tu colegio</p>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-section">
    <div class="container">
      <div class="row">
        <!-- Contact Info -->
        <div class="col-lg-5 mb-4">
          <div class="contact-info">
            <h3>Información de Contacto</h3>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div class="contact-details">
                <h5>Dirección</h5>
                <p>Passeig de Gràcia, 101<br>08008 Barcelona, España</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fas fa-phone"></i>
              </div>
              <div class="contact-details">
                <h5>Teléfono</h5>
                <p>+34 932 123 456<br>+34 932 123 457</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="contact-details">
                <h5>Email</h5>
                <p>info@schoolgram.com<br>soporte@schoolgram.com</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="contact-details">
                <h5>Horario de Atención</h5>
                <p>Lunes - Viernes: 9:00 - 18:00<br>Sábado: 10:00 - 14:00</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
          <div class="contact-form">
            <h3>Envíanos un Mensaje</h3>

            <form id="contactForm" action="./functions/create/contact.php" method="post">
              <div class="form-group">
                <label for="asunto">Asunto</label>
                <select class="form-control" name="asunto" id="asunto" required>
                  <? foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_options_task'] ?>"><?= $categoria['option_name'] ?></option>
                  <? endforeach ?>
                </select>
              </div>
              <div class="form-group">
                <label for="title">Titulo</label>
                <textarea class="form-control" name="title" id="title" required></textarea>
              </div>

              <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea class="form-control" name="mensaje" id="mensaje" required></textarea>
              </div>

              <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Enviar Mensaje
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Map -->
      <div class="map-section">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2993.2669596756043!2d2.1635612156747!3d41.395283479263514!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a4a2f5f4b5b5b5%3A0x1234567890abcdef!2sPasseig%20de%20Gr%C3%A0cia%2C%20Barcelona!5e0!3m2!1ses!2ses!4v1234567890123!5m2!1ses!2ses"
          allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </section>

  <!-- Social Connect -->
  <section class="social-connect">
    <div class="container">
      <h3>Síguenos en Redes Sociales</h3>
      <p style="font-size: 18px; opacity: 0.9; margin-bottom: 30px;">
        Mantente al día con las últimas noticias y actualizaciones
      </p>
      <div class="social-icons-large">
        <a href="#" target="_blank">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" target="_blank">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#" target="_blank">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="#" target="_blank">
          <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="#" target="_blank">
          <i class="fab fa-youtube"></i>
        </a>
        <a href="#" target="_blank">
          <i class="fab fa-whatsapp"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <?php include './components/footer.php'; ?>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>