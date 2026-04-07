<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("reportes_venta");

require_once "../backend/vendedor/reportes_venta.php";
require_once "../libs/fpdf.php";

// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Vendedor';

// Crear PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// ===============================
// ENCABEZADO
// ===============================
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Z-CONTAY'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, utf8_decode('Galpón Aves del Paraíso'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, utf8_decode('Reporte de Ventas del Vendedor'), 0, 1, 'C');
$pdf->Ln(5);

// ===============================
// INFORMACIÓN DEL REPORTE
// ===============================
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, utf8_decode('Vendedor:'), 0, 0);
$pdf->Cell(0, 6, utf8_decode($nombre), 0, 1);

$pdf->Cell(40, 6, utf8_decode('Fecha inicial:'), 0, 0);
$pdf->Cell(0, 6, $fecha_inicial !== '' ? $fecha_inicial : 'Todas', 0, 1);

$pdf->Cell(40, 6, utf8_decode('Fecha final:'), 0, 0);
$pdf->Cell(0, 6, $fecha_final !== '' ? $fecha_final : 'Todas', 0, 1);

$pdf->Cell(40, 6, utf8_decode('Cliente:'), 0, 0);
$pdf->Cell(0, 6, $cliente !== '' ? utf8_decode($cliente) : 'Todos', 0, 1);

$pdf->Cell(40, 6, utf8_decode('Tipo de venta:'), 0, 0);
$pdf->Cell(0, 6, $tipo_venta !== '' ? utf8_decode($tipo_venta) : 'Todas', 0, 1);

$pdf->Ln(5);

// ===============================
// RESUMEN
// ===============================
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode('Resumen'), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, utf8_decode('Total ventas:'), 1, 0);
$pdf->Cell(30, 6, $totalVentas, 1, 1);

$pdf->Cell(60, 6, utf8_decode('Total vendido:'), 1, 0);
$pdf->Cell(30, 6, '$' . number_format($totalMonto, 0, ',', '.'), 1, 1);

$pdf->Cell(60, 6, utf8_decode('Ventas directas:'), 1, 0);
$pdf->Cell(30, 6, $ventasDirectas, 1, 1);

$pdf->Cell(60, 6, utf8_decode('Ventas tipo pedido:'), 1, 0);
$pdf->Cell(30, 6, $ventasPedido, 1, 1);

$pdf->Ln(8);

// ===============================
// TABLA DE VENTAS
// ===============================
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(20, 8, 'Factura', 1, 0, 'C');
$pdf->Cell(28, 8, 'Fecha', 1, 0, 'C');
$pdf->Cell(55, 8, utf8_decode('Cliente'), 1, 0, 'C');
$pdf->Cell(25, 8, 'Tipo', 1, 0, 'C');
$pdf->Cell(25, 8, 'Estado', 1, 0, 'C');
$pdf->Cell(35, 8, 'Total', 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);

if (!empty($ventas)) {
    foreach ($ventas as $venta) {
        $pdf->Cell(20, 8, $venta['id_factura'], 1, 0, 'C');
        $pdf->Cell(28, 8, date("d/m/Y", strtotime($venta['fecha'])), 1, 0, 'C');
        $pdf->Cell(55, 8, utf8_decode(substr($venta['cliente'], 0, 28)), 1, 0, 'L');
        $pdf->Cell(25, 8, utf8_decode($venta['tipo_venta']), 1, 0, 'C');
        $pdf->Cell(25, 8, utf8_decode($venta['estado_factura']), 1, 0, 'C');
        $pdf->Cell(35, 8, '$' . number_format($venta['total'], 0, ',', '.'), 1, 1, 'R');
    }
} else {
    $pdf->Cell(188, 10, utf8_decode('No se encontraron ventas con los filtros seleccionados.'), 1, 1, 'C');
}

// ===============================
// SALIDA
// ===============================
$pdf->Output('I', 'reporte_ventas_vendedor.pdf');
exit;
?>