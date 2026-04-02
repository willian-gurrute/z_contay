<?php
// backend vendedor panel_principal
require_once __DIR__ . "/../conexion.php";

$idUsuario = $_SESSION['id_usuario'] ?? 0;

// ==============================
// 1. Ventas del día del vendedor
// ==============================
$ventasHoy = 0;

$sqlVentasHoy = "SELECT COUNT(*) AS total_ventas
                 FROM factura
                 WHERE DATE(fecha) = CURDATE()
                 AND id_usuario = ?";

$stmtVentasHoy = $conn->prepare($sqlVentasHoy);

if ($stmtVentasHoy) {
    $stmtVentasHoy->bind_param("i", $idUsuario);
    $stmtVentasHoy->execute();
    $resVentasHoy = $stmtVentasHoy->get_result();

    if ($fila = $resVentasHoy->fetch_assoc()) {
        $ventasHoy = (int)$fila['total_ventas'];
    }

    $stmtVentasHoy->close();
}

// ==============================
// 2. Ingresos del día del vendedor
// ==============================
$ingresosHoy = 0;

$sqlIngresosHoy = "SELECT SUM(total) AS total_ingresos
                   FROM factura
                   WHERE DATE(fecha) = CURDATE()
                   AND id_usuario = ?";

$stmtIngresosHoy = $conn->prepare($sqlIngresosHoy);

if ($stmtIngresosHoy) {
    $stmtIngresosHoy->bind_param("i", $idUsuario);
    $stmtIngresosHoy->execute();
    $resIngresosHoy = $stmtIngresosHoy->get_result();

    if ($fila = $resIngresosHoy->fetch_assoc()) {
        $ingresosHoy = $fila['total_ingresos'] ?? 0;
    }

    $stmtIngresosHoy->close();
}

// Pedidos disponibles (web sin tomar)
// ==============================
$pedidosPendientes = 0;

$sqlPedidosPendientes = "SELECT COUNT(*) AS total
                         FROM pedido
                         WHERE estado = 'pendiente'
                         AND estado_pago = 'pagado'
                         AND id_usuario IS NULL";

$resPedidosPendientes = $conn->query($sqlPedidosPendientes);

if ($resPedidosPendientes && $fila = $resPedidosPendientes->fetch_assoc()) {
    $pedidosPendientes = (int)$fila['total'];
}

// ==============================
// 4. Despachos
// ==============================
$despachos = 0;

$sqlDespachos = "SELECT COUNT(*) AS total_despachos
                 FROM despacho
                 WHERE estado IN ('pendiente', 'asignado')";

$resDespachos = $conn->query($sqlDespachos);

if ($resDespachos && $fila = $resDespachos->fetch_assoc()) {
    $despachos = (int)$fila['total_despachos'];
}

// ==============================
// 5. Ventas recientes del vendedor
// Mostrar una fila por factura
// ==============================
$ventasRecientes = [];

$sqlVentasRecientes = "SELECT 
                            f.id_factura,
                            f.fecha,
                            c.nombre_completo AS cliente,
                            f.total,
                            f.tipo_venta,
                            f.estado_factura
                       FROM factura f
                       INNER JOIN cliente c ON f.id_cliente = c.id_cliente
                       WHERE f.id_usuario = ?
                       ORDER BY f.fecha DESC
                       LIMIT 5";

$stmtVentasRecientes = $conn->prepare($sqlVentasRecientes);

if ($stmtVentasRecientes) {
    $stmtVentasRecientes->bind_param("i", $idUsuario);
    $stmtVentasRecientes->execute();
    $resVentasRecientes = $stmtVentasRecientes->get_result();

    while ($fila = $resVentasRecientes->fetch_assoc()) {
        $ventasRecientes[] = $fila;
    }

    $stmtVentasRecientes->close();
}
?>