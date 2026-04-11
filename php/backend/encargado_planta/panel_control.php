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

/* =========================================
   1. PEDIDOS PENDIENTES POR ASIGNAR
   Pedidos pagados que todavía siguen pendientes
========================================= */
$sqlPendientesAsignar = "SELECT COUNT(*) AS total
                         FROM pedido
                         WHERE estado = 'pendiente'
                         AND estado_pago = 'pagado'";

$resPendientesAsignar = $conn->query($sqlPendientesAsignar);

if ($resPendientesAsignar && $fila = $resPendientesAsignar->fetch_assoc()) {
    $pedidos_pendientes_asignar = (int)$fila['total'];
}

/* =========================================
   2. PEDIDOS POR ENTREGAR
   Aquí sumamos:
   - pedidos pendientes por asignar
   - despachos no entregados
========================================= */

/* 2.1 pedidos pendientes */
$total_pendientes = 0;

$sqlTotalPendientes = "SELECT COUNT(*) AS total
                       FROM pedido
                       WHERE estado = 'pendiente'
                       AND estado_pago = 'pagado'";

$resTotalPendientes = $conn->query($sqlTotalPendientes);

if ($resTotalPendientes && $fila = $resTotalPendientes->fetch_assoc()) {
    $total_pendientes = (int)$fila['total'];
}

/* 2.2 despachos aún no entregados */
$total_despachos_activos = 0;

$sqlDespachosActivos = "SELECT COUNT(*) AS total
                        FROM despacho
                        WHERE estado IN ('pendiente', 'asignado', 'incidencia')";

$resDespachosActivos = $conn->query($sqlDespachosActivos);

if ($resDespachosActivos && $fila = $resDespachosActivos->fetch_assoc()) {
    $total_despachos_activos = (int)$fila['total'];
}

/* 2.3 total general por entregar */
$pedidos_por_entregar = $total_pendientes + $total_despachos_activos;

/* =========================================
   3. MOVIMIENTOS HOY
========================================= */
$sqlMovimientosHoy = "SELECT COUNT(*) AS total
                      FROM movimiento_inventario
                      WHERE DATE(fecha) = CURDATE()";

$resMovimientosHoy = $conn->query($sqlMovimientosHoy);

if ($resMovimientosHoy && $fila = $resMovimientosHoy->fetch_assoc()) {
    $movimientos_hoy = (int)$fila['total'];
}

/* =========================================
   4. PRODUCTOS CON STOCK BAJO
========================================= */
$sqlStockBajo = "SELECT COUNT(*) AS total
                 FROM inventario
                 WHERE cantidad <= stock_minimo";

$resStockBajo = $conn->query($sqlStockBajo);

if ($resStockBajo && $fila = $resStockBajo->fetch_assoc()) {
    $productos_stock_bajo = (int)$fila['total'];
}

/* =========================================
   5. ÚLTIMA ACTUALIZACIÓN PENDIENTES
========================================= */
$sqlUltimaPendientes = "SELECT MAX(fecha) AS ultima_fecha
                        FROM pedido
                        WHERE estado = 'pendiente'
                        AND estado_pago = 'pagado'";

$resUltimaPendientes = $conn->query($sqlUltimaPendientes);

if ($resUltimaPendientes && $fila = $resUltimaPendientes->fetch_assoc()) {
    if (!empty($fila['ultima_fecha'])) {
        $ultima_actualizacion_pendientes = date("d/m/Y h:i A", strtotime($fila['ultima_fecha']));
    }
}

/* =========================================
   6. ÚLTIMA ACTUALIZACIÓN ENTREGAS
   Tomamos la fecha más reciente entre despachos activos
   Si no hay, dejamos "Sin registros"
========================================= */
$sqlUltimaEntrega = "SELECT MAX(fecha_creacion) AS ultima_fecha
                     FROM despacho
                     WHERE estado IN ('pendiente', 'asignado', 'incidencia')";

$resUltimaEntrega = $conn->query($sqlUltimaEntrega);

if ($resUltimaEntrega && $fila = $resUltimaEntrega->fetch_assoc()) {
    if (!empty($fila['ultima_fecha'])) {
        $ultima_actualizacion_entregas = date("d/m/Y h:i A", strtotime($fila['ultima_fecha']));
    } elseif ($ultima_actualizacion_pendientes !== "Sin registros") {
        $ultima_actualizacion_entregas = $ultima_actualizacion_pendientes;
    }
}

/* =========================================
   7. ÚLTIMA ACTUALIZACIÓN MOVIMIENTOS
========================================= */
$sqlUltimoMovimiento = "SELECT MAX(fecha) AS ultima_fecha
                        FROM movimiento_inventario
                        WHERE DATE(fecha) = CURDATE()";

$resUltimoMovimiento = $conn->query($sqlUltimoMovimiento);

if ($resUltimoMovimiento && $fila = $resUltimoMovimiento->fetch_assoc()) {
    if (!empty($fila['ultima_fecha'])) {
        $ultima_actualizacion_movimientos = date("d/m/Y h:i A", strtotime($fila['ultima_fecha']));
    }
}

/* =========================================
   8. ÚLTIMA ACTUALIZACIÓN STOCK BAJO
========================================= */
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