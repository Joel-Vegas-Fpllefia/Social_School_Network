<?php
session_start();
require_once('../../db/config.php');
include '../../querys/selects.php';
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'Admin') {
    header('Location: ../../index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $id_sub_response = 0;
    $estado = $_POST['status_task'];
    $id_task =  $_POST['id_task'];
    $respuesta = $_POST['respuesta_admin'];
    $stmt = $mysqli->prepare("INSERT INTO response_task (id_task,response)  VALUES (?,?)");
    if (!$stmt) {
        die("ERROR AL PREPARA LA QUERY" . $mysqli->error);
    }
    $stmt->bind_param("is", $id_task, $respuesta);

    if ($stmt->execute()) {
        $estado_name = select("SELECT st.id_options_status FROM status_task st WHERE st.status = '" . $estado . "'", $mysqli);
        foreach ($estado_name as $name_state) {
            $stmt = $mysqli->prepare("UPDATE tasks SET status_task = ? WHERE id_task = ?");
            if (!$stmt) {
                die("ERROR AL PREPARAR LA QUERY" . $mysqli->error);
            }
            $stmt->bind_param("ii", $name_state['id_options_status'], $id_task);
            if ($stmt->execute()) {
                header('Location: ../../admin.php');
                exit();
            } else {
                echo "ERROR AL EJECUTAR";
            }
        }
    } else {
        echo $mysqli->error;
    }
}
