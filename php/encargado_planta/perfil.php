<?php
session_start();

/* 
   Verificamos que el usuario haya iniciado sesión
   y que tenga permiso para entrar al perfil
*/
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("perfil");

/* 
   Cargamos los datos del perfil desde backend
*/
require_once "../backend/encargado_planta/obtener_perfil.php";

/* 
   Nombre del usuario logueado
*/
$nombre = $_SESSION['nombre'] ?? 'Encargado de planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Encargado de planta</title>

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
            <img src="../../img/usuario-gestion.png" width="24" alt="Usuario">
        </span>
    </div>

</header>

<div class="main-container">

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