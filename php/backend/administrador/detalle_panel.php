<?php
// Trae listas para mostrar en detalle_panel.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión (está en php/backend/conexion.php)
require_once __DIR__ . "/../conexion.php";

// Seguridad: solo admin
if (!isset($_SESSION['id_usuario']) || ($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Últimas 5 facturas de hoy
 
$ultimas_facturas = [];

$sqlFacturas = "
SELECT f.id_factura, f.fecha, f.total, f.estado_factura, c.nombre_completo
FROM factura f
JOIN cliente c ON c.id_cliente = f.id_cliente
WHERE DATE(f.fecha) = CURDATE()
ORDER BY f.fecha DESC
LIMIT 5
";

$res = $conn->query($sqlFacturas);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $ultimas_facturas[] = $row;
    }
}

// Productos con stock bajo (lista)

$lista_stock_bajo = [];

$sqlStockBajo = "
SELECT p.nombre_producto, i.cantidad, i.stock_minimo
FROM inventario i
JOIN producto p ON p.id_producto = i.id_producto
WHERE i.cantidad <= i.stock_minimo
ORDER BY i.cantidad ASC
";

$res = $conn->query($sqlStockBajo);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $lista_stock_bajo[] = $row;
    }
}

// Últimos 5 pedidos del sistema
$pedidos_pendientes_lista = [];

$sqlPedidos = "
SELECT 
    p.id_pedido, 
    p.fecha, 
    p.total, 
    p.estado,
    p.estado_pago,
    c.nombre_completo
FROM pedido p
JOIN cliente c ON c.id_cliente = p.id_cliente
ORDER BY p.fecha DESC
LIMIT 5
";

$res = $conn->query($sqlPedidos);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $pedidos_pendientes_lista[] = $row;
    }
}