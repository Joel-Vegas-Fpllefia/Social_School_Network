<?php
session_start();
require_once('../../db/config.php');
if(!isset($_SESSION['id_user']) && $_SESSION['role'] != 'Admin'){
    header('Location: ../../index.php');
    exit();
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id_user = $_POST['id_user'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $mail = $_POST['mail'];
    $id_role = $_POST['id_role'];
    $id_job = $_POST['id_job'];
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $foto_perfil = "https://imgs.search.brave.com/CFBTYPNRel95sDw00APELv5D4Ghs73sYYcN0-tLpV5U/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tYXJr/ZXRwbGFjZS5jYW52/YS5jb20vZ0pseTAv/TUFHRGtNZ0pseTAv/MS90bC9jYW52YS11/c2VyLXByb2ZpbGUt/aWNvbi12ZWN0b3Iu/LWF2YXRhci1vci1w/ZXJzb24taWNvbi4t/cHJvZmlsZS1waWN0/dXJlLC1wb3J0cmFp/dC1zeW1ib2wuLU1B/R0RrTWdKbHkwLnBu/Zw";

    if($id_user != '(Nuevo)'){
        $data = $mysqli -> query ("SELECT us.mail, us.id_user, us.password FROM users us WHERE us.mail = '".$mail."'");
        if($data -> num_rows > 0){
            $verify_mail = $data -> fetch_assoc();
            if($verify_mail['id_user'] === $id_user){
                // Si la contraseña está vacía, no cambiar
                if(empty($password)){
                    $stmt = $mysqli -> prepare("UPDATE users SET name = ?,surname = ?,mail = ?,id_role = ?,id_job = ? WHERE id_user = ?");
                    if(!$stmt){
                        die("ERROR AL PREPARA LA QUERY");
                    }
                    $stmt -> bind_param("sssiii",$name,$surname,$mail,$id_role,$id_job,$id_user);
                    if($stmt -> execute()){
                        header("Location: ../../admin.php");
                        exit();
                    }
                }else{
                    // Contraseña nueva: hashear y actualizar
                    $password_hash = password_hash($password,PASSWORD_DEFAULT);
                    $stmt = $mysqli -> prepare("UPDATE users SET name = ?,surname = ?,mail = ?,password = ? ,id_role = ?,id_job = ? WHERE id_user = ?");
                     if(!$stmt){
                        die("ERROR AL PREPARA LA QUERY");
                    }
                    $stmt -> bind_param("ssssiii",$name,$surname,$mail,$password_hash,$id_role,$id_job,$id_user);
                    if($stmt -> execute()){
                        header('Location: ../../admin.php');
                        exit();
                    }
                }
            }else{
                header('Location: ../../admin.php');
                exit();
            }
        }
    }else{
        $data = $mysqli -> query ("SELECT us.mail, us.id_user, us.password FROM users us WHERE us.mail = '".$mail."'");
        if($data -> num_rows > 0){
            // Email ya existe
            header('Location: ../../admin.php');
        }else{
            
            // Email no existe, crear nuevo usuario
            if(empty($password)){
                die("ERROR: La contraseña es requerida para crear un nuevo usuario");
            }
            $password_hash = password_hash($password,PASSWORD_DEFAULT);
            $stmt = $mysqli -> prepare("INSERT INTO users (name,surname,mail,picture_profile,password,id_role,id_job) VALUES(?,?,?,?,?,?,?)");
            if(!$stmt){
                die("ERROR AL PREPARAR LA QUERY");
            }
            $stmt -> bind_param("sssssii",$name,$surname,$mail,$foto_perfil,$password_hash,$id_role,$id_job);
            if($stmt -> execute()){
                header('Location: ../../admin.php');
                exit();
            } else {
                die("ERROR AL INSERTAR: " . $stmt->error);
            }
        }
    }
}