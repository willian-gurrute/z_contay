<?php

require_once __DIR__ . "/../conexion.php";

// Tomar id de factura
$id = $_GET['id'] ?? 0;

// Traer información general de factura
$stmt = $conn->prepare("
SELECT 
f.id_factura,
f.fecha,
f.subtotal,
f.iva,
f.total,
f.tipo_venta,
f.metodo_pago,
c.nombre_completo AS cliente,
c.numero_documento,
c.telefono,
u.nombre_completo AS vendedor
FROM factura f
INNER JOIN cliente c ON c.id_cliente = f.id_cliente
INNER JOIN usuario u ON u.id_usuario = f.id_usuario
WHERE f.id_factura = ?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$res = $stmt->get_result();
$factura = $res->fetch_assoc();


// Traer productos de la factura
$stmt = $conn->prepare("
SELECT 
p.nombre_producto,
d.cantidad,
d.precio_unitario,
d.subtotal
FROM detalle_factura d
INNER JOIN producto p ON p.id_producto = d.id_producto
WHERE d.id_factura = ?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$res = $stmt->get_result();

$productos = [];

while($row = $res->fetch_assoc()){
    $productos[] = $row;
}