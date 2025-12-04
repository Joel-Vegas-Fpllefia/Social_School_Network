<?php
session_start();
require_once('./db/config.php');
include './querys/selects.php';

if (!isset($_SESSION['user'])) {
    if ($_SESSION['role'] != 'Admin') {
        header('Location: index.php');
        exit();
    }
}
$puestos = select("SELECT jb.id_job , jb.job FROM jobs jb", $mysqli);
$category_roles = select("SELECT rl.id_role , rl.role FROM roles rl",$mysqli);
$amount_users = select("SELECT COUNT(us.id_user) AS cantidad FROM users us", $mysqli);
$amount_news = select("SELECT COUNT(nw.id_news) AS cantidad FROM news nw;", $mysqli);
$amount_comments = select("SELECT COUNT(nrc.id_nvc) AS cantidad FROM news_relation_comment nrc LEFT JOIN response rp ON rp.id_nrc_parent = nrc.id_nvc WHERE rp.id_nrc_parent IS NULL", $mysqli);
$estado_incidencies = select("SELECT st.status FROM status_task st", $mysqli);
$category_incidencies = select("SELECT tk.id_options_task,tk.option_name FROM options_task tk", $mysqli);
$incidencias = select("SELECT tk.id_task, us.id_user, us.name, us.surname, tk.message, tk.date_task, opt.option_name, stt.status, opt.option_name FROM tasks tk JOIN users us ON us.id_user = tk.id_user JOIN options_task opt ON opt.id_options_task = tk.id_options_support JOIN status_task stt ON stt.id_options_status = tk.status_task;", $mysqli);
$noticias = select("SELECT nw.id_news, nw.title, nw.descr, nw.content_new, nw.imagen, nw.fecha_creacion, ct.category, ct.id_category  FROM news nw JOIN category ct ON nw.id_category = ct.id_category", $mysqli);
$categoria_noticias = select("SELECT ct.category, ct.id_category FROM category ct", $mysqli);
$testimonios = select("SELECT op.id_opinion, us.id_user, us.name, us.surname, op.comentario, op.puntuacion, rl.role FROM opinions op JOIN users us ON op.id_user = us.id_user JOIN roles rl ON rl.id_role = us.id_role;", $mysqli);
$faqs = select("SELECT fq.id_faqs, fq.title, fq.text, sfq.section_faq FROM faqs fq JOIN sections_faqs sfq ON fq.id_section_faq = sfq.id_sections_faqs;", $mysqli);
$categoria_faqs = select("SELECT sfq.id_sections_faqs ,sfq.section_faq FROM sections_faqs sfq", $mysqli);
$users = select("SELECT us.id_user,us.password, us.name, us.surname, us.mail, rl.role, jb.job FROM users us JOIN roles rl ON us.id_role = rl.id_role LEFT JOIN jobs jb ON jb.id_job = us.id_job", $mysqli);
function tipo_estado_incidencias($estado)
{
    switch ($estado) {
        case "Abierta":
            echo '<td><span class="badge badge-danger">' . $estado . '</span></td>';
            break;
        case "En Progreso":
            echo '<td><span class="badge badge-warning">' . $estado . '</span></td>';
            break;
        case "Resuelta":
            echo '<td><span class="badge badge-success">' . $estado . '</span></td>';
            break;
        case "Cerrada":
            echo '<td><span class="badge badge-secondary">' . $estado . '</span></td>';
            break;
        default:
            echo '<td><span class="badge badge-info">' . $estado . '</span></td>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>SchoolGram | Panel de Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --dark-color: #2d3561;
            --light-color: #f8f9fa;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            background-color: var(--light-color);
            overflow-x: hidden;
        }

        /* 1. Navbar Superior (Dashboard Header) */
        .admin-header {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 70px;
            /* Altura fija */
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .admin-header .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-color);
            margin-right: auto;
            display: flex;
            align-items: center;
        }

        .admin-header .navbar-brand i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        /* 2. Sidebar Lateral */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 70px;
            /* Deja espacio para el header */
            left: 0;
            background: var(--dark-color);
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.15);
            transition: all 0.3s;
            z-index: 1020;
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            display: block;
            transition: all 0.3s;
            border-left: 5px solid transparent;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background: var(--secondary-color);
            border-left: 5px solid var(--accent-color);
            font-weight: 600;
        }

        /* 3. Contenido Principal */
        .content {
            margin-left: 250px;
            /* Debe ser igual al ancho del sidebar */
            padding: 90px 30px 30px;
            /* Ajuste para el header */
            min-height: 100vh;
        }

        /* Estilos de las Tarjetas de Métrica (Widgets) */
        .metric-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .metric-card h4 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .metric-card p {
            margin: 0;
            color: #666;
            font-weight: 500;
        }

        .metric-icon {
            font-size: 40px;
            color: var(--primary-color);
            opacity: 0.7;
        }

        /* Estilo para las tablas de gestión */
        .management-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background-color: var(--dark-color);
            color: white;
            border: none;
            font-weight: 600;
        }

        /* Botón de añadir con gradiente */
        .btn-add-custom {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border: none;
            transition: opacity 0.3s;
        }

        .btn-add-custom:hover {
            opacity: 0.9;
            color: white;
        }

        /* Estilo para el header del modal */
        .modal-header.bg-add {
            background-color: var(--primary-color) !important;
            color: white;
        }

        .modal-header.bg-edit {
            background-color: #17a2b8 !important;
            color: white;
        }

        /* Estilos específicos para Incidencias */
        .incidencia-respuesta {
            background-color: var(--light-color);
            border-left: 3px solid var(--primary-color);
            padding: 10px;
            margin-top: 10px;
            font-size: 0.9em;
            border-radius: 4px;
        }

        /* Estilos para el contenedor de filtros */
        #formFiltrosIncidencias {
            background-color: #f0f3ff;
            border: 1px solid #dcdcdc;
        }

        /* Estilo para botones de acción pequeños */
        .table .btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: .2rem;
        }

        .table .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .table .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }

            .sidebar a span {
                display: none;
            }

            .sidebar a {
                text-align: center;
                padding: 15px 0;
            }

            .sidebar a i {
                margin: 0;
            }

            .content {
                margin-left: 70px;
            }

            .admin-header .navbar-brand {
                margin-left: 50px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                height: auto;
                position: relative;
                width: 100%;
                top: 0;
                padding: 0;
            }

            .content {
                margin-left: 0;
                padding-top: 20px;
            }

            .admin-header {
                position: static;
                height: auto;
                box-shadow: none;
            }

            .admin-header .navbar-brand {
                margin-left: 0;
            }

            .sidebar a {
                display: inline-block;
                width: 25%;
                /* Para 4 iconos por fila */
                text-align: center;
                border-left: none;
                border-bottom: 3px solid transparent;
            }

            .sidebar a.active {
                border-left: none;
                border-bottom: 3px solid var(--accent-color);
            }
        }
    </style>
</head>

<body>
    <header class="admin-header">
        <a class="navbar-brand" href="#">
            <i class="fas fa-columns"></i>
            SchoolGram Admin
        </a>
        <div class="ml-auto">
            <?php
            echo '
                <span class="text-secondary mr-3"><i class="fas fa-user-circle"></i> ' . $_SESSION['user_name'] . ' </span>
                ';
            ?>
            <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </header>

    <div class="sidebar">
        <a href="admin.php" class="active">
            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
        </a>
        <a href="#gestion-noticias">
            <i class="fas fa-newspaper"></i> <span>Noticias</span>
        </a>
        <a href="#gestion-testimonios">
            <i class="fas fa-star"></i> <span>Testimonios</span>
        </a>
        <a href="#gestion-faqs">
            <i class="fas fa-question-circle"></i> <span>FAQs</span>
        </a>
        <a href="#gestion-incidencias">
            <i class="fas fa-headset"></i> <span>Incidencias</span>
        </a>
        <hr style="border-color: rgba(255,255,255,0.1); margin: 10px 20px;">
        <a href="#gestion-usuarios">
            <i class="fas fa-users"></i> <span>Usuarios</span>
        </a>
    </div>

    <main class="content">
        <h1 class="mb-4">Dashboard Principal</h1>

        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="metric-card">
                    <div>
                        <?php foreach ($amount_users as $number): ?>
                            <h4><?= $number['cantidad'] ?></h4>
                        <? endforeach ?>
                        <p>Usuarios Registrados</p>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="metric-card">
                    <div>
                        <? foreach ($amount_news as $cantidad_news): ?>
                            <h4><?= $cantidad_news['cantidad'] ?></h4>
                        <? endforeach ?>
                        <p>Noticias Publicadas</p>
                    </div>
                    <div class="metric-icon" style="color: var(--secondary-color);">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="metric-card">
                    <div>
                        <? foreach ($amount_comments as $cantidad_comen): ?>
                            <h4><?= $cantidad_comen['cantidad'] ?></h4>
                        <? endforeach ?>
                        <p>Comentarios Nuevos</p>
                    </div>
                    <div class="metric-icon" style="color: var(--accent-color);">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>

        </div>

        <section id="gestion-incidencias" class="management-section mb-5">
            <h2 class="h4 text-primary mb-4"><i class="fas fa-headset"></i> Gestión de Incidencias</h2>

            <form id="formFiltrosIncidencias" class="mb-4 p-3">
                <h5 class="mb-3 text-secondary"><i class="fas fa-filter"></i> Opciones de Filtrado</h5>
                <div class="row align-items-end">

                    <div class="col-md-4 col-sm-6 form-group mb-3 mb-md-0">
                        <label for="filtroEstado">Filtrar por **Estado**:</label>
                        <select class="form-control" id="filtroEstado" name="estado">
                            <option value="">-- Todos los Estados --</option>
                            <? foreach ($estado_incidencies as $estado): ?>
                                <option value="<?= $estado['status'] ?>"><?= $estado['status'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-6 form-group mb-3 mb-md-0">
                        <label for="filtroCategoria">Filtrar por **Categoría**:</label>
                        <select class="form-control" id="filtroCategoria" name="categoria">
                            <option value="">-- Todas las Categorías --</option>
                            <? foreach ($category_incidencies as $category): ?>
                                <option value="<?= $category['option_name'] ?>"><?= $category['option_name'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4 col-sm-12 form-group mb-0 d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-add-custom mr-2" onclick="aplicarFiltros()">
                            <i class="fas fa-search"></i> **Filtrar**
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                            <i class="fas fa-times"></i> Limpiar
                        </button>
                    </div>
                </div>
            </form>
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-sm btn-outline-primary"><i class="fas fa-sync-alt"></i> Actualizar Listado</button>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asunto</th>
                        <th>Usuario</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($incidencias as $incidencia): ?>
                        <tr data-estado="<?= $incidencia['status'] ?>" data-categoria="<?= $incidencia['option_name'] ?>">
                            <td><?= $incidencia['id_task'] ?></td>
                            <td><?= $incidencia['message'] ?></td>
                            <td> <?= $incidencia['name'] ?> <?= $incidencia['surname'] ?> (ID <?= $incidencia['id_user'] ?>)</td>
                            <td><?= $incidencia['option_name'] ?></td>
                            <?= tipo_estado_incidencias($incidencia['status']) ?>
                            <td><?= $incidencia['date_task'] ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary"
                                    onclick="simularEditar(
                                        <?= $incidencia['id_task'] ?>, 
                                        'Incidencia', 
                                        '<?= htmlspecialchars($incidencia['message']) ?>',
                                        {
                                            usuario: '<?= htmlspecialchars($incidencia['name'] . ' ' . $incidencia['surname']) ?>',
                                            estado_actual: '<?= htmlspecialchars($incidencia['status']) ?>'
                                        })">
                                    <i class="fas fa-reply"></i> Responder
                                </button>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="gestion-noticias" class="management-section mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 text-primary"><i class="fas fa-newspaper"></i> Gestión de Noticias</h2>
                <button class="btn btn-add-custom" onclick="simularCrear('Noticia')"><i class="fas fa-plus"></i> Añadir Nueva Noticia</button>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Categoría</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Contenido</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($noticias as $noticia): ?>
                        <tr>
                            <td><?= $noticia['id_news'] ?></td>
                            <td><?= $noticia['title'] ?></td>
                            <td><?= $noticia['fecha_creacion'] ?></td>
                            <td><?= $noticia['category'] ?></td>

                            <td>
                                <img src="<?= $noticia['imagen'] ?>" alt="Miniatura de la noticia" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>

                            <td><?= $noticia['descr'] ?></td>

                            <td> <?= htmlspecialchars($noticia['content_new']) ?> </td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-info"
                                    onclick="simularEditar(
                                        <?= $noticia['id_news'] ?>, 
                                        'Noticia', 
                                        '<?= htmlspecialchars($noticia['title']) ?>', 
                                        {
                                            // CLAVE: json_encode() para el HTML complejo
                                            contenido: <?= htmlspecialchars(json_encode($noticia['content_new']), ENT_QUOTES, 'UTF-8') ?>,
                                            descripcion: '<?= htmlspecialchars($noticia['descr']) ?>',
                                            categoria: '<?= htmlspecialchars($noticia['category']) ?>',
                                            imagen: '<?= htmlspecialchars($noticia['imagen']) ?>'
                                        }
                                    )">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="./functions/delete/eliminar_noticia.php?id_new=<?= $noticia['id_news'] ?>" class="btn-eliminar btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este comentario?')" title="Eliminar permanentemente">
                                    <i class="fas fa-trash-alt"> </i>
                                </a>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="gestion-testimonios" class="management-section mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 text-primary"><i class="fas fa-star"></i> Gestión de Testimonios</h2>
                <button class="btn btn-add-custom" onclick="simularCrear('Testimonio')"><i class="fas fa-plus"></i> Añadir Testimonio</button>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Autor</th>
                        <th>Contenido</th>
                        <th>Puntuación</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($testimonios as $testimonio) : ?>
                        <tr>
                            <td><?= $testimonio['id_opinion'] ?></td>
                            <td><?= $testimonio['name'] ?> <?= $testimonio['surname'] ?> (<?= $testimonio['role'] ?>)</td>
                            <td>"<?= $testimonio['comentario'] ?>"</td>
                            <td> <?= $testimonio['puntuacion'] ?>/5</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info"
                                    onclick="simularEditar(
                                        <?= $testimonio['id_opinion'] ?>, 
                                        'Testimonio', 
                                        '<?= htmlspecialchars($testimonio['name'] . ' ' . $testimonio['surname']) ?>',
                                        {
                                            comentario: '<?= htmlspecialchars($testimonio['comentario']) ?>',
                                            puntuacion: '<?= htmlspecialchars($testimonio['puntuacion']) ?>',
                                            rol: '<?= htmlspecialchars($testimonio['role']) ?>'
                                        })">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- CAMBIAR URL, (COGER EL PARAMETRO CON GET) -->
                                <a href="./functions/delete/eliminar_testimonio.php?id_opinion=<?= $testimonio['id_opinion'] ?>" class="btn-eliminar btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este comentario?')" title="Eliminar permanentemente">
                                    <i class="fas fa-trash-alt"> </i>
                                </a>
                            </td>
                        </tr>
                    <? endforeach ?>

                </tbody>
            </table>
        </section>

        <section id="gestion-faqs" class="management-section mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 text-primary"><i class="fas fa-question-circle"></i> Gestión de Preguntas Frecuentes (FAQs)</h2>
                <button class="btn btn-add-custom" onclick="simularCrear('FAQ')"><i class="fas fa-plus"></i> Añadir Nueva FAQ</button>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pregunta</th>
                        <th>Respuesta</th>
                        <th>Categoría</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($faqs as $faq): ?>
                        <tr>
                            <td><?= $faq['id_faqs'] ?></td>
                            <td><?= $faq['title'] ?></td>
                            <td><?= $faq['text'] ?></td>
                            <td><?= $faq['section_faq'] ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info"
                                    onclick="simularEditar(
                                        <?= $faq['id_faqs'] ?>, 
                                        'FAQ', 
                                        '<?= htmlspecialchars($faq['title']) ?>',
                                        {
                                            respuesta: '<?= htmlspecialchars($faq['text']) ?>',
                                            categoria: '<?= htmlspecialchars($faq['section_faq']) ?>'
                                        })">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- CAMBIAR URL, (COGER EL PARAMETRO CON GET) -->
                                <a href="./functions/delete/eliminar_faq.php?id_faq=<?= $faq['id_faqs'] ?>" class="btn-eliminar btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este comentario?')" title="Eliminar permanentemente">
                                    <i class="fas fa-trash-alt"> </i>
                                </a>
                            </td>
                        </tr>
                    <? endforeach ?>
                </tbody>
            </table>
        </section>

        <section id="gestion-usuarios" class="management-section mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 text-primary"><i class="fas fa-users"></i> Gestión de Usuarios</h2>
                <button class="btn btn-add-custom" onclick="simularCrear('Usuario')"><i class="fas fa-plus"></i> Añadir Nuevo Usuario</button>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Puesto</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id_user'] ?></td>
                            <td><?= $user['name'] ?></td>
                            <td><?= $user['surname'] ?></td>
                            <td><?= $user['mail'] ?></td>
                            <td><code style="font-size: 0.75rem; word-break: break-all;"><?= $user['password'] ?>...</code></td>
                            <td><?= $user['role'] ?></td>
                            <td><?= $user['job'] ?></td>

                            <td class="text-center">
                                <button class="btn btn-sm btn-info"
                                    onclick="simularEditar(
                                        <?= $user['id_user'] ?>, 
                                        'Usuario', 
                                        '<?= htmlspecialchars($user['name'] . ' ' . $user['surname']) ?>',
                                        {
                                            nombre: '<?= htmlspecialchars($user['name']) ?>',
                                            apellido: '<?= htmlspecialchars($user['surname']) ?>',
                                            email: '<?= htmlspecialchars($user['mail']) ?>',
                                            password: '<?= htmlspecialchars($user['password']) ?>',
                                            rol: '<?= htmlspecialchars($user['role']) ?>',
                                            puesto: '<?= htmlspecialchars($user['job']) ?>'
                                        })">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="./functions/delete/eliminar_usuario.php?id=<?= $user['id_user'] ?>" class="btn-eliminar btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este comentario?')" title="Eliminar permanentemente">
                                    <i class="fas fa-trash-alt"> </i>
                                </a>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </tbody>
            </table>
        </section>

    </main>

    <div class="modal fade" id="modalNoticia" tabindex="-1" role="dialog" aria-labelledby="modalNoticiaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" id="modalNoticiaHeader">
                    <h5 class="modal-title" id="modalNoticiaLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <!-- NOTICIAS -->
                <form id="formNoticia" action="./functions/update/noticias_admin.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="noticiaId" name="id_news">
                        <div class="form-group"><label for="noticiaTitulo">Título:</label><input type="text" class="form-control" id="noticiaTitulo" name="title" required></div>
                        <div class="form-group">
                            <label for="noticiaCategoria">Categoría:</label>
                            <select class="form-control" id="noticiaCategoria" name="category" required>
                                <? foreach ($categoria_noticias as $noticia): ?>
                                    <option value=<?= $noticia['id_category'] ?>><?= $noticia['category'] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <div class="form-group"><label for="noticiaDesc">Descripción (Texto plano):</label><textarea class="form-control" id="noticiaDesc" name="descr" rows="2" required></textarea></div>
                        <div class="form-group"><label for="noticiaContenido">Contenido (Incluye HTML):</label><textarea class="form-control" id="noticiaContenido" name="content_new" rows="8" required></textarea></div>
                        <div class="form-group"><label for="noticiaImagen">Imagen (URL o Ruta):</label><input type="text" class="form-control" id="noticiaImagen" name="imagen"></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-primary" id="btnGuardarNoticia">Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTestimonio" tabindex="-1" role="dialog" aria-labelledby="modalTestimonioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" id="modalTestimonioHeader">
                    <h5 class="modal-title" id="modalTestimonioLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <!-- Gestión de Testimonios -->
                <form id="formTestimonio" action="./functions/update/testimonios_admin.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="testimonioId" name="id_opinion">
                        <div class="form-group"><label for="testimonioAutor">Autor:</label><input type="text" class="form-control" id="testimonioAutor" name="autor" readonly></div>
                        <div class="form-group"><label for="testimonioComentario">Comentario:</label><textarea class="form-control" id="testimonioComentario" name="comentario" rows="4" required></textarea></div>
                        <div class="form-group">
                            <label for="testimonioPuntuacion">Puntuación (1-5):</label>
                            <select class="form-control" id="testimonioPuntuacion" name="puntuacion" required>
                                <option value="5">5 Estrellas</option>
                                <option value="4">4 Estrellas</option>
                                <option value="3">3 Estrellas</option>
                                <option value="2">2 Estrellas</option>
                                <option value="1">1 Estrella</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-primary" id="btnGuardarTestimonio">Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFaqs" tabindex="-1" role="dialog" aria-labelledby="modalFaqsLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" id="modalFaqsHeader">
                    <h5 class="modal-title" id="modalFaqsLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <!-- Gestión de Preguntas Frecuentes (FAQs) -->
                <form id="formFaqs" action="./functions/update/faqs_admin.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="faqsId" name="id_faqs">
                        <div class="form-group"><label for="faqsTitle">Pregunta / Título:</label><input type="text" class="form-control" id="faqsTitle" name="title" required></div>
                        <div class="form-group"><label for="faqsText">Respuesta:</label><textarea class="form-control" id="faqsText" name="text" rows="5" required></textarea></div>
                        <div class="form-group">
                            <label for="faqsSection">Sección:</label>
                            <select class="form-control" id="faqsSection" name="section_faq" required>
                                <!-- JOEL -->
                                <? foreach ($categoria_faqs as $categoria_faq): ?>
                                    <option value=<?= $categoria_faq['id_sections_faqs'] ?>><?= $categoria_faq['section_faq'] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-primary" id="btnGuardarFaqs">Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalIncidenciaRespuesta" tabindex="-1" aria-labelledby="modalIncidenciaRespuestaLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-edit">
                    <h5 class="modal-title" id="modalIncidenciaRespuestaLabel">Responder Incidencia #<span id="incidenciaIdDisplay"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <!-- INCIDENCIAS -->
                <form id="formIncidenciaRespuesta" action="./functions/update/incidencias_admin.php" method="POST">
                    <div class="modal-body">
                        <? foreach ($incidencias as $incidencia): ?>
                            <input type="hidden" id="incidenciaId" name="id_task" value=<?= $incidencia['id_task'] ?>>
                        <? endforeach ?>
                        <div class="form-group">
                            <label>Mensaje del Usuario:</label>
                            <p id="incidenciaMensaje" class="alert alert-light border"></p>
                        </div>

                        <div class="form-group">
                            <label>Estado Actual:</label>
                            <p id="incidenciaEstadoActual" class="font-weight-bold text-info"></p>
                        </div>

                        <div class="form-group">
                            <label for="incidenciaEstadoNuevo">Cambiar Estado a:</label>
                            <select class="form-control" id="incidenciaEstadoNuevo" name="status_task" required>
                                <? foreach ($estado_incidencies as $estado): ?>
                                    <option value="<?= $estado['status'] ?>"><?= $estado['status'] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="incidenciaRespuestaTexto">Respuesta/Comentario del Administrador:</label>
                            <textarea class="form-control" id="incidenciaRespuestaTexto" name="respuesta_admin" rows="3"></textarea>
                            <small class="form-text text-muted">Este comentario se guarda y puede notificarse al usuario.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-info" id="btnGuardarIncidencia">Actualizar Incidencia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" id="modalUsuarioHeader">
                    <h5 class="modal-title" id="modalUsuarioLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <!-- USUARIO -->
                <form id="formUsuario" action="./functions/update/usuario_admin.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="usuarioId" name="id_user">
                        <div class="form-group"><label for="usuarioNombre">Nombre:</label><input type="text" class="form-control" id="usuarioNombre" name="name" required></div>
                        <div class="form-group"><label for="usuarioApellido">Apellido:</label><input type="text" class="form-control" id="usuarioApellido" name="surname" required></div>
                        <div class="form-group"><label for="usuarioEmail">Email:</label><input type="email" class="form-control" id="usuarioEmail" name="mail" required></div>
                        <div class="form-group"><label for="usuarioPassword">Contraseña (Dejar vacío para no cambiar):</label><input type="password" class="form-control" id="usuarioPassword" name="password" placeholder="Nueva contraseña o vacío"></div>
                        <div class="form-group">
                            <label for="usuarioRol">Rol:</label>
                            <select class="form-control" id="usuarioRol" name="id_role" required>
                                <? foreach($category_roles as $category_role): ?>
                                    <option value=<?= $category_role['id_role'] ?>><?= $category_role['role'] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="usuarioPuesto">Puesto/Trabajo (Opcional):</label>
                            <select class="form-control" id="usuarioPuesto" name="id_job" required>
                                <? foreach($puestos as $puesto): ?>
                                    <option value=<?= $puesto['id_job'] ?>><?= $puesto['job'] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-primary" id="btnGuardarUsuario">Guardar Cambios</button></div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- Lógica de Interacción Simulación (CRUD) con Modales Específicos ---

        /**
         * Inicializa y abre el modal de Creación para un tipo específico.
         * @param {string} tipo - El tipo de elemento ('Noticia', 'Testimonio', 'FAQ', 'Usuario').
         */
        function simularCrear(tipo) {
            let modalId = '';
            let modalLabel = '';

            // Cerrar todos los modales para asegurar limpieza
            $('.modal').modal('hide');

            switch (tipo) {
                case 'Noticia':
                    modalId = '#modalNoticia';
                    modalLabel = 'Noticia';
                    // Limpiar campos específicos
                    $('#noticiaId').val('(Nuevo)');
                    $('#noticiaTitulo').val('');
                    $('#noticiaCategoria').val('General'); // Default
                    $('#noticiaDesc').val('');
                    $('#noticiaContenido').val('');
                    $('#noticiaImagen').val('');
                    break;
                case 'Testimonio':
                    modalId = '#modalTestimonio';
                    modalLabel = 'Testimonio';
                    $('#testimonioId').val('(Nuevo)');
                    $('#testimonioAutor').val('Asignado al Admin');
                    $('#testimonioComentario').val('');
                    $('#testimonioPuntuacion').val('5');
                    break;
                case 'FAQ':
                    modalId = '#modalFaqs';
                    modalLabel = 'Pregunta Frecuente';
                    $('#faqsId').val('(Nuevo)');
                    $('#faqsTitle').val('');
                    $('#faqsText').val('');
                    $('#faqsSection').val('Matrícula'); // Default
                    break;
                case 'Usuario':
                    modalId = '#modalUsuario';
                    modalLabel = 'Usuario';
                    $('#usuarioId').val('(Nuevo)');
                    $('#usuarioNombre').val('');
                    $('#usuarioApellido').val('');
                    $('#usuarioEmail').val('');
                    $('#usuarioRol').val('Estudiante'); // Default
                    $('#usuarioPuesto').val('');
                    break;
                default:
                    return;
            }

            // Configurar Modal
            $(`${modalId}Label`).text(`➕ Añadir Nuevo ${modalLabel}`);
            $(`${modalId}Header`).removeClass('bg-edit').addClass('bg-add');

            // Mostrar el modal
            $(modalId).modal('show');
        }

        /**
         * Inicializa y abre el modal de Edición para un elemento existente.
         * @param {number} id - El ID del elemento a editar.
         * @param {string} tipo - El tipo de elemento ('Noticia', 'Testimonio', 'FAQ', 'Incidencia', 'Usuario').
         * @param {string} titulo - El título o nombre (usado en el modal label).
         * @param {object} [datosExtra={}] - Objeto con datos específicos de la fila.
         */
        function simularEditar(id, tipo, titulo, datosExtra = {}) {
            let modalId = '';
            let modalLabel = '';

            // Cerrar todos los modales para asegurar limpieza
            $('.modal').modal('hide');

            switch (tipo) {
                case 'Noticia':
                    modalId = '#modalNoticia';
                    modalLabel = 'Noticia';
                    $('#noticiaId').val(id);
                    $('#noticiaTitulo').val(titulo);
                    $('#noticiaCategoria').val(datosExtra.categoria);
                    $('#noticiaDesc').val(datosExtra.descripcion);
                    // El contenido viene con JSON escape, lo cargamos directamente
                    $('#noticiaContenido').val(datosExtra.contenido);
                    $('#noticiaImagen').val(datosExtra.imagen);
                    break;

                case 'Testimonio':
                    modalId = '#modalTestimonio';
                    modalLabel = 'Testimonio';
                    $('#testimonioId').val(id);
                    $('#testimonioAutor').val(titulo); // Titulo es el nombre del autor
                    $('#testimonioComentario').val(datosExtra.comentario);
                    $('#testimonioPuntuacion').val(datosExtra.puntuacion);
                    break;

                case 'FAQ':
                    modalId = '#modalFaqs';
                    modalLabel = 'Pregunta Frecuente';
                    $('#faqsId').val(id);
                    $('#faqsTitle').val(titulo);
                    $('#faqsText').val(datosExtra.respuesta);
                    $('#faqsSection').val(datosExtra.categoria);
                    break;

                case 'Incidencia':
                    modalId = '#modalIncidenciaRespuesta';
                    modalLabel = 'Incidencia';
                    $('#incidenciaId').val(id);
                    $('#incidenciaIdDisplay').text(id);
                    $('#incidenciaMensaje').text(titulo); // Título es el mensaje del usuario
                    $('#incidenciaEstadoActual').text(datosExtra.estado_actual);
                    $('#incidenciaEstadoNuevo').val(datosExtra.estado_actual);
                    $('#incidenciaRespuestaTexto').val('');
                    break;

                case 'Usuario':
                    modalId = '#modalUsuario';
                    modalLabel = 'Usuario';
                    $('#usuarioId').val(id);
                    $('#usuarioNombre').val(datosExtra.nombre);
                    $('#usuarioApellido').val(datosExtra.apellido);
                    $('#usuarioEmail').val(datosExtra.email);
                    $('#usuarioPassword').val(datosExtra.password); // Mostrar hash actual
                    $('#usuarioRol').val(datosExtra.rol);
                    $('#usuarioPuesto').val(datosExtra.puesto);
                    break;

                default:
                    return;
            }

            // Configurar Modal
            $(`${modalId}Label`).text(`✏️ Editar ${modalLabel} #${id}`);
            $(`${modalId}Header`).removeClass('bg-add').addClass('bg-edit');

            // Mostrar el modal
            $(modalId).modal('show');
        }


        /**
         * Simula la acción de Eliminar un elemento existente.
         * @param {number} id - El ID del elemento a eliminar.
         * @param {string} tipo - El tipo de elemento (e.g., 'Noticia', 'Usuario').
         */
        function simularEliminar(id, tipo) {
            // 1. Mostrar la ventana de confirmación
            if (confirm(`⚠️ ¿Estás seguro de que quieres ELIMINAR el ${tipo} con ID #${id}? Esta acción no se puede deshacer.`)) {

                // 2. Preparar los datos que se enviarán por POST
                const urlEliminacion = './function/delete/eliminar_noticia.php';
                const data = new FormData();
                data.append('id', id);

                // 3. Enviar la solicitud DELETE al servidor usando Fetch
                fetch(urlEliminacion, {
                        method: 'GET',
                        body: data // Los datos de ID y Tipo
                    })
                    .then(response => {
                        // Manejar errores HTTP (ej: 404, 500)
                        if (!response.ok) {
                            throw new Error(`Error HTTP: ${response.status}`);
                        }
                        // Esperar que el PHP devuelva un JSON
                        return response.json();
                    })
                    .then(result => {
                        // 4. Procesar la respuesta del servidor (JSON)
                        if (result.success) {
                            // Eliminación exitosa en la DB
                            alert(`🗑️ ¡${tipo} #${id} eliminado correctamente!`);

                            // Opcional pero recomendado: Eliminar la fila de la tabla del DOM
                            // Asume que la fila de la tabla tiene el ID: "fila-15"
                            const fila = document.getElementById(`fila-${id}`);
                            if (fila) {
                                fila.remove();
                            }

                            // O si prefieres recargar la página para ver el cambio
                            // window.location.reload();

                        } else {
                            // El servidor PHP devolvió success: false (ej: error de DB)
                            alert(`❌ Error al eliminar ${tipo} #${id}: ${result.message}`);
                        }
                    })
                    .catch(error => {
                        // Fallo en la red o en el procesamiento del JSON
                        alert(`⚠️ Error de conexión/servidor: ${error.message}`);
                        console.error('Fetch Error:', error);
                    });

            } else {
                // El usuario hizo clic en "Cancelar"
                alert(`❌ Eliminación de ${tipo} #${id} cancelada.`);
            }
        }

        // --- Lógica de Filtrado de la Tabla de Incidencias (Mantenida) ---

        function aplicarFiltros() {
            const estadoSeleccionado = document.getElementById('filtroEstado').value;
            const categoriaSeleccionada = document.getElementById('filtroCategoria').value;
            const filas = document.querySelectorAll('#gestion-incidencias tbody tr');
            let contadorVisible = 0;

            filas.forEach(fila => {
                const estadoFila = fila.getAttribute('data-estado');
                const categoriaFila = fila.getAttribute('data-categoria');

                const matchEstado = estadoSeleccionado === '' || estadoFila === estadoSeleccionado;
                const matchCategoria = categoriaSeleccionada === '' || categoriaFila === categoriaSeleccionada;

                if (matchEstado && matchCategoria) {
                    fila.style.display = ''; // Mostrar fila
                    contadorVisible++;
                } else {
                    fila.style.display = 'none'; // Ocultar fila
                }
            });

            if (contadorVisible === 0) {
                console.log("No se encontraron coincidencias con los filtros.");
            }
        }

        function limpiarFiltros() {
            document.getElementById('filtroEstado').value = '';
            document.getElementById('filtroCategoria').value = '';
            aplicarFiltros(); // Restablece los filtros y muestra todas las filas
        }

        // --- Anclajes para el Sidebar (Mantenida) ---
        document.querySelectorAll('.sidebar a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                // Remueve la clase active de todos
                document.querySelectorAll('.sidebar a').forEach(item => {
                    item.classList.remove('active');
                });
                // Añade la clase active al elemento clickeado
                this.classList.add('active');
            });
        });

        // SIMULACIÓN DE GUARDADO PARA CADA FORMULARIO (Se puede integrar en una función genérica más avanzada)
        $('#formNoticia, #formTestimonio, #formFaqs, #formIncidenciaRespuesta, #formUsuario').on('submit', function(e) {
            const formId = $(this).attr('id');
            const itemId = $(this).find('input[type="hidden"]').val();
            const action = (itemId === '(Nuevo)') ? 'creado' : 'actualizado';
            const itemType = $(this).attr('id').replace('form', '');
            alert(`✅ ${itemType} ${action} (Simulación). ID: ${itemId}`);
            $(this).closest('.modal').modal('hide');
        });
    </script>
</body>

</html>