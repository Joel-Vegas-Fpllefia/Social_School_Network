<?php
session_start();
require_once('./db/config.php');
include('./querys/selects.php');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location:blog.php');
}
$noticia = select("SELECT nw.title, nw.imagen, nw.content_new, nw.fecha_creacion, COUNT(cm.data_comment) AS cantidad_comments from news nw JOIN category ct ON nw.id_category = ct.id_category JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment WHERE nw.id_news = " . $_POST['id_news'] . " GROUP BY nw.id_news", $mysqli);
$comentarios_noticias = select("SELECT nw.id_news,cm.id_comment,us.name, us.surname, us.picture_profile, cm.data_comment, cm.text_comment, rl.role FROM news nw JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment JOIN users us ON us.id_user = cm.id_user JOIN roles rl ON rl.id_role = us.id_role LEFT JOIN response rp ON rp.id_nrc_parent = nrc.id_nvc where rp.id_response IS NULL AND nw.id_news = " . $_POST['id_news'] . "", $mysqli);

function load_new($noticia)
{
    foreach ($noticia as $final_data)
        echo '<span class="news-badge">Eventos</span>
                    <p class="news-meta">
                        <i class="far fa-calendar"></i> ' . $final_data['fecha_creacion'] . '|
                        <i class="far fa-comments"></i> ' . $final_data['cantidad_comments'] . ' Comentarios
                    </p>

                    <img src="' . $final_data['imagen'] . '"
                        alt="' . $final_data['title'] . '">

                    <div class="news-text mt-4">
                        ' . $final_data['content_new'] . '
                    </div>
                ';
}


function cargar_respuesta_comentarios($id_noticia, $id_comentario, $mysqli)
{
    // MODIFICACIÓN DE LA QUERY: Se añade cm.id_comment para poder gestionar las respuestas anidadas.
    $comentarios = select("SELECT cm.id_comment, us.name, us.surname, us.picture_profile, cm.data_comment, cm.text_comment, rl.role FROM news nw JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment JOIN users us ON us.id_user = cm.id_user JOIN roles rl ON rl.id_role = us.id_role JOIN response rp ON rp.id_nrc_parent = nrc.id_nvc where nw.id_news = " . $id_noticia . " AND rp.id_comment_reply = " . $id_comentario . "", $mysqli);
    foreach ($comentarios as $comentario) {
        // Se utiliza un ID único basado en el ID del comentario de la respuesta.
        $reply_form_id = 'replyForm-reply-' . $comentario['id_comment'];
        echo '<div class="comment-replies">
                <div class="reply">
                    <div class="reply-content w-100">
                        <img src="' . $comentario['picture_profile'] . '"
                            alt="Avatar 4" class="comment-avatar">

                        <div class="comment-body p-0 w-100">
                            <h5>' . $comentario['name'] . ' ' . $comentario['surname']  . ' <span class="badge badge-primary ml-2">' . $comentario['role'] . '</span>
                            </h5>
                            <small>Publicado el ' . $comentario['data_comment'] . '</small>
                            <p class="mb-0">' . $comentario['text_comment'] . '</p>
                            
                            ' . (isset($_SESSION['user']) ? '
                            <a href="#' . $reply_form_id . '"
                                class="btn btn-sm btn-link text-secondary reply-link"
                                data-toggle="collapse"
                                aria-expanded="false"
                                aria-controls="' . $reply_form_id . '">
                                <i class="fas fa-reply"></i> Responder
                            </a>
                            ' : '') . '
                            <div id="' . $reply_form_id . '" class="collapse comment-form mt-2">
                                <h6>Responder a ' . $comentario['name'] . ' ' . $comentario['surname']  . '</h6>
                                <form action="./functions/create/responder.php" method="POST">
                                    <input type="hidden" name="id_news" value="' . $id_noticia . '">
                                    <input type="hidden" name="parent_id_comment" value="' . $id_comentario . '">

                                    <div class="form-group">
                                        <textarea class="form-control" name="text_comment" rows="3" placeholder="Escribe tu respuesta a ' . $comentario['name'] . ' ' . $comentario['surname']  . '..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-comment-submit">Enviar Respuesta</button>
                                </form>
                            </div>
                            </div>
                    </div>
                </div>
        </div>';
    }
}
function mostrar_respondes()
{
    if (isset($_SESSION['user'])) {
        return '
            <a href="#replyForm-' . $_SESSION['username'] . '"
                class="btn btn-sm btn-link text-secondary reply-link"
                data-toggle="collapse"
                aria-expanded="false"
                aria-controls="replyForm-' . $_SESSION['username'] . '">
                <i class="fas fa-reply"></i> Responder
            </a>
        ';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <?php foreach ($noticia as $final_data): ?>
        <title>SchoolGram | <?= $final_data['title'] ?></title>
    <? endforeach ?>
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
            padding: 60px 0;
            text-align: center;
            position: relative;

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

        /* Features Section (Para reusar estilos de títulos) */
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

        /* News Grid (Reutilizado como News Section) */
        .news-section {
            padding: 80px 0;
            background: var(--light-color);
        }

        .news-badge {
            display: inline-block;
            padding: 5px 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .news-meta {
            color: #999;
            font-size: 14px;
            margin-bottom: 25px;
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

        /* NUEVOS ESTILOS PARA LA PÁGINA DE NOTICIA */
        .single-news-content {
            padding: 60px 0;
            background: white;
        }

        .single-news-content img {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .single-news-content h1 {
            font-size: 36px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .news-text p {
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #555;
        }

        /* Estilos de Comentarios */
        .comments-section {
            padding-top: 40px;
            border-top: 1px solid #eee;
            margin-top: 40px;
        }

        .comment {
            display: flex;
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #f0f0f0;
            border-radius: 10px;
            background: #fdfdfd;
        }

        /* --- NUEVOS ESTILOS PARA LAS RESPUESTAS ANIDADAS (REPLIES) --- */
        .comment-replies {
            margin-top: 20px;
            margin-left: 65px;
            /* Alinea las respuestas debajo del avatar del comentario principal */
        }

        .reply {
            display: flex;
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #f0f0f0;
            border-left: 3px solid var(--accent-color);
            /* Barra de acento para diferenciar */
            border-radius: 8px;
            background: #ffffff;
            /* Flexibilidad para anidar más respuestas */
            flex-direction: column;
        }

        .reply .comment-body {
            padding-left: 0;
            /* Resetear el padding si viene de .comment-body */
        }

        /* El reply-content es la parte del reply que contiene el avatar, nombre y texto. */
        .reply-content {
            display: flex;
            width: 100%;
        }

        /* Si una respuesta (reply) tiene otra respuesta anidada */
        .reply .comment-replies {
            margin-left: 50px;
            /* Mayor sangría para el tercer nivel */
        }

        /* ------------------------------------------------ */

        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        .comment-body h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .comment-body h5 .badge {
            font-size: 12px;
        }

        .comment-body small {
            color: #999;
            display: block;
            margin-bottom: 10px;
        }

        .comment-form {
            padding: 30px;
            background: var(--light-color);
            border-radius: 15px;
            margin-top: 40px;
        }

        /* Estilos para que el formulario de respuesta se vea más pequeño */
        .comment-body .comment-form {
            padding: 15px;
            margin-top: 10px !important;
        }

        .comment-body .comment-form h6 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .comment-form h4 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 20px;
        }

        .btn-comment-submit {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
            transition: background 0.3s;
        }

        .btn-comment-submit:hover {
            background: var(--secondary-color);
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
    <?php include './components/header.php' ?>

    <section class="page-hero">
        <div class="container">
            <?php foreach ($noticia as $final_data): ?>
                <h1><?= $final_data['title'] ?></h1>
            <? endforeach ?>
        </div>
    </section>

    <section class="single-news-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php load_new($noticia); ?>
                    <div class="comments-section">
                        <? foreach ($noticia as $final_data): ?>
                            <h4><?= $final_data['cantidad_comments'] ?> Comentarios</h4>
                        <? endforeach ?>

                        <?php
                        foreach ($comentarios_noticias as $comentario): ?>
                            <div class="comment">
                                <img src="<?= $comentario['picture_profile'] ?>" alt="Avatar 1"
                                    class="comment-avatar">
                                <div class="comment-body w-100">
                                    <h5><?= $comentario['name'] ?> <?= $comentario['surname'] ?> <span class="badge badge-info ml-2"><?= $comentario['role'] ?></span></h5>
                                    <small>Publicado el <?= $comentario['data_comment'] ?></small>
                                    <p><?= $comentario['text_comment'] ?> </p>
                                    <? if (isset($_SESSION['user'])): ?>
                                        <a href="#replyForm-<?= $comentario['id_comment'] ?>"
                                            class="btn btn-sm btn-link text-secondary reply-link"
                                            data-toggle="collapse"
                                            aria-expanded="false"
                                            aria-controls="replyForm-<?= $comentario['id_comment'] ?>">
                                            <i class="fas fa-reply"></i> Responder
                                        </a>
                                    <? endif ?>

                                    <div id="replyForm-<?= $comentario['id_comment'] ?>" class="collapse comment-form mt-2">
                                        <h6>Responder a <?= $comentario['name'] ?></h6>
                                        <form action="./functions/create/responder.php" method="POST">
                                            <input type="hidden" name="id_news" value="<?= ($_POST['id_news']) ?>">
                                            <input type="hidden" name="parent_id_comment" value="<?= $comentario['id_comment'] ?>">

                                            <div class="form-group">
                                                <textarea class="form-control" name="text_comment" rows="3" placeholder="Escribe tu respuesta..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-comment-submit">Enviar Respuesta</button>
                                        </form>
                                    </div>
                                    <?= cargar_respuesta_comentarios($comentario['id_news'], $comentario['id_comment'], $mysqli) ?>
                                </div>
                            </div>
                        <? endforeach; ?>
                    </div>

                    <? if (isset($_SESSION['user'])): ?>
                        <div id="newCommentForm" class="comment-form mt-4">
                            <h4>Deja tu comentario</h4>
                            <form action="./functions/create/comentario.php" method="POST">
                                <input type="hidden" name="id_news" value="<?= ($_POST['id_news']) ?>">
                            
                                <div class="form-group">
                                    <textarea class="form-control" name="text_comment" rows="5" placeholder="Escribe tu comentario..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-lg btn-comment-submit">Publicar Comentario</button>
                            </form>
                        </div>
                    <? endif ?>
                </div>
            </div>
    </section>

    <?php include './components/footer.php' ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>