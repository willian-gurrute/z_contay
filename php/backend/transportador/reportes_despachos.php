<?php
require_once __DIR__ . "/../conexion.php";

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_transportador = 0;

$estado_filtro = $_GET['estado'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';

$resumen_total = 0;
$resumen_entregados = 0;
$resumen_incidencias = 0;
$resumen_asignados = 0;

$reportes_despachos = [];

/*
|--------------------------------------------------------------------------
| Buscar transportador del usuario logueado
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

/*
|--------------------------------------------------------------------------
| Resumen general
|--------------------------------------------------------------------------
*/
if ($id_transportador > 0) {
    // Total
    $sqlTotal = "SELECT COUNT(*) AS total
                 FROM despacho
                 WHERE id_transportador = ?";

    $stmtTotal = $conn->prepare($sqlTotal);
    if ($stmtTotal) {
        $stmtTotal->bind_param("i", $id_transportador);
        $stmtTotal->execute();
        $resTotal = $stmtTotal->get_result();
        if ($fila = $resTotal->fetch_assoc()) {
            $resumen_total = (int)$fila['total'];
        }
        $stmtTotal->close();
    }

    // Entregados
    $sqlEntregados = "SELECT COUNT(*) AS total
                      FROM despacho
                      WHERE id_transportador = ?
                      AND estado = 'entregado'";

    $stmtEntregados = $conn->prepare($sqlEntregados);
    if ($stmtEntregados) {
        $stmtEntregados->bind_param("i", $id_transportador);
        $stmtEntregados->execute();
        $resEntregados = $stmtEntregados->get_result();
        if ($fila = $resEntregados->fetch_assoc()) {
            $resumen_entregados = (int)$fila['total'];
        }
        $stmtEntregados->close();
    }

    // Incidencias
    $sqlIncidencias = "SELECT COUNT(*) AS total
                       FROM despacho
                       WHERE id_transportador = ?
                       AND estado = 'incidencia'";

    $stmtIncidencias = $conn->prepare($sqlIncidencias);
    if ($stmtIncidencias) {
        $stmtIncidencias->bind_param("i", $id_transportador);
        $stmtIncidencias->execute();
        $resIncidencias = $stmtIncidencias->get_result();
        if ($fila = $resIncidencias->fetch_assoc()) {
            $resumen_incidencias = (int)$fila['total'];
        }
        $stmtIncidencias->close();
    }

    // Pendientes
    // Asignados
    $sqlAsignados = "SELECT COUNT(*) AS total
                 FROM despacho
                 WHERE id_transportador = ?
                 AND estado = 'asignado'";


    $stmtAsignados = $conn->prepare($sqlAsignados);
    if ($stmtAsignados) {
        $stmtAsignados->bind_param("i", $id_transportador);
        $stmtAsignados->execute();
        $resAsignados = $stmtAsignados->get_result();
        if ($fila = $resAsignados->fetch_assoc()) {
            $resumen_asignados = (int)$fila['total'];
        }
        $stmtAsignados->close();
    }
}

/*
|--------------------------------------------------------------------------
| Construcción de consulta con filtros
|--------------------------------------------------------------------------
*/
if ($id_transportador > 0) {
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
}
?>