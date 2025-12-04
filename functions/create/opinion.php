<?php
session_start();
require_once('../../db/config.php');
if (!isset($_SESSION['id_user'])) {
    header('Location ../../index.php');
    exit();
}
$comentario_user = $_POST['comentario'];
$puntuacion = $_POST['puntuacion'];
$id_user = $_SESSION['id_user'];

$stmt = $mysqli->prepare("INSERT INTO opinions    (id_user, puntuacion, comentario) VALUES    (?,?,?)");

if (!$stmt) {
    die("Error al preparar la consulta: " . $mysqli->error);
}

$stmt->bind_param("iis", $id_user, $puntuacion, $comentario_user);

if ($stmt->execute()) {
    header('Location: ../../testimonios.php');
} else {
    echo "Error al ejecutar la consulta";
}
