<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("ventas");

require_once "../backend/administrador/detalle_factura.php";

$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle de Factura</title>
<link rel="stylesheet" href="../../_css/detalle-factura.css">
</head>

<body>

<div class="factura-container">

    <!-- ENCABEZADO EMPRESA -->
   <div class="empresa">
    <h1>Z-CONTAY - Galpón Aves del Paraíso</h1>

    <p>NIT: <?= htmlspecialchars($empresa['nit'] ?? '') ?></p>

    <?php if (!empty($empresa['direccion'])): ?>
        <p>Dirección: <?= htmlspecialchars($empresa['direccion']) ?></p>
    <?php endif; ?>

    <p>Teléfono: <?= htmlspecialchars($empresa['telefono'] ?? '') ?></p>

    <p>
        <?= htmlspecialchars($empresa['ciudad'] ?? '') ?>
        <?php if (!empty($empresa['departamento'])): ?>
            - <?= htmlspecialchars($empresa['departamento']) ?>
        <?php endif; ?>
    </p>

    <?php if (!empty($empresa['horario_atencion'])): ?>
        <p>Horario: <?= htmlspecialchars($empresa['horario_atencion']) ?></p>
    <?php endif; ?>
</div>

    <hr>

    <!-- INFORMACIÓN FACTURA -->
    <div class="info-factura">
        <p><strong>Factura N°:</strong> <?= $factura['id_factura'] ?></p>
        <p><strong>Fecha:</strong> <?= $factura['fecha'] ?></p>
        <p><strong>Vendedor:</strong> <?= htmlspecialchars($factura['vendedor']) ?></p>
        <p><strong>Tipo de venta:</strong> <?= htmlspecialchars($factura['tipo_venta']) ?></p>
        <p><strong>Método de pago:</strong> <?= htmlspecialchars($factura['metodo_pago']) ?></p>
    </div>

    <hr>

    <!-- CLIENTE -->
    <div class="info-cliente">
        <h3>Datos del Cliente</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($factura['cliente']) ?></p>
        <p><strong>Documento:</strong> <?= htmlspecialchars($factura['numero_documento']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($factura['telefono']) ?></p>
    </div>

    <hr>

    <!-- PRODUCTOS -->
    <table class="tabla-productos">

        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($productos as $p): ?>

        <tr>
            <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
            <td><?= $p['cantidad'] ?></td>
            <td>$<?= number_format($p['precio_unitario'],0,",",".") ?></td>
            <td>$<?= number_format($p['subtotal'],0,",",".") ?></td>
        </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

    <!-- TOTALES -->
    <div class="totales">
        <p><strong>Subtotal:</strong> $<?= number_format($factura['subtotal'],0,",",".") ?></p>
        <p><strong>IVA:</strong> $<?= number_format($factura['iva'],0,",",".") ?></p>
        <h2>Total a pagar: $<?= number_format($factura['total'],0,",",".") ?></h2>
    </div>

    <hr>

    <div class="acciones">
        <button class="main-button" onclick="window.print()">Imprimir</button>

        <!-- El PDF lo activaremos en el módulo reportes -->
        <button class="main-button" disabled>Descargar PDF</button>

        <a href="ventas.php">
            <button class="main-button">Volver</button>
        </a>
    </div>

</div>

</body>
</html>