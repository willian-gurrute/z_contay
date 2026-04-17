<?php
require_once __DIR__ . "/../conexion.php";

$despacho = null;
$detalle_productos = [];

$id_despacho = $_GET['id'] ?? 0;

if (!$id_despacho || !is_numeric($id_despacho)) {
    die("despacho no valido.");
}

//traer informacion general del despacho//

$sqlDespacho = "SELECT
                    d.id_despacho,
                    d.id_factura,
                    d.fecha_creacion,
                    d.estado,
                    d.zona_entrega,
                    f.fecha AS fecha_factura,
                    f.total AS total_factura,
                    f.tipo_venta,
                    c.nombre_completo AS cliente,
                    COALESCE(cd.direccion, 'sin direccion registrada') AS direccion,
                    COALESCE(u_trans.nombre_completo, 'sin asignar') AS transportador,
                    COALESCE(u_planta.nombre_completo, 'No registrado') AS gestionado_por
                FROM despacho d
                INNER JOIN factura f
                    ON d.id_factura = f.id_factura
                INNER JOIN cliente c
                    ON f.id_cliente = c.id_cliente
                LEFT JOIN cliente_direccion cd
                    ON cd.id_cliente = c.id_cliente
                    AND cd.es_principal = 1
                    AND cd.estado = 'A'
                LEFT JOIN transportador t
                    ON d.id_transportador = t.id_transportador
                LEFT JOIN usuario u_trans
                    ON t.id_usuario = u_trans.id_usuario
                LEFT JOIN usuario u_planta
                    ON d.id_usuario = u_planta.id_usuario
                WHERE d.id_despacho = ?
                LIMIT 1";

$stmtDespacho = $conn->prepare($sqlDespacho);

if ($stmtDespacho) {
    $stmtDespacho->bind_param("i", $id_despacho);
    $stmtDespacho->execute();
    $resDespacho = $stmtDespacho->get_result();

    if ($resDespacho && $resDespacho->num_rows > 0 ) {
        $despacho = $resDespacho->fetch_assoc();
    } else {
        die("No se encontro el despacho");
    }

    $stmtDespacho->close();
}

//traer detalle de productos de la factura//

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
?>
           