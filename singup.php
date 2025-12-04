<?php
session_start();
require_once('./db/config.php');

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $passwd = $_POST['passwd'];
    $confirm_passwd = $_POST['confirm_passwd'];
    $foto = $_POST['foto'];

    if ($passwd != $confirm_passwd) {
        echo "Contraseñas diferentes";
    }
    if (verificar_usario_existente($email, $mysqli)) {
        echo "El Usuario ya esta registrado";
    }
    $password_hased = password_hash($passwd, PASSWORD_DEFAULT);
    $smt = $mysqli->prepare("INSERT INTO  users (name,surname,mail,picture_profile,password,id_role,id_job) VALUE (?,?,?,?,?,4,4)");
    if (!$smt) {
        die("Error al preparara la query: " . $mysqli->error);
    }
    $smt->bind_param("sssss", $nombre, $apellido, $email, $foto, $password_hased);
    if ($smt->execute()) {
        header('Location: login.php');
        exit();
    }
    $smt->close();
    $mysqli->close();
}

function verificar_usario_existente($email, $mysqli)
{
    $usuario_existente = false;
    $smt = $mysqli->prepare("SELECT us.mail FROM users us WHERE us.mail = ?");
    if (!$smt) {
        die("Error al preparar la consulta");
    }
    $smt->bind_param('s', $email);
    $smt->execute();
    $result = $smt->get_result();
    if ($result->num_rows === 1) {
        $usuario_existente = true;
    }
    return $usuario_existente;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro | SchoolGram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Estilos CSS Base y Colores (se mantienen igual) */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --dark-color: #2d3561;
            --light-color: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-color);
            padding: 40px 20px;
        }

        .signup-card {
            max-width: 600px;
            width: 100%;
            margin: 40px auto;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: white;
            text-align: center;
        }

        .signup-card h2 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .form-group {
            text-align: left;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-signup-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-signup-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="signup-card" role="form" aria-labelledby="signupHeading">

                    <h2 id="signupHeading"><i class="fas fa-user-plus" aria-hidden="true"></i> Crear Cuenta en SchoolGram</h2>
                    <p class="text-muted mb-5">Únete a nuestra comunidad educativa para acceder a todas las funciones.</p>

                    <form action="singup.php" method="POST">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nombre_input">Nombre</label>
                                <input type="text" class="form-control" id="nombre_input" name="nombre" placeholder="Tu nombre"
                                    required aria-required="true" autocomplete="given-name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="apellido_input">Apellido</label>
                                <input type="text" class="form-control" id="apellido_input" name="apellido" placeholder="Tu apellido"
                                    required aria-required="true" autocomplete="family-name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email_input">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email_input" name="email" placeholder="ejemplo@colegio.edu"
                                required aria-required="true" autocomplete="email">
                            <small id="emailHelp" class="form-text text-muted">Usaremos tu correo para las notificaciones y acceso.</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password_input">Contraseña</label>
                                <input type="password" class="form-control" id="password_input" name="passwd" placeholder="Contraseña segura"
                                    required aria-required="true" autocomplete="new-password">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="confirm_password_input">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirm_password_input" name="confirm_passwd" placeholder="Repite la contraseña"
                                    required aria-required="true" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="foto_url_input">URL de la Foto de Perfil (Opcional)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-link" aria-hidden="true"></i></span>
                                </div>
                                <input type="url" class="form-control" id="foto_url_input" name="foto" placeholder="https://ejemplo.com/mi-foto.jpg"
                                    aria-describedby="urlHelp" autocomplete="photo">
                            </div>
                            <small id="urlHelp" class="form-text text-muted">Introduce el enlace directo a una imagen (ej. de un servicio de alojamiento).</small>
                        </div>
                        <div class="form-group form-check text-center mt-4">
                            <input type="checkbox" class="form-check-input" id="terms_check" required aria-required="true">
                            <label class="form-check-label" for="terms_check">Acepto los <a href="#" style="color: var(--primary-color); font-weight: 600;">Términos y Condiciones</a></label>
                        </div>


                        <button type="submit" class="btn btn-block btn-signup-submit mt-5">
                            Crear Cuenta
                        </button>

                    </form>

                    <p class="mt-4">
                        ¿Ya tienes cuenta? <a href="login.php" class="text-primary" style="font-weight: 600;">Inicia Sesión aquí</a>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>