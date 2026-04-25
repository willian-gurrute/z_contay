<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("reportes");

require_once "../backend/administrador/reportes.php";
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

// ENCABEZADO
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

// INFORMACIÓN
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(45, 6, utf8_decode('Generado por:'), 0, 0);
$pdf->Cell(0, 6, utf8_decode($nombre), 0, 1);

$pdf->Cell(45, 6, utf8_decode('Tipo de reporte:'), 0, 0);
$pdf->Cell(0, 6, utf8_decode(ucfirst($tipo_reporte)), 0, 1);

$pdf->Cell(45, 6, utf8_decode('Fecha de generación:'), 0, 0);
$pdf->Cell(0, 6, date('d/m/Y H:i'), 0, 1);

$pdf->Ln(8);


// ===============================
// REPORTE USUARIOS
// ===============================
if ($tipo_reporte == 'usuarios') {

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, utf8_decode('Resumen de Usuarios'), 0, 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, 7, 'Total usuarios', 1, 0);
    $pdf->Cell(35, 7, $totalUsuarios, 1, 1);

    $pdf->Cell(50, 7, 'Activos', 1, 0);
    $pdf->Cell(35, 7, $totalUsuariosActivos, 1, 1);

    $pdf->Cell(50, 7, 'Inactivos', 1, 0);
    $pdf->Cell(35, 7, $totalUsuariosInactivos, 1, 1);

    $pdf->Cell(50, 7, 'Clientes', 1, 0);
    $pdf->Cell(35, 7, $totalClientes, 1, 1);

    $pdf->Ln(6);

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(12, 8, 'ID', 1, 0, 'C');
    $pdf->Cell(50, 8, 'Nombre', 1, 0, 'C');
    $pdf->Cell(60, 8, 'Correo', 1, 0, 'C');
    $pdf->Cell(35, 8, 'Rol', 1, 0, 'C');
    $pdf->Cell(25, 8, 'Estado', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 7);

    if (!empty($reporte_usuarios)) {
        foreach ($reporte_usuarios as $u) {
            $pdf->Cell(12, 8, $u['id_usuario'], 1, 0, 'C');
            $pdf->Cell(50, 8, utf8_decode(substr($u['nombre_completo'], 0, 28)), 1, 0);
            $pdf->Cell(60, 8, utf8_decode(substr($u['correo_electronico'], 0, 35)), 1, 0);
            $pdf->Cell(35, 8, utf8_decode($u['rol']), 1, 0, 'C');
            $pdf->Cell(25, 8, ($u['estado'] == 'A') ? 'Activo' : 'Inactivo', 1, 1, 'C');
        }
    } else {
        $pdf->Cell(182, 10, utf8_decode('No hay registros.'), 1, 1, 'C');
    }
}


// ===============================
// REPORTE INVENTARIO
// ===============================
if ($tipo_reporte == 'inventario') {

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, utf8_decode('Resumen de Inventario'), 0, 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 7, 'Productos', 1, 0);
    $pdf->Cell(35, 7, $totalProductosInventario, 1, 1);

    $pdf->Cell(55, 7, 'Stock bajo', 1, 0);
    $pdf->Cell(35, 7, $totalStockBajo, 1, 1);

    $pdf->Cell(55, 7, 'Total unidades', 1, 0);
    $pdf->Cell(35, 7, $totalUnidades, 1, 1);

    $pdf->Ln(6);

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(12, 8, 'ID', 1, 0, 'C');
    $pdf->Cell(60, 8, 'Producto', 1, 0, 'C');
    $pdf->Cell(25, 8, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(25, 8, 'Minimo', 1, 0, 'C');
    $pdf->Cell(35, 8, 'Estado stock', 1, 0, 'C');
    $pdf->Cell(30, 8, 'Actualizado', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 7);

    if (!empty($reporte_inventario)) {
        foreach ($reporte_inventario as $i) {

            $estadoStock = ((int)$i['cantidad'] <= (int)$i['stock_minimo'])
                ? 'Stock bajo'
                : 'Stock normal';

            $pdf->Cell(12, 8, $i['id_inventario'], 1, 0, 'C');
            $pdf->Cell(60, 8, utf8_decode(substr($i['nombre_producto'], 0, 32)), 1, 0);
            $pdf->Cell(25, 8, $i['cantidad'], 1, 0, 'C');
            $pdf->Cell(25, 8, $i['stock_minimo'], 1, 0, 'C');
            $pdf->Cell(35, 8, utf8_decode($estadoStock), 1, 0, 'C');
            $pdf->Cell(30, 8, date("d/m/Y", strtotime($i['ultima_actualizacion'])), 1, 1, 'C');
        }
    } else {
        $pdf->Cell(187, 10, utf8_decode('No hay registros.'), 1, 1, 'C');
    }
}


// ===============================
// REPORTE VENTAS
// ===============================
if ($tipo_reporte == 'ventas') {

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, utf8_decode('Resumen de Ventas'), 0, 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, 7, 'Total ventas', 1, 0);
    $pdf->Cell(40, 7, $totalVentas, 1, 1);

    $pdf->Cell(55, 7, 'Total vendido', 1, 0);
    $pdf->Cell(40, 7, '$' . number_format($totalVendido, 0, ',', '.'), 1, 1);

    $pdf->Cell(55, 7, 'Ventas directas', 1, 0);
    $pdf->Cell(40, 7, $totalVentasDirectas, 1, 1);

    $pdf->Cell(55, 7, 'Ventas por pedido', 1, 0);
    $pdf->Cell(40, 7, $totalVentasPedido, 1, 1);

    $pdf->Ln(6);

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(16, 8, 'Factura', 1, 0, 'C');
    $pdf->Cell(25, 8, 'Fecha', 1, 0, 'C');
    $pdf->Cell(42, 8, 'Cliente', 1, 0, 'C');
    $pdf->Cell(38, 8, 'Vendedor', 1, 0, 'C');
    $pdf->Cell(22, 8, 'Tipo', 1, 0, 'C');
    $pdf->Cell(22, 8, 'Estado', 1, 0, 'C');
    $pdf->Cell(25, 8, 'Total', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 7);

    if (!empty($reporte_ventas)) {
        foreach ($reporte_ventas as $v) {
            $pdf->Cell(16, 8, $v['id_factura'], 1, 0, 'C');
            $pdf->Cell(25, 8, date("d/m/Y", strtotime($v['fecha'])), 1, 0, 'C');
            $pdf->Cell(42, 8, utf8_decode(substr($v['cliente'], 0, 22)), 1, 0);
            $pdf->Cell(38, 8, utf8_decode(substr($v['vendedor'], 0, 20)), 1, 0);
            $pdf->Cell(22, 8, utf8_decode($v['tipo_venta']), 1, 0, 'C');
            $pdf->Cell(22, 8, utf8_decode($v['estado_factura']), 1, 0, 'C');
            $pdf->Cell(25, 8, '$' . number_format($v['total'], 0, ',', '.'), 1, 1, 'R');
        }
    } else {
        $pdf->Cell(190, 10, utf8_decode('No hay registros.'), 1, 1, 'C');
    }
}


// ===============================
// REPORTE FINANCIERO
// ===============================
if ($tipo_reporte == 'financiero') {

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, utf8_decode('Resumen Financiero'), 0, 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, 7, 'Ingresos', 1, 0);
    $pdf->Cell(40, 7, '$' . number_format($reporte_financiero['ingresos'], 0, ',', '.'), 1, 1);

    $pdf->Cell(50, 7, 'Egresos', 1, 0);
    $pdf->Cell(40, 7, '$' . number_format($reporte_financiero['egresos'], 0, ',', '.'), 1, 1);

    $pdf->Cell(50, 7, 'Balance', 1, 0);
    $pdf->Cell(40, 7, '$' . number_format($reporte_financiero['balance'], 0, ',', '.'), 1, 1);

    $pdf->Ln(6);

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 8, 'Fecha', 1, 0, 'C');
    $pdf->Cell(30, 8, 'Tipo', 1, 0, 'C');
    $pdf->Cell(90, 8, 'Descripcion', 1, 0, 'C');
    $pdf->Cell(35, 8, 'Monto', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 8);

    if (!empty($reporte_movimientos)) {
        foreach ($reporte_movimientos as $m) {
            $pdf->Cell(30, 8, date("d/m/Y", strtotime($m['fecha'])), 1, 0, 'C');
            $pdf->Cell(30, 8, utf8_decode($m['tipo']), 1, 0, 'C');
            $pdf->Cell(90, 8, utf8_decode(substr($m['descripcion'], 0, 45)), 1, 0);
            $pdf->Cell(35, 8, '$' . number_format($m['monto'], 0, ',', '.'), 1, 1, 'R');
        }
    } else {
        $pdf->Cell(185, 10, utf8_decode('No hay registros.'), 1, 1, 'C');
    }
}


// ===============================
// REPORTE GENERAL
// ===============================
if ($tipo_reporte == 'general') {

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, utf8_decode('Resumen General del Sistema'), 0, 1);

    $pdf->SetFont('Arial', '', 10);

    $pdf->Cell(70, 8, 'Total usuarios', 1, 0);
    $pdf->Cell(35, 8, $reporte_general['usuarios'], 1, 1);

    $pdf->Cell(70, 8, 'Total productos', 1, 0);
    $pdf->Cell(35, 8, $reporte_general['productos'], 1, 1);

    $pdf->Cell(70, 8, 'Total ventas', 1, 0);
    $pdf->Cell(35, 8, $reporte_general['ventas'], 1, 1);

    $pdf->Cell(70, 8, 'Movimientos contables', 1, 0);
    $pdf->Cell(35, 8, $reporte_general['movimientos'], 1, 1);
}

$pdf->Output('D', 'reporte_' . $tipo_reporte . '.pdf');
exit;
?>