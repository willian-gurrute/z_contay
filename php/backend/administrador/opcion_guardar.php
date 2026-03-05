<?php
// Guarda una nueva opción en la tabla opciones

session_start();

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || ($_SESSION['id_rol'] ?? 0) != 1) {

header("Location: ../../login/inicio-seccion.php");
exit;

}

// Recibir datos del formulario

$nombre_opcion = trim($_POST['nombre_opcion'] ?? '');
$nombre_controlador = trim($_POST['nombre_controlador'] ?? '');
$nombre_funcion = trim($_POST['nombre_funcion'] ?? '');
$estado = trim($_POST['estado'] ?? '');

// Validación básica

if ($nombre_opcion == '' || $nombre_controlador == '' || $nombre_funcion == '' || $estado == '') {

header("Location: ../../administrador/crear_opciones.php?msg=error");
exit;

}

// Insertar en base de datos

$stmt = $conn->prepare("
INSERT INTO opciones (nombre_opcion,nombre_controlador,nombre_funcion,estado)
VALUES (?,?,?,?)
");

$stmt->bind_param("ssss",$nombre_opcion,$nombre_controlador,$nombre_funcion,$estado);

if ($stmt->execute()) {

header("Location: ../../administrador/crear_opciones.php?msg=ok");

} else {

header("Location: ../../administrador/crear_opciones.php?msg=error");

}

exit;