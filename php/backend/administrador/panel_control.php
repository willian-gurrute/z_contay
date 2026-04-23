<?php
// php/backend/administrador/panel_control.php
//calculamos los datos del panel del administrador

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo adminasrador
if (!isset($_SESSION['id_usuario']) || ($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

/* 1) Ventas del día */
$ventas_dia = 0;

$sqlVentas = "SELECT COALESCE(SUM(total), 0) AS total_ventas
              FROM factura
              WHERE DATE(fecha) = CURDATE()
              AND estado_factura <> 'anulada'";

$res = $conn->query($sqlVentas);
if ($res && $row = $res->fetch_assoc()) {
    $ventas_dia = (float)$row['total_ventas'];
}

//Productos con stock bajo 
$stock_bajo = 0;

$sqlStock = "SELECT COUNT(*) AS cant
             FROM inventario
             WHERE cantidad <= stock_minimo";

$res = $conn->query($sqlStock);
if ($res && $row = $res->fetch_assoc()) {
    $stock_bajo = (int)$row['cant'];
}


// Pedidos por entregar
$pedidos_pendientes = 0;

$sqlPedidos = "SELECT COUNT(*) AS cant
               FROM despacho
               WHERE estado IN ('pendiente', 'asignado')";

$res = $conn->query($sqlPedidos);
if ($res && $row = $res->fetch_assoc()) {
    $pedidos_pendientes = (int)$row['cant'];
}

// Utilidad neta del día (ingresos - egresos) 
$utilidad_neta = 0;

$sqlIngresos = "SELECT COALESCE(SUM(monto),0) AS total
                FROM movimiento_contable
                WHERE DATE(fecha) = CURDATE()
                AND tipo = 'ingreso'";

$sqlEgresos = "SELECT COALESCE(SUM(monto),0) AS total
               FROM movimiento_contable
               WHERE DATE(fecha) = CURDATE()
               AND tipo = 'egreso'";

$ingresos = 0;
$egresos  = 0;

$res = $conn->query($sqlIngresos);
if ($res && $row = $res->fetch_assoc()) $ingresos = (float)$row['total'];

$res = $conn->query($sqlEgresos);
if ($res && $row = $res->fetch_assoc()) $egresos = (float)$row['total'];

$utilidad_neta = $ingresos - $egresos;

// Retornamos un arreglo (para incluirlo en las pantallas)
$dashboard = [
    "ventas_dia" => $ventas_dia,
    "stock_bajo" => $stock_bajo,
    "utilidad_neta" => $utilidad_neta,
    "pedidos_pendientes" => $pedidos_pendientes
];