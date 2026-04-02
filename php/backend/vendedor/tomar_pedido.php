<?php
session_start();

require_once __DIR__ . "/../conexion.php";

// Verificar que exista sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../vendedor/pedidos_clientes.php");
    exit();
}

// Verificar que llegue el id del pedido
if (!isset($_POST['id_pedido']) || empty($_POST['id_pedido'])) {
    header("Location: ../../vendedor/pedidos_clientes.php");
    exit();
}

// Obtener datos
$idUsuario = intval($_SESSION['id_usuario']);
$idPedido  = intval($_POST['id_pedido']);

// Actualizar el pedido solo si sigue disponible
$sqlTomarPedido = "UPDATE pedido
                   SET id_usuario = ?
                   WHERE id_pedido = ?
                   AND id_usuario IS NULL
                   AND estado = 'pendiente'
                   AND estado_pago = 'pagado'";

$stmtTomarPedido = $conn->prepare($sqlTomarPedido);

if ($stmtTomarPedido) {
    $stmtTomarPedido->bind_param("ii", $idUsuario, $idPedido);
    $stmtTomarPedido->execute();

    // Verificamos si sí se actualizó
    if ($stmtTomarPedido->affected_rows > 0) {
        $stmtTomarPedido->close();
        $conn->close();

        // Redirigir a la pantalla de detalle del pedido
          header("Location: ../../vendedor/detalle_pedido_vendedor.php?id=" . $idPedido . "&mensaje=tomado");
          exit();
    }

    $stmtTomarPedido->close();
}

$conn->close();

// Si no se pudo tomar, volver a la lista
header("Location: ../../vendedor/pedidos_clientes.php");
exit();
?>