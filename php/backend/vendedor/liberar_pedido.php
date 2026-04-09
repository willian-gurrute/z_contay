<?php
session_start();

require_once __DIR__ . "/../conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_liberar");
    exit();
}

if (!isset($_POST['id_pedido']) || empty($_POST['id_pedido'])) {
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_liberar");
    exit();
}

$idUsuario = intval($_SESSION['id_usuario']);
$idPedido = intval($_POST['id_pedido']);

// Verificar pedido
$sqlVerificar = "SELECT id_pedido, id_usuario, estado
                 FROM pedido
                 WHERE id_pedido = ?
                 LIMIT 1";

$stmtVerificar = $conn->prepare($sqlVerificar);

if (!$stmtVerificar) {
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_liberar");
    exit();
}

$stmtVerificar->bind_param("i", $idPedido);
$stmtVerificar->execute();
$resVerificar = $stmtVerificar->get_result();

if ($resVerificar->num_rows === 0) {
    $stmtVerificar->close();
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_liberar");
    exit();
}

$pedido = $resVerificar->fetch_assoc();
$stmtVerificar->close();

// Solo permitir liberar si el pedido pertenece al vendedor y sigue pendiente
if ((int)$pedido['id_usuario'] !== (int)$idUsuario || $pedido['estado'] !== 'pendiente') {
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_liberar");
    exit();
}

// Liberar pedido
$sqlLiberar = "UPDATE pedido
               SET id_usuario = NULL,
                   estado = 'pendiente',
                   estado_pago = 'pagado'
               WHERE id_pedido = ?";

$stmtLiberar = $conn->prepare($sqlLiberar);

if (!$stmtLiberar) {
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_liberar");
    exit();
}

$stmtLiberar->bind_param("i", $idPedido);

if ($stmtLiberar->execute()) {
    $stmtLiberar->close();
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=liberado");
    exit();
} else {
    $stmtLiberar->close();
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_liberar");
    exit();
}
?>