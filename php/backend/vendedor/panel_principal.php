<?php
//backend vendedor panel_principal
require_once __DIR__ . "/../conexion.php";

$idUsuario = $_SESSION['id_usuario'] ?? 0;


//ventas del dia 
$ventasHoy = 0;

$sqlVentasHoy = "SELECT COUNT(*) AS total_ventas
                 FROM factura
                 WHERE DATE(fecha) = CURDATE()
                 AND id_usuario = $idUsuario";

$resVentashoy = $conn->query($sqlVentasHoy);

if($resVentashoy && $fila = $resVentashoy->fetch_assoc()) {
    $ventasHoy = (int)$fila['total_ventas'];
}

//ingresos del dia 
$ingresosHoy = 0;

$sqlIngresosHoy = "SELECT SUM(total) AS total_ingresos
                   FROM factura
                   WHERE DATE(fecha) = CURDATE()";

$resIngresoHoy = $conn->query($sqlIngresosHoy);
 
if ($resIngresoHoy && $fila = $resIngresoHoy->fetch_assoc()) {
    $ingresosHoy = $fila['total_ingresos'] ?? 0;
}

//pedidos pendientes
$pedidosPendientes = 0;

$sqlPedidosPendientes = "SELECT COUNT(*) AS total_pendientes
                         FROM pedido
                         WHERE estado = 'pendiente'";

$resPedidosPendientes = $conn->query($sqlPedidosPendientes);

if ($resPedidosPendientes && $fila = $resPedidosPendientes->fetch_assoc()) {
    $pedidosPendientes = (int)$fila['total_pendientes'];
}

//despachos
$despachos = 0;

$sqlDespachos = "SELECT COUNT(*) AS total_despachos
                 FROM despacho
                 WHERE estado IN ('pendiente', 'asignado')";

$resDespachos = $conn->query($sqlDespachos);

if ($resDespachos && $fila = $resDespachos->fetch_assoc()) {
    $despachos = (int)$fila['total_despachos'];
}

//ventas recientes 
$ventasRecientes = [];

$sqlVentasRecientes = "SELECT 
                            c.nombre_completo AS cliente,
                            p.nombre_producto AS producto,
                            df.cantidad,
                            df.subtotal AS total,
                            f.fecha
                       FROM factura f
                       INNER JOIN cliente c ON f.id_cliente = c.id_cliente
                       INNER JOIN detalle_factura df ON f.id_factura = df.id_factura
                       INNER JOIN producto p ON df.id_producto = p.id_producto
                       WHERE f.id_usuario = $idUsuario
                       ORDER BY f.fecha DESC
                       LIMIT 5";

$resVentasRecientes = $conn->query($sqlVentasRecientes);

if ($resVentasRecientes) {
    while ($fila = $resVentasRecientes->fetch_assoc()) {
        $ventasRecientes[] = $fila;
    }
}

?>
