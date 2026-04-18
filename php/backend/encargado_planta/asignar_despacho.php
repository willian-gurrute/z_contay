<?php

require_once __DIR__ . "/../conexion.php";

$factura = null;
$detalle_productos = [];
$transportadores = [];

$id_factura = $_GET['id_factura'] ?? 0;
$id_despacho = $_GET['id_despacho'] ?? 0;

$modo_reasignacion = false;
$despacho_actual = null;

if ($id_despacho && is_numeric($id_despacho)) {
    $modo_reasignacion = true;

    $sqlDespacho = "SELECT 
                        d.id_despacho,
                        d.id_factura,
                        d.id_transportador,
                        d.zona_entrega
                    FROM despacho d
                    WHERE d.id_despacho = ?
                    LIMIT 1";

    $stmtDespacho = $conn->prepare($sqlDespacho);
    $stmtDespacho->bind_param("i", $id_despacho);
    $stmtDespacho->execute();
    $resDespacho = $stmtDespacho->get_result();

    if ($resDespacho && $resDespacho->num_rows > 0) {
        $despacho_actual = $resDespacho->fetch_assoc();
        $id_factura = $despacho_actual['id_factura'];
    } else {
        die("Despacho no válido.");
    }

    $stmtDespacho->close();
}

if (!$id_factura || !is_numeric($id_factura)) {
    die("Factura no válida.");
}

$sqlFactura = "SELECT 
                    f.id_factura,
                    f.fecha,
                    f.tipo_venta,
                    f.total,
                    c.nombre_completo AS cliente,
                    COALESCE(cd.direccion, 'Sin dirección registrada') AS direccion
               FROM factura f
               INNER JOIN cliente c 
                    ON f.id_cliente = c.id_cliente
               LEFT JOIN cliente_direccion cd 
                    ON cd.id_cliente = c.id_cliente
                    AND cd.es_principal = 1
                    AND cd.estado = 'A'
               WHERE f.id_factura = ?
               AND f.tipo_venta = 'pedido'
               AND f.estado_factura = 'pagada'
               LIMIT 1";

$stmtFactura = $conn->prepare($sqlFactura);
$stmtFactura->bind_param("i", $id_factura);
$stmtFactura->execute();
$resFactura = $stmtFactura->get_result();

if ($resFactura && $resFactura->num_rows > 0) {
    $factura = $resFactura->fetch_assoc();
} else {
    die("No se encontró una factura válida.");
}

$stmtFactura->close();

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
$stmtDetalle->bind_param("i", $id_factura);
$stmtDetalle->execute();
$resDetalle = $stmtDetalle->get_result();

while ($fila = $resDetalle->fetch_assoc()) {
    $detalle_productos[] = $fila;
}

$stmtDetalle->close();

$sqlTransportadores = "SELECT 
                            t.id_transportador,
                            u.nombre_completo
                       FROM transportador t
                       INNER JOIN usuario u 
                            ON t.id_usuario = u.id_usuario
                       WHERE t.estado = 'A'
                       AND u.estado = 'A'
                       ORDER BY u.nombre_completo ASC";

$resTransportadores = $conn->query($sqlTransportadores);

if ($resTransportadores) {
    while ($fila = $resTransportadores->fetch_assoc()) {
        $transportadores[] = $fila;
    }
}
?>