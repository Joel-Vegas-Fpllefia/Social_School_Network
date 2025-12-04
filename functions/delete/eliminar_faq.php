<?php
session_start();
require_once('../../db/config.php');
if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'Admin') {
    header('Location: ../../index.php');
    exit();
}
$id_faq = $_GET['id_faq'];

$stmt = $mysqli->prepare("DELETE FROM faqs WHERE id_faqs = ?");
if (!$stmt) {
    die("ERROR AL PREPARAR LA CONSULTA" . $mysqli->error);
}
$stmt->bind_param("i", $id_faq);
if ($stmt->execute()) {
    header('Location: ../../admin.php');
    exit();
}
