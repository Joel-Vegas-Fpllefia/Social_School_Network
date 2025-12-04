<?php
session_start();
require_once('../../db/config.php');
include '../../querys/selects.php';
if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'Admin') {
    header('Location: ../../index.php');
    exit();
}

$id_opinion = $_POST['id_opinion'];

$comentario = $_POST['comentario'];
$puntuacion = $_POST['puntuacion'];

if ($id_opinion != '(Nuevo)') {
    $stmt = $mysqli->prepare("UPDATE  opinions SET  puntuacion = ?, comentario = ? WHERE id_opinion = ?");
    if (!$stmt) {
        die("ERROR AL PREPARAR LA QUERY");
    }
    $stmt->bind_param("isi", $puntuacion, $comentario, $id_opinion);
    if ($stmt->execute()) {
        header('Location: ../../admin.php');
        exit();
    }
} else {
    $nombre_usuario = $_POST['autor'];

    $id_user = 105;
    $stmt = $mysqli->prepare("INSERT INTO opinions (id_user,puntuacion,comentario) VALUES (?,?,?)");
    if (!$stmt) {
        die("ERROR AL PREPARAR LA QUERY");
    }
    $stmt->bind_param("iis", $id_user, $puntuacion, $comentario);
    if ($stmt->execute()) {
        header('Location: ../../admin.php');
        exit();
    } else {
        echo  $mysqli->error;
    }
}
