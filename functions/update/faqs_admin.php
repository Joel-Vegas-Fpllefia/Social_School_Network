<?php
session_start();
require_once('../../db/config.php');

if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'Admin') {
    header('../../index.php');
    exit();
}

$id_faqs = $_POST['id_faqs'];
$title = $_POST['title'];
$text = $_POST['text'];

$section_faq = $_POST['section_faq'];

if ($id_faqs != '(Nuevo)') {
    $stmt = $mysqli->prepare("UPDATE faqs SET id_section_faq = ?,title = ?,text = ? WHERE id_faqs = ?");
    if (!$stmt) {
        die("ERROR AL PREPARAR LA QUERY" . $mysqli->error);
    }
    $stmt->bind_param("issi", $section_faq, $title, $text, $id_faqs);
    if ($stmt->execute()) {
        header('Location: ../../admin.php');
        exit();
    }
} else {
    $stmt = $mysqli->prepare("INSERT INTO faqs (id_section_faq,title,text) VALUES (?,?,?)");
    if (!$stmt) {
        die("ERROR AL PREPARAR LA QUERY" . $mysqli->error);
    }
    $stmt->bind_param("iss", $section_faq, $title, $text);
    if ($stmt->execute()) {
        header('Location: ../../admin.php');
        exit();
    }
}
