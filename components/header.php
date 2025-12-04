<?php
session_start();

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
            /* Aumentado el espaciado entre enlaces */
            transition: all 0.3s;
            padding: 0.5rem 0.5rem;
            display: block;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        /* --- ESTILOS PARA LOS BOTONES DE AUTENTICACIÓN (LOGIN/SIGNUP) --- */
        .btn-auth {
            min-width: 120px;
            padding: 10px 20px !important;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            text-align: center;
            line-height: 1.2;
        }

        .btn-auth i {
            font-size: 1.2rem;
            margin-bottom: 2px;
        }

        /* Asegura que los botones se vean bien en móviles */
        @media (max-width: 991.98px) {
            .btn-auth {
                min-width: 100%;
                margin-top: 10px !important;
            }
        }

        /* --- FIN ESTILOS BOTONES --- */

        /* --- NUEVOS ESTILOS PARA FOTO DE PERFIL --- */
        .profile-nav-item {
            display: flex;
            align-items: center;
            padding-left: 10px;
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            transition: border-color 0.3s, transform 0.3s;

        }

        .profile-picture:hover {
            border-color: var(--accent-color);
            transform: scale(1.05);
        }

        /* Ajuste para el separador entre el menú y el ícono de perfil */
        @media (min-width: 992px) {
            .navbar-nav:last-child {
                margin-left: 15px !important;
            }
        }

        /* --- FIN ESTILOS NAVBAR --- */

        /* Estilos de las secciones de contenido (no modificados) */
        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

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

        /* Estilos del footer */
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

        .page-top-content {
            /* Este valor debe ser mayor que la altura de tu navbar */
            padding-top: 120px;
            padding-bottom: 50px;
            background-color: var(--color-fondo-pagina);
        }

        /* Media Queries */
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
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" role="navigation">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" aria-label="Volver a la página de inicio de SchoolGram">
                <i class="fas fa-graduation-cap"></i>
                SchoolGram
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php" aria-current="page">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog.php?id_category=5">Noticias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="portfolio.php">Proyectos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="testimonios.php">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="faqs.php?id_category=1">FAQs</a>
                    </li>
                    <?php
                    if (isset($_SESSION['user'])) {
                        echo '
                            <li class="nav-item">
                                <a class="nav-link" href="contact.php">Contacto</a>
                            </li>
                            ';
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['user'])) {
                        if ($_SESSION['role'] === 'Admin') {
                            echo '
                                <li class="nav-item">
                                    <a class="nav-link" href="admin.php" style="font-weight: 700; color: var(--accent-color) !important;">
                                        <i class="fas fa-chart-line" aria-hidden="true"></i> Admin
                                    </a>
                                </li>
                            ';
                        }
                    }
                    ?>

                </ul>
                <?php
                if (!isset($_SESSION['user'])) {
                    echo '
                    <ul class="navbar-nav ml-auto">
                    <li class="nav-item ml-lg-3">
                        <a class="nav-link btn btn-auth mt-2 mt-lg-0"
                            href="login.php"
                            style="background-color: var(--secondary-color); color: white !important;">
                            <i class="fas fa-sign-in-alt d-block mx-auto mb-1"></i>
                            Iniciar Sesión
                        </a>
                    </li>

                    <li class="nav-item ml-lg-2">
                        <a class="nav-link btn btn-auth mt-2 mt-lg-0"
                            href="singup.php"
                            style="background: var(--accent-color); color: white !important; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <i class="fas fa-user-plus d-block mx-auto mb-1"></i>
                            Registrarse
                        </a>
                    </li>
                    ';
                }
                ?>


                <?php
                if (isset($_SESSION['user'])) {
                    echo '
                            
                            <li class="nav-item profile-nav-item ml-lg-3">
                                <a href="profile.php" aria-label="Configuración de usuario y perfil">
                                    <img src="' . $_SESSION['picture_profile'] . '"
                                        alt="Foto de perfil del usuario" class="profile-picture">
                                </a>
                            </li>
                            <li class="nav-item ml-lg-2">
                                <a class="nav-link btn btn-auth mt-2 mt-lg-0"
                                    href="logout.php"
                                    style="background: var(--accent-color); color: white !important; box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
                                        padding: 8px 15px !important; font-size: 14px;"> <i class="fas fa-sign-out-alt d-block mx-auto" style="font-size: 1rem;"></i> Cerrar Sesión
                                </a>
                            </li>
                        ';
                }
                ?>

                </ul>
            </div>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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