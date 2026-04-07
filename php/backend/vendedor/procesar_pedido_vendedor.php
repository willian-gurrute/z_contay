<?php
require_once __DIR__ . "/../conexion.php";

//validar que exista el id del pedido 
if (!isset($idPedido) || empty($idPedido)) {
    header("locadtion: ../../vendedor/pedidos_clientes.php");
    exit();
}

//consultar datos generales del producto

$pedido = null;

$sqlPedido = "SELECT
             p.id_pedido,
             p.fecha,
             p.estado,
             p.estado_pago,
             p.subtotal,
             p.iva,
             p.total,
             c.id_cliente,
             c.nombre_completo AS cliente,
             c.numero_documento,
             c.telefono,
             cd.direccion,
             cd.barrio,
             cd.ciudad,
             cd.referencia
            FROM pedido p
            INNER JOIN cliente c ON p.id_cliente = c.id_cliente
            LEFT JOIN cliente_direccion cd
                  ON c.id_cliente = cd.id_cliente
                  AND cd.es_principal = 1
            WHERE p.id_pedido = ? 
            LIMIT 1";

$stmtPedido = $conn->prepare($sqlPedido);

if ($stmtPedido) {
    $stmtPedido->bind_param("i", $idPedido);
    $stmtPedido->execute();
    $resultadoPedido = $stmtPedido->get_result();

    if ($resultadoPedido->num_rows > 0) {
        $pedido = $resultadoPedido->fetch_assoc();
    }

    $stmtPedido->close();
}

//si no existe el pedido, regresar 
if (!$pedido) {
    header("location: ../../vendedor/pedidos_clientes.php");
    exit();
}

//consultar productos del pedido 

$detalleProductos = [];

$sqlDetalle = "SELECT
              dp.id_detallePedido,
              dp.id_producto,
              dp.cantidad,
              dp.precio_unitario,
              dp.subtotal,
              p.nombre_producto
            FROM detalle_pedido dp
            INNER JOIN producto p ON dp.id_producto = p.id_producto
            WHERE dp.id_pedido = ?";

$stmtDetalle = $conn->prepare($sqlDetalle);

if ($stmtDetalle) {
    $stmtDetalle->bind_param("i", $idPedido);
    $stmtDetalle->execute();
    $resultadoDetalle = $stmtDetalle->get_result();

    while ($fila = $resultadoDetalle->fetch_assoc()) {
        $detalleProductos[] = $fila;
    }

    $stmtDetalle->close();
}
?>