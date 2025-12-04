<?php
session_start();
require_once('./db/config.php');
include './querys/selects.php';
$categoys = select("SELECT sf.id_sections_faqs,sf.section_faq FROM sections_faqs sf", $mysqli);
if (isset($_POST['id_category'])) {
  $info_faq = select("SELECT fq.title,fq.text FROM faqs fq JOIN sections_faqs sfq ON fq.id_section_faq = sfq.id_sections_faqs WHERE sfq.id_sections_faqs = " . $_POST['id_category'], $mysqli);
} else {
  $info_faq = select("SELECT fq.title,fq.text FROM faqs fq JOIN sections_faqs sfq ON fq.id_section_faq = sfq.id_sections_faqs WHERE sfq.id_sections_faqs = 1", $mysqli);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Preguntas Frecuentes | SchoolGram</title>
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
      background: #f8f9fa;
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

    .faqs-section {
      padding: 80px 0;
    }

    .faq-categories {
      margin-bottom: 50px;
    }

    .category-btn {
      background: white;
      border: none;
      padding: 15px 30px;
      margin: 10px;
      border-radius: 50px;
      font-weight: 600;
      color: var(--primary-color);
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s;
      cursor: pointer;
    }

    .category-btn:hover,
    .category-btn.active {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .faq-item {
      background: white;
      border-radius: 15px;
      margin-bottom: 20px;
      overflow: hidden;
      box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s;
    }

    .faq-item:hover {
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.12);
    }

    .faq-question {
      padding: 25px 30px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: 600;
      color: #2d3561;
      user-select: none;
    }

    .faq-question:hover {
      background: #f8f9fa;
    }

    .faq-icon {
      width: 30px;
      height: 30px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 14px;
      transition: transform 0.3s;
      flex-shrink: 0;
      margin-left: 20px;
    }

    .faq-item.active .faq-icon {
      transform: rotate(180deg);
    }

    .faq-answer {
      padding: 0 30px;
      max-height: 0;
      overflow: hidden;
      transition: all 0.4s ease;
      color: #666;
      line-height: 1.8;
    }

    .faq-item.active .faq-answer {
      padding: 0 30px 25px 30px;
      max-height: 500px;
    }

    .contact-cta {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 60px 0;
      text-align: center;
      border-radius: 20px;
      margin-top: 60px;
    }

    .contact-cta h2 {
      font-size: 36px;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .btn-white {
      background: white;
      color: var(--primary-color);
      padding: 15px 40px;
      border-radius: 50px;
      font-weight: 600;
      border: none;
      transition: all 0.3s;
      text-decoration: none;
      display: inline-block;
    }

    .btn-white:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
      color: var(--primary-color);
      text-decoration: none;
    }

    footer {
      background: #2d3561;
      color: white;
      padding: 40px 0 20px;
      margin-top: 60px;
    }

    .footer-bottom {
      text-align: center;
      padding-top: 20px;
      margin-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(255, 255, 255, 0.5);
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
      <h1><i class="fas fa-question-circle"></i> Preguntas Frecuentes</h1>
      <p>Encuentra respuestas a las dudas más comunes sobre SchoolGram</p>
    </div>
  </section>

  <!-- FAQs Section -->
  <section class="faqs-section">
    <div class="container">
      <!-- Category Buttons -->
      <div class="faq-categories text-center">
        <? foreach ($categoys as $categoy): ?>
          <form action="faqs.php" method="POST">
            <?
            if (isset($_POST['id_category'])) {
              if ($_POST['id_category'] == $categoy['id_sections_faqs']) {
                echo '<button class="category-btn active"  name="id_category" value="' . $categoy['id_sections_faqs'] . '" data-category="' . $categoy['section_faq'] . '">' . $categoy['section_faq'] . '</button>';
              } else {
                echo '<button class="category-btn" name="id_category" value="' . $categoy['id_sections_faqs'] . '" data-category="' . $categoy['section_faq'] . '">' . $categoy['section_faq'] . '</button>';
              }
            } else if ($_GET['id_category'] == $categoy['id_sections_faqs']) {
              echo '<button class="category-btn active"  name="id_category" value="' . $categoy['id_sections_faqs'] . '" data-category="' . $categoy['section_faq'] . '">' . $categoy['section_faq'] . '</button>';
            } else {
              echo '<button class="category-btn" name="id_category" value="' . $categoy['id_sections_faqs'] . '" data-category="' . $categoy['section_faq'] . '">' . $categoy['section_faq'] . '</button>';
            }



            ?>
          <? endforeach ?>
          </form>
      </div>

      <!-- FAQ Items -->
      <div class="row">
        <div class="col-lg-10 mx-auto">

          <!-- General FAQs -->
          <div class="faq-category" data-category="general">
            <? foreach ($info_faq as $faq): ?>
              <div class="faq-item">
                <div class="faq-question">
                  <span><?= $faq['title'] ?></span>
                  <div class="faq-icon">
                    <i class="fas fa-chevron-down"></i>
                  </div>
                </div>
                <div class="faq-answer">
                  <?= $faq['text'] ?>
                </div>
              </div>
            <? endforeach ?>

          </div>



          <!-- Contact CTA -->
          <? if (isset($_SESSION['user'])): ?>
            <div class="contact-cta">
              <h2>¿No encuentras tu respuesta?</h2>
              <p style="font-size: 18px; opacity: 0.9; margin-bottom: 30px;">
                Nuestro equipo está listo para ayudarte con cualquier duda
              </p>
              <a href="contact.php" class="btn-white">
                <i class="fas fa-envelope"></i> Contáctanos
              </a>
            </div>
          <? endif ?>
        </div>
  </section>

  <!-- Footer -->

  <?php include './components/footer.php'; ?>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // FAQ Toggle
    $('.faq-question').click(function() {
      $(this).parent().toggleClass('active');
    });

    // Category Filter
    $('.category-btn').click(function() {
      $('.category-btn').removeClass('active');
      $(this).addClass('active');

      var category = $(this).data('category');
      $('.faq-category').hide();
      $('.faq-category[data-category="' + category + '"]').fadeIn(400);

      // Close all open FAQs
      $('.faq-item').removeClass('active');
    });
  </script>
</body>

</html>