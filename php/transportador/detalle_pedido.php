<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("listado_pedidos");

require_once "../backend/transportador/detalle_pedido.php";

$nombre = $_SESSION['nombre'] ?? 'Transportador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del pedido - Transportador</title>

    <link rel="stylesheet" href="/prototipo/_css/admin-base.css">
    <link rel="stylesheet" href="/prototipo/_css/transportador-panel-principal.css">
    <link rel="stylesheet" href="/prototipo/_css/transportador-rutas-despachos.css">
</head>

<body>

<header class="header-bar">

    <div class="header-rol">
        Transportador
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
            <li>
                <a href="panel_principal.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                    Panel principal
                </a>
            </li>

            <li>
                <a href="zonas_despachos.php">
                    <span class="icon"><img src="../../img/despachos.png" alt=""></span>
                    Zonas y despachos
                </a>
            </li>

            <li class="active-item">
                <a href="listado_pedidos.php">
                    <span class="icon"><img src="../../img/listado-pedidos.png" alt=""></span>
                    Listado de pedidos
                </a>
            </li>

            <li>
                <a href="reportes_despachos.php">
                    <span class="icon"><img src="../../img/reportes.png" alt=""></span>
                    Reportes de despachos
                </a>
            </li>

            <li>
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

        <h1 class="h1-title">Detalle del pedido</h1>

        <?php if (!$despacho): ?>
            <p>No se encontró la información del pedido.</p>
        <?php else: ?>

            <div class="gestion-card">
                <h2 class="gestion-subtitle">Información general</h2>

                <div class="gestion-grid">
                    <div class="dato-item">
                        <span class="dato-label">N° despacho</span>
                        <span class="dato-valor"><?php echo (int)$despacho['id_despacho']; ?></span>
                    </div>

                    <div class="dato-item">
                        <span class="dato-label">Factura</span>
                        <span class="dato-valor"><?php echo (int)$despacho['id_factura']; ?></span>
                    </div>

                    <div class="dato-item">
                        <span class="dato-label">Cliente</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($despacho['cliente']); ?></span>
                    </div>

                    <div class="dato-item">
                        <span class="dato-label">Dirección</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($despacho['direccion']); ?></span>
                    </div>

                    <div class="dato-item">
                        <span class="dato-label">Zona</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($despacho['zona_entrega']); ?></span>
                    </div>

                    <div class="dato-item">
                        <span class="dato-label">Estado</span>
                        <span class="dato-valor estado estado-<?php echo strtolower($despacho['estado']); ?>">
                            <?php echo htmlspecialchars(ucfirst($despacho['estado'])); ?>
                        </span>
                    </div>

                    <div class="dato-item">
                        <span class="dato-label">Fecha despacho</span>
                        <span class="dato-valor"><?php echo htmlspecialchars($despacho['fecha_creacion']); ?></span>
                    </div>

                    <div class="dato-item">
                        <span class="dato-label">Total</span>
                        <span class="dato-valor">$<?php echo number_format((float)$despacho['total_factura'], 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>

            <div class="gestion-card">
                <h2 class="gestion-subtitle">Detalle de productos</h2>

                <?php if (empty($detalle_productos)): ?>
                    <p>No hay productos asociados a este despacho.</p>
                <?php else: ?>
                    <table class="tabla-despachos">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalle_productos as $item): ?>
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
            </div>

        <?php endif; ?>

        <button class="btn-accion btn-confirmar-entrega" onclick="location.href='listado_pedidos.php'">
            ← Volver al listado
        </button>

        <div class="logo-footer">Z-CONTAY</div>
    </main>
</div>

</body>
</html>