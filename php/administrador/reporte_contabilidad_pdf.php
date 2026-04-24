<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("contabilidad");

require_once "../backend/administrador/contabilidad.php";
require_once "../libs/fpdf.php";

$nombre = $_SESSION['nombre'] ?? 'Administrador';

$movimientos = $filtro_aplicado ? $movimientos_filtrados : $movimientos_generales;

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// ENCABEZADO
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Z-CONTAY'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, utf8_decode('Galpón Aves del Paraíso'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, utf8_decode('Reporte de Contabilidad'), 0, 1, 'C');
$pdf->Ln(5);

// INFORMACIÓN DEL REPORTE
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, utf8_decode('Generado por:'), 0, 0);
$pdf->Cell(0, 6, utf8_decode($nombre), 0, 1);

$pdf->Cell(40, 6, utf8_decode('Fecha desde:'), 0, 0);
$pdf->Cell(0, 6, $fecha_desde !== '' ? $fecha_desde : 'Todas', 0, 1);

$pdf->Cell(40, 6, utf8_decode('Fecha hasta:'), 0, 0);
$pdf->Cell(0, 6, $fecha_hasta !== '' ? $fecha_hasta : 'Todas', 0, 1);

$pdf->Cell(40, 6, utf8_decode('Tipo:'), 0, 0);
$pdf->Cell(0, 6, $tipo !== '' ? utf8_decode($tipo) : 'Todos', 0, 1);

$pdf->Ln(5);

// RESUMEN
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode('Resumen del mes actual'), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, utf8_decode('Ingresos del mes:'), 1, 0);
$pdf->Cell(40, 6, '$' . number_format($resumen['ingresos_mes'], 0, ',', '.'), 1, 1);

$pdf->Cell(60, 6, utf8_decode('Egresos del mes:'), 1, 0);
$pdf->Cell(40, 6, '$' . number_format($resumen['egresos_mes'], 0, ',', '.'), 1, 1);

$pdf->Cell(60, 6, utf8_decode('Balance del mes:'), 1, 0);
$pdf->Cell(40, 6, '$' . number_format($resumen['balance_mes'], 0, ',', '.'), 1, 1);

$pdf->Ln(8);

// TABLA
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(18, 8, 'ID', 1, 0, 'C');
$pdf->Cell(30, 8, 'Fecha', 1, 0, 'C');
$pdf->Cell(30, 8, 'Tipo', 1, 0, 'C');
$pdf->Cell(75, 8, utf8_decode('Descripción'), 1, 0, 'C');
$pdf->Cell(35, 8, 'Monto', 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);

if (!empty($movimientos)) {
    foreach ($movimientos as $mov) {
        $pdf->Cell(18, 8, $mov['id_movimientoContable'], 1, 0, 'C');
        $pdf->Cell(30, 8, date("d/m/Y", strtotime($mov['fecha'])), 1, 0, 'C');
        $pdf->Cell(30, 8, utf8_decode($mov['tipo']), 1, 0, 'C');
        $pdf->Cell(75, 8, utf8_decode(substr($mov['descripcion'], 0, 40)), 1, 0, 'L');
        $pdf->Cell(35, 8, '$' . number_format($mov['monto'], 0, ',', '.'), 1, 1, 'R');
    }
} else {
    $pdf->Cell(188, 10, utf8_decode('No se encontraron movimientos contables.'), 1, 1, 'C');
}

// DESCARGA
$pdf->Output('D', 'reporte_contabilidad.pdf');
exit;
?>