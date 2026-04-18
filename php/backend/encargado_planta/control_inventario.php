<?php
/* 
   Conexión a la base de datos
*/
require_once __DIR__ . "/../conexion.php";

/* 
   Consulta:
   - Trae productos
   - Une con inventario
*/
$sql = "SELECT 
            p.id_producto,
            p.nombre_producto,
            i.cantidad,
            i.ultima_actualizacion
        FROM producto p
        LEFT JOIN inventario i 
            ON i.id_producto = p.id_producto
        WHERE p.estado = 'A'
        ORDER BY p.id_producto ASC";

$resultado = $conn->query($sql);

/* 
   Guardamos en arreglo
*/
$productos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}