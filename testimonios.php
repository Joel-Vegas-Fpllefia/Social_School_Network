<?php
session_start();
require_once('./db/config.php');
include('./querys/selects.php');
$testimonios = select(
    // EVITAMOS QUE SE MUESTRE LA OPINION DEL ADMIN QUE DA MUY EGOISTA
    "SELECT us.name,us.surname,rl.role,us.picture_profile,op.puntuacion,op.comentario from users us JOIN roles rl ON us.id_role = rl.id_role JOIN opinions op ON op.id_user = us.id_user where rl.role != 'Admin'",
    $mysqli
);

function stars($puntuacion)
{
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $puntuacion) {
            $stars .= '<i class="fas fa-star"></i>';
        } else {
            $stars .= '<i class="far fa-star"></i>';
        }
    }
    return $stars;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <title>Testimonios | SchoolGram</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --text-dark: #2d3561;
        }

        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 80px;
            background-color: #f7f9fc;
            /* Fondo general más claro */
        }

        /* --- Header y Page Header (Manteniendo tu estilo) --- */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        /* --- SECCIÓN DE TESTIMONIOS --- */

        .testimonials-section {
            padding: 80px 0;
        }

        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            /* Sombra más pronunciada pero suave */
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            /* Asegura que todas las tarjetas tengan la misma altura si están en un flex row */
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        /* Pequeño detalle de borde superior para modernidad */
        .testimonial-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 0 0 3px 3px;
        }

        .quote-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 50px;
            color: var(--primary-color);
            opacity: 0.1;
        }

        .testimonial-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .testimonial-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--secondary-color);
            /* Usamos el color secundario para el borde */
            margin-right: 15px;
            flex-shrink: 0;
        }

        .testimonial-info h4 {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 3px;
            font-size: 18px;
        }

        .testimonial-info p {
            color: #777;
            margin: 0;
            font-size: 13px;
        }

        .testimonial-rating {
            color: #ffc107;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .testimonial-text {
            color: #495057;
            line-height: 1.6;
            font-size: 15px;
        }

        /* --- Ajustes generales de sección --- */

        .stats-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 0;
            text-align: center;
        }

        .add-testimonial-section {
            padding: 80px 0;
            background-color: #eef1f9;
        }

        .testimonial-form {
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .testimonial-form .btn-primary {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <?php include './components/header.php'; ?>

    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-quote-left"></i> Testimonios</h1>
            <p>Lo que dicen los colegios que confían en SchoolGram</p>
        </div>
    </section>

    <section class="testimonials-section">
        <div class="container">
            <h2 class="text-center mb-5 text-dark" style="font-weight: 700;">Lo que dicen nuestros usuarios</h2>
            <div class="row d-flex align-items-stretch">

                <?php foreach ($testimonios as $testimonio) : ?>
                    <div class="col-lg-6 col-md-12">
                        <div class="testimonial-card">
                            <i class="fas fa-quote-right quote-icon"></i>
                            <div class="testimonial-header">
                                <img src="<?= $testimonio["picture_profile"] ?>" alt="<?= $testimonio["name"]; ?>" class="testimonial-avatar">
                                <div class="testimonial-info">
                                    <h4><?= $testimonio["name"]; ?> <?= $testimonio["surname"]; ?></h4>
                                    <p><?= $testimonio["role"]; ?></p>
                                </div>
                            </div>
                            <div class="testimonial-rating">
                                <?= stars($testimonio["puntuacion"]); ?>
                            </div>
                            <p class="testimonial-text">
                                <?= $testimonio["comentario"]; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">250+</span>
                        <span class="stat-label">Colegios Activos</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Estudiantes</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">100K+</span>
                        <span class="stat-label">Publicaciones</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Satisfacción</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="add-testimonial-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-4">¿Quieres añadir tu opinión sobre SchoolGram?</h2>
                    <p class="lead mb-4">¡Tu experiencia es importante para nosotros! Ayúdanos a mejorar y a que otros colegios nos conozcan.</p>

                    <form class="testimonial-form" action="./functions/create/opinion.php" method="post">

                        <div class="form-group">
                            <textarea class="form-control" name="comentario" rows="4" placeholder="Escribe tu testimonio aquí..." required></textarea>
                        </div>
                        <div class="form-group rating-input">
                            <label for="rating-select" class="d-block text-left mb-2">Tu calificación:</label>

                            <select class="select-rating" name="puntuacion" id="rating-select" required>
                                <option value="">-- Selecciona una puntuación --</option>
                                <option value="5">5 Estrellas - Excelente</option>
                                <option value="4">4 Estrellas - Muy Bueno</option>
                                <option value="3">3 Estrellas - Bueno</option>
                                <option value="2">2 Estrellas - Regular</option>
                                <option value="1">1 Estrella - Pobre</option>
                            </select>

                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">Enviar Mi Opinión</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include './components/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>