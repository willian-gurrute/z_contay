<?php
require_once __DIR__ . "/../conexion.php";

// obtener usuario logueado
$id_usuario = $_SESSION['id_usuario'] ?? 0;

// variables por defecto
$despachos_asignados = 0;
$pendientes_por_entregar = 0;
$entregados_hoy = 0;
$incidencias_reportadas = 0;

$ultima_actualizacion_asignados = "Sin registros";
$ultima_actualizacion_pendientes = "Sin registros";
$ultima_actualizacion_entregados = "Sin registros";
$ultima_actualizacion_incidencias = "Sin registros";

// buscar el transportador relacionado con el usuario logueado
$id_transportador = 0;

$sqlTransportador = "SELECT id_transportador
                     FROM transportador
                     WHERE id_usuario = ? AND estado = 'A'
                     LIMIT 1";

$stmtTransportador = $conn->prepare($sqlTransportador);

if ($stmtTransportador) {
    $stmtTransportador->bind_param("i", $id_usuario);
    $stmtTransportador->execute();
    $resultadoTransportador = $stmtTransportador->get_result();

    if ($filaTransportador = $resultadoTransportador->fetch_assoc()) {
        $id_transportador = (int)$filaTransportador['id_transportador'];
    }

    $stmtTransportador->close();
}

// si existe transportador, consultar indicadores
if ($id_transportador > 0) {


    // 1. Despachos asignados
   $sqlAsignados = "SELECT COUNT(*) AS total,
                        MAX(fecha_creacion) AS ultima_fecha
                 FROM despacho
                 WHERE id_transportador = ?
                 AND estado = 'asignado'";

    $stmtAsignados = $conn->prepare($sqlAsignados);

    if ($stmtAsignados) {
        $stmtAsignados->bind_param("i", $id_transportador);
        $stmtAsignados->execute();
        $resultadoAsignados = $stmtAsignados->get_result();

        if ($fila = $resultadoAsignados->fetch_assoc()) {
            $despachos_asignados = (int)$fila['total'];

            if (!empty($fila['ultima_fecha'])) {
                $ultima_actualizacion_asignados = $fila['ultima_fecha'];
            }
        }

        $stmtAsignados->close();
    }

    // 2. Pendientes por entregar
    $sqlPendientes = "SELECT COUNT(*) AS total,
                             MAX(fecha_creacion) AS ultima_fecha
                      FROM despacho
                      WHERE id_transportador = ?
                      AND estado IN ('asignado', 'pendiente')";

    $stmtPendientes = $conn->prepare($sqlPendientes);

    if ($stmtPendientes) {
        $stmtPendientes->bind_param("i", $id_transportador);
        $stmtPendientes->execute();
        $resultadoPendientes = $stmtPendientes->get_result();

        if ($fila = $resultadoPendientes->fetch_assoc()) {
            $pendientes_por_entregar = (int)$fila['total'];

            if (!empty($fila['ultima_fecha'])) {
                $ultima_actualizacion_pendientes = $fila['ultima_fecha'];
            }
        }

        $stmtPendientes->close();
    }

    // 3. Entregados hoy
    $sqlEntregadosHoy = "SELECT COUNT(*) AS total,
                                MAX(fecha_entrega) AS ultima_fecha
                         FROM despacho
                         WHERE id_transportador = ?
                         AND estado = 'entregado'
                         AND DATE(fecha_entrega) = CURDATE()";

    $stmtEntregadosHoy = $conn->prepare($sqlEntregadosHoy);

    if ($stmtEntregadosHoy) {
        $stmtEntregadosHoy->bind_param("i", $id_transportador);
        $stmtEntregadosHoy->execute();
        $resultadoEntregadosHoy = $stmtEntregadosHoy->get_result();

        if ($fila = $resultadoEntregadosHoy->fetch_assoc()) {
            $entregados_hoy = (int)$fila['total'];

            if (!empty($fila['ultima_fecha'])) {
                $ultima_actualizacion_entregados = $fila['ultima_fecha'];
            }
        }

        $stmtEntregadosHoy->close();
    }

    // 4. Incidencias reportadas
     $sqlIncidencias = "SELECT COUNT(*) AS total,
                          MAX(fecha_creacion) AS ultima_fecha
                   FROM despacho
                   WHERE id_transportador = ?
                   AND estado = 'incidencia'";
    $stmtIncidencias = $conn->prepare($sqlIncidencias);

    if ($stmtIncidencias) {
        $stmtIncidencias->bind_param("i", $id_transportador);
        $stmtIncidencias->execute();
        $resultadoIncidencias = $stmtIncidencias->get_result();

        if ($fila = $resultadoIncidencias->fetch_assoc()) {
            $incidencias_reportadas = (int)$fila['total'];

            if (!empty($fila['ultima_fecha'])) {
                $ultima_actualizacion_incidencias = $fila['ultima_fecha'];
            }
        }

        $stmtIncidencias->close();
    }
}
?>