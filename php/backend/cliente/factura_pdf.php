<?php
require_once __DIR__ . "/../conexion.php";
require_once __DIR__ . "/../../libs/fpdf.php";

session_start();

$id_factura = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_factura <= 0) {
    exit("Factura no válida");
}

// Reutilizamos tu backend existente
require_once __DIR__ . "/../vendedor/factura_imprimir_datos.php";

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'FACTURA DE VENTA', 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);

// Empresa
$pdf->Cell(0, 8, $empresa['nombre'], 0, 1);
$pdf->Cell(0, 8, 'NIT: ' . $empresa['nit'], 0, 1);
$pdf->Cell(0, 8, 'Telefono: ' . $empresa['telefono'], 0, 1);

$pdf->Ln(5);

// Factura
$pdf->Cell(0, 8, 'Factura #: ' . $factura['id_factura'], 0, 1);
$pdf->Cell(0, 8, 'Fecha: ' . $factura['fecha'], 0, 1);

$pdf->Ln(5);

// Cliente
$pdf->Cell(0, 8, 'Cliente: ' . $factura['cliente'], 0, 1);
$pdf->Cell(0, 8, 'Documento: ' . $factura['numero_documento'], 0, 1);

$pdf->Ln(5);

// Tabla productos
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(70, 8, 'Producto', 1);
$pdf->Cell(30, 8, 'Cantidad', 1);
$pdf->Cell(40, 8, 'Precio', 1);
$pdf->Cell(40, 8, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);

foreach ($productos as $p) {
    $pdf->Cell(70, 8, $p['nombre_producto'], 1);
    $pdf->Cell(30, 8, $p['cantidad'], 1);
    $pdf->Cell(40, 8, number_format($p['precio_unitario'], 0, ',', '.'), 1);
    $pdf->Cell(40, 8, number_format($p['subtotal'], 0, ',', '.'), 1);
    $pdf->Ln();
}

$pdf->Ln(5);

// Totales
$pdf->Cell(0, 8, 'Subtotal: $' . number_format($factura['subtotal'], 0, ',', '.'), 0, 1);
$pdf->Cell(0, 8, 'IVA: $' . number_format($factura['iva'], 0, ',', '.'), 0, 1);
$pdf->Cell(0, 8, 'Total: $' . number_format($factura['total'], 0, ',', '.'), 0, 1);

$pdf->Output("D", "Factura_" . $factura['id_factura'] . ".pdf");