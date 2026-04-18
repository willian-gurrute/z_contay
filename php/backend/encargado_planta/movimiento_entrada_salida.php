<?php

/* 
   Conexión a la base de datos
*/
require_once __DIR__ . "/../conexion.php";

/* 
   Arreglo donde guardaremos productos
*/
$productos = [];

/* 
   Consulta para traer productos activos
*/
$sql = "SELECT 
            id_producto,
            nombre_producto
        FROM producto
        WHERE estado = 'A'
        ORDER BY nombre_producto ASC";

$resultado = $conn->query($sql);

/* 
   Guardamos en arreglo
*/
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}
?>