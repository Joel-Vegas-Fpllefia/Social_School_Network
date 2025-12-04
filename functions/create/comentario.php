<?php
session_start();
require_once('../../db/config.php');
include '../../querys/selects.php';
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../blog.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id_noticia = $_POST['id_news'];
    $comentario = $_POST['text_comment'];
    $id_user = $_SESSION['id_user'];

    $stmt = $mysqli->prepare("INSERT INTO comment (id_user,text_comment) VALUES (?,?)");
    if (!$stmt) {
        die("ERROR AL PREPARA LA QUERY" . $mysqli->error);
    }
    $stmt->bind_param("is", $id_user, $comentario);
    if ($stmt->execute()) {
        $id_commentario_ultimo = select("SELECT cm.id_comment FROM comment cm WHERE id_user = " . $id_user . " AND cm.text_comment = '" . $comentario . "'", $mysqli);
        foreach ($id_commentario_ultimo as $id_commentario) {
            $stmt = $mysqli->prepare("INSERT INTO news_relation_comment (id_news,id_comment) VALUES (?,?)");
            if (!$stmt) {
                die("ERROR AL PREPARAR LA QUERY: " . $mysqli->error);
            }
            $stmt->bind_param('ii', $id_noticia, $id_commentario['id_comment']);
            if ($stmt->execute()) {
                header('Location: ../../blog.php');
                exit();
            } else {
                echo "ERROR AL EJECUTAR LA QUERY" . $mysqli->error;
            }
        }
    } else {
        echo "NO SE HA PODIDO EJECUTAR LA QUERY" . $mysqli->error;
    }
}
