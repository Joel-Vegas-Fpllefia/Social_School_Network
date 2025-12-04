<?php
session_start();
require_once('../../db/config.php');
if (!isset($_SESSION['id_user'])) {
    header('Location: ../../index.php');
    exit();
}
$titulo = $_POST['title'];
$mesage = $_POST['mensaje'];
$category = $_POST['asunto'];
$id_user = $_SESSION['id_user'];
$estado = 1;
$stmt = $mysqli->prepare("INSERT INTO tasks   (id_user, id_options_support , status_task ,message,titulo) VALUES  (?,?,?,?,?)");
if (!$stmt) {
    die("Error al preparar la consulta" . $mysqli->error);
}
$stmt->bind_param("iiiss", $id_user, $category, $estado, $mesage, $titulo);
if ($stmt->execute()) {
    header('Location: ../../index.php');
    exit();
} else {
    echo "Error al ejecutar la query: " . $stmt->error;
}
