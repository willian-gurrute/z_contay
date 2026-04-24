<?php
require_once __DIR__ . "/../conexion.php";

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_pedido = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$pedido = null;
$detalle_productos = [];

if ($id_pedido <= 0) {
    header("Location: historial_pedidos.php");
    exit;
}

// Buscar documento del usuario logueado
$sqlUsuario = "SELECT numero_documento
               FROM usuario
               WHERE id_usuario = ?
               LIMIT 1";

$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $id_usuario);
$stmtUsuario->execute();
$resUsuario = $stmtUsuario->get_result();

$numero_documento = "";

if ($filaUsuario = $resUsuario->fetch_assoc()) {
    $numero_documento = $filaUsuario['numero_documento'];
}

$stmtUsuario->close();

// Traer pedido solo si pertenece al cliente logueado
$sqlPedido = "SELECT 
                p.id_pedido,
                p.fecha,
                p.estado,
                p.estado_pago,
                p.observaciones,
                p.subtotal,
                p.iva,
                p.total,
                c.nombre_completo AS cliente
              FROM pedido p
              INNER JOIN cliente c
                ON p.id_cliente = c.id_cliente
              WHERE p.id_pedido = ?
              AND c.numero_documento = ?
              LIMIT 1";

$stmtPedido = $conn->prepare($sqlPedido);
$stmtPedido->bind_param("is", $id_pedido, $numero_documento);
$stmtPedido->execute();
$resPedido = $stmtPedido->get_result();

if ($resPedido && $resPedido->num_rows > 0) {
    $pedido = $resPedido->fetch_assoc();
}

$stmtPedido->close();

if (!$pedido) {
    header("Location: historial_pedidos.php");
    exit;
}

// Traer productos del pedido
$sqlDetalle = "SELECT 
                    dp.id_producto,
                    p.nombre_producto,
                    dp.cantidad,
                    dp.precio_unitario,
                    dp.subtotal
               FROM detalle_pedido dp
               INNER JOIN producto p
                    ON dp.id_producto = p.id_producto
               WHERE dp.id_pedido = ?";

$stmtDetalle = $conn->prepare($sqlDetalle);
$stmtDetalle->bind_param("i", $id_pedido);
$stmtDetalle->execute();
$resDetalle = $stmtDetalle->get_result();

while ($fila = $resDetalle->fetch_assoc()) {
    $detalle_productos[] = $fila;
}

$stmtDetalle->close();
?>