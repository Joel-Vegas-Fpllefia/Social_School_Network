<?php
session_start();
require_once('../../db/config.php');

if (!isset($_SESSION['id_user'])) {
    header('Location: ../../index.php');
    exit();
}

$id = $_GET['id_new'];
$stmt  = $mysqli->prepare("DELETE FROM news WHERE id_news = ?");
if (!$stmt) {
    die("ERROR AL PREPARAR LA QUERY" . $mysqli->error);
}
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header('Location: ../../admin.php');
    exit();
}
