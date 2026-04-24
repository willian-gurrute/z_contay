<?php
// php/backend/administrador/reportes.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// =========================
// FILTROS GENERALES
// =========================
$tipo_reporte = $_GET['tipo_reporte'] ?? 'usuarios';

$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

$filtro_rol = $_GET['rol'] ?? '';
$filtro_estado = $_GET['estado'] ?? '';

$filtro_producto = $_GET['producto'] ?? '';
$filtro_stock = $_GET['stock'] ?? '';

$filtro_vendedor = $_GET['vendedor'] ?? '';
$filtro_tipo_venta = $_GET['tipo_venta'] ?? '';

$filtro_tipo_movimiento = $_GET['tipo_movimiento'] ?? '';


// =========================
// LISTAS PARA SELECTS
// =========================

// Roles
$roles = [];
$resRoles = $conn->query("SELECT id_rol, nombre FROM rol ORDER BY nombre ASC");
if ($resRoles) {
    while ($row = $resRoles->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Productos
$productos = [];
$resProductos = $conn->query("SELECT id_producto, nombre_producto FROM producto ORDER BY nombre_producto ASC");
if ($resProductos) {
    while ($row = $resProductos->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Vendedores
$vendedores = [];
$resVendedores = $conn->query("
    SELECT id_usuario, nombre_completo 
    FROM usuario 
    WHERE id_rol = 2 AND estado = 'A'
    ORDER BY nombre_completo ASC
");
if ($resVendedores) {
    while ($row = $resVendedores->fetch_assoc()) {
        $vendedores[] = $row;
    }
}


// =========================
// REPORTE USUARIOS
// =========================
$reporte_usuarios = [];

$sqlUsuarios = "
    SELECT 
        u.id_usuario,
        u.nombre_completo,
        u.correo_electronico,
        u.estado,
        r.nombre AS rol
    FROM usuario u
    INNER JOIN rol r ON r.id_rol = u.id_rol
    WHERE 1=1
";

if ($filtro_rol !== '') {
    $rol_seguro = (int)$filtro_rol;
    $sqlUsuarios .= " AND u.id_rol = $rol_seguro";
}

if ($filtro_estado !== '') {
    $estado_seguro = $conn->real_escape_string($filtro_estado);
    $sqlUsuarios .= " AND u.estado = '$estado_seguro'";
}

$sqlUsuarios .= " ORDER BY u.id_usuario ASC";

$res = $conn->query($sqlUsuarios);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $reporte_usuarios[] = $row;
    }
}

// Cards usuarios
$totalUsuarios = count($reporte_usuarios);
$totalUsuariosActivos = 0;
$totalUsuariosInactivos = 0;
$totalClientes = 0;

foreach ($reporte_usuarios as $u) {
    if ($u['estado'] === 'A') {
        $totalUsuariosActivos++;
    } else {
        $totalUsuariosInactivos++;
    }

    if (strtolower($u['rol']) === 'cliente') {
        $totalClientes++;
    }
}


// =========================
// REPORTE INVENTARIO
// =========================
$reporte_inventario = [];

$sqlInventario = "
    SELECT 
        i.id_inventario,
        p.id_producto,
        p.nombre_producto,
        i.cantidad,
        i.stock_minimo,
        i.ultima_actualizacion
    FROM inventario i
    INNER JOIN producto p ON p.id_producto = i.id_producto
    WHERE 1=1
";

if ($filtro_producto !== '') {
    $producto_seguro = (int)$filtro_producto;
    $sqlInventario .= " AND p.id_producto = $producto_seguro";
}

if ($filtro_stock === 'bajo') {
    $sqlInventario .= " AND i.cantidad <= i.stock_minimo";
}

if ($filtro_stock === 'normal') {
    $sqlInventario .= " AND i.cantidad > i.stock_minimo";
}

$sqlInventario .= " ORDER BY p.nombre_producto ASC";

$res = $conn->query($sqlInventario);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $reporte_inventario[] = $row;
    }
}

// Cards inventario
$totalProductosInventario = count($reporte_inventario);
$totalStockBajo = 0;
$totalUnidades = 0;

foreach ($reporte_inventario as $i) {
    $totalUnidades += (int)$i['cantidad'];

    if ((int)$i['cantidad'] <= (int)$i['stock_minimo']) {
        $totalStockBajo++;
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
        f.tipo_venta,
        f.estado_factura,
        c.nombre_completo AS cliente,
        u.nombre_completo AS vendedor
    FROM factura f
    INNER JOIN cliente c ON c.id_cliente = f.id_cliente
    INNER JOIN usuario u ON u.id_usuario = f.id_usuario
    WHERE 1=1
";

if ($fecha_desde !== '') {
    $fecha_desde_segura = $conn->real_escape_string($fecha_desde);
    $sqlVentas .= " AND DATE(f.fecha) >= '$fecha_desde_segura'";
}

if ($fecha_hasta !== '') {
    $fecha_hasta_segura = $conn->real_escape_string($fecha_hasta);
    $sqlVentas .= " AND DATE(f.fecha) <= '$fecha_hasta_segura'";
}

if ($filtro_vendedor !== '') {
    $vendedor_seguro = (int)$filtro_vendedor;
    $sqlVentas .= " AND f.id_usuario = $vendedor_seguro";
}

if ($filtro_tipo_venta !== '') {
    $tipo_venta_seguro = $conn->real_escape_string($filtro_tipo_venta);
    $sqlVentas .= " AND f.tipo_venta = '$tipo_venta_seguro'";
}

$sqlVentas .= " ORDER BY f.fecha DESC";

$res = $conn->query($sqlVentas);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $reporte_ventas[] = $row;
    }
}

// Cards ventas
$totalVentas = count($reporte_ventas);
$totalVendido = 0;
$totalVentasDirectas = 0;
$totalVentasPedido = 0;

foreach ($reporte_ventas as $v) {
    $totalVendido += (float)$v['total'];

    if ($v['tipo_venta'] === 'directa') {
        $totalVentasDirectas++;
    }

    if ($v['tipo_venta'] === 'pedido') {
        $totalVentasPedido++;
    }
}


// =========================
// REPORTE FINANCIERO
// =========================
$reporte_financiero = [
    'ingresos' => 0,
    'egresos'  => 0,
    'balance'  => 0
];

$reporte_movimientos = [];

$sqlFin = "
    SELECT id_movimientoContable, fecha, tipo, descripcion, monto
    FROM movimiento_contable
    WHERE 1=1
";

if ($fecha_desde !== '') {
    $fecha_desde_segura = $conn->real_escape_string($fecha_desde);
    $sqlFin .= " AND DATE(fecha) >= '$fecha_desde_segura'";
}

if ($fecha_hasta !== '') {
    $fecha_hasta_segura = $conn->real_escape_string($fecha_hasta);
    $sqlFin .= " AND DATE(fecha) <= '$fecha_hasta_segura'";
}

if ($filtro_tipo_movimiento !== '') {
    $tipo_mov_seguro = $conn->real_escape_string($filtro_tipo_movimiento);
    $sqlFin .= " AND tipo = '$tipo_mov_seguro'";
}

$sqlFin .= " ORDER BY fecha DESC";

$res = $conn->query($sqlFin);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $reporte_movimientos[] = $row;

        if ($row['tipo'] === 'ingreso') {
            $reporte_financiero['ingresos'] += (float)$row['monto'];
        }

        if ($row['tipo'] === 'egreso') {
            $reporte_financiero['egresos'] += (float)$row['monto'];
        }
    }
}

$reporte_financiero['balance'] =
    $reporte_financiero['ingresos'] - $reporte_financiero['egresos'];


// =========================
// REPORTE GENERAL
// =========================
$reporte_general = [
    'usuarios'     => 0,
    'productos'    => 0,
    'ventas'       => 0,
    'movimientos'  => 0
];

$r1 = $conn->query("SELECT COUNT(*) total FROM usuario");
if ($r1) {
    $reporte_general['usuarios'] = $r1->fetch_assoc()['total'];
}

$r2 = $conn->query("SELECT COUNT(*) total FROM producto");
if ($r2) {
    $reporte_general['productos'] = $r2->fetch_assoc()['total'];
}

$r3 = $conn->query("SELECT COUNT(*) total FROM factura");
if ($r3) {
    $reporte_general['ventas'] = $r3->fetch_assoc()['total'];
}

$r4 = $conn->query("SELECT COUNT(*) total FROM movimiento_contable");
if ($r4) {
    $reporte_general['movimientos'] = $r4->fetch_assoc()['total'];
}
?>