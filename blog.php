<?php
session_start();
require_once('./db/config.php');
include('./querys/selects.php');
$categorias = select("SELECT id_category,category FROM category", $mysqli);

/* El 5 es la categoria de Todas, ya que cuando fui hacerlo se me olvido este campo y como es autoincremental se introdujo el id 5 en la Db */
function indicar_section($categorias, $categoria_to_show)
{

    foreach ($categorias as $categoria) {

        if ($categoria["id_category"] == $categoria_to_show) {
            echo "
                    <form method='POST' action='blog.php?id_category=" . $categoria["id_category"] . "' class='d-inline-block m-1'>
                        <input type='hidden' name='id_category' value='" . $categoria["id_category"] . "'>
                        <button type='submit' class='filter-btn active' data-filter='all'>" . $categoria["category"] . "</button>
                    </form>
                ";
        } else {
            echo "
                <form method='POST' action='blog.php?id_category=" . $categoria["id_category"] . "' class='d-inline-block m-1'>
                    <input type='hidden' name='id_category' value='" . $categoria["id_category"] . "'>
                    <button type='submit' class='filter-btn' data-filter='all'>" . $categoria["category"] . "</button>
                </form>
            ";
        }
    }
}

function gestionar_a_mostrar_noticias($mysqli)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        /* Si es true es que hay que filtrar */
        if (validar_numero_post($_POST['id_category'])) {
            mostrar_section_filtrada($mysqli, $_POST['id_category']);
        } else {
            mostrar_section_todas($mysqli);
        }
    } else {
        mostrar_section_todas($mysqli);
    }
}
function validar_numero_post($numero)
{
    $bool_number = true;
    if ($numero == 5) {
        $bool_number = false;
    }
    return $bool_number;
}

function mostrar_section_todas($mysqli)
{
    $noticias = select("SELECT nw.id_news, nw.title, nw.descr,nw.imagen, COUNT(cm.text_comment) AS cantidad_comentarios FROM news nw  LEFT JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news LEFT JOIN comment cm ON cm.id_comment = nrc.id_comment GROUP BY nw.id_news", $mysqli);
    mostrar_targetas_noticias($noticias);
}

function mostrar_section_filtrada($mysqli, $number_section)
{
    $noticias = select("SELECT nw.id_news, nw.title, nw.descr,nw.imagen, COUNT(cm.text_comment) AS cantidad_comentarios FROM news nw LEFT JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news LEFT JOIN comment cm ON cm.id_comment = nrc.id_comment WHERE nw.id_category = '" . $number_section . "' GROUP BY nw.id_news", $mysqli);
    mostrar_targetas_noticias($noticias);
}

function mostrar_targetas_noticias($noticias)
{
    foreach ($noticias as $noticia) {
        echo '
               <div class="col-md-4 portfolio-col" data-category="eventos">
                    <div class="portfolio-item">
                        <img src="' . $noticia['imagen'] . '" alt="' . $noticia['title'] . '">
                        <div class="portfolio-overlay">
                            <h3>' . $noticia['title'] . '</h3>
                            <p>' . $noticia['descr'] . '</p>
                            <div class="portfolio-meta">
                                <span><i class="fas fa-comment"></i>' . $noticia['cantidad_comentarios'] . '</span>
                            </div>
                            <form method="POST" action="noticia.php" class="mt-2">
                                <input type="hidden" name="id_news" value="' . $noticia['id_news'] . '">
                                <button type="submit" class="btn btn-primary btn-sm">Ver más detalles</button>
                            </form>
                        </div>
                    </div>
                </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Portfolio | SchoolGram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./components/header.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        .filter-buttons {
            padding: 40px 0;
            text-align: center;
            background: #f8f9fa;
        }

        .filter-btn {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 10px 30px;
            margin: 5px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            transform: translateY(-2px);
        }

        .portfolio-grid {
            padding: 60px 0;
        }

        .portfolio-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            margin-bottom: 30px;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .portfolio-item img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .portfolio-item:hover img {
            transform: scale(1.1);
        }

        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(102, 126, 234, 0.95), rgba(118, 75, 162, 0.95));
            opacity: 0;
            transition: all 0.4s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }

        .portfolio-overlay h3 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            transform: translateY(20px);
            transition: transform 0.4s 0.1s;
        }

        .portfolio-item:hover .portfolio-overlay h3 {
            transform: translateY(0);
        }

        .portfolio-overlay p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-bottom: 15px;
            transform: translateY(20px);
            transition: transform 0.4s 0.2s;
        }

        .portfolio-item:hover .portfolio-overlay p {
            transform: translateY(0);
        }

        .portfolio-meta {
            display: flex;
            gap: 20px;
            color: white;
            font-size: 14px;
            transform: translateY(20px);
            transition: transform 0.4s 0.3s;
        }

        .portfolio-item:hover .portfolio-meta {
            transform: translateY(0);
        }

        .portfolio-meta i {
            margin-right: 5px;
        }

        /* Modal */
        .modal-content {
            border-radius: 20px;
            overflow: hidden;
        }

        .modal-body {
            padding: 0;
        }

        .modal-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
        }

        .modal-info {
            padding: 30px;
        }

        .modal-info h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 15px;
        }

        .modal-stats {
            display: flex;
            gap: 30px;
            margin: 20px 0;
            padding: 20px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .modal-stats div {
            text-align: center;
        }

        .modal-stats i {
            color: var(--primary-color);
            font-size: 24px;
            margin-bottom: 10px;
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
    <?php include 'components/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-images"></i> Noticias Escolar</h1>
            <p>Descubre los mejores momentos de nuestra comunidad educativa</p>
        </div>
    </section>

    <!-- Filter Buttons -->
    <section class="filter-buttons">
        <div class="container">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                indicar_section($categorias, $_POST['id_category']);
            } else {
                indicar_section($categorias, 5);
            }
            ?>
        </div>
    </section>

    <!-- Portfolio Grid -->
    <section class="portfolio-grid">
        <div class="container">
            <div class="row" id="portfolioContainer">
                <!-- Item 1 -->
                <?php gestionar_a_mostrar_noticias($mysqli); ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Filter functionality
        $('.filter-btn').click(function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');

            var filter = $(this).data('filter');

            if (filter === 'all') {
                $('.portfolio-col').fadeIn(400);
            } else {
                $('.portfolio-col').hide();
                $('.portfolio-col[data-category="' + filter + '"]').fadeIn(400);
            }
        });
    </script>
</body>

</html>