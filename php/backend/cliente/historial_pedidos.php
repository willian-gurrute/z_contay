<?php
require_once __DIR__ . "/../conexion.php";

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$pedidos = [];

// Buscar documento del usuario logueado
$sqlUsuario = "SELECT numero_documento
               FROM usuario
               WHERE id_usuario = ?
               LIMIT 1";

$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $id_usuario);
$stmtUsuario->execute();
$resUsuario = $stmtUsuario->get_result();

$numero_documento = "";

if ($filaUsuario = $resUsuario->fetch_assoc()) {
    $numero_documento = $filaUsuario['numero_documento'];
}

$stmtUsuario->close();

// Buscar cliente por documento
$id_cliente = 0;

if ($numero_documento !== "") {
    $sqlCliente = "SELECT id_cliente
                   FROM cliente
                   WHERE numero_documento = ?
                   LIMIT 1";

    $stmtCliente = $conn->prepare($sqlCliente);
    $stmtCliente->bind_param("s", $numero_documento);
    $stmtCliente->execute();
    $resCliente = $stmtCliente->get_result();

    if ($filaCliente = $resCliente->fetch_assoc()) {
        $id_cliente = (int)$filaCliente['id_cliente'];
    }

    $stmtCliente->close();
}

// Traer pedidos con factura y despacho si existen
if ($id_cliente > 0) {
    $sqlPedidos = "SELECT 
                        p.id_pedido,
                        p.fecha,
                        p.estado AS estado_pedido,
                        p.estado_pago,
                        p.total,
                        f.id_factura,
                        d.id_despacho,
                        d.estado AS estado_despacho
                   FROM pedido p
                   LEFT JOIN factura f
                        ON f.id_pedido = p.id_pedido
                   LEFT JOIN despacho d
                        ON d.id_factura = f.id_factura
                   WHERE p.id_cliente = ?
                   ORDER BY p.fecha DESC";

    $stmtPedidos = $conn->prepare($sqlPedidos);
    $stmtPedidos->bind_param("i", $id_cliente);
    $stmtPedidos->execute();
    $resPedidos = $stmtPedidos->get_result();

    while ($fila = $resPedidos->fetch_assoc()) {
        $pedidos[] = $fila;
    }

    $stmtPedidos->close();
}
?>