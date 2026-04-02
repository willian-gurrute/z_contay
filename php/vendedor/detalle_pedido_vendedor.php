<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("pedidos_clientes");

// Tomar el nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Vendedor';

// Validar que llegue el id del pedido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: pedidos_clientes.php");
    exit();
}

$idPedido = intval($_GET['id']);

// Cargar la información del pedido
require_once "../backend/vendedor/detalle_pedido_vendedor.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del pedido</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Vendedor</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span><?php echo htmlspecialchars($nombre); ?></span>
    </div>
</header>

<div class="main-container">
    <main class="content-area">
        <h1>Detalle del Pedido</h1>

        <p><strong>ID del pedido:</strong> <?php echo $pedido['id_pedido']; ?></p>
        <p><strong>Fecha:</strong> <?php echo date("d/m/Y H:i", strtotime($pedido['fecha'])); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
        <p><strong>Estado de pago:</strong> <?php echo htmlspecialchars($pedido['estado_pago']); ?></p>

        <hr>

        <h2>Datos del cliente</h2>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['cliente']); ?></p>
        <p><strong>Documento:</strong> <?php echo htmlspecialchars($pedido['numero_documento']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono'] ?? 'No registrado'); ?></p>

        <hr>

        <h2>Dirección principal</h2>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion'] ?? 'No registrada'); ?></p>
        <p><strong>Barrio:</strong> <?php echo htmlspecialchars($pedido['barrio'] ?? 'No registrado'); ?></p>
        <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($pedido['ciudad'] ?? 'No registrada'); ?></p>
        <p><strong>Referencia:</strong> <?php echo htmlspecialchars($pedido['referencia'] ?? 'No registrada'); ?></p>

        <hr>

        <h2>Productos del pedido</h2>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($detalleProductos)) : ?>
                    <?php foreach ($detalleProductos as $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                            <td><?php echo $item['cantidad']; ?></td>
                            <td>$<?php echo number_format($item['precio_unitario'], 0, ',', '.'); ?></td>
                            <td>$<?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">Este pedido no tiene productos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <hr>

        <h2>Resumen del pedido</h2>
        <p><strong>Subtotal:</strong> $<?php echo number_format($pedido['subtotal'], 0, ',', '.'); ?></p>
        <p><strong>IVA:</strong> $<?php echo number_format($pedido['iva'], 0, ',', '.'); ?></p>
        <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 0, ',', '.'); ?></p>

        <br>

       <form action="../backend/vendedor/facturar_pedido.php" method="POST">
          <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
          <button type="submit">Generar factura</button>
       </form>

       <br>

        <p>
         <a href="pedidos_clientes.php">Volver a pedidos</a>
        </p>
    </main>
</div>

</body>
</html>