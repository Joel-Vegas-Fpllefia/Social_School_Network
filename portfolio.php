<?php
session_start();
require_once('./db/config.php');
include('./querys/selects.php');
$portfolios = select("SELECT id_proyect,title,descr,image,date_proyect FROM projects", $mysqli);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>SchoolGram | Proyectos Escolares</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Galería de proyectos innovadores realizados por los estudiantes de SchoolGram.">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Importa los estilos base de tu index */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --dark-color: #2d3561;
            --light-color: #f8f9fa;
            --info-color: #00bcd4;
            /* Nuevo color para badges de proyecto */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            background-color: #f4f6f9;
            /* Fondo ligero para la galería */
            overflow-x: hidden;
        }

        /* --- Estilos Reutilizados (Navbar, Footer, etc.) --- */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
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
            padding: 0.5rem 0.5rem;
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

        footer {
            background: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
        }

        /* --- ESTILOS ESPECÍFICOS DE PROYECTOS --- */
        .projects-section {
            padding: 100px 0 80px;
            /* Padding superior ajustado para cuando no hay Hero */
            background: #f4f6f9;
        }

        .project-card {
            border-radius: 15px;
            overflow: hidden;
            background: white;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.2);
        }

        /* ************************************** */
        /* === MODIFICACIÓN PARA CENTRAR IMAGEN === */
        /* ************************************** */
        .project-image-container {
            height: 220px;
            overflow: hidden;
            /* 1. Usar Flexbox para centrar contenido */
            display: flex;
            justify-content: center;
            /* 2. Centrado horizontal */
            align-items: center;
            /* 3. Centrado vertical */
        }

        .project-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* 4. Asegura que el punto de enfoque sea el centro */
            object-position: center;
            transition: transform 0.5s ease;
        }

        .project-card:hover .project-image-container img {
            transform: scale(1.05);
        }

        /* ************************************** */
        /* ************************************** */

        .project-content {
            padding: 25px;
            flex-grow: 1;
        }

        .project-badge {
            display: inline-block;
            padding: 5px 12px;
            background: var(--info-color);
            /* Color azul/cian */
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .project-card h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .project-meta {
            color: #999;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .btn-project-details {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            /* Ocupa todo el ancho del content */
            text-align: center;
            margin-top: 15px;
        }

        .btn-project-details:hover {
            opacity: 0.9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            color: white;
        }
    </style>
</head>

<body>
    <?php include './components/header.php'; ?>

    <section class="projects-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-lightbulb" aria-hidden="true"></i> Galería de Proyectos</h2>
                <p>Explora las innovaciones y logros creativos de nuestra comunidad escolar.</p>
            </div>



            <div class="row">
                <?php
                foreach ($portfolios as $portfolio):
                ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="project-card">
                            <div class="project-image-container">
                                <img src="<?= $portfolio["image"] ?> "
                                    alt="<?= $portfolio["title"] ?>">
                            </div>
                            <div class="project-content">
                                <h3><?= $portfolio["title"] ?></h3>
                                <p class="project-meta">
                                    <i class="far fa-calendar" aria-hidden="true"></i> <?= $portfolio["date_proyect"] ?>
                                </p>
                                <p><?= $portfolio["descr"] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
    </section>

    <?php include './components/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para el efecto de scroll en el navbar (copiado de tu index)
        $(window).scroll(function() {
            if ($(document).scrollTop() > 50) {
                $('.navbar').addClass('scrolled');
            } else {
                $('.navbar').removeClass('scrolled');
            }
        });
    </script>
</body>

</html>