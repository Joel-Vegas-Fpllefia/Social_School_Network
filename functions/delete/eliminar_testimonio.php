<?php
session_start();
require_once('../../db/config.php');
if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'Admin') {
    header('Location: ../../index.php');
    exit();
}

$id_opinion = $_GET['id_opinion'];
$stmt = $mysqli->prepare("DELETE FROM opinions WHERE id_opinion = ?");

if (!$stmt) {
    die("ERROR AL PREPARAR LA QUERY");
}

$stmt->bind_param("i", $id_opinion);
if ($stmt->execute()) {
    header('Location: ../../admin.php');
    exit();
}
