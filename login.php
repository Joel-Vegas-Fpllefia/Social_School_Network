<?php
session_start();
require_once('./db/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $mail = $_POST['mail'];
    // Preparamos la consulta 
    $smt = $mysqli->prepare("SELECT us.id_user,us.mail,us.name,us.surname,us.password,us.picture_profile,rl.role FROM users us JOIN roles rl ON rl.id_role = us.id_role WHERE us.mail = ?");
    if (!$smt) {
        die("Error al preparar la consulta");
    }
    // Bindeamos la consulta
    $smt->bind_param('s', $mail);
    $smt->execute();
    $resultado = $smt->get_result();
    if ($resultado->num_rows === 1) {
        $data_query = $resultado->fetch_assoc();

        if (password_verify($password, $data_query['password'])) {
            $_SESSION['id_user'] =  $data_query['id_user'];
            $_SESSION['mail'] = $data_query['mail'];
            $_SESSION['user_name'] = "" . $data_query['name'] . " " . $data_query['surname'] . "";
            $_SESSION['user'] =  $data_query['name'];
            $_SESSION['surname'] = $data_query['surname'];
            $_SESSION['picture_profile'] = $data_query['picture_profile'];
            $_SESSION['role'] = $data_query['role'];

            header('Location: index.php');
            exit();
        } else {
            echo ("CONTRASEÑA INCORECTA" . $data_query['password']);
        }
    } else {
        echo "Usuario no encontrado";
    }
    $smt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Login | SchoolGram</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Aquí iría la mayoría de tu CSS global (Navbar, root variables, body, etc.)
           Para este ejemplo, solo incluiremos el mínimo necesario y los estilos de formulario. */
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
            min-height: 100vh;
            display: flex;
            /* Centrar contenido */
            align-items: center;
            /* Centrar verticalmente */
            justify-content: center;
            /* Centrar horizontalmente */
            padding: 20px;
        }

        .login-card {
            max-width: 450px;
            width: 100%;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: white;
            text-align: center;
        }

        .login-card h2 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 25px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            /* rgba(var(--primary-color), 0.25) */
        }

        .btn-login-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-login-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-card" role="form" aria-labelledby="loginHeading">

                    <h2 id="loginHeading"><i class="fas fa-lock" aria-hidden="true"></i> Iniciar Sesión en SchoolGram</h2>
                    <p class="text-muted mb-4">Accede a tu perfil de estudiante o colaborador.</p>

                    <form action="login.php" method="POST">

                        <div class="form-group">
                            <label for="user" class="sr-only">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                </span>
                                <input type="text" class="form-control" id="mail" name="mail" placeholder="Introduce el Email"
                                    required aria-required="true" autocomplete="mail">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="sr-only">Contraseña</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key" aria-hidden="true"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña"
                                    required aria-required="true" autocomplete="current-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-block btn-login-submit mt-4">
                            Acceder
                        </button>

                    </form>

                    <p class="mt-4">
                        ¿No tienes cuenta? <a href="signup.php" class="text-primary" style="font-weight: 600;">Regístrate aquí</a>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>