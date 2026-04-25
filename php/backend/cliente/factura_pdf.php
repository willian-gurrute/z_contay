<?php
require_once __DIR__ . "/../conexion.php";
require_once __DIR__ . "/../../libs/fpdf.php";

session_start();

$id_factura = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_factura <= 0) {
    exit("Factura no válida");
}

// Backend propio del cliente
require_once __DIR__ . "/ver_factura.php";

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'FACTURA DE VENTA', 0, 1, 'C');

$pdf->Ln(5);

// Empresa
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, utf8_decode('Z-CONTAY - Galpón Aves del Paraíso'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'NIT: ' . utf8_decode($empresa['nit'] ?? ''), 0, 1, 'C');

if (!empty($empresa['direccion'])) {
    $pdf->Cell(0, 7, utf8_decode('Direccion: ' . $empresa['direccion']), 0, 1, 'C');
}

$pdf->Cell(0, 7, 'Telefono: ' . utf8_decode($empresa['telefono'] ?? ''), 0, 1, 'C');

$pdf->Cell(
    0,
    7,
    utf8_decode(($empresa['ciudad'] ?? '') . ' - ' . ($empresa['departamento'] ?? '')),
    0,
    1,
    'C'
);

if (!empty($empresa['horario_atencion'])) {
    $pdf->Cell(0, 7, utf8_decode('Horario: ' . $empresa['horario_atencion']), 0, 1, 'C');
}

$pdf->Ln(6);

// Factura
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, 'Factura #: ' . $factura['id_factura'], 0, 1);
$pdf->Cell(0, 8, 'Fecha: ' . $factura['fecha'], 0, 1);
$pdf->Cell(0, 8, 'Tipo venta: ' . utf8_decode($factura['tipo_venta']), 0, 1);

$metodoPago = ($factura['metodo_pago'] === 'tarjeta')
    ? 'Transferencia'
    : $factura['metodo_pago'];

$pdf->Cell(0, 8, 'Metodo de pago: ' . utf8_decode($metodoPago), 0, 1);

$pdf->Ln(5);

// Cliente
$pdf->Cell(0, 8, 'Cliente: ' . utf8_decode($factura['cliente']), 0, 1);
$pdf->Cell(0, 8, 'Documento: ' . $factura['numero_documento'], 0, 1);
$pdf->Cell(0, 8, 'Telefono: ' . utf8_decode($factura['telefono']), 0, 1);

$pdf->Ln(5);

// Tabla productos
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(70, 8, 'Producto', 1);
$pdf->Cell(30, 8, 'Cantidad', 1);
$pdf->Cell(40, 8, 'Precio', 1);
$pdf->Cell(40, 8, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);

foreach ($detalle_factura as $p) {
    $pdf->Cell(70, 8, utf8_decode($p['nombre_producto']), 1);
    $pdf->Cell(30, 8, $p['cantidad'], 1);
    $pdf->Cell(40, 8, '$' . number_format($p['precio_unitario'], 0, ',', '.'), 1);
    $pdf->Cell(40, 8, '$' . number_format($p['subtotal'], 0, ',', '.'), 1);
    $pdf->Ln();
}

$pdf->Ln(5);

// Totales
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, 'Subtotal: $' . number_format($factura['subtotal'], 0, ',', '.'), 0, 1);
$pdf->Cell(0, 8, 'IVA: $' . number_format($factura['iva'], 0, ',', '.'), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Total: $' . number_format($factura['total'], 0, ',', '.'), 0, 1);

$pdf->Output("D", "Factura_" . $factura['id_factura'] . ".pdf");
exit;
?>