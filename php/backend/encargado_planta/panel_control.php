<?php
require_once __DIR__ . "/../conexion.php";

/*
    VARIABLES POR DEFECTO
*/
$pedidos_pendientes_asignar = 0;
$pedidos_por_entregar = 0;
$movimientos_hoy = 0;
$productos_stock_bajo = 0;

$ultima_actualizacion_pendientes = "Sin registros";
$ultima_actualizacion_entregas = "Sin registros";
$ultima_actualizacion_movimientos = "Sin registros";
$ultima_actualizacion_stock = "Sin registros";

/* =========================================================
   1. PEDIDOS PENDIENTES POR ASIGNAR
   Facturas tipo pedido, pagadas, que aún no tienen despacho
   creado. Aquí entran:
   - pedidos presenciales tipo pedido
   - pedidos web ya facturados
========================================================= */
$sqlPendientesAsignar = "SELECT COUNT(*) AS total
                         FROM factura f
                         LEFT JOIN despacho d ON d.id_factura = f.id_factura
                         WHERE f.tipo_venta = 'pedido'
                         AND f.estado_factura = 'pagada'
                         AND d.id_despacho IS NULL";

$resPendientesAsignar = $conn->query($sqlPendientesAsignar);

if ($resPendientesAsignar && $fila = $resPendientesAsignar->fetch_assoc()) {
    $pedidos_pendientes_asignar = (int)$fila['total'];
}

/* =========================================================
   2. PEDIDOS POR ENTREGAR
   Todos los pedidos facturados tipo pedido que todavía
   no han sido entregados:
   - sin despacho
   - con despacho pendiente
   - con despacho asignado
   - con incidencia
========================================================= */
$sqlPedidosPorEntregar = "SELECT COUNT(*) AS total
                          FROM factura f
                          LEFT JOIN despacho d ON d.id_factura = f.id_factura
                          WHERE f.tipo_venta = 'pedido'
                          AND f.estado_factura = 'pagada'
                          AND (
                              d.id_despacho IS NULL
                              OR d.estado IN ('pendiente', 'asignado', 'incidencia')
                          )";

$resPedidosPorEntregar = $conn->query($sqlPedidosPorEntregar);

if ($resPedidosPorEntregar && $fila = $resPedidosPorEntregar->fetch_assoc()) {
    $pedidos_por_entregar = (int)$fila['total'];
}

/* =========================================================
   3. MOVIMIENTOS HOY
========================================================= */
$sqlMovimientosHoy = "SELECT COUNT(*) AS total
                      FROM movimiento_inventario
                      WHERE DATE(fecha) = CURDATE()";

$resMovimientosHoy = $conn->query($sqlMovimientosHoy);

if ($resMovimientosHoy && $fila = $resMovimientosHoy->fetch_assoc()) {
    $movimientos_hoy = (int)$fila['total'];
}

/* =========================================================
   4. PRODUCTOS CON STOCK BAJO
========================================================= */
$sqlStockBajo = "SELECT COUNT(*) AS total
                 FROM inventario
                 WHERE cantidad <= stock_minimo";

$resStockBajo = $conn->query($sqlStockBajo);

if ($resStockBajo && $fila = $resStockBajo->fetch_assoc()) {
    $productos_stock_bajo = (int)$fila['total'];
}

/* =========================================================
   5. ÚLTIMA ACTUALIZACIÓN PENDIENTES POR ASIGNAR
========================================================= */
$sqlUltimaPendientes = "SELECT MAX(f.fecha) AS ultima_fecha
                        FROM factura f
                        LEFT JOIN despacho d ON d.id_factura = f.id_factura
                        WHERE f.tipo_venta = 'pedido'
                        AND f.estado_factura = 'pagada'
                        AND d.id_despacho IS NULL";

$resUltimaPendientes = $conn->query($sqlUltimaPendientes);

if ($resUltimaPendientes && $fila = $resUltimaPendientes->fetch_assoc()) {
    if (!empty($fila['ultima_fecha'])) {
        $ultima_actualizacion_pendientes = date("d/m/Y h:i A", strtotime($fila['ultima_fecha']));
    }
}

/* =========================================================
   6. ÚLTIMA ACTUALIZACIÓN PEDIDOS POR ENTREGAR
========================================================= */
$sqlUltimaEntrega = "SELECT MAX(f.fecha) AS ultima_fecha
                     FROM factura f
                     LEFT JOIN despacho d ON d.id_factura = f.id_factura
                     WHERE f.tipo_venta = 'pedido'
                     AND f.estado_factura = 'pagada'
                     AND (
                         d.id_despacho IS NULL
                         OR d.estado IN ('pendiente', 'asignado', 'incidencia')
                     )";

$resUltimaEntrega = $conn->query($sqlUltimaEntrega);

if ($resUltimaEntrega && $fila = $resUltimaEntrega->fetch_assoc()) {
    if (!empty($fila['ultima_fecha'])) {
        $ultima_actualizacion_entregas = date("d/m/Y h:i A", strtotime($fila['ultima_fecha']));
    }
}

/* =========================================================
   7. ÚLTIMA ACTUALIZACIÓN MOVIMIENTOS
========================================================= */
$sqlUltimoMovimiento = "SELECT MAX(fecha) AS ultima_fecha
                        FROM movimiento_inventario
                        WHERE DATE(fecha) = CURDATE()";

$resUltimoMovimiento = $conn->query($sqlUltimoMovimiento);

if ($resUltimoMovimiento && $fila = $resUltimoMovimiento->fetch_assoc()) {
    if (!empty($fila['ultima_fecha'])) {
        $ultima_actualizacion_movimientos = date("d/m/Y h:i A", strtotime($fila['ultima_fecha']));
    }
}

/* =========================================================
   8. ÚLTIMA ACTUALIZACIÓN STOCK BAJO
========================================================= */
$sqlUltimoStockBajo = "SELECT MAX(ultima_actualizacion) AS ultima_fecha
                       FROM inventario
                       WHERE cantidad <= stock_minimo";

$resUltimoStockBajo = $conn->query($sqlUltimoStockBajo);

if ($resUltimoStockBajo && $fila = $resUltimoStockBajo->fetch_assoc()) {
    if (!empty($fila['ultima_fecha'])) {
        $ultima_actualizacion_stock = date("d/m/Y h:i A", strtotime($fila['ultima_fecha']));
    }
}
?>