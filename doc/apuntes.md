```php
<?php
    foreach($_SESSION['personajes'] as $personaje): ?>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm  border border-md bg-primary">

        </div>
    </div>
<?php endforeach; ?>
```

```php
if($_SERVER['REQUEST_METHOD'] === "POST"){
    $nombre = $_POST['nombre'];
    $img = $_POST['img'];
    $poder = $_POST['poder'];
    $descripcion = $_POST['descripcion'];
}
```

```php
header('../loging.php');
exit();
```

```php
<?php
    $host = "mysql-joelvegasromero.alwaysdata.net";
    $username = "439220";
    $password = "Ju94714016*";
    $dbname = "joelvegasromero_social_schools_network";


    // Creamos el objeto de DB
    $mysqli = new mysqli($host,$username,$password,$dbname);

    // -> es como acceder con . a un methodo
    if($mysqli->connect_errno){
        die("Error de conexion: ". $mysqli -> connect_errno);
    }

    // Metacharset para los accentos
    //$mysqli -> set_charset()
?>
```

```php
function returnDataUsers($mysqli){
    $users = $mysqli->query("SELECT * FROM usuaris");
    $resultUsers = $users->fetch_all(MYSQLI_ASSOC);
    return $resultUsers;
}
```

```php
$password_haseheada  = password_hash($password,PASSWORD_DEFAULT);
// 3. Preparar la consulta para insertar el nuevo usuario
// prepare statement --> evitamos el inyecciont sql
// stmt statement
// Preparara es avisar de lo que va a llegar 
// Hacemos la consulta de mentira 
$stmt = $mysqli -> prepare("INSERT INTO usuaris (nom,email,password,rol,data_registre) VALUES (?,?,?,'user',NOW())");

// 4. Comprobar que la preparacion tuvo exito
if(!$stmt){
    die('Error en la preparación' . $mysqli -> error);
}
// 5. Bindieamos los parametros
$stmt -> bind_param('sss',$nom,$email,$password_haseheada);

// 6. Ejecutamos la consulta 
if ($stmt -> execute()){
    echo 'Usuario registrado correctamente. <a href="loging.php"> Iniciar sessión </a>';
} else {
    echo 'Error al registrar el usuario: ' . $stmt -> error;
}
```

```php
<?php
session_start();
require_once('db/config.php');

// 1. Verificar si el formulario ha sido enviado 
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // 2. Recoger los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];
    // 3.  Preparar la consulta para obtener el usuario por email
    $stmt = $mysqli -> prepare("SELECT id,nom,email,password,rol FROM usuaris where email = ?");
    // 4. comprobar que la preparacion tuvo exito
    if(!$stmt){
        die('Error en la preparacion'.$mysqli -> error);
    }

    // 5. bindear los parametros
    $stmt -> bind_param('s',$email);
    // 6. ejecutamos la consulta 
    $stmt -> execute();
    // 7. Obtener el resultado
    $result = $stmt -> get_result();

    // 8. comprobar si se encontroo un usuario
    if($result -> num_rows === 1){
        $data_query = $result -> fetch_assoc();
        // 9. Verificar la contraseña 
        // password_verify -> es capaz de deshasear la contraseña y comparar 
        if(password_verify($password,$data_query['password'])){
            // 10. iniciar sesión y guardar datos en la sesión 
            $_SESSION['user_id'] = $data_query['id'];
            $_SESSION['user_nom'] = $data_query['nom'];
            $_SESSION['user_email'] = $data_query['email'];
            $_SESSION['user_rol'] = $data_query['rol'];

            header('Location: index.php');
            exit();
        }else{
            echo 'Contraseña incorrecta';
        }
        print_r($data_query);
    } else {
         echo ' No se encontrao ningun usuario con ese email';
    }
}
?>
```
