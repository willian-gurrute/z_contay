<?php
// php/backend/administrador/ventas.php
// Trae ventas para la pantalla ventas.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Filtros
$fecha_inicial = $_GET['fecha_inicial'] ?? '';
$fecha_final   = $_GET['fecha_final'] ?? '';
$id_vendedor   = $_GET['id_vendedor'] ?? '';

// 1) Traer vendedores para el filtro
$vendedores = [];

$sqlVendedores = "
    SELECT id_usuario, nombre_completo
    FROM usuario
    WHERE estado = 'A'
    ORDER BY nombre_completo ASC
";

$resV = $conn->query($sqlVendedores);
if ($resV) {
    while ($row = $resV->fetch_assoc()) {
        $vendedores[] = $row;
    }
}

// 2) Traer ventas con producto principal
$ventas = [];

$sql = "
    SELECT 
        f.id_factura,
        f.fecha,
        f.total,
        c.nombre_completo AS cliente,
        u.nombre_completo AS vendedor,
        p.nombre_producto AS producto,
        df.cantidad
    FROM factura f
    INNER JOIN cliente c ON c.id_cliente = f.id_cliente
    INNER JOIN usuario u ON u.id_usuario = f.id_usuario
    INNER JOIN detalle_factura df ON df.id_factura = f.id_factura
    INNER JOIN producto p ON p.id_producto = df.id_producto
    WHERE 1=1
";

// Arreglos para prepared statement
$tipos = "";
$valores = [];

// Filtro fecha inicial
if ($fecha_inicial !== '') {
    $sql .= " AND DATE(f.fecha) >= ?";
    $tipos .= "s";
    $valores[] = $fecha_inicial;
}

// Filtro fecha final
if ($fecha_final !== '') {
    $sql .= " AND DATE(f.fecha) <= ?";
    $tipos .= "s";
    $valores[] = $fecha_final;
}

// Filtro vendedor
if ($id_vendedor !== '') {
    $sql .= " AND f.id_usuario = ?";
    $tipos .= "i";
    $valores[] = (int)$id_vendedor;
}

$sql .= " ORDER BY f.fecha DESC";

$stmt = $conn->prepare($sql);

// Si hay filtros, los vinculamos
if (!empty($valores)) {
    $stmt->bind_param($tipos, ...$valores);
}

$stmt->execute();
$res = $stmt->get_result();

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $ventas[] = $row;
    }
}