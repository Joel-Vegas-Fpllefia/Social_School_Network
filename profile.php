<?php
session_start();
require_once('./db/config.php');
include('./querys/selects.php');
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$incidencias = select("SELECT tk.id_task, tk.message, tk.titulo, tk.date_task, opt.option_name, stt.status, rpst.response FROM tasks tk JOIN users us ON us.id_user = tk.id_user JOIN options_task opt ON opt.id_options_task = tk.id_options_support JOIN status_task stt ON stt.id_options_status = tk.status_task LEFT JOIN response_task rpst ON rpst.id_task = tk.id_task WHERE us.id_user = " . $_SESSION['id_user'], $mysqli);


$comentarios = select("SELECT cm.id_comment, cm.data_comment, cm.text_comment FROM comment cm JOIN users us ON cm.id_user = us.id_user where us.id_user =" . $_SESSION['id_user'], $mysqli);

function color_estado_incidencia($estado)
{
    $texto = "";
    switch ($estado) {
        case "Abierta":
            $texto =     '<span class="badge badge-danger">' . $estado . '</span>';
            break;
        case "En Progreso":
            $texto =     '<span class="badge badge-warning">' . $estado . '</span>';
            break;
        case "Resuelta":
            $texto =     '<span class="badge badge-success">' . $estado . '</span>';
            break;
        case "Cerrada":
            $texto =     '<span class="badge badge-secondary">' . $estado . '</span>';
            break;
        default:
            // Si el estado no coincide con ningún case, se muestra en gris
            $texto = '<span class="badge badge-secondary">Error: ' . $estado . '</span>';
            break;
    }
    echo $texto;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - SchoolGram</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Variables de Estilo basadas en la imagen de SchoolGram (Gradiente) */
        :root {
            --color-gradient-start: #6a11cb;
            --color-gradient-end: #2575fc;
            --color-texto-claro: #ffffff;
            --color-texto-oscuro: #333333;
            --color-acento-claro: #f8f8f8;
            --color-borde-claro: #e0e0e0;
        }

        .badge-danger {
            color: #fff;
            background-color: #dc3545 !important;
            /* Añadir !important para forzar el color de fondo */
        }

        .badge {
            /* Aumentar el padding horizontal y vertical para que se vea más como una pastilla */
            padding: .5em 1em;
            /* Aumentar el radio para que las esquinas sean más suaves */
            border-radius: 20px;
            /* Asegurar que el tamaño de la fuente sea legible */
            font-size: 0.85rem;
            /* Darle un poco más de peso a la fuente */
            font-weight: 600;
            /* Eliminar cualquier sombra de texto por si acaso */
            text-shadow: none;
            /* Para que se vea mejor en la tabla */
            min-width: 90px;
            display: inline-block;
        }

        /* 2. ESTADOS ESPECÍFICOS: Colores y Sombras */

        /* Abierta (Rojo Fuerte) */
        .badge-danger {
            color: #fff;
            background-color: #e54c5c !important;
            box-shadow: 0 4px 6px rgba(229, 76, 92, 0.4);
            /* Sombra sutil roja */
        }

        /* En Progreso (Naranja/Ámbar) */
        .badge-warning {
            color: #333;
            /* Usar texto oscuro para contraste en fondo claro */
            background-color: #ffb142 !important;
            box-shadow: 0 4px 6px rgba(255, 177, 66, 0.4);
            /* Sombra sutil naranja */
        }

        /* Resuelta (Verde Brillante) */
        .badge-success {
            color: #fff;
            background-color: #38c172 !important;
            box-shadow: 0 4px 6px rgba(56, 193, 114, 0.4);
            /* Sombra sutil verde */
        }

        /* Cerrada (Gris Suave) */
        .badge-secondary {
            color: #fff;
            background-color: #8c98a4 !important;
            box-shadow: 0 4px 6px rgba(140, 152, 164, 0.4);
            /* Sombra sutil gris */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, var(--color-gradient-start), var(--color-gradient-end));
            color: var(--color-texto-oscuro);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Estilo del Header/Navbar */
        .navbar {
            background: rgba(0, 0, 0, 0.2);
            color: var(--color-texto-claro);
            padding: 15px 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.5em;
            color: var(--color-texto-claro);
            display: flex;
            align-items: center;
        }

        .container {
            flex-grow: 1;
            max-width: 1000px;
            margin: 30px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: var(--color-gradient-start);
            border-bottom: 2px solid var(--color-borde-claro);
            padding-bottom: 10px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        /* --- Perfil y Formularios --- */
        .perfil-card {
            display: flex;
            align-items: center;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            background-color: var(--color-acento-claro);
            border-left: 5px solid var(--color-gradient-end);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .perfil-foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 30px;
            border: 4px solid var(--color-gradient-end);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input,
        .modal textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--color-borde-claro);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
        }

        .form-group input:focus {
            border-color: var(--color-gradient-end);
            box-shadow: 0 0 0 2px rgba(37, 117, 252, 0.2);
            outline: none;
        }

        /* Botón de Estilo SchoolGram */
        .btn-submit {
            background: linear-gradient(to right, var(--color-gradient-start), var(--color-gradient-end));
            color: var(--color-texto-claro);
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.05em;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        /* --- Estilos de Comentarios --- */
        .comentario {
            border-left: 5px solid var(--color-gradient-end);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .comentario .acciones a {
            margin-right: 20px;
            text-decoration: none;
            color: var(--color-gradient-start);
            font-weight: bold;
        }

        .comentario .acciones .btn-eliminar {
            color: #d9534f;
        }

        /* --- Estilos de Incidencias --- */
        .incidencia-card {
            border: 1px solid var(--color-borde-claro);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            background-color: var(--color-acento-claro);
        }

        .incidencia-card h3 {
            color: var(--color-gradient-end);
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.3em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .incidencia-card .estado {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
            color: white;
        }

        .estado.pendiente {
            background-color: #f0ad4e;
        }

        .estado.resuelta {
            background-color: #5cb85c;
        }

        .estado.cerrada {
            background-color: #d9534f;
        }

        .incidencia-respuesta {
            margin-top: 15px;
            padding: 15px;
            border-left: 4px solid var(--color-gradient-start);
            background-color: #eaf1ff;
            border-radius: 4px;
        }

        .incidencia-respuesta strong {
            color: var(--color-gradient-start);
        }

        .incidencia-acciones {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed var(--color-borde-claro);
            display: flex;
            gap: 15px;
        }

        .btn-accion {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .btn-accion:hover {
            opacity: 0.8;
        }

        .btn-resuelto {
            background-color: #5cb85c;
            color: white;
        }

        .btn-no-resuelto {
            background-color: #d9534f;
            color: white;
        }

        /* --- Modal --- */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal.show-modal {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #ffffff;
            padding: 30px;
            width: 90%;
            max-width: 550px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .modal .close {
            color: #aaa;
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 32px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1><i class="fas fa-user-circle"></i> Mi Perfil</h1>
    </div>

    <div class="container">
        <p>Aquí puedes actualizar tu información y gestionar tus contribuciones a la comunidad.</p>

        <section>
            <h2>Datos de la Cuenta</h2>
            <form action="./functions/update/update_profile.php" method="POST">

                <div class="perfil-card">
                    <?php echo '' ?>
                    <img src="<?= $_SESSION['picture_profile'] ?>" alt="Foto de Perfil" class="perfil-foto" id="preview-foto">
                    <div>
                        <div class="form-group">
                            <label for="foto_url">URL de la Foto de Perfil:</label>
                            <input type="text" id="foto_url" name="foto_url" placeholder="Ej: https://ejemplo.com/tu-foto.jpg"
                                value="">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= $_SESSION['user'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?= $_SESSION['surname'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $_SESSION['mail'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Cambiar Contraseña (dejar vacío para no cambiar):</label>
                    <input type="password" id="password" name="password" placeholder="********">
                </div>

                <button type="submit" class="btn-submit">Guardar Cambios de Perfil</button>
            </form>
        </section>

        <hr style="margin-top: 50px; border: 0; border-top: 1px dashed var(--color-borde-claro);">

        <section>
            <h2><i class="fas fa-headset"></i> Mis Incidencias Reportadas</h2>
            <p>Revisa el estado de tus solicitudes de soporte y confirma si han sido resueltas.</p>
            <? foreach ($incidencias as $incidencia): ?>
                <div class="incidencia-card" id="incidencia-<?= $incidencia['id_task'] ?>">
                    <h3><?= $incidencia['titulo'] ?> <?php color_estado_incidencia($incidencia['status']) ?> </h3>

                    <p><strong>Mensaje Original:</strong> <?= htmlspecialchars($incidencia['message']) ?></p>
                    <p style="font-size: 0.9em; color: #777;">Categoría: <?= $incidencia['option_name'] ?>. Reportada: <?= $incidencia['date_task'] ?></p>
                    <? if ($incidencia['response'] != NULL): ?>
                        <div class="incidencia-respuesta">
                            <strong>Respuesta del Administrador:</strong>
                            <p><?= htmlspecialchars($incidencia['response']) ?></p>
                        </div>
                    <? endif ?>
                    <? if ($incidencia['status'] != 'Cerrada' && $incidencia['status'] != 'Resuelta'): ?>
                        <div class="incidencia-acciones">
                            <p>¿La respuesta ha resuelto tu incidencia?</p>
                            <button class="btn-accion btn-resuelto" onclick="confirmarResolucion(<?= $incidencia['id_task'] ?>, true)">
                                <i class="fas fa-check-circle"></i> Sí, Resuelta
                            </button>
                            <button class="btn-accion btn-no-resuelto" onclick="confirmarResolucion(<?= $incidencia['id_task'] ?>, false)">
                                <i class="fas fa-times-circle"></i> No, Sigue el Problema
                            </button>
                        </div>
                    <? endif ?>
                </div>
            <? endforeach ?>



        </section>

        <hr style="margin-top: 50px; border: 0; border-top: 1px dashed var(--color-borde-claro);">

        <section>
            <h2>Comentarios Publicados</h2>
            <? foreach ($comentarios as $comentario): ?>
                <?
          
                $comentario_texto_json = json_encode($comentario['text_comment']);
               
                $comentario_texto_json_escapado = htmlspecialchars($comentario_texto_json, ENT_QUOTES, 'UTF-8');
                ?>
                <div class="comentario" id="comentario-<?= $comentario['id_comment'] ?>">
                    <p class="contenido"><?= htmlspecialchars($comentario['text_comment']) ?></p>
                    <p class="fecha" style="font-size: 0.9em; color: #777;">Publicado: <?= $comentario['data_comment'] ?></p>
                    <div class="acciones">
                        <a href="#" onclick="abrirModal(<?= $comentario['id_comment'] ?>, <?= $comentario_texto_json_escapado ?>)">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="./functions/delete/eliminar_comentario_perfil.php?action=delete&id=<?= $comentario['id_comment'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este comentario?')" title="Eliminar permanentemente">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </a>
                    </div>
                </div>
            <? endforeach ?>
        </section>
    </div>

    <div id="modalComentario" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2>Modificar Comentario</h2>


            <form action="./functions/update/editar_comentarios_perfil.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="comentario_id" id="modal_comentario_id">

                <div class="form-group">
                    <label for="comentario_texto">Nuevo Texto:</label>
                    <textarea id="comentario_texto" name="comentario_texto" rows="7"></textarea>
                </div>
                <button type="submit" class="btn-submit">Guardar Modificación</button>
            </form>

        </div>
    </div>

    <script>
        // --- Funciones del Modal de Edición de Comentarios ---
        function abrirModal(id, texto) {
            // Esto debería funcionar ahora que el JSON está bien escapado en el HTML
            document.getElementById('modalComentario').classList.add('show-modal');

            // 1. Asigna la ID real al campo oculto para enviarla al servidor
            document.getElementById('modal_comentario_id').value = id;

            // 2. Asigna el texto al textarea del modal
            document.getElementById('comentario_texto').value = texto;
        }

        function cerrarModal() {
            document.getElementById('modalComentario').classList.remove('show-modal');
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                cerrarModal();
            }
        }

        // --- Lógica de la Sección de Incidencias ---
        function confirmarResolucion(incidenciaId, estaResuelta) {
            if (estaResuelta) {
                // En un entorno de producción, aquí se haría una llamada AJAX para actualizar la DB
                alert('Incidencia #' + incidenciaId + ' marcada como RESUELTA. ¡Gracias por confirmar!');

                // Para simular el cambio visual
                const incidenciaCard = document.getElementById('incidencia-' + incidenciaId);
                const estadoSpan = incidenciaCard.querySelector('h3 .badge');

                // Asegurar que exista el elemento de estado
                if (estadoSpan) {
                    estadoSpan.innerText = 'Cerrada';
                    estadoSpan.classList.remove('badge-danger', 'badge-warning', 'badge-success');
                    estadoSpan.classList.add('badge-secondary');
                }

                // Reemplazar los botones de acción
                const accionesDiv = incidenciaCard.querySelector('.incidencia-acciones');
                if (accionesDiv) {
                    accionesDiv.innerHTML = '<p>✅ Incidencia marcada como cerrada por el usuario.</p>';
                }


            } else {
                // En un entorno de producción, aquí se haría una llamada AJAX para notificar al Admin
                alert('Incidencia #' + incidenciaId + ': Notificando al equipo de soporte que el problema persiste. Revisarán su caso de nuevo.');
            }
        }
    </script>

</body>

</html>