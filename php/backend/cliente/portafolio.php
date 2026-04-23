<?php
require_once __DIR__ . "/../conexion.php";

$productos = [];
$mensaje = $_SESSION['mensaje_portafolio'] ?? '';
$tipoMensaje = $_SESSION['tipo_portafolio'] ?? '';

unset($_SESSION['mensaje_portafolio'], $_SESSION['tipo_portafolio']);

$sqlProductos = "SELECT 
                    p.id_producto,
                    p.nombre_producto,
                    p.precio,
                    p.estado AS estado_producto,
                    COALESCE(i.cantidad, 0) AS cantidad_stock
                 FROM producto p
                 LEFT JOIN inventario i 
                    ON p.id_producto = i.id_producto
                 WHERE p.estado = 'A'
                 ORDER BY p.nombre_producto ASC";

$resProductos = $conn->query($sqlProductos);

if ($resProductos) {
    while ($fila = $resProductos->fetch_assoc()) {
        $productos[] = $fila;
    }
}
?>