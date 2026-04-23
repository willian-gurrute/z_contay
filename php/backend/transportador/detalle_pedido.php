<?php
require_once __DIR__ . "/../conexion.php";

$despacho = null;
$detalle_productos = [];

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_despacho = $_GET['id'] ?? 0;

if (!$id_despacho || !is_numeric($id_despacho)) {
    $id_despacho = 0;
}

/*
|--------------------------------------------------------------------------
| Buscar id_transportador del usuario logueado
|--------------------------------------------------------------------------
*/
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
| Traer información general del despacho
|--------------------------------------------------------------------------
*/
if ($id_despacho > 0 && $id_transportador > 0) {
    $sqlDespacho = "SELECT
                        d.id_despacho,
                        d.id_factura,
                        d.fecha_creacion,
                        d.estado,
                        d.zona_entrega,
                        f.total AS total_factura,
                        c.nombre_completo AS cliente,
                        COALESCE(cd.direccion, 'Sin dirección registrada') AS direccion
                    FROM despacho d
                    INNER JOIN factura f
                        ON d.id_factura = f.id_factura
                    INNER JOIN cliente c
                        ON f.id_cliente = c.id_cliente
                    LEFT JOIN cliente_direccion cd
                        ON cd.id_cliente = c.id_cliente
                        AND cd.es_principal = 1
                        AND cd.estado = 'A'
                    WHERE d.id_despacho = ?
                    AND d.id_transportador = ?
                    LIMIT 1";

    $stmtDespacho = $conn->prepare($sqlDespacho);

    if ($stmtDespacho) {
        $stmtDespacho->bind_param("ii", $id_despacho, $id_transportador);
        $stmtDespacho->execute();
        $resDespacho = $stmtDespacho->get_result();

        if ($resDespacho && $resDespacho->num_rows > 0) {
            $despacho = $resDespacho->fetch_assoc();
        }

        $stmtDespacho->close();
    }
}

/*
|--------------------------------------------------------------------------
| Traer detalle de productos de la factura
|--------------------------------------------------------------------------
*/
if ($despacho && !empty($despacho['id_factura'])) {
    $sqlDetalle = "SELECT
                       p.nombre_producto,
                       df.cantidad,
                       df.precio_unitario,
                       df.subtotal
                   FROM detalle_factura df
                   INNER JOIN producto p
                       ON df.id_producto = p.id_producto
                   WHERE df.id_factura = ?";

    $stmtDetalle = $conn->prepare($sqlDetalle);

    if ($stmtDetalle) {
        $stmtDetalle->bind_param("i", $despacho['id_factura']);
        $stmtDetalle->execute();
        $resDetalle = $stmtDetalle->get_result();

        while ($fila = $resDetalle->fetch_assoc()) {
            $detalle_productos[] = $fila;
        }

        $stmtDetalle->close();
    }
}
?>