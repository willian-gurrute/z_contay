<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../cliente/portafolio.php");
    exit;
}

// Recibir producto
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;

if ($id_producto <= 0) {
    $_SESSION['mensaje_portafolio'] = "Producto no válido.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

// Buscar producto real en BD
$sqlProducto = "SELECT 
                    p.id_producto,
                    p.nombre_producto,
                    p.precio,
                    p.estado,
                    COALESCE(i.cantidad, 0) AS stock
                FROM producto p
                LEFT JOIN inventario i ON p.id_producto = i.id_producto
                WHERE p.id_producto = ?
                LIMIT 1";

$stmt = $conn->prepare($sqlProducto);

if (!$stmt) {
    $_SESSION['mensaje_portafolio'] = "Error al buscar el producto.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado || $resultado->num_rows === 0) {
    $stmt->close();
    $_SESSION['mensaje_portafolio'] = "El producto no existe.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

$producto = $resultado->fetch_assoc();
$stmt->close();

// Validar disponibilidad
if ($producto['estado'] !== 'A' || (int)$producto['stock'] <= 0) {
    $_SESSION['mensaje_portafolio'] = "Este producto está agotado.";
    $_SESSION['tipo_portafolio'] = "error";
    header("Location: ../../cliente/portafolio.php");
    exit;
}

// Crear carrito en sesión si no existe
if (!isset($_SESSION['carrito_cliente'])) {
    $_SESSION['carrito_cliente'] = [];
}

// Cantidad base por clic
$cantidadAgregar = 30;

// Si ya existe el producto en el carrito, aumenta cantidad
if (isset($_SESSION['carrito_cliente'][$id_producto])) {
    $_SESSION['carrito_cliente'][$id_producto]['cantidad'] += $cantidadAgregar;
} else {
    $_SESSION['carrito_cliente'][$id_producto] = [
        'id_producto' => (int)$producto['id_producto'],
        'nombre_producto' => $producto['nombre_producto'],
        'precio' => (float)$producto['precio'],
        'cantidad' => $cantidadAgregar
    ];
}

$_SESSION['mensaje_portafolio'] = "Producto agregado al pedido.";
$_SESSION['tipo_portafolio'] = "success";

header("Location: ../../cliente/portafolio.php");
exit;
?>