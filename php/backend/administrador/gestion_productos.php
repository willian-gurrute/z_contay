<?php
// php/backend/administrador/gestion_productos.php
// Trae los productos de la base de datos

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

$productos = [];

$sql = "
    SELECT id_producto, nombre_producto, precio, estado
    FROM producto
    ORDER BY id_producto ASC
";

$res = $conn->query($sql);

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $productos[] = $row;
    }
}