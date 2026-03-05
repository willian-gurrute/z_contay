<?php
// php/backend/administrador/usuario_estado.php
// Cambia estado del usuario: A (activo) / I (inactivo)

//inicia sesion

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//conexion a la base de datos.
require_once __DIR__ . "/../conexion.php";


// verificar que sea administrador.
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

//obtener datos enviados por el formulario.
$id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
$accion = $_POST['accion'] ?? '';

//validar accion.
if ($id_usuario <= 0 || !in_array($accion, ['activar', 'desactivar'], true)) {
    header("Location: ../../administrador/gestion_usuarios.php?msg=badreq");
    exit;
}

// Evitar que el admin se desactive a sí mismo 
if ($id_usuario === (int)($_SESSION['id_usuario'] ?? 0) && $accion === 'desactivar') {
    header("Location: ../../administrador/gestion_usuarios.php?msg=self");
    exit;
}

//definir nuevo estado A=activo-I=inactivo.
$nuevo_estado = ($accion === 'activar') ? 'A' : 'I';

//actualizar estado del usuario.
$stmt = $conn->prepare("UPDATE usuario SET estado = ? WHERE id_usuario = ?");
$stmt->bind_param("si", $nuevo_estado, $id_usuario);
$stmt->execute();

//volver a la pantalla.
header("Location: ../../administrador/gestion_usuarios.php?msg=ok");
exit;