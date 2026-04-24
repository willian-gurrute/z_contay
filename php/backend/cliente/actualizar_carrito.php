<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

$accion = $_POST['accion'] ?? '';
$cantidades = $_POST['cantidades'] ?? [];
$eliminar = $_POST['eliminar'] ?? [];

if (!isset($_SESSION['carrito_cliente'])) {
    $_SESSION['carrito_cliente'] = [];
}

/* Eliminar productos marcados */
foreach ($eliminar as $idEliminar) {
    $idEliminar = (int)$idEliminar;

    if (isset($_SESSION['carrito_cliente'][$idEliminar])) {
        unset($_SESSION['carrito_cliente'][$idEliminar]);
    }
}

/* Actualizar cantidades */
foreach ($cantidades as $id_producto => $cantidad) {
    $id_producto = (int)$id_producto;
    $cantidad = (int)$cantidad;

    if ($cantidad <= 0) {
        unset($_SESSION['carrito_cliente'][$id_producto]);
        continue;
    }

    if (isset($_SESSION['carrito_cliente'][$id_producto])) {
        $_SESSION['carrito_cliente'][$id_producto]['cantidad'] = $cantidad;
    }
}

/* Recalcular totales */
$total_unidades = 0;
$total_pedido = 0;

foreach ($_SESSION['carrito_cliente'] as $item) {
    $total_unidades += (int)$item['cantidad'];
    $total_pedido += (float)$item['precio'] * (int)$item['cantidad'];
}

/* Si solo actualiza */
if ($accion === 'actualizar') {
    $_SESSION['mensaje_pedido'] = "Pedido actualizado correctamente.";
    $_SESSION['tipo_pedido'] = "success";

    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

/* Si confirma */
if ($accion === 'confirmar') {

    if (empty($_SESSION['carrito_cliente'])) {
        $_SESSION['mensaje_pedido'] = "No tienes productos para confirmar.";
        $_SESSION['tipo_pedido'] = "error";

        header("Location: ../../cliente/realizar_pedido.php");
        exit;
    }

    if ($total_unidades < 60) {
        $_SESSION['mensaje_pedido'] = "El pedido mínimo es de 60 unidades.";
        $_SESSION['tipo_pedido'] = "error";

        header("Location: ../../cliente/realizar_pedido.php");
        exit;
    }

    $direccion = trim($_POST['direccion'] ?? '');
    $barrio = trim($_POST['barrio'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $referencia = trim($_POST['referencia'] ?? '');
    $tipo_transferencia = trim($_POST['tipo_transferencia'] ?? '');
    $metodo_pago = $_POST['metodo_pago'] ?? 'tarjeta';

    if ($direccion === '' || $barrio === '' || $telefono === '' || $tipo_transferencia === '') {
        $_SESSION['mensaje_pedido'] = "Debes completar los datos de entrega y el medio de transferencia.";
        $_SESSION['tipo_pedido'] = "error";

        header("Location: ../../cliente/realizar_pedido.php");
        exit;
    }

    $_SESSION['datos_entrega_cliente'] = [
        'direccion' => $direccion,
        'barrio' => $barrio,
        'telefono' => $telefono,
        'referencia' => $referencia,
        'tipo_transferencia' => $tipo_transferencia,
        'metodo_pago' => $metodo_pago,
        'total_unidades' => $total_unidades,
        'total_pedido' => $total_pedido
    ];

    $_SESSION['mensaje_pedido'] = "Datos validados correctamente. Ahora falta guardar el pedido en la base de datos.";
    $_SESSION['tipo_pedido'] = "success";

    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

header("Location: ../../cliente/realizar_pedido.php");
exit;
?>