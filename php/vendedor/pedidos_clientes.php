<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("pedidos_clientes");

require_once "../backend/vendedor/pedidos_clientes.php";

$nombre = $_SESSION['nombre'] ?? 'Vendedor';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Clientes</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/vendedor-pedidos-clientes.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Vendedor</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png" alt="Notificaciones"></span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon"><img src="../../img/usuario-gestion.png" alt="Perfil"></span>
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li><a href="panel_principal.php"><span class="icon"><img src="../../img/panel.jpg"></span> Panel Principal</a></li>
            <li><a href="registrar_venta.php"><span class="icon"><img src="../../img/ventas.png"></span> Registrar venta</a></li>
            <li class="active-item"><a href="pedidos_clientes.php"><span class="icon"><img src="../../img/pedidos.png"></span> Pedidos de clientes</a></li>
            <li><a href="reportes_venta.php"><span class="icon"><img src="../../img/reportes.png"></span> Reportes de venta</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span> Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Pedidos de Clientes</h1>
        <h3 class="subtitulo">Gestiona los pedidos disponibles y los pedidos que ya has tomado</h3>

        <section class="seccion-pedidos">
            <h2 class="subtitulo">Pedidos disponibles</h2>

            <table class="tabla-pedidos">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidosDisponibles)) : ?>
                        <?php foreach ($pedidosDisponibles as $pedido) : ?>
                            <tr>
                                <td><?php echo $pedido['id_pedido']; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['numero_documento']); ?></td>
                                <td>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>
                                    <button class="main-button" type="button">Tomar pedido</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">No hay pedidos disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="seccion-pedidos" style="margin-top: 40px;">
            <h2 class="subtitulo">Mis pedidos</h2>

            <table class="tabla-pedidos">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($misPedidos)) : ?>
                        <?php foreach ($misPedidos as $pedido) : ?>
                            <tr>
                                <td><?php echo $pedido['id_pedido']; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['numero_documento']); ?></td>
                                <td>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>
                                    <button class="main-button" type="button">Ver detalle</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">Aún no has tomado pedidos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <div class="logo-footer">Z-CONTAY</div>

    </main>

</div>

</body>
</html>