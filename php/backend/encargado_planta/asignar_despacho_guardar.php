<?php
session_start();

require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../verificar_permiso.php";

verificarPermiso("gestion_despachos");

$id_factura = $_POST['id_factura'] ?? null;
$id_transportador = $_POST['id_transportador'] ?? null;
$zona_entrega = $_POST['zona_entrega'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_factura || !$id_transportador || !$zona_entrega) {
    header("Location: ../../encargado_planta/asignar_despacho.php?error=1&id_factura=" . urlencode($id_factura));
    exit();
}

/* Verificar que no exista ya un despacho para esta factura */
$sql_verificar = "SELECT id_despacho FROM despacho WHERE id_factura = ?";
$stmt_verificar = $conn->prepare($sql_verificar);

if (!$stmt_verificar) {
    die("Error al preparar verificación de despacho.");
}

$stmt_verificar->bind_param("i", $id_factura);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows > 0) {
    die("Este pedido ya tiene un despacho asignado.");
}

$stmt_verificar->close();

/* Insertar despacho */
$sql_insertar = "INSERT INTO despacho 
    (id_factura, id_usuario, id_transportador, estado, fecha_creacion, zona_entrega)
    VALUES (?, ?, ?, 'asignado', NOW(), ?)";

$stmt_insertar = $conn->prepare($sql_insertar);

if (!$stmt_insertar) {
    die("Error al preparar inserción del despacho.");
}

$stmt_insertar->bind_param("iiis", $id_factura, $id_usuario, $id_transportador, $zona_entrega);

if ($stmt_insertar->execute()) {
    header("Location: ../../encargado_planta/gestion_despachos.php?ok=1");
    exit();
} else {
    die("Error al crear el despacho.");
}
?>