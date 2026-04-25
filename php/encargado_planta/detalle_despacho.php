<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("gestion_despachos");

require_once "../backend/encargado_planta/detalle_despacho.php";

$nombre = $_SESSION['nombre'] ?? 'Encargado de Planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Despacho</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/encargado-registrar-despacho.css">
</head>
<body>

<header class="header-bar">

    <div class="header-rol">
        Encargado de planta
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/usuario-gestion.png" alt="">
        </span>

    </div>

</header>

<div class="main-container">

<nav class="sidebar">
    <ul>
        <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel Principal</a></li>
        <li><a href="control_inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de inventario</a></li>
        <li><a href="movimiento_entrada_salida.php"><span class="icon"><img src="../../img/entrada-salida.png" alt=""></span> Movimiento Entrada/Salida</a></li>
        <li class="active-item"><a href="gestion_despachos.php"><span class="icon"><img src="../../img/despachos.png" alt=""></span> Gestión de Despachos</a></li>
        <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil</a></li>
        <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión</a></li>
    </ul>
</nav>

<main class="content-area">

    <h1 class="h1-title">Detalle del Despacho</h1>
    <h3 class="subtitulo">Información completa del despacho gestionado</h3>

    <div class="card-seccion">
        <h3>Información del Despacho</h3>

        <div class="grid-dos-columnas">
            <div>
                <label>ID Despacho</label>
                <input type="text" value="<?php echo (int)$despacho['id_despacho']; ?>" readonly>
            </div>
            <div>
                <label>Fecha de creación</label>
                <input type="text" value="<?php echo date('d/m/Y h:i A', strtotime($despacho['fecha_creacion'])); ?>" readonly>
            </div>
        </div>

        <div class="grid-dos-columnas">
            <div>
                <label>Estado</label>
                <input type="text" value="<?php echo htmlspecialchars(ucfirst($despacho['estado'])); ?>" readonly>
            </div>
            <div>
                <label>Zona de entrega</label>
                <input type="text" value="<?php echo htmlspecialchars($despacho['zona_entrega'] ?? 'Sin zona'); ?>" readonly>
            </div>
        </div>

        <div class="grid-dos-columnas">
            <div>
                <label>Transportador</label>
                <input type="text" value="<?php echo htmlspecialchars($despacho['transportador'] ?? 'Sin asignar'); ?>" readonly>
            </div>
            <div>
                <label>Gestionado por</label>
                <input type="text" value="<?php echo htmlspecialchars($despacho['gestionado_por'] ?? 'No registrado'); ?>" readonly>
            </div>
        </div>
    </div>

    <div class="card-seccion">
        <h3>Información de la Factura</h3>

        <div class="grid-dos-columnas">
            <div>
                <label>Número de Factura</label>
                <input type="text" value="<?php echo (int)$despacho['id_factura']; ?>" readonly>
            </div>
            <div>
                <label>Cliente</label>
                <input type="text" value="<?php echo htmlspecialchars($despacho['cliente']); ?>" readonly>
            </div>
        </div>

        <div class="grid-dos-columnas">
            <div>
                <label>Fecha factura</label>
                <input type="text" value="<?php echo date('d/m/Y h:i A', strtotime($despacho['fecha_factura'])); ?>" readonly>
            </div>
            <div>
                <label>Total factura</label>
                <input type="text" value="$<?php echo number_format($despacho['total_factura'], 0, ',', '.'); ?>" readonly>
            </div>
        </div>

        <div class="grid-dos-columnas">
            <div>
                <label>Dirección de entrega</label>
                <input type="text" value="<?php echo htmlspecialchars($despacho['direccion'] ?? 'Sin dirección registrada'); ?>" readonly>
            </div>
            <div>
                <label>Tipo de venta</label>
                <input type="text" value="<?php echo htmlspecialchars($despacho['tipo_venta']); ?>" readonly>
            </div>
        </div>
    </div>

    <div class="card-seccion">
        <h3>Detalle de Productos</h3>

        <table class="tabla-despachos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($detalle_productos)) : ?>
                    <?php foreach ($detalle_productos as $producto) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                            <td><?php echo (int)$producto['cantidad']; ?></td>
                            <td>$<?php echo number_format($producto['precio_unitario'], 0, ',', '.'); ?></td>
                            <td>$<?php echo number_format($producto['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No hay productos registrados en este despacho.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="gestion_despachos.php" class="main-button" style="display:inline-block; text-align:center;">
        Volver
    </a>

    <div class="logo-footer">Z-CONTAY</div>

</main>

</div>

</body>
</html>