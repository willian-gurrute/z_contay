<?php
// php/administrador/detalle_panel.php

session_start();
require_once "../backend/verificar_sesion.php";

// Si no hay sesión, al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Solo Administrador (rol 1)
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Traer datos reales del panel (ventas, stock bajo, utilidad, pedidos)
require_once "../backend/administrador/panel_control.php";
require_once "../backend/administrador/detalle_panel.php";


// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Panel - Administrador</title>
     <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/detalle-panel.css">
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

            <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>
            <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a></li>
            <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>
            <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>
            <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png" alt=""></span>Productos</a></li>
            <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
            <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span>Inventario</a></li>
            <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes</a></li>
            <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png" alt=""></span>Configuración</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>

            <li> <a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar Sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Detalles del Panel General</h1>

        <div class="detalle-grid">

            <div class="detalle-card">
                <h3>Ventas del día</h3>
                <p class="valor">
                    $<?= number_format($dashboard['ventas_dia'], 0, ',', '.') ?>
                </p>
                <p class="descripcion">Total vendido durante la jornada actual.</p>
            </div>

            <div class="detalle-card">
                <h3>Productos con stock bajo</h3>
                <p class="valor"><?= $dashboard['stock_bajo'] ?></p>
                <p class="descripcion">Productos que requieren reposición inmediata.</p>
            </div>

            <div class="detalle-card">
                <h3>Utilidad neta</h3>
                <p class="valor">
                    $<?= number_format($dashboard['utilidad_neta'], 0, ',', '.') ?>
                </p>
                <p class="descripcion">Utilidad generada después de gastos.</p>
            </div>

            <div class="detalle-card">
                <h3>Pedidos pendientes</h3>
                <p class="valor"><?= $dashboard['pedidos_pendientes'] ?></p>
                <p class="descripcion">Pedidos aún en proceso de despacho.</p>
            </div>

           <!-- =========================
ÚLTIMAS 5 FACTURAS DE HOY
========================= -->
<h2 style="margin-top: 25px;">Últimas facturas de hoy</h2>

<?php if (empty($ultimas_facturas)): ?>
    <p>No hay facturas registradas hoy.</p>
<?php else: ?>
    <table class="tabla-detalle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ultimas_facturas as $f): ?>
                <tr>
                    <td><?= (int)$f['id_factura'] ?></td>
                    <td><?= htmlspecialchars($f['fecha']) ?></td>
                    <td><?= htmlspecialchars($f['nombre_completo']) ?></td>
                    <td>$<?= number_format((float)$f['total'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($f['estado_factura']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- =========================
PRODUCTOS CON STOCK BAJO
========================= -->
<h2 style="margin-top: 25px;">Productos con stock bajo</h2>

<?php if (empty($lista_stock_bajo)): ?>
    <p>No hay productos en stock bajo.</p>
<?php else: ?>
    <table class="tabla-detalle">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Stock mínimo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_stock_bajo as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
                    <td><?= (int)$p['cantidad'] ?></td>
                    <td><?= (int)$p['stock_minimo'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- =========================
ÚLTIMOS PEDIDOS PENDIENTES
========================= -->
<h2 style="margin-top: 25px;">Últimos pedidos pendientes</h2>

<?php if (empty($pedidos_pendientes_lista)): ?>
    <p>No hay pedidos pendientes.</p>
<?php else: ?>
    <table class="tabla-detalle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos_pendientes_lista as $p): ?>
                <tr>
                    <td><?= (int)$p['id_despacho'] ?></td>
                    <td><?= htmlspecialchars($p['fecha']) ?></td>
                    <td><?= htmlspecialchars($p['nombre_completo']) ?></td>
                    <td>$<?= number_format((float)$p['total'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($p['estado']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

        </div>

        <button class="main-button volver-btn" onclick="location.href='panel_control.php'">← Volver</button>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>