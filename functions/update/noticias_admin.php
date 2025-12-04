<?php
session_start();
require_once('../../db/config.php');
include '../../querys/selects.php';
if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'Admin') {
    header('Location: ../../index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $id_new = $_POST['id_news'];
    $title = $_POST['title'];
    $id_category = $_POST['category'];
    $descr = $_POST['descr'];
    $new = $_POST['content_new'];
    $img = $_POST['imagen'];
    if ($id_new !== "(Nuevo)") {
        $result = $mysqli->query("SELECT nw.id_news FROM news nw WHERE nw.id_news = " . $id_new);
    }

    if ($result === false) {
        die("Error en la consulta SELECT: " . $mysqli->error);
    }

    if ($result->num_rows > 0) {
        $stmt = $mysqli->prepare("UPDATE news SET 
        title = ?,
        descr = ?,
        content_new = ?,
        imagen = ?,
        id_category  = ? 
        WHERE id_news = ?");
        if (!$stmt) {
            die("ERROR AL PREPARAR LA QUERY");
        }
        $stmt->bind_param("ssssii", $title, $descr, $new, $img, $id_category, $id_new);
        if ($stmt->execute()) {
            header('Location: ../../admin.php');
            exit();
        } else {
            echo $mysqli->error;
        }
    } else {
        $stmt = $mysqli->prepare("INSERT INTO news (title,descr,content_new,imagen,id_category) VALUES (?,?,?,?,?)");
        if (!$stmt) {
            die("ERROR AL PREPARAR LA QUERY" . $mysqli->error);
        }
        $stmt->bind_param("ssssi", $title, $descr, $new, $img, $id_category);
        if ($stmt->execute()) {
            header('Location: ../../admin.php');
            exit();
        }
    }
}
