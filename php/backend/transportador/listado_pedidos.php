<?php
require_once __DIR__ . "/../conexion.php";

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_transportador = 0;
$listado_pedidos = [];

/*
|--------------------------------------------------------------------------
| Buscar el transportador del usuario logueado
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
| Traer historial de pedidos/despachos del transportador
|--------------------------------------------------------------------------
*/
if ($id_transportador > 0) {
    $sqlPedidos = "SELECT
                        d.id_despacho,
                        d.fecha_creacion,
                        d.estado,
                        f.total,
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
                   WHERE d.id_transportador = ?
                   ORDER BY d.fecha_creacion DESC";

    $stmtPedidos = $conn->prepare($sqlPedidos);

    if ($stmtPedidos) {
        $stmtPedidos->bind_param("i", $id_transportador);
        $stmtPedidos->execute();
        $resPedidos = $stmtPedidos->get_result();

        while ($filaPedido = $resPedidos->fetch_assoc()) {
            $listado_pedidos[] = $filaPedido;
        }

        $stmtPedidos->close();
    }
}
?>