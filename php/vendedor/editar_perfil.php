<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("perfil");

// Traer datos del usuario
require_once "../backend/vendedor/obtener_perfil.php";

// Mensajes
$mensaje = $_SESSION['mensaje_perfil'] ?? "";
$tipoMensaje = $_SESSION['tipo_perfil'] ?? "";

// Limpiar mensaje
unset($_SESSION['mensaje_perfil']);
unset($_SESSION['tipo_perfil']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Vendedor</title>

    <link rel="stylesheet" href="../../_css/vendedor-base.css">
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

        <span>
            <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
        </span>

        <span class="icon">
            <img src="../../img/perfil.png" width="24" alt="Perfil">
        </span>

    </div>

</header>

<div class="main-container">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <ul>
            <li><a href="panel_principal.php"><span class="icon"><img src="../../img/panel.jpg"></span> Panel Principal</a></li>
            <li><a href="registrar_venta.php"><span class="icon"><img src="../../img/ventas.png"></span> Registrar venta</a></li>
            <li><a href="pedidos_clientes.php"><span class="icon"><img src="../../img/pedidos.png"></span> Pedidos de clientes</a></li>
            <li><a href="reportes_venta.php"><span class="icon"><img src="../../img/reportes.png"></span> Reportes de venta</a></li>

            <li class="active-item">
                <a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span> Perfil</a>
            </li>

            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Editar Perfil</h1>

        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje-alerta <?php echo $tipoMensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form class="perfil-form" action="../backend/vendedor/actualizar_perfil.php" method="POST">

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
                value="<?php echo htmlspecialchars($perfil['numero_documento']); ?>"
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