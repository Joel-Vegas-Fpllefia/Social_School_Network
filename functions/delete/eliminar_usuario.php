<?php
session_start();
require_once('../../db/config.php');
$id_user = $_GET['id'];
if(!isset($_SESSION['id_user']) && $_SESSION['role'] != 'Admin'){
    header('Location: ../../index.php');
    exit();
}

if($_SESSION['id_user'] == $id_user){
    header('Location: ../../admin.php');
    exit();
}else{
    $stmt = $mysqli -> prepare("DELETE FROM users WHERE id_user = ?");
    if(!$stmt){
        die(" ERROR AL PREPARAR LA QUERY");
    }
    $stmt -> bind_param("i",$id_user);
    if($stmt -> execute()){
        header('Location: ../../admin.php');
        exit();
    }
}
