<?php
session_start();
require_once("../../db/config.php");
if(!isset($_SESSION['id_user'])){
    header('Location: ../../index.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $comentario_id = $_POST['comentario_id'];
    $comentario_texto = $_POST['comentario_texto'];
    $stmt = $mysqli -> prepare("UPDATE comment SET text_comment = ? WHERE id_comment = ?");
    if(!$stmt){
        die("ERROR AL PREPARA LA QUERY". $mysqli -> error);
    } 
    $stmt -> bind_param("si",$comentario_texto,$comentario_id);
    if($stmt -> execute()){
        header('Location: ../../profile.php');
        exit();
    }else{
        echo "ERROR AL EJECUTAR EL UPDATE: ". $mysql -> error;
    }
}

