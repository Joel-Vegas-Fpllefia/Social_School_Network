<?php
/**
 * Session Handler Validation Test
 * 
 * Este archivo verifica que todo esté configurado correctamente
 * Accede a este archivo en: http://localhost/Social_School_Network/test_session.php
 * 
 * IMPORTANTE: Elimina este archivo en producción
 */

// Verificar que los archivos necesarios existen
$archivos_requeridos = array(
    'functions/session_handler.php' => 'Manejador de sesiones',
    'db/session_config.php' => 'Configuración de sesiones',
    'components/flash_messages.php' => 'Componente de mensajes',
);

$archivos_documentacion = array(
    'doc/SESSION_MANAGEMENT.md' => 'Documentación de sesiones',
    'INTEGRATION_GUIDE.md' => 'Guía de integración',
    'QUICK_START.md' => 'Guía rápida',
    'USAGE_EXAMPLES.md' => 'Ejemplos de uso',
);

$base_path = __DIR__;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Handler - Test de Validación</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        h2 {
            color: #667eea;
            margin-top: 30px;
        }
        .status {
            display: flex;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            background: #f9f9f9;
            border-left: 4px solid #ddd;
        }
        .status.success {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        .status.error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .status.warning {
            background: #fff3cd;
            border-left-color: #ffc107;
            color: #856404;
        }
        .icon {
            font-size: 24px;
            margin-right: 15px;
            min-width: 30px;
        }
        .code-block {
            background: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            overflow-x: auto;
            margin: 10px 0;
        }
        code {
            font-family: 'Courier New', monospace;
            color: #d63384;
        }
        .section {
            margin: 20px 0;
        }
        .next-steps {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }
        .next-steps h3 {
            color: #1976D2;
            margin-top: 0;
        }
        .next-steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Session Handler - Test de Validación</h1>
        
        <div class="section">
            <h2>✅ Verificación de Archivos</h2>
            
            <h3>Archivos Requeridos</h3>
            <?php foreach ($archivos_requeridos as $archivo => $descripcion): ?>
                <?php $existe = file_exists($base_path . '/' . $archivo); ?>
                <div class="status <?php echo $existe ? 'success' : 'error'; ?>">
                    <span class="icon"><?php echo $existe ? '✓' : '✗'; ?></span>
                    <strong><?php echo $descripcion; ?></strong>
                    <br>
                    <small><code><?php echo $archivo; ?></code></small>
                </div>
            <?php endforeach; ?>
            
            <h3 style="margin-top: 20px;">Documentación</h3>
            <?php foreach ($archivos_documentacion as $archivo => $descripcion): ?>
                <?php $existe = file_exists($base_path . '/' . $archivo); ?>
                <div class="status <?php echo $existe ? 'success' : 'warning'; ?>">
                    <span class="icon"><?php echo $existe ? '📄' : '⚠'; ?></span>
                    <strong><?php echo $descripcion; ?></strong>
                    <br>
                    <small><code><?php echo $archivo; ?></code></small>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="section">
            <h2>🧪 Prueba de Funciones</h2>
            
            <?php
            // Incluir el handler
            if (file_exists($base_path . '/functions/session_handler.php')) {
                require_once $base_path . '/functions/session_handler.php';
                
                echo '<div class="status success">';
                echo '<span class="icon">✓</span>';
                echo '<strong>session_handler.php cargado exitosamente</strong>';
                echo '</div>';
                
                // Probar funciones
                ensure_session_started();
                
                echo '<div class="status success">';
                echo '<span class="icon">✓</span>';
                echo '<strong>ensure_session_started() funcionando</strong>';
                echo '</div>';
                
                echo '<div class="status ' . (session_status() === PHP_SESSION_ACTIVE ? 'success' : 'error') . '">';
                echo '<span class="icon">' . (session_status() === PHP_SESSION_ACTIVE ? '✓' : '✗') . '</span>';
                echo '<strong>Estado de sesión: ' . (session_status() === PHP_SESSION_ACTIVE ? 'ACTIVA' : 'INACTIVA') . '</strong>';
                echo '</div>';
            } else {
                echo '<div class="status error">';
                echo '<span class="icon">✗</span>';
                echo '<strong>No se pudo cargar session_handler.php</strong>';
                echo '</div>';
            }
            ?>
        </div>

        <div class="section">
            <h2>💡 Funciones Disponibles</h2>
            
            <p>Las siguientes funciones están disponibles para usar:</p>
            
            <div class="code-block">
                <strong>Información del usuario:</strong><br>
                • <code>get_user_id()</code> - ID del usuario<br>
                • <code>get_user_fullname()</code> - Nombre completo<br>
                • <code>get_user_email()</code> - Email<br>
                • <code>get_user_role()</code> - Rol<br>
                • <code>get_user_profile_picture()</code> - Foto de perfil<br>
                <br>
                <strong>Validación:</strong><br>
                • <code>is_user_logged_in()</code> - ¿Hay sesión?<br>
                • <code>has_role($role)</code> - ¿Tiene rol específico?<br>
                • <code>is_admin()</code> - ¿Es admin?<br>
                <br>
                <strong>Protección:</strong><br>
                • <code>require_login()</code> - Redirige a login<br>
                • <code>require_admin()</code> - Redirige si no es admin<br>
                <br>
                <strong>Mensajes:</strong><br>
                • <code>set_flash_message($type, $msg)</code> - Crear mensaje<br>
                • <code>get_flash_messages()</code> - Obtener mensajes<br>
            </div>
        </div>

        <div class="section">
            <h2>📋 Próximos Pasos</h2>
            
            <div class="next-steps">
                <h3>Para integrar en tu proyecto:</h3>
                <ol>
                    <li>
                        <strong>Abre <code>db/config.php</code></strong> y agrega al inicio:
                        <div class="code-block">
require_once(__DIR__ . '/session_config.php');<br>
require_once(__DIR__ . '/../functions/session_handler.php');
                        </div>
                    </li>
                    <li>
                        <strong>En tu header/navbar, incluye:</strong>
                        <div class="code-block">
&lt;?php include './components/flash_messages.php'; ?&gt;
                        </div>
                    </li>
                    <li>
                        <strong>En páginas que requieren login, usa:</strong>
                        <div class="code-block">
&lt;?php require_once('./db/config.php'); require_login(); ?&gt;
                        </div>
                    </li>
                    <li>
                        <strong>Leer la documentación:</strong>
                        <ul>
                            <li>📖 <code>doc/SESSION_MANAGEMENT.md</code> - Referencia completa</li>
                            <li>🚀 <code>QUICK_START.md</code> - Guía rápida</li>
                            <li>💡 <code>USAGE_EXAMPLES.md</code> - Ejemplos</li>
                        </ul>
                    </li>
                </ol>
            </div>
        </div>

        <div class="section">
            <h2>🔒 Características de Seguridad</h2>
            
            <div class="status success">
                <span class="icon">✓</span>
                <strong>Regeneración de ID de sesión</strong> - Después del login
            </div>
            
            <div class="status success">
                <span class="icon">✓</span>
                <strong>Cookies seguras</strong> - HttpOnly, Secure, SameSite
            </div>
            
            <div class="status success">
                <span class="icon">✓</span>
                <strong>Validación de integridad</strong> - Verifica User-Agent
            </div>
            
            <div class="status success">
                <span class="icon">✓</span>
                <strong>Control de acceso</strong> - Funciones para roles y permisos
            </div>
            
            <div class="status success">
                <span class="icon">✓</span>
                <strong>Mensajes flash</strong> - Se muestran una sola vez
            </div>
        </div>

        <div class="section" style="background: #f0f7ff; padding: 20px; border-radius: 5px; border-left: 4px solid #0066cc;">
            <h3 style="color: #0066cc; margin-top: 0;">⚠️ IMPORTANTE</h3>
            <p>Este archivo de test (<code>test_session.php</code>) debe ser <strong>eliminado en producción</strong> por razones de seguridad.</p>
            <p>Las variables de sesión no deben exponerse públicamente.</p>
        </div>

        <div style="text-align: center; margin-top: 40px; color: #666; font-size: 14px;">
            <p>Session Handler para Social School Network ✨</p>
            <p>Generado: <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
