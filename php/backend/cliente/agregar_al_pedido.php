<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../cliente/portafolio.php");
    exit;
}

$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;

if ($id_producto <= 0) {
    $_SESSION['mensaje_portafolio'] = "Producto no válido.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Validar producto y stock
|--------------------------------------------------------------------------
*/
$sqlProducto = "SELECT 
                    p.id_producto,
                    p.nombre_producto,
                    p.precio,
                    p.estado AS estado_producto,
                    COALESCE(i.cantidad, 0) AS cantidad_stock
                FROM producto p
                LEFT JOIN inventario i 
                    ON p.id_producto = i.id_producto
                WHERE p.id_producto = ?
                LIMIT 1";

$stmtProducto = $conn->prepare($sqlProducto);

if (!$stmtProducto) {
    $_SESSION['mensaje_portafolio'] = "No se pudo procesar el producto.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

$stmtProducto->bind_param("i", $id_producto);
$stmtProducto->execute();
$resProducto = $stmtProducto->get_result();

if (!$resProducto || $resProducto->num_rows === 0) {
    $stmtProducto->close();
    $_SESSION['mensaje_portafolio'] = "El producto no existe.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

$producto = $resProducto->fetch_assoc();
$stmtProducto->close();

if ($producto['estado_producto'] !== 'A' || (int)$producto['cantidad_stock'] <= 0) {
    $_SESSION['mensaje_portafolio'] = "Este producto no está disponible en este momento.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Crear carrito temporal en sesión
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['carrito_cliente'])) {
    $_SESSION['carrito_cliente'] = [];
}

if (isset($_SESSION['carrito_cliente'][$id_producto])) {
    $_SESSION['carrito_cliente'][$id_producto]['cantidad'] += 30;
} else {
    $_SESSION['carrito_cliente'][$id_producto] = [
        'id_producto' => (int)$producto['id_producto'],
        'nombre_producto' => $producto['nombre_producto'],
        'precio' => (float)$producto['precio'],
        'cantidad' => 30
    ];
}

$_SESSION['mensaje_portafolio'] = "Producto agregado al pedido correctamente.";
$_SESSION['tipo_portafolio'] = "success";

header("Location: ../../cliente/portafolio.php");
exit;
?>