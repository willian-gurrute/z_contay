<?php
// php/backend/administrador/guardar_gasto.php
// Guarda un gasto como egreso en movimiento_contable

session_start();
require_once __DIR__ . "/../conexion.php";
require_once __DIR__ . "/../notificaciones_helper.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Recibir datos del formulario
$fecha = $_POST['fecha'] ?? '';
$descripcion = trim($_POST['descripcion'] ?? '');
$monto = $_POST['monto'] ?? '';
$id_usuario = $_SESSION['id_usuario'];

// Validación simple
if ($fecha === '' || $descripcion === '' || $monto === '' || $monto <= 0) {
    header("Location: ../../administrador/registrar_gasto.php?msg=error");
    exit;
}

// Agregar hora a la fecha
$fechaCompleta = $fecha . " " . date("H:i:s");

// Insertar como egreso
$stmt = $conn->prepare("
    INSERT INTO movimiento_contable (fecha, tipo, descripcion, monto, id_usuario)
    VALUES (?, 'egreso', ?, ?, ?)
");

$stmt->bind_param("ssdi", $fechaCompleta, $descripcion, $monto, $id_usuario);

if ($stmt->execute()) {

    // 14 Nuevo gasto registrado
    notificarRol($conn,14,1);

    // 15 Movimiento contable registrado
    notificarRol($conn,15,1);

    header("Location: ../../administrador/registrar_gasto.php?msg=ok");

} else {
    header("Location: ../../administrador/registrar_gasto.php?msg=error");
}
exit;