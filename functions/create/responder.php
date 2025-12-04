<?php
session_start();
require_once('../../db/config.php');
include('../../querys/selects.php');

if (!isset($_SESSION['id_user'])) {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_new = $_POST['id_news'];
    $parent_id_comment = $_POST['parent_id_comment'];

    $text_comment = $_POST['text_comment'];
    $id_user = $_SESSION['id_user'];

    //Insertar el comentario
    $stmt = $mysqli->prepare("INSERT INTO comment (id_user,text_comment) VALUES (?,?)");
    if (!$stmt) {
        die("ERROR al prepara la consulta: " . $mysqli->error);
    }
    $stmt->bind_param("is", $id_user, $text_comment);
    if ($stmt->execute()) {
        $id_commentario_ultimo = select("SELECT cm.id_comment FROM comment cm WHERE id_user = " . $id_user . " AND cm.text_comment = '" . $text_comment . "'", $mysqli);
        foreach ($id_commentario_ultimo as $id_commentario) {

            $stmt = $mysqli->prepare("INSERT INTO news_relation_comment (id_news,id_comment) VALUES (?,?)");
            if (!$stmt) {
                die("error al prepara la query:" . $mysqli->error);
            }
            $stmt->bind_param("ii", $id_new, $id_commentario['id_comment']);

            if ($stmt->execute()) {
                $id_relations_comment = select("SELECT nrc.id_nvc FROM news_relation_comment nrc WHERE nrc.id_comment = " . $id_commentario['id_comment'] . " AND nrc.id_news = " . $id_new, $mysqli);
                foreach ($id_relations_comment as $id_relation) {
                    $stmt = $mysqli->prepare("INSERT INTO response (id_nrc_parent,id_comment_reply) VALUES (?,?)");
                    if (!$stmt) {
                        die("ERROR al preparar la query" . $mysqli->error);
                    }
                    $stmt->bind_param("ii", $id_relation['id_nvc'], $parent_id_comment);
                    if ($stmt->execute()) {
                        header('Location: ../../blog.php');
                        exit();
                    } else {
                        echo "Error al ejecutar la query" . $mysqli->error;
                    }
                }
            } else {
                echo "Error al ejecutar la consulta" . $mysqli->error;
            }
        }
    }
}
