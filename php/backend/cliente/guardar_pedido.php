<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$carrito = $_SESSION['carrito_cliente'] ?? [];

if ($id_usuario <= 0 || empty($carrito)) {
    $_SESSION['mensaje_pedido'] = "No hay productos para guardar el pedido.";
    $_SESSION['tipo_pedido'] = "error";
    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

/* Buscar cliente relacionado con el usuario */
/* Buscar datos del usuario logueado */
$sqlUsuario = "SELECT tipo_documento, numero_documento, nombre_completo
               FROM usuario
               WHERE id_usuario = ?
               LIMIT 1";

$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $id_usuario);
$stmtUsuario->execute();
$resUsuario = $stmtUsuario->get_result();

if (!$resUsuario || $resUsuario->num_rows === 0) {
    $_SESSION['mensaje_pedido'] = "No se encontró la información del usuario.";
    $_SESSION['tipo_pedido'] = "error";
    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

$usuario = $resUsuario->fetch_assoc();
$stmtUsuario->close();

$tipoDocumento = $usuario['tipo_documento'] ?? 'CC';
$numeroDocumento = $usuario['numero_documento'];
$nombreCliente = $usuario['nombre_completo'];

/* Buscar cliente por documento */
$id_cliente = 0;

$sqlBuscarCliente = "SELECT id_cliente
                     FROM cliente
                     WHERE numero_documento = ?
                     LIMIT 1";

$stmtCliente = $conn->prepare($sqlBuscarCliente);
$stmtCliente->bind_param("s", $numeroDocumento);
$stmtCliente->execute();
$resCliente = $stmtCliente->get_result();

if ($filaCliente = $resCliente->fetch_assoc()) {
    $id_cliente = (int)$filaCliente['id_cliente'];
}

$stmtCliente->close();

/* Si no existe cliente, lo creamos */
if ($id_cliente <= 0) {
    $sqlCrearCliente = "INSERT INTO cliente
                        (tipo_documento, numero_documento, nombre_completo, telefono, estado)
                        VALUES (?, ?, ?, ?, 'A')";

    $stmtCrearCliente = $conn->prepare($sqlCrearCliente);
    $stmtCrearCliente->bind_param("ssss", $tipoDocumento, $numeroDocumento, $nombreCliente, $telefono);
    $stmtCrearCliente->execute();

    $id_cliente = $conn->insert_id;
    $stmtCrearCliente->close();
} else {
    $sqlActualizarCliente = "UPDATE cliente
                             SET nombre_completo = ?,
                                 telefono = ?,
                                 estado = 'A'
                             WHERE id_cliente = ?";

    $stmtActualizarCliente = $conn->prepare($sqlActualizarCliente);
    $stmtActualizarCliente->bind_param("ssi", $nombreCliente, $telefono, $id_cliente);
    $stmtActualizarCliente->execute();
    $stmtActualizarCliente->close();
}

/* Datos de entrega */
$direccion = trim($_POST['direccion'] ?? '');
$barrio = trim($_POST['barrio'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$referencia = trim($_POST['referencia'] ?? '');
$tipo_transferencia = trim($_POST['tipo_transferencia'] ?? '');

if ($direccion === '' || $barrio === '' || $telefono === '' || $tipo_transferencia === '') {
    $_SESSION['mensaje_pedido'] = "Completa los datos de entrega y transferencia.";
    $_SESSION['tipo_pedido'] = "error";
    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

/* Recalcular totales */
$total_unidades = 0;
$subtotal = 0;

foreach ($carrito as $item) {
    $cantidad = (int)$item['cantidad'];
    $precio = (float)$item['precio'];

    $total_unidades += $cantidad;
    $subtotal += $precio * $cantidad;
}

if ($total_unidades < 60) {
    $_SESSION['mensaje_pedido'] = "El pedido mínimo es de 60 unidades.";
    $_SESSION['tipo_pedido'] = "error";
    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

$iva = 0;
$total = $subtotal;

$observaciones = "Pedido web. Dirección: $direccion. Barrio: $barrio. Teléfono: $telefono. Referencia: $referencia. Pago por transferencia: $tipo_transferencia.";

$conn->begin_transaction();

try {
    /* Guardar dirección principal o actualizarla */
    $sqlDireccion = "INSERT INTO cliente_direccion 
                    (id_cliente, direccion, barrio, ciudad, referencia, es_principal, estado)
                    VALUES (?, ?, ?, 'Popayán', ?, 1, 'A')";

    $stmtDireccion = $conn->prepare($sqlDireccion);
    $stmtDireccion->bind_param("isss", $id_cliente, $direccion, $barrio, $referencia);
    $stmtDireccion->execute();
    $stmtDireccion->close();

    /* Crear pedido web */
    $sqlPedido = "INSERT INTO pedido 
                 (id_cliente, id_usuario, estado, subtotal, iva, total, observaciones, estado_pago)
                 VALUES (?, NULL, 'pendiente', ?, ?, ?, ?, 'pagado')";

    $stmtPedido = $conn->prepare($sqlPedido);
    $stmtPedido->bind_param("iddds", $id_cliente, $subtotal, $iva, $total, $observaciones);
    $stmtPedido->execute();

    $id_pedido = $stmtPedido->insert_id;
    $stmtPedido->close();

    /* Guardar detalle del pedido */
    $sqlDetalle = "INSERT INTO detalle_pedido
                  (id_pedido, id_producto, cantidad, precio_unitario, subtotal)
                  VALUES (?, ?, ?, ?, ?)";

    $stmtDetalle = $conn->prepare($sqlDetalle);

    foreach ($carrito as $item) {
        $id_producto = (int)$item['id_producto'];
        $cantidad = (int)$item['cantidad'];
        $precio = (float)$item['precio'];
        $subtotal_item = $precio * $cantidad;

        $stmtDetalle->bind_param("iiidd", $id_pedido, $id_producto, $cantidad, $precio, $subtotal_item);
        $stmtDetalle->execute();
    }

    $stmtDetalle->close();

    $conn->commit();

    unset($_SESSION['carrito_cliente']);

    $_SESSION['mensaje_pedido'] = "Pedido realizado correctamente. El vendedor podrá revisarlo.";
    $_SESSION['tipo_pedido'] = "success";

    header("Location: ../../cliente/historial_pedidos.php");
    exit;

} catch (Exception $e) {
    $conn->rollback();

    $_SESSION['mensaje_pedido'] = "Error al guardar el pedido: " . $e->getMessage();
    $_SESSION['tipo_pedido'] = "error";

    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}
?>