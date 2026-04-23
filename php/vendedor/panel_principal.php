<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Verificar permiso para esta pantalla
verificarPermiso("panel_principal");

require_once "../backend/vendedor/panel_principal.php";


// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Vendedor';
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Vendedor</title>

    <!-- CSS BASE DEL VENDEDOR -->
    <link rel="stylesheet" href="../../_css/vendedor-base.css">

    <!-- CSS ESPECÍFICO DEL PANEL -->
    <link rel="stylesheet" href="../../_css/vendedor-panel-principal.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">Vendedor</div>
    <div class="header-system">Z-CONTAY – Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png"></span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon"><img src="../../img/usuario-gestion.png"></span>
    </div>
</header>

<div class="main-container">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <ul>
            <li class="active-item"><a href="panel_principal.php"><span class="icon"><img src="../../img/panel.jpg"></span>Panel Principal</a></li>
            <li><a href="registrar_venta.php"><span class="icon"><img src="../../img/ventas.png"></span>Registrar Venta</a></li>
            <li><a href="pedidos_clientes.php"><span class="icon"><img src="../../img/pedidos.png"></span>Pedidos de clientes</a></li>
            <li><a href="reportes_venta.php"><span class="icon"><img src="../../img/reportes.png"></span>Reportes de venta</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span>Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Panel Principal</h1>

        <div class="cards-container">

            <div class="card-indicador">
                <h2>Ventas Hoy</h2>
                <p class="valor"><?php echo $ventasHoy; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Ingresos Hoy</h2>
                <p class="valor">$<?php echo number_format($ingresosHoy, 0,',', '.'); ?></p>
            </div>

            <div class="card-indicador">
                <h2>Pedidos disponibles</h2>
                <p class="valor"><?php echo $pedidosPendientes; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Mis despachos pendientes</h2>
                <p class="valor"><?php echo $despachos; ?></p>
            </div>

        </div>

       <h3 class="subtitulo">Resumen de ventas recientes</h3>

<table class="tabla-resumen">
    <thead>
        <tr>
            <th>ID Factura</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Tipo venta</th>
            <th>Estado</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($ventasRecientes)) : ?>
            <?php foreach ($ventasRecientes as $venta) : ?>
                <tr>
                    <td><?php echo (int)$venta['id_factura']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($venta['fecha'])); ?></td>
                    <td><?php echo htmlspecialchars($venta['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($venta['tipo_venta']); ?></td>
                    <td><?php echo htmlspecialchars($venta['estado_factura']); ?></td>
                    <td>$<?php echo number_format($venta['total'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">No hay ventas recientes registradas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="logo-footer">Z-CONTAY</div>

    </main>

</div>

</body>
</html>
