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
require_once "../backend/vendedor/procesar_pedido_vendedor.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesar pedido</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/vendedor-procesar-pedido.css">
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

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'stock_insuficiente') : ?>
            <div class="mensaje-sistema mensaje-error">
                No hay inventario suficiente para generar la factura de este pedido. Puedes liberarlo o intentarlo más tarde.
            </div>
        <?php endif; ?>

        <div class="detalle-card">
            <p><strong>ID del pedido:</strong> <?php echo $pedido['id_pedido']; ?></p>
            <p><strong>Fecha:</strong> <?php echo date("d/m/Y H:i", strtotime($pedido['fecha'])); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['estado']); ?></p>
            <p><strong>Estado de pago:</strong> <?php echo htmlspecialchars($pedido['estado_pago']); ?></p>
        </div>

        <div class="detalle-card">
            <h2>Datos del cliente</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['cliente']); ?></p>
            <p><strong>Documento:</strong> <?php echo htmlspecialchars($pedido['numero_documento']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono'] ?? 'No registrado'); ?></p>
        </div>

        <div class="detalle-card">
            <h2>Dirección principal</h2>
            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion'] ?? 'No registrada'); ?></p>
            <p><strong>Barrio:</strong> <?php echo htmlspecialchars($pedido['barrio'] ?? 'No registrado'); ?></p>
            <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($pedido['ciudad'] ?? 'No registrada'); ?></p>
            <p><strong>Referencia:</strong> <?php echo htmlspecialchars($pedido['referencia'] ?? 'No registrada'); ?></p>
        </div>

        <div class="detalle-card">
            <h2>Productos del pedido</h2>

            <table class="tabla-productos">
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
                                <td><?php echo (int)$item['cantidad']; ?></td>
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
        </div>

      <div class="detalle-card">
    <h2>Resumen del pedido</h2>
    <p><strong>Subtotal:</strong> $<?php echo number_format($pedido['subtotal'], 0, ',', '.'); ?></p>
    <p><strong>IVA:</strong> $<?php echo number_format($pedido['iva'], 0, ',', '.'); ?></p>
    <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 0, ',', '.'); ?></p>
</div>

<div class="detalle-card">

    <!-- BOTÓN FACTURAR -->
    <form action="../backend/vendedor/facturar_pedido.php" method="POST" onsubmit="marcarPedidoResuelto()">
        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
        <button type="submit" class="main-button">Generar factura</button>
    </form>

    <br>

    <!-- BOTÓN LIBERAR -->
    <form action="../backend/vendedor/liberar_pedido.php" method="POST"
    onsubmit="marcarPedidoResuelto(); return confirm('¿Seguro que deseas liberar este pedido? Volverá a quedar disponible para cualquier vendedor.');">
        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
        <button type="submit" class="main-button btn-liberar">Liberar pedido</button>
    </form>

</div>
    </main>
</div>


<script>
let pedidoResuelto = false;

// cuando factura o libera
function marcarPedidoResuelto() {
    pedidoResuelto = true;
}

// alerta si intenta salir
window.addEventListener('beforeunload', function (e) {
    if (!pedidoResuelto) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
</body>
</html>