<?php
session_start();

require_once __DIR__ . "/../conexion.php";

// seguridad
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: /prototipo/php/login/inicio-seccion.php");
    exit;
}

$id_notificacion = $_POST['id_notificacion'] ?? 0;
$accion = $_POST['accion'] ?? '';

if ($id_notificacion == 0 || !in_array($accion, ['activar', 'desactivar'])) {
    header("Location: /prototipo/php/administrador/notificaciones.php?msg=error");
    exit;
}

$estado = ($accion === 'activar') ? 'A' : 'I';

$stmt = $conn->prepare("
    UPDATE notificacion
    SET estado = ?
    WHERE id_notificacion = ?
");

$stmt->bind_param("si", $estado, $id_notificacion);

if ($stmt->execute()) {
    header("Location: /prototipo/php/administrador/notificaciones.php?msg=ok");
} else {
    header("Location: /prototipo/php/administrador/notificaciones.php?msg=error");
}
exit;