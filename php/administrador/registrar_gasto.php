<?php
// php/administrador/registrar_gasto.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Verifica permiso
verificarPermiso("registrar_gasto");


// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';

// Mensaje simple
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Gasto</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/administrador-registrar-gasto.css">
</head>

<body>

<!-- HEADER -->
<header class="header-bar">

    <div class="header-rol">
        Administrador
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/usuario-gestion.png" width="24" alt="Usuario">
        </span>

    </div>

</header>
<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span>Panel Principal</a></li>
            <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>
            <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a></li>
            <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg" alt=""></span>Crear Opciones</a></li>
            <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>
            <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>
            <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png" alt=""></span>Productos</a></li>

            <li class="active-item">
                <a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a>
            </li>

            <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span>Inventario</a></li>
            <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes</a></li>
            <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png" alt=""></span>Configuración</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Registrar Gasto</h1>
        <p class="subtitulo">Registro manual de egresos del sistema</p>

        <?php if ($msg == "ok"): ?>
            <p style="color:green;">Gasto registrado correctamente.</p>
        <?php endif; ?>

        <?php if ($msg == "error"): ?>
            <p style="color:red;">No se pudo registrar el gasto.</p>
        <?php endif; ?>

        <div class="card-container">

            <form class="form-gasto" method="POST" action="../backend/administrador/guardar_gasto.php">

                <label>Fecha</label>
                <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>

                <label>Descripción</label>
                <textarea name="descripcion" rows="3" required></textarea>

                <label>Monto</label>
                <input type="number" name="monto" step="0.01" min="0" required>

                <div class="botones">
                    <button type="submit" class="main-button">
                        Guardar Gasto
                    </button>
                </div>

            </form>

        </div>

        <div class="logo-footer">
            Z-CONTAY © 2026
        </div>

    </main>

</div>

</body>
</html>