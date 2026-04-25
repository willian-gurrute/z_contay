<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("historial_pedidos");

// Backend propio del cliente
require_once __DIR__ . "/../backend/cliente/ver_factura.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Factura de Venta</title>

<link rel="stylesheet" href="../../_css/vendedor-factura.css">
</head>

<body>

<div class="factura-container">

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

    <div class="info-factura">
        <p><strong>Factura N°:</strong> <?= $factura['id_factura'] ?></p>
        <p><strong>Fecha:</strong> <?= $factura['fecha'] ?></p>
        <p><strong>Tipo de venta:</strong> <?= htmlspecialchars($factura['tipo_venta']) ?></p>
        <p>
            <strong>Método de pago:</strong>
            <?= ($factura['metodo_pago'] === 'tarjeta') ? 'Transferencia' : htmlspecialchars($factura['metodo_pago']) ?>
        </p>
    </div>

    <hr>

    <div class="info-cliente">
        <h3>Datos del Cliente</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($factura['cliente']) ?></p>
        <p><strong>Documento:</strong> <?= htmlspecialchars($factura['numero_documento']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($factura['telefono']) ?></p>
    </div>

    <hr>

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
            <?php foreach ($detalle_factura as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
                <td><?= (int)$p['cantidad'] ?></td>
                <td>$<?= number_format($p['precio_unitario'], 0, ",", ".") ?></td>
                <td>$<?= number_format($p['subtotal'], 0, ",", ".") ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totales">
        <p><strong>Subtotal:</strong> $<?= number_format($factura['subtotal'], 0, ",", ".") ?></p>
        <p><strong>IVA:</strong> $<?= number_format($factura['iva'], 0, ",", ".") ?></p>
        <h2>Total a pagar: $<?= number_format($factura['total'], 0, ",", ".") ?></h2>
    </div>

    <div class="acciones-factura">

        <a href="../backend/cliente/factura_pdf.php?id=<?= (int)$factura['id_factura'] ?>" 
           class="btn-factura btn-pdf">
            Descargar PDF
        </a>

        <a href="historial_pedidos.php" class="btn-factura btn-volver">
            Volver
        </a>

    </div>

</div>

</body>
</html>