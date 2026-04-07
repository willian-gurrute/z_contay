<?php
require_once __DIR__ . "/../conexion.php";

// Tomar el id del vendedor logueado
$idUsuario = $_SESSION['id_usuario'] ?? 0;

// ===============================
// FILTROS
// ===============================
$fecha_inicial = $_GET['fecha_inicial'] ?? '';
$fecha_final   = $_GET['fecha_final'] ?? '';
$cliente       = trim($_GET['cliente'] ?? '');
$tipo_venta    = trim($_GET['tipo_venta'] ?? '');

// ===============================
// REPORTE DE VENTAS
// ===============================
$ventas = [];

$sqlVentas = "SELECT 
                f.id_factura,
                f.fecha,
                f.total,
                f.tipo_venta,
                f.metodo_pago,
                f.estado_factura,
                c.nombre_completo AS cliente
              FROM factura f
              INNER JOIN cliente c ON f.id_cliente = c.id_cliente
              WHERE f.id_usuario = ?";

$tipos = "i";
$valores = [$idUsuario];

// Filtro fecha inicial
if ($fecha_inicial !== '') {
    $sqlVentas .= " AND DATE(f.fecha) >= ?";
    $tipos .= "s";
    $valores[] = $fecha_inicial;
}

// Filtro fecha final
if ($fecha_final !== '') {
    $sqlVentas .= " AND DATE(f.fecha) <= ?";
    $tipos .= "s";
    $valores[] = $fecha_final;
}

// Filtro cliente
if ($cliente !== '') {
    $sqlVentas .= " AND c.nombre_completo LIKE ?";
    $tipos .= "s";
    $valores[] = "%" . $cliente . "%";
}

// Filtro tipo de venta
if ($tipo_venta !== '') {
    $sqlVentas .= " AND f.tipo_venta = ?";
    $tipos .= "s";
    $valores[] = $tipo_venta;
}

$sqlVentas .= " ORDER BY f.fecha DESC";

$stmtVentas = $conn->prepare($sqlVentas);

if ($stmtVentas) {
    $stmtVentas->bind_param($tipos, ...$valores);
    $stmtVentas->execute();
    $resVentas = $stmtVentas->get_result();

    while ($fila = $resVentas->fetch_assoc()) {
        $ventas[] = $fila;
    }

    $stmtVentas->close();
}

// ===============================
// RESUMEN DEL REPORTE
// ===============================
$totalVentas = count($ventas);
$totalMonto = 0;
$ventasDirectas = 0;
$ventasPedido = 0;

foreach ($ventas as $venta) {
    $totalMonto += (float)$venta['total'];

    if ($venta['tipo_venta'] === 'directa') {
        $ventasDirectas++;
    } elseif ($venta['tipo_venta'] === 'pedido') {
        $ventasPedido++;
    }
}
?>