<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("perfil");

// Reutilizamos los datos del usuario logueado
require_once "../backend/transportador/obtener_perfil.php";

$mensaje = $_SESSION['mensaje_password'] ?? "";
$tipoMensaje = $_SESSION['tipo_password'] ?? "";

// Borramos el mensaje para que solo salga una vez
unset($_SESSION['mensaje_password']);
unset($_SESSION['tipo_password']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña - Transportador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/cliente-cambiar contraseña.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">
        <?php echo htmlspecialchars($perfil['nombre_rol']); ?>
    </div>

    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon">
            <img src="../../img/campana.png" width="24" alt="Notificaciones">
        </span>

        <span>
            <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
        </span>

        <span class="icon">
            <img src="../../img/perfil.png" width="24" alt="Perfil">
        </span>
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li>
                <a href="panel_principal.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>Panel Principal
                </a>
            </li>

            <li>
                <a href="zonas_despachos.php">
                    <span class="icon"><img src="../../img/despachos.png" alt=""></span>Zonas y despachos
                </a>
            </li>

            <li>
                <a href="listado_pedidos.php">
                    <span class="icon"><img src="../../img/listado-pedidos.png" alt=""></span>Listado de pedidos
                </a>
            </li>

            <li>
                <a href="reportes_despachos.php">
                    <span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes de despachos
                </a>
            </li>

            <li class="active-item">
                <a href="perfil.php">
                    <span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil
                </a>
            </li>

            <li>
                <a href="cerrar_sesion.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Cambiar contraseña</h1>

        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje-alerta <?php echo $tipoMensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form class="form-pass" action="../backend/transportador/cambiar_contrasena.php" method="POST">

            <input type="hidden" name="id_usuario" value="<?php echo $perfil['id_usuario']; ?>">

            <label>Contraseña actual:</label>
            <input type="password" name="password_actual" required>

            <label>Nueva contraseña:</label>
            <input type="password" name="password_nueva" required>

            <label>Confirmar nueva contraseña:</label>
            <input type="password" name="password_confirmar" required>

            <div class="pass-botones">
                <button class="btn-guardar" type="submit">Guardar</button>
                <button type="button" class="btn-cancelar" onclick="location.href='perfil.php'">Cancelar</button>
            </div>

        </form>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

<script>
    setTimeout(function () {
        const mensaje = document.querySelector('.mensaje-alerta');
        if (mensaje) {
            mensaje.style.transition = 'opacity 0.5s ease';
            mensaje.style.opacity = '0';

            setTimeout(function () {
                mensaje.remove();
            }, 500);
        }
    }, 4000);
</script>

</body>
</html>