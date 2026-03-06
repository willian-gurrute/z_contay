<?php
// php/backend/administrador/contabilidad.php
// Trae resumen del mes y lista de movimientos desde movimiento_contable

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// 1) Resumen del mes actual
$resumen = [
    'ingresos_mes' => 0,
    'egresos_mes'  => 0,
    'balance_mes'  => 0,
    'mes_texto'    => date('F Y') // ejemplo: March 2026 (si quieres lo pasamos a español luego)
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

// 2) Lista de movimientos (últimos 30)
$movimientos = [];

$sqlMov = "
    SELECT id_movimientoContable, fecha, tipo, descripcion, monto
    FROM movimiento_contable
    ORDER BY fecha DESC
    LIMIT 30
";

$res2 = $conn->query($sqlMov);
if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        $movimientos[] = $row;
    }
}