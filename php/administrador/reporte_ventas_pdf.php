<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("ventas");

require_once "../backend/administrador/ventas.php";
require_once "../libs/fpdf.php";

$sqlEmpresa = "
SELECT nit,direccion,telefono,ciudad,departamento,horario_atencion
FROM empresa
WHERE id_empresa=1
LIMIT 1
";

$resEmpresa = $conn->query($sqlEmpresa);
$empresa = $resEmpresa->fetch_assoc();

$nombre = $_SESSION['nombre'] ?? 'Administrador';

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Encabezado
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('Z-CONTAY - Galpón Aves del Paraíso'),0,1,'C');

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,6,'NIT: '.$empresa['nit'],0,1,'C');

if (!empty($empresa['direccion'])) {
    $pdf->Cell(0,6,utf8_decode('Dirección: '.$empresa['direccion']),0,1,'C');
}

$pdf->Cell(0,6,utf8_decode('Tel: '.$empresa['telefono']),0,1,'C');

$pdf->Cell(0,6,utf8_decode($empresa['ciudad'].' - '.$empresa['departamento']),0,1,'C');

if (!empty($empresa['horario_atencion'])) {
    $pdf->Cell(0,6,utf8_decode('Horario: '.$empresa['horario_atencion']),0,1,'C');
}

$pdf->Ln(4);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,8,utf8_decode('Reporte de Ventas - Administrador'),0,1,'C');
$pdf->Ln(5);

// Info filtros
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, utf8_decode('Generado por:'), 0, 0);
$pdf->Cell(0, 6, utf8_decode($nombre), 0, 1);

$pdf->Cell(40, 6, utf8_decode('Fecha inicial:'), 0, 0);
$pdf->Cell(0, 6, $fecha_inicial !== '' ? $fecha_inicial : 'Todas', 0, 1);

$pdf->Cell(40, 6, utf8_decode('Fecha final:'), 0, 0);
$pdf->Cell(0, 6, $fecha_final !== '' ? $fecha_final : 'Todas', 0, 1);

$pdf->Cell(40, 6, utf8_decode('Vendedor:'), 0, 0);

$nombreVendedor = 'Todos';

if ($id_vendedor !== '') {
    foreach ($vendedores as $v) {
        if ((int)$v['id_usuario'] === (int)$id_vendedor) {
            $nombreVendedor = $v['nombre_completo'];
            break;
        }
    }
}

$pdf->Cell(0, 6, utf8_decode($nombreVendedor), 0, 1);
$pdf->Ln(6);

// Resumen
$totalVentas = count($ventas);
$totalMonto = 0;

foreach ($ventas as $venta) {
    $totalMonto += (float)$venta['total'];
}

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode('Resumen'), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(55, 6, utf8_decode('Total ventas:'), 1, 0);
$pdf->Cell(40, 6, $totalVentas, 1, 1);

$pdf->Cell(55, 6, utf8_decode('Total vendido:'), 1, 0);
$pdf->Cell(40, 6, '$' . number_format($totalMonto, 0, ',', '.'), 1, 1);

$pdf->Ln(8);

// Tabla
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(18, 8, 'Factura', 1, 0, 'C');
$pdf->Cell(28, 8, 'Fecha', 1, 0, 'C');
$pdf->Cell(42, 8, 'Cliente', 1, 0, 'C');
$pdf->Cell(38, 8, 'Vendedor', 1, 0, 'C');
$pdf->Cell(24, 8, 'Tipo', 1, 0, 'C');
$pdf->Cell(22, 8, 'Estado', 1, 0, 'C');
$pdf->Cell(18, 8, 'Total', 1, 1, 'C');

$pdf->SetFont('Arial', '', 7);

if (!empty($ventas)) {
    foreach ($ventas as $venta) {
        $pdf->Cell(18, 8, $venta['id_factura'], 1, 0, 'C');
        $pdf->Cell(28, 8, date("d/m/Y", strtotime($venta['fecha'])), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode(substr($venta['cliente'], 0, 22)), 1, 0, 'L');
        $pdf->Cell(38, 8, utf8_decode(substr($venta['vendedor'], 0, 20)), 1, 0, 'L');
        $pdf->Cell(24, 8, utf8_decode($venta['tipo_venta']), 1, 0, 'C');
        $pdf->Cell(22, 8, utf8_decode($venta['estado_factura']), 1, 0, 'C');
        $pdf->Cell(18, 8, '$' . number_format($venta['total'], 0, ',', '.'), 1, 1, 'R');
    }
} else {
    $pdf->Cell(190, 10, utf8_decode('No se encontraron ventas con los filtros seleccionados.'), 1, 1, 'C');
}

// Descargar PDF
$pdf->Output('D', 'reporte_ventas_administrador.pdf');
exit;
?>