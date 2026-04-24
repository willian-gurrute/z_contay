<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("perfil");

require_once "../backend/cliente/obtener_perfil.php";

$nombre = $_SESSION['nombre'] ?? 'Cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Cliente</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/Administrador-perfil.css">
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
            <img src="../../img/perfil.png" width="24" alt="Usuario">
        </span>
    </div>

</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li>
                <a href="portafolio.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                    Portafolio
                </a>
            </li>

            <li>
                <a href="realizar_pedido.php">
                    <span class="icon"><img src="../../img/carrito.png" alt=""></span>
                    Realizar pedido
                </a>
            </li>

            <li>
                <a href="historial_pedidos.php">
                    <span class="icon"><img src="../../img/historial.png" alt=""></span>
                    Historial de pedidos
                </a>
            </li>

            <li class="active-item">
                <a href="perfil.php">
                    <span class="icon"><img src="../../img/perfil.png" alt=""></span>
                    Perfil
                </a>
            </li>

            <li>
                <a href="cerrar_sesion.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>
                    Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Mi perfil</h1>

        <div class="perfil-card">

            <img src="../../img/perfil.png" class="perfil-foto" alt="Foto de perfil">

            <h2 class="perfil-nombre">
                <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
            </h2>

            <p>
                <strong>Rol:</strong>
                <?php echo htmlspecialchars($perfil['nombre_rol']); ?>
            </p>

            <p>
                <strong>Correo:</strong>
                <?php echo htmlspecialchars($perfil['correo_electronico']); ?>
            </p>

            <p>
                <strong>Documento:</strong>
                <?php echo htmlspecialchars($perfil['tipo_documento'] . ' - ' . $perfil['numero_documento']); ?>
            </p>

            <p>
                <strong>Dirección:</strong>
                <?php echo htmlspecialchars($perfil['direccion']); ?>
            </p>


            <p>
                <strong>Estado:</strong>
                <?php echo ($perfil['estado'] === 'A') ? 'Activo' : 'Inactivo'; ?>
            </p>

            <div class="perfil-botones">
                <button class="btn-editar" onclick="location.href='editar_perfil.php'">
                    Editar perfil
                </button>

                <button class="btn-contra" onclick="location.href='cambiar_contrasena.php'">
                    Cambiar contraseña
                </button>
            </div>

        </div>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>