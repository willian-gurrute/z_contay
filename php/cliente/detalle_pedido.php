<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("historial_pedidos");

require_once "../backend/cliente/detalle_pedido.php";

$nombre = $_SESSION['nombre'] ?? 'Cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del pedido - Cliente</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/realizar-pedido.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">Cliente</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png" alt="Notificaciones" width="24"></span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon"><img src="../../img/perfil.png" alt="Perfil" width="24"></span>
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li><a href="portafolio.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span>Portafolio</a></li>

            <li><a href="realizar_pedido.php"><span class="icon"><img src="../../img/carrito.png" alt=""></span>Realizar pedido</a></li>

            <li class="active-item"><a href="historial_pedidos.php"><span class="icon"><img src="../../img/historial.png" alt=""></span>Historial de pedidos</a></li>

            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>

            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Detalle del pedido</h1>

        <section class="datos-entrega">
            <h2>Información del pedido</h2>

            <div class="datos-grid">
                <div class="campo-form">
                    <label>Código:</label>
                    <p>Pedido #<?php echo (int)$pedido['id_pedido']; ?></p>
                </div>

                <div class="campo-form">
                    <label>Fecha:</label>
                    <p><?php echo htmlspecialchars($pedido['fecha']); ?></p>
                </div>

                <div class="campo-form">
                    <label>Estado:</label>
                    <p><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></p>
                </div>

                <div class="campo-form">
                    <label>Estado de pago:</label>
                    <p><?php echo htmlspecialchars(ucfirst($pedido['estado_pago'])); ?></p>
                </div>

                <div class="campo-form">
                    <label>Total:</label>
                    <p>$<?php echo number_format((float)$pedido['total'], 0, ',', '.'); ?></p>
                </div>

                <div class="campo-form">
                    <label>Cliente:</label>
                    <p><?php echo htmlspecialchars($pedido['cliente']); ?></p>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <strong>Observaciones:</strong>
                <p><?php echo htmlspecialchars($pedido['observaciones']); ?></p>
            </div>
        </section>

        <h2 class="h1-title">Productos del pedido</h2>

        <?php if (empty($detalle_productos)) : ?>
            <p>No hay productos asociados a este pedido.</p>
        <?php else : ?>
            <table class="tabla-pedido">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($detalle_productos as $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                            <td><?php echo (int)$item['cantidad']; ?></td>
                            <td>$<?php echo number_format((float)$item['precio_unitario'], 0, ',', '.'); ?></td>
                            <td>$<?php echo number_format((float)$item['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="historial_pedidos.php" class="btn-agregar">
            Volver al historial
        </a>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>