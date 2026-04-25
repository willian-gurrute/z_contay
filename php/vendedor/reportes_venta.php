<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("reportes_venta");

require_once "../backend/vendedor/reportes_venta.php";


// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Vendedor';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Venta</title>

    <link rel="stylesheet" href="../../_css/vendedor-base.css">
    <link rel="stylesheet" href="../../_css/vendedor-reportes-venta.css">
</head>

<body>

<header class="header-bar">

    <div class="header-rol">
        Vendedor
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/perfil.png" width="24" alt="Perfil">
        </span>

    </div>

</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li><a href="panel_principal.php"><span class="icon"><img src="../../img/panel.jpg"></span> Panel Principal</a></li>
            <li><a href="registrar_venta.php"><span class="icon"><img src="../../img/ventas.png"></span> Registrar venta</a></li>
            <li><a href="pedidos_clientes.php"><span class="icon"><img src="../../img/pedidos.png"></span> Pedidos de clientes</a></li>
            <li class="active-item"><a href="reportes_venta.php"><span class="icon"><img src="../../img/reportes.png"></span> Reportes de venta</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span> Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Reportes de Venta</h1>
        <h3 class="subtitulo">Consulta historial de ventas realizadas</h3>

        <form class="filtros-form" method="GET">

            <div class="campo">
                <label>Fecha inicial:</label>
                <input type="date" name="fecha_inicial" value="<?php echo htmlspecialchars($fecha_inicial); ?>">
            </div>

            <div class="campo">
                <label>Fecha final:</label>
                <input type="date" name="fecha_final" value="<?php echo htmlspecialchars($fecha_final); ?>">
            </div>

            <div class="campo">
                <label>Cliente:</label>
                <input type="text" name="cliente" placeholder="Ej: Juan Pérez" value="<?php echo htmlspecialchars($cliente); ?>">
            </div>

            <div class="campo">
                <label>Tipo de venta:</label>
                <select name="tipo_venta">
                    <option value="">Todas</option>
                    <option value="directa" <?php echo ($tipo_venta === 'directa') ? 'selected' : ''; ?>>Directa</option>
                    <option value="pedido" <?php echo ($tipo_venta === 'pedido') ? 'selected' : ''; ?>>Pedido</option>
                </select>
            </div>

            <button class="btn-reporte" type="submit">Generar reporte</button>

            <a class="btn-reporte" 
                href="reporte_pdf.php?fecha_inicial=<?php echo urlencode($fecha_inicial); ?>
                 &fecha_final=<?php echo urlencode($fecha_final); ?>
                 &cliente=<?php echo urlencode($cliente); ?>
                 &tipo_venta=<?php echo urlencode($tipo_venta); ?>" 
                  target="_blank">
                     Generar PDF
            </a>
        </form>

        <div class="resumen-reportes">
            <div class="card-indicador">
                <h2>Total ventas</h2>
                <p class="valor"><?php echo $totalVentas; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Total vendido</h2>
                <p class="valor">$<?php echo number_format($totalMonto, 0, ',', '.'); ?></p>
            </div>

            <div class="card-indicador">
                <h2>Ventas directas</h2>
                <p class="valor"><?php echo $ventasDirectas; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Ventas tipo pedido</h2>
                <p class="valor"><?php echo $ventasPedido; ?></p>
            </div>
        </div>

        <table class="tabla-reportes">
            <thead>
                <tr>
                    <th>ID Factura</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Tipo venta</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($ventas)) : ?>
                    <?php foreach ($ventas as $venta) : ?>
                        <tr>
                            <td><?php echo (int)$venta['id_factura']; ?></td>
                            <td><?php echo date("d/m/Y", strtotime($venta['fecha'])); ?></td>
                            <td><?php echo htmlspecialchars($venta['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($venta['tipo_venta']); ?></td>
                            <td><?php echo htmlspecialchars($venta['estado_factura']); ?></td>
                            <td>$<?php echo number_format($venta['total'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="factura_imprimir.php?id=<?php echo $venta['id_factura']; ?>" class="btn-reporte" target="_blank">
                                    Ver / Imprimir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No se encontraron ventas con los filtros seleccionados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="logo-footer">Z-CONTAY</div>
    </main>

</div>

</body>
</html>