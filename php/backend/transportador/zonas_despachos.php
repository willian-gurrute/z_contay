<?php

require_once __DIR__ . "/../conexion.php";

/*
|--------------------------------------------------------------------------
| Obtener usuario y transportador logueado
|--------------------------------------------------------------------------
*/
$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_transportador = 0;

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
| Mensajes
|--------------------------------------------------------------------------
*/
$mensaje = "";
$tipoMensaje = "";

$msg = $_GET['msg'] ?? '';

if ($msg === 'entregado') {
    $mensaje = "Entrega confirmada correctamente.";
    $tipoMensaje = "success";
} elseif ($msg === 'incidencia') {
    $mensaje = "Incidencia reportada correctamente.";
    $tipoMensaje = "error";
} elseif ($msg === 'error') {
    $mensaje = "No fue posible procesar la acción.";
    $tipoMensaje = "error";
}


/*
|--------------------------------------------------------------------------
| Traer despachos del transportador
|--------------------------------------------------------------------------
*/
$despachos = [];

$sqlDespachos = "SELECT
                    d.id_despacho,
                    d.estado,
                    d.zona_entrega,
                    COALESCE(c.nombre_completo, 'Sin cliente') AS cliente,
                    COALESCE(cd.direccion, 'Sin dirección registrada') AS direccion,
                    GROUP_CONCAT(CONCAT(p.nombre_producto, ' x ', df.cantidad) SEPARATOR ', ') AS productos
                 FROM despacho d
                 INNER JOIN factura f
                    ON d.id_factura = f.id_factura
                 INNER JOIN cliente c
                    ON f.id_cliente = c.id_cliente
                 LEFT JOIN cliente_direccion cd
                    ON cd.id_cliente = c.id_cliente
                    AND cd.es_principal = 1
                    AND cd.estado = 'A'
                 INNER JOIN detalle_factura df
                    ON f.id_factura = df.id_factura
                 INNER JOIN producto p
                    ON df.id_producto = p.id_producto
                 WHERE d.id_transportador = ?
                 GROUP BY d.id_despacho, d.estado, d.zona_entrega, c.nombre_completo, cd.direccion
                 ORDER BY d.fecha_creacion DESC";

$stmtDespachos = $conn->prepare($sqlDespachos);

if ($stmtDespachos) {
    $stmtDespachos->bind_param("i", $id_transportador);
    $stmtDespachos->execute();
    $resDespachos = $stmtDespachos->get_result();

    while ($filaDespacho = $resDespachos->fetch_assoc()) {
        $despachos[] = $filaDespacho;
    }

    $stmtDespachos->close();
}
?>