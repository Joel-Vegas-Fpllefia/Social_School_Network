<?php 
session_start();
require_once('../../db/config.php');
include('../../querys/selects.php');

if(!isset($_SESSION['id_user'])){
    header('Location: ../../index.php');
    exit();
}

// echo $_POST['foto_url'];
// echo $_POST['nombre'];
// echo $_POST['apellido'];
// echo $_POST['email'];
// echo $_POST['password'];

$query = select("SELECT us.name, us.surname, us.mail, us.picture_profile, us.password FROM users us WHERE us.id_user =".$_SESSION['id_user'],$mysqli);
/*
UPDATE users
SET 
    name = :nuevo_nombre, 
    surname = :nuevo_apellido, 
    mail = :nuevo_correo, 
    picture_profile = :nueva_foto_perfil,
    password = :nueva_contrasena  -- ¡Esta debe ser una contraseña HASHED!
WHERE 
    id_user = :id_usuario_actual;
*/
$password = $_POST['password'];
$password_hash = "";
foreach ($query as $data){
    if($_POST['foto_url'] == NULL){
        $_POST['foto_url'] = $data['picture_profile'];
       
    }
    if($_POST['nombre'] == NULL){
        $_POST['nombre'] = $data['name'];
        
    }
    if($_POST['apellido'] == NULL){
        $_POST['apellido'] = $data['surname'];
       
    }
    if($_POST['email'] == NULL){
        $_POST['email'] = $data['mail'];
       
    }
    if($password == NULL){
        $password_hash = $data['password'];
        
    }else{
        $password_hash = password_hash($password,PASSWORD_DEFAULT); 
    }
}

$stmt = $mysqli -> prepare ("UPDATE users SET 
    name = ?, 
    surname = ?, 
    mail = ?, 
    picture_profile = ?,
    password = ? 
WHERE 
    id_user = ?");
$stmt -> bind_param("sssssi",$_POST['nombre'],$_POST['apellido'],$_POST['email'],$_POST['foto_url'],$password_hash,$_SESSION['id_user']);
$stmt->execute();


$_SESSION['mail'] = $_POST['email'];
$_SESSION['user_name'] = "" . $_POST['nombre'] . " " . $_POST['apellido'] . "";
$_SESSION['user'] =  $_POST['nombre'];
$_SESSION['surname'] = $_POST['apellido'];
$_SESSION['picture_profile'] = $_POST['foto_url'];

header('Location: ../../profile.php');
exit();


