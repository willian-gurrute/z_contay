<?php
// php/backend/administrador/actualizar_producto.php
// Actualiza precio y estado del producto

session_start();
require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Recibir datos
$id_producto = $_POST['id_producto'] ?? 0;
$precio = $_POST['precio'] ?? '';
$estado = $_POST['estado'] ?? '';

// Validación simple
if ($id_producto == 0 || $precio === '' || !in_array($estado, ['A', 'I'])) {
    header("Location: ../../administrador/editar_producto.php?id=$id_producto&msg=error");
    exit;
}

// Actualizar producto
$stmt = $conn->prepare("
    UPDATE producto
    SET precio = ?, estado = ?
    WHERE id_producto = ?
");

$stmt->bind_param("dsi", $precio, $estado, $id_producto);

if ($stmt->execute()) {
    header("Location: ../../administrador/editar_producto.php?id=$id_producto&msg=ok");
} else {
    header("Location: ../../administrador/editar_producto.php?id=$id_producto&msg=error");
}
exit;