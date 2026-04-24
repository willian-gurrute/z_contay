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

/*
|--------------------------------------------------------------------------
| Eliminar productos marcados
|--------------------------------------------------------------------------
*/
foreach ($eliminar as $idEliminar) {
    $idEliminar = (int)$idEliminar;

    if (isset($_SESSION['carrito_cliente'][$idEliminar])) {
        unset($_SESSION['carrito_cliente'][$idEliminar]);
    }
}

/*
|--------------------------------------------------------------------------
| Actualizar cantidades
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Recalcular total de unidades
|--------------------------------------------------------------------------
*/
$total_unidades = 0;
$total_pedido = 0;

foreach ($_SESSION['carrito_cliente'] as $item) {
    $total_unidades += (int)$item['cantidad'];
    $total_pedido += ((float)$item['precio'] * (int)$item['cantidad']);
}

/*
|--------------------------------------------------------------------------
| Si solo actualiza
|--------------------------------------------------------------------------
*/
if ($accion === 'actualizar') {
    $_SESSION['mensaje_pedido'] = "Pedido actualizado correctamente.";
    $_SESSION['tipo_pedido'] = "success";

    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Si confirma, validar mínimo
|--------------------------------------------------------------------------
*/
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

    /*
    |--------------------------------------------------------------------------
    | Por ahora NO guardamos en base de datos todavía.
    | El siguiente paso será crear guardar_pedido.php.
    |--------------------------------------------------------------------------
    */
    $_SESSION['mensaje_pedido'] = "El pedido está listo para ser guardado. Falta conectar el guardado en base de datos.";
    $_SESSION['tipo_pedido'] = "success";

    header("Location: ../../cliente/realizar_pedido.php");
    exit;
}

header("Location: ../../cliente/realizar_pedido.php");
exit;
?>