<?php
$host = "mysql-joelvegasromero.alwaysdata.net";
$user_name = "439220";
$password = "Ju94714016*";
$db_name = "joelvegasromero_school_gram_2";

$mysqli = new mysqli($host, $user_name, $password, $db_name);

if ($mysqli->connect_errno) {
    die("Error de conexion: " . $mysqli->connect_errno);
}
