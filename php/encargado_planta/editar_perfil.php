<?php
session_start();

/* 
   Verificamos sesión y permiso
*/
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("perfil");

/* 
   Traemos los datos actuales del usuario
*/
require_once "../backend/encargado_planta/obtener_perfil.php";

/* 
   Mensajes temporales para mostrar después de guardar
*/
$mensaje = $_SESSION['mensaje_perfil'] ?? "";
$tipoMensaje = $_SESSION['tipo_perfil'] ?? "";

/* 
   Limpiamos mensajes para que no se repitan
*/
unset($_SESSION['mensaje_perfil']);
unset($_SESSION['tipo_perfil']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Encargado de planta</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/cliente-editar perfil.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">
        <?php echo htmlspecialchars($perfil['nombre_rol']); ?>
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

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

    <!-- MENÚ LATERAL -->
    <nav class="sidebar">
        <ul>
            <li>
                <a href="panel_control.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel Principal
                </a>
            </li>

            <li>
                <a href="control_inventario.php">
                    <span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de inventario
                </a>
            </li>

            <li>
                <a href="gestion_despachos.php">
                    <span class="icon"><img src="../../img/despachos.png" alt=""></span> Gestión de Despachos
                </a>
            </li>

            <li>
                <a href="zonas_entrega.php">
                    <span class="icon"><img src="../../img/rutas.png" alt=""></span> Zonas de Entrega
                </a>
            </li>

            <li class="active-item">
                <a href="perfil.php">
                    <span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil
                </a>
            </li>

            <li>
                <a href="../backend/cerrar_sesion.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Editar Perfil</h1>

        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje-alerta <?php echo htmlspecialchars($tipoMensaje); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form class="perfil-form" action="../backend/encargado_planta/actualizar_perfil.php" method="POST">

            <!-- 
                Enviamos oculto el id del usuario
                para validar que solo edite su propio perfil
            -->
            <input type="hidden" name="id_usuario" value="<?php echo $perfil['id_usuario']; ?>">

            <label for="nombre_completo">Nombre completo:</label>
            <input
                type="text"
                id="nombre_completo"
                name="nombre_completo"
                value="<?php echo htmlspecialchars($perfil['nombre_completo']); ?>"
                required
            >

            <label for="numero_documento">Documento de identificación:</label>
            <input
                type="text"
                id="numero_documento"
                name="numero_documento"
                value="<?php echo htmlspecialchars($perfil['tipo_documento'] . ' - ' . $perfil['numero_documento']); ?>"
                readonly
            >

            <label for="correo_electronico">Correo electrónico:</label>
            <input
                type="email"
                id="correo_electronico"
                name="correo_electronico"
                value="<?php echo htmlspecialchars($perfil['correo_electronico']); ?>"
                required
            >

            <label for="direccion">Dirección:</label>
            <input
                type="text"
                id="direccion"
                name="direccion"
                value="<?php echo htmlspecialchars($perfil['direccion']); ?>"
            >

            <div class="perfil-botones">
                <button class="btn-guardar" type="submit">Guardar cambios</button>
                <button class="btn-cancelar" onclick="location.href='perfil.php'" type="button">Cancelar</button>
            </div>

        </form>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

<script>
/* 
   Este script hace que el mensaje desaparezca
   después de unos segundos
*/
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