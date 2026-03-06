<?php
// php/backend/administrador/reportes.php
// Trae datos para los distintos reportes

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Filtros
$tipo_reporte = $_GET['tipo_reporte'] ?? 'usuarios';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

// =========================
// REPORTE USUARIOS
// =========================
$reporte_usuarios = [];

$sqlUsuarios = "
    SELECT u.id_usuario, u.nombre_completo, u.correo_electronico, u.estado, r.nombre AS rol
    FROM usuario u
    INNER JOIN rol r ON r.id_rol = u.id_rol
    ORDER BY u.id_usuario ASC
";

$res = $conn->query($sqlUsuarios);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $reporte_usuarios[] = $row;
    }
}

// =========================
// REPORTE INVENTARIO
// =========================
$reporte_inventario = [];

$sqlInventario = "
    SELECT i.id_inventario, p.nombre_producto, i.cantidad, i.stock_minimo, i.ultima_actualizacion
    FROM inventario i
    INNER JOIN producto p ON p.id_producto = i.id_producto
    ORDER BY i.id_inventario ASC
";

$res = $conn->query($sqlInventario);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $reporte_inventario[] = $row;
    }
}

// =========================
// REPORTE VENTAS
// =========================
$reporte_ventas = [];

$sqlVentas = "
    SELECT 
        f.id_factura,
        f.fecha,
        f.total,
        c.nombre_completo AS cliente,
        u.nombre_completo AS vendedor
    FROM factura f
    INNER JOIN cliente c ON c.id_cliente = f.id_cliente
    INNER JOIN usuario u ON u.id_usuario = f.id_usuario
    WHERE 1=1
";

if ($fecha_desde != '') {
    $sqlVentas .= " AND DATE(f.fecha) >= '$fecha_desde'";
}

if ($fecha_hasta != '') {
    $sqlVentas .= " AND DATE(f.fecha) <= '$fecha_hasta'";
}

$sqlVentas .= " ORDER BY f.fecha DESC";

$res = $conn->query($sqlVentas);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $reporte_ventas[] = $row;
    }
}

// =========================
// REPORTE FINANCIERO
// =========================
$reporte_financiero = [
    'ingresos' => 0,
    'egresos' => 0,
    'balance' => 0
];

$sqlFin = "
    SELECT tipo, SUM(monto) total
    FROM movimiento_contable
    WHERE 1=1
";

if ($fecha_desde != '') {
    $sqlFin .= " AND DATE(fecha) >= '$fecha_desde'";
}

if ($fecha_hasta != '') {
    $sqlFin .= " AND DATE(fecha) <= '$fecha_hasta'";
}

$sqlFin .= " GROUP BY tipo";

$res = $conn->query($sqlFin);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        if ($row['tipo'] == 'ingreso') $reporte_financiero['ingresos'] = (float)$row['total'];
        if ($row['tipo'] == 'egreso') $reporte_financiero['egresos'] = (float)$row['total'];
    }
}

$reporte_financiero['balance'] = $reporte_financiero['ingresos'] - $reporte_financiero['egresos'];

// =========================
// REPORTE GENERAL
// =========================
$reporte_general = [
    'usuarios' => 0,
    'productos' => 0,
    'ventas' => 0,
    'movimientos' => 0
];

$r1 = $conn->query("SELECT COUNT(*) total FROM usuario");
if ($r1) $reporte_general['usuarios'] = $r1->fetch_assoc()['total'];

$r2 = $conn->query("SELECT COUNT(*) total FROM producto");
if ($r2) $reporte_general['productos'] = $r2->fetch_assoc()['total'];

$r3 = $conn->query("SELECT COUNT(*) total FROM factura");
if ($r3) $reporte_general['ventas'] = $r3->fetch_assoc()['total'];

$r4 = $conn->query("SELECT COUNT(*) total FROM movimiento_contable");
if ($r4) $reporte_general['movimientos'] = $r4->fetch_assoc()['total'];