<?php
session_start();

//cargamos conexion y validaciones

require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../verificar_permiso.php";

verificarPermiso("zonas_entrega");

//recibimos datos del formulario//

$id_transportador = $_POST['id_transportador'] ?? null;
$zona_asignada = $_POST['zona_asignada'] ?? null;

//validamos que no tenga vacios//

if (!$id_transportador || !$zona_asignada) {
    die("Datos incompletos.");
}

//actualizamos la zona del transportador//

$sql = "UPDATE transportador
        SET zona_asignada = ?
        WHERE id_transportador = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $zona_asignada, $id_transportador);

//si guarda bien, volmvemos a la lista con mensaje de exito//

if ($stmt->execute()) {
    header("location: ../../encargado_planta/zonas_entrega.php?ok=1");
    exit();
}else{
    die("Error al actualizar la zona.");
}
?>
