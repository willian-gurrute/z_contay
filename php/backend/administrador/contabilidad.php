<?php
// php/backend/administrador/contabilidad.php

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
// FILTROS
// =========================
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';
$tipo = $_GET['tipo'] ?? '';

$filtro_aplicado = ($fecha_desde !== '' || $fecha_hasta !== '' || $tipo !== '');

// =========================
// RESUMEN DEL MES ACTUAL
// =========================
$resumen = [
    'ingresos_mes' => 0,
    'egresos_mes'  => 0,
    'balance_mes'  => 0,
    'mes_texto'    => date('F Y')
];

$sqlResumen = "
    SELECT tipo, COALESCE(SUM(monto),0) AS total
    FROM movimiento_contable
    WHERE YEAR(fecha) = YEAR(CURDATE())
      AND MONTH(fecha) = MONTH(CURDATE())
    GROUP BY tipo
";

$res = $conn->query($sqlResumen);

if ($res) {
    while ($row = $res->fetch_assoc()) {
        if ($row['tipo'] === 'ingreso') $resumen['ingresos_mes'] = (float)$row['total'];
        if ($row['tipo'] === 'egreso')  $resumen['egresos_mes']  = (float)$row['total'];
    }
}

$resumen['balance_mes'] = $resumen['ingresos_mes'] - $resumen['egresos_mes'];

// =========================
// TABLA ORIGINAL
// =========================
$movimientos_generales = [];

$sqlGeneral = "
    SELECT id_movimientoContable, fecha, tipo, descripcion, monto
    FROM movimiento_contable
    ORDER BY fecha DESC
";

$res2 = $conn->query($sqlGeneral);

if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        $movimientos_generales[] = $row;
    }
}

// =========================
// TABLA FILTRADA
// =========================
$movimientos_filtrados = [];

$sqlFiltro = "
    SELECT id_movimientoContable, fecha, tipo, descripcion, monto
    FROM movimiento_contable
    WHERE 1=1
";

if ($fecha_desde !== '') {
    $fecha_desde_segura = $conn->real_escape_string($fecha_desde);
    $sqlFiltro .= " AND DATE(fecha) >= '$fecha_desde_segura'";
}

if ($fecha_hasta !== '') {
    $fecha_hasta_segura = $conn->real_escape_string($fecha_hasta);
    $sqlFiltro .= " AND DATE(fecha) <= '$fecha_hasta_segura'";
}

if ($tipo !== '') {
    $tipo_seguro = $conn->real_escape_string($tipo);
    $sqlFiltro .= " AND tipo = '$tipo_seguro'";
}

$sqlFiltro .= " ORDER BY fecha DESC";

if ($filtro_aplicado) {
    $res3 = $conn->query($sqlFiltro);

    if ($res3) {
        while ($row = $res3->fetch_assoc()) {
            $movimientos_filtrados[] = $row;
        }
    }
}