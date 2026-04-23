<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
require_once "../backend/conexion.php";

/*
|--------------------------------------------------------------------------
| Permiso
|--------------------------------------------------------------------------
*/
verificarPermiso("reportes_despachos");

/*
|--------------------------------------------------------------------------
| Cargar FPDF
|--------------------------------------------------------------------------
| Cambia esta ruta según donde tengas tu librería.
|--------------------------------------------------------------------------
*/
require_once "../libs/fpdf.php";


/*
|--------------------------------------------------------------------------
| Datos del usuario logueado
|--------------------------------------------------------------------------
*/
$id_usuario = $_SESSION['id_usuario'] ?? 0;
$nombre_usuario = $_SESSION['nombre'] ?? 'Transportador';

$estado_filtro = $_GET['estado'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

$id_transportador = 0;
$reportes_despachos = [];

/*
|--------------------------------------------------------------------------
| Buscar transportador
|--------------------------------------------------------------------------
*/
$sqlTransportador = "SELECT id_transportador
                     FROM transportador
                     WHERE id_usuario = ? AND estado = 'A'
                     LIMIT 1";

$stmtTransportador = $conn->prepare($sqlTransportador);

if ($stmtTransportador) {
    $stmtTransportador->bind_param("i", $id_usuario);
    $stmtTransportador->execute();
    $resTransportador = $stmtTransportador->get_result();

    if ($filaTransportador = $resTransportador->fetch_assoc()) {
        $id_transportador = (int)$filaTransportador['id_transportador'];
    }

    $stmtTransportador->close();
}

if ($id_transportador <= 0) {
    die("No se encontró el transportador asociado al usuario.");
}

/*
|--------------------------------------------------------------------------
| Consulta del reporte
|--------------------------------------------------------------------------
*/
$sql = "SELECT
            d.id_despacho,
            d.fecha_creacion,
            d.estado,
            d.zona_entrega,
            f.total,
            c.nombre_completo AS cliente
        FROM despacho d
        INNER JOIN factura f
            ON d.id_factura = f.id_factura
        INNER JOIN cliente c
            ON f.id_cliente = c.id_cliente
        WHERE d.id_transportador = ?";

$tipos = "i";
$params = [$id_transportador];

if ($estado_filtro !== '') {
    $sql .= " AND d.estado = ?";
    $tipos .= "s";
    $params[] = $estado_filtro;
}

if ($fecha_desde !== '') {
    $sql .= " AND DATE(d.fecha_creacion) >= ?";
    $tipos .= "s";
    $params[] = $fecha_desde;
}

if ($fecha_hasta !== '') {
    $sql .= " AND DATE(d.fecha_creacion) <= ?";
    $tipos .= "s";
    $params[] = $fecha_hasta;
}

$sql .= " ORDER BY d.fecha_creacion DESC";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($fila = $res->fetch_assoc()) {
        $reportes_despachos[] = $fila;
    }

    $stmt->close();
}

/*
|--------------------------------------------------------------------------
| Generar PDF
|--------------------------------------------------------------------------
*/
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

/*
|--------------------------------------------------------------------------
| Encabezado
|--------------------------------------------------------------------------
*/
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Reporte de despachos - Transportador'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, utf8_decode('Z-CONTAY - Galpón Aves del Paraíso'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 7, utf8_decode('Transportador: ' . $nombre_usuario), 0, 0);
$pdf->Cell(90, 7, utf8_decode('Estado: ' . ($estado_filtro !== '' ? ucfirst($estado_filtro) : 'Todos')), 0, 0);
$pdf->Cell(90, 7, utf8_decode('Fecha generación: ' . date('Y-m-d H:i:s')), 0, 1);

$pdf->Cell(90, 7, utf8_decode('Desde: ' . ($fecha_desde !== '' ? $fecha_desde : 'Sin filtro')), 0, 0);
$pdf->Cell(90, 7, utf8_decode('Hasta: ' . ($fecha_hasta !== '' ? $fecha_hasta : 'Sin filtro')), 0, 1);

$pdf->Ln(5);

/*
|--------------------------------------------------------------------------
| Tabla
|--------------------------------------------------------------------------
*/
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 8, 'Despacho', 1, 0, 'C');
$pdf->Cell(40, 8, 'Fecha', 1, 0, 'C');
$pdf->Cell(75, 8, utf8_decode('Cliente'), 1, 0, 'C');
$pdf->Cell(45, 8, utf8_decode('Zona'), 1, 0, 'C');
$pdf->Cell(35, 8, 'Total', 1, 0, 'C');
$pdf->Cell(40, 8, 'Estado', 1, 1, 'C');

$pdf->SetFont('Arial', '', 9);

if (empty($reportes_despachos)) {
    $pdf->Cell(260, 8, utf8_decode('No hay registros para mostrar.'), 1, 1, 'C');
} else {
    foreach ($reportes_despachos as $item) {
        $pdf->Cell(25, 8, $item['id_despacho'], 1, 0, 'C');
        $pdf->Cell(40, 8, $item['fecha_creacion'], 1, 0, 'C');
        $pdf->Cell(75, 8, utf8_decode($item['cliente']), 1, 0, 'L');
        $pdf->Cell(45, 8, utf8_decode($item['zona_entrega']), 1, 0, 'L');
        $pdf->Cell(35, 8, '$' . number_format((float)$item['total'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(40, 8, utf8_decode(ucfirst($item['estado'])), 1, 1, 'C');
    }
}

/*
|--------------------------------------------------------------------------
| Salida
|--------------------------------------------------------------------------
*/
$pdf->Output('I', 'reporte_despachos_transportador.pdf');
exit;
?>