<?php
session_start();
require_once('../../db/config.php');
include('../../querys/selects.php');
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../index.php');
    exit();
}
$id_comment = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_comment <= 0) {
    header('Location: ../../profile.php');
    exit();
}

try {
    // Iniciar transacción para evitar errores por constraints
    $mysqli->begin_transaction();

    // Obtener los id_nvc relacionados con el comentario
    $ids = select("SELECT nrc.id_nvc FROM news_relation_comment nrc WHERE nrc.id_comment = " . $id_comment, $mysqli);

    // Preparar sentencias
    $stmt_del_response = $mysqli->prepare("DELETE FROM response WHERE id_nrc_parent = ?");
    if (!$stmt_del_response) {
        throw new Exception("ERROR AL PREPARAR DELETE response: " . $mysqli->error);
    }

    $stmt_del_nrc = $mysqli->prepare("DELETE FROM news_relation_comment WHERE id_nvc = ?");
    if (!$stmt_del_nrc) {
        throw new Exception("ERROR AL PREPARAR DELETE news_relation_comment: " . $mysqli->error);
    }

    foreach ($ids as $id_nrc) {
        $nvc = (int)$id_nrc['id_nvc'];

        // Borrar respuestas que referencian la fila de news_relation_comment
        $stmt_del_response->bind_param("i", $nvc);
        if (!$stmt_del_response->execute()) {
            throw new Exception("ERROR AL EJECUTAR DELETE response: " . $stmt_del_response->error);
        }

        // Borrar la relación news_relation_comment
        $stmt_del_nrc->bind_param("i", $nvc);
        if (!$stmt_del_nrc->execute()) {
            throw new Exception("ERROR AL EJECUTAR DELETE news_relation_comment: " . $stmt_del_nrc->error);
        }
    }

    // Borrar el comentario
    $stmt_del_comment = $mysqli->prepare("DELETE FROM comment WHERE id_comment = ?");
    if (!$stmt_del_comment) {
        throw new Exception("ERROR AL PREPARAR DELETE comment: " . $mysqli->error);
    }
    $stmt_del_comment->bind_param("i", $id_comment);
    if (!$stmt_del_comment->execute()) {
        throw new Exception("ERROR AL EJECUTAR DELETE comment: " . $stmt_del_comment->error);
    }

    // Confirmar transacción
    $mysqli->commit();

    header('Location: ../../profile.php');
    exit();

} catch (Exception $e) {
    // Revertir si algo falla
    if ($mysqli->errno) {
        $mysqli->rollback();
    }
    echo $e->getMessage();
}
