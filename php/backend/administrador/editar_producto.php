<?php
// php/backend/administrador/editar_producto.php
// Trae un producto por id

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

$producto = [];

$stmt = $conn->prepare("
    SELECT id_producto, nombre_producto, precio, estado
    FROM producto
    WHERE id_producto = ?
    LIMIT 1
");

$stmt->bind_param("i", $id);
$stmt->execute();

$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    $producto = $res->fetch_assoc();
}