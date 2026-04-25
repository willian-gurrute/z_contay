<?php
// php/administrador/panel_control.php
session_start();
require_once "../backend/verificar_sesion.php"; // debe validar que exista sesion

require_once "../backend/verificar_permiso.php"; // archivo que revisa permisos

verificarPermiso("panel_control");// nombre del controlador

require_once "../backend/administrador/panel_control.php";


// Si no hay sesión, al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Si no es administrador (rol 1), lo sacamos
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador - Panel de Control</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <!-- Evita espacios en nombres de archivos -->
    <link rel="stylesheet" href="../../_css/administrador-panel control.css">
</head>
<body>

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
                <li>
                    <a href="panel_control.php">
                        <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                        Panel Principal
                    </a>
                </li>

                <li>
                    <a href="gestion_usuarios.php">
                        <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
                        Gestión de usuarios
                    </a>
                </li>

                <li>
                    <a href="roles_permisos.php">
                        <span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>
                        Roles y permisos
                    </a>
                </li>

                <li>
                    <a href="crear_opciones.php">
                        <span class="icon"><img src="../../img/opciones.jpg" alt=""></span>
                        Crear Opciones
                    </a>
                </li>

                <li>
                    <a href="contabilidad.php">
                        <span class="icon"><img src="../../img/contabilidad.png" alt=""></span>
                        Contabilidad
                    </a>
                </li>

                <li>
                    <a href="ventas.php">
                        <span class="icon"><img src="../../img/ventas.png" alt=""></span>
                        Ventas
                    </a>
                </li>

                <li>
                    <a href="gestion_productos.php">
                        <span class="icon"><img src="../../img/productos.png" alt=""></span>
                        Productos
                    </a>
                </li>

                <li>
                    <a href="registrar_gasto.php">
                        <span class="icon"><img src="../../img/gastos.png" alt=""></span>
                        Registrar Gasto
                    </a>
                </li>

                <li>
                    <a href="inventario.php">
                        <span class="icon"><img src="../../img/inventario.png" alt=""></span>
                        Inventario
                    </a>
                </li>

                <li>
                    <a href="reportes.php">
                        <span class="icon"><img src="../../img/reportes.png" alt=""></span>
                        Reportes
                    </a>
                </li>

                <li>
                    <a href="configuracion.php">
                        <span class="icon"><img src="../../img/configuracion.png" alt=""></span>
                        Configuración
                    </a>
                </li>

                <li>
                    <a href="perfil.php">
                        <span class="icon"><img src="../../img/perfil.png" alt=""></span>
                        Perfil
                    </a>
                </li>

                <li>
                    <!-- Cerrar sesión de verdad -->
                    <a href="cerrar_sesion.php">
                        <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>
                        Cerrar sesión
                    </a>
                </li>
            </ul>
        </nav>

        <main class="content-area">
            <h1 class="h1-title">Panel de control - Administrador</h1>

            <div class="card-grid">
                <div class="info-card">
                    <span class="card-icon">
                        <img src="../../img/Ventas-dia.png" alt="Icono de Ventas">
                    </span>
                    <h2>Ventas del día</h2>
                    <p class="data">$<?= number_format($dashboard['ventas_dia'], 0, ',', '.') ?></p>
                </div>

                <div class="info-card">
                    <span class="card-icon">
                        <img src="../../img/stok-bajo.png" alt="Icono stock bajo">
                    </span>
                    <h2>Productos con Stock Bajo</h2>
                    <p class="data"><?= $dashboard['stock_bajo'] ?></p>
                </div>

                <div class="info-card">
                    <span class="card-icon">
                        <img src="../../img/utilidad neta.png" alt="Icono de Utilidad Neta">
                    </span>
                    <h2>Utilidad Neta</h2>
                    <p class="data">$<?= number_format($dashboard['utilidad_neta'], 0, ',', '.') ?></p>
                </div>

                <div class="info-card">
                    <span class="card-icon">
                        <img src="../../img/pedidos pendientes.png" alt="Icono de Pedidos Pendientes">
                    </span>
                    <h2>Pedidos por entregar</h2>
                    <p class="data"><?= $dashboard['pedidos_pendientes'] ?></p>
                </div>
            </div>

            <button class="main-button admin-button" onclick="location.href='detalle_panel.php'">
                Ver detalles
            </button>

            <div class="logo-footer">Z-CONTAY</div>
        </main>

    </div>

</body>
</html>