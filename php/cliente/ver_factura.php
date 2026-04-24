<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("historial_pedidos");

/*
    Reutilizamos la misma lógica de factura del vendedor.
    Este archivo debe recibir el id de factura por GET:
    ver_factura.php?id=45
*/
require_once __DIR__ . "/../backend/vendedor/factura_imprimir_datos.php";
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
        <h1><?php echo htmlspecialchars($empresa['nombre'] ?? 'Z-CONTAY'); ?></h1>
        <p>NIT: <?php echo htmlspecialchars($empresa['nit'] ?? ''); ?></p>
        <p>Teléfono: <?php echo htmlspecialchars($empresa['telefono'] ?? ''); ?></p>
        <p>
            <?php echo htmlspecialchars($empresa['ciudad'] ?? ''); ?>
            <?php if (!empty($empresa['departamento'])) : ?>
                - <?php echo htmlspecialchars($empresa['departamento']); ?>
            <?php endif; ?>
        </p>
    </div>

    <hr>

    <div class="info-factura">
        <p><strong>Factura N°:</strong> <?= $factura['id_factura'] ?></p>
        <p><strong>Fecha:</strong> <?= $factura['fecha'] ?></p>
        <p><strong>Vendedor:</strong> <?= htmlspecialchars($factura['vendedor']) ?></p>
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
        <p><strong>Dirección:</strong> <?= htmlspecialchars($factura['direccion'] ?? 'No registrada') ?></p>
        <p><strong>Barrio:</strong> <?= htmlspecialchars($factura['barrio'] ?? 'No registrado') ?></p>
        <p><strong>Ciudad:</strong> <?= htmlspecialchars($factura['ciudad'] ?? 'No registrada') ?></p>
        <p><strong>Referencia:</strong> <?= htmlspecialchars($factura['referencia'] ?? 'No registrada') ?></p>
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
            <?php foreach ($productos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
                <td><?= $p['cantidad'] ?></td>
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

    <a href="../backend/cliente/factura_pdf.php?id=<?php echo (int)$factura['id_factura']; ?>" 
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