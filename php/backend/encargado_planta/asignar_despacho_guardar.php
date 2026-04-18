<?php
session_start();

require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../verificar_permiso.php";

verificarPermiso("gestion_despachos");

$id_factura = $_POST['id_factura'] ?? null;
$id_despacho = $_POST['id_despacho'] ?? null;
$id_transportador = $_POST['id_transportador'] ?? null;
$zona_entrega = $_POST['zona_entrega'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_factura || !$id_transportador || !$zona_entrega) {
    header("Location: ../../encargado_planta/gestion_despachos.php?error=1");
    exit();
}

if (!empty($id_despacho)) {
    $sqlUpdate = "UPDATE despacho
                  SET id_transportador = ?, zona_entrega = ?, estado = 'asignado', id_usuario = ?
                  WHERE id_despacho = ?";

    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("isii", $id_transportador, $zona_entrega, $id_usuario, $id_despacho);

    if ($stmtUpdate->execute()) {
        header("Location: ../../encargado_planta/gestion_despachos.php?ok=2");
        exit();
    } else {
        die("Error al reasignar el despacho.");
    }
}

$sql_verificar = "SELECT id_despacho FROM despacho WHERE id_factura = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("i", $id_factura);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows > 0) {
    die("Este pedido ya tiene un despacho asignado.");
}

$stmt_verificar->close();

$sql_insertar = "INSERT INTO despacho 
    (id_factura, id_usuario, id_transportador, estado, fecha_creacion, zona_entrega)
    VALUES (?, ?, ?, 'asignado', NOW(), ?)";

$stmt_insertar = $conn->prepare($sql_insertar);
$stmt_insertar->bind_param("iiis", $id_factura, $id_usuario, $id_transportador, $zona_entrega);

if ($stmt_insertar->execute()) {
    header("Location: ../../encargado_planta/gestion_despachos.php?ok=1");
    exit();
} else {
    die("Error al crear el despacho.");
}
?>