<?php

require_once __DIR__ . "/../conexion.php";

$incidencia = null;
$id_despacho = $_GET['id'] ?? 0;

if (!$id_despacho || !is_numeric($id_despacho)) {
    die("Despacho no valido");
}

$sqlIncidencia = "SELECT
                    d.id_despacho,
                    d.estado AS estado_despacho,
                    d.zona_entrega,
                    c.nombre_completo AS cliente,
                    COALESCE(cd.direccion, 'Sin dirección') AS direccion,
                    COALESCE(u_trans.nombre_completo, 'Sin asignar') AS transportador,
                    COALESCE(ti.nombre, 'No registrada') AS tipo_incidencia,
                    i.observaciones,
                    i.fecha_reporte
                 FROM despacho d
                 INNER JOIN factura f 
                    ON d.id_factura = f.id_factura
                 INNER JOIN cliente c 
                    ON f.id_cliente = c.id_cliente
                 LEFT JOIN cliente_direccion cd 
                    ON cd.id_cliente = c.id_cliente 
                    AND cd.es_principal = 1
                 LEFT JOIN transportador t 
                    ON d.id_transportador = t.id_transportador
                 LEFT JOIN usuario u_trans 
                    ON t.id_usuario = u_trans.id_usuario
                 LEFT JOIN incidencia i 
                    ON d.id_despacho = i.id_despacho
                 LEFT JOIN tipo_incidencia ti 
                    ON i.id_tipoIncidencia = ti.id_tipoIncidencia
                 WHERE d.id_despacho = ?
                 LIMIT 1";

$stmtIncidencia = $conn->prepare($sqlIncidencia);

if ($stmtIncidencia) {
    $stmtIncidencia->bind_param("i", $id_despacho);
    $stmtIncidencia->execute();
    $resIncidencia = $stmtIncidencia->get_result();

    if ($resIncidencia && $resIncidencia->num_rows > 0) {
        $incidencia = $resIncidencia->fetch_assoc();
    }else{
        die("No se encontro la incidencia del despacho");
    }
    $stmtIncidencia->close();
}
?>