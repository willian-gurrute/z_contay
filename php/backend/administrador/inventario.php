<?php
// php/backend/administrador/inventario.php
// Trae resumen y detalle del inventario

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// =========================
// RESUMEN DEL INVENTARIO
// =========================
$resumen = [];

$sqlResumen = "
    SELECT 
        p.nombre_producto,
        i.cantidad
    FROM inventario i
    INNER JOIN producto p ON p.id_producto = i.id_producto
    ORDER BY p.id_producto ASC
";

$res = $conn->query($sqlResumen);

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $resumen[] = $row;
    }
}

// =========================
// DETALLE DEL INVENTARIO
// =========================
$inventario = [];

$sqlDetalle = "
    SELECT 
        i.id_inventario,
        p.nombre_producto,
        i.cantidad,
        i.ultima_actualizacion
    FROM inventario i
    INNER JOIN producto p ON p.id_producto = i.id_producto
    ORDER BY i.id_inventario ASC
";

$res2 = $conn->query($sqlDetalle);

if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        $inventario[] = $row;
    }
}