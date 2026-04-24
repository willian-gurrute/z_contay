<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

function volverConMensaje($mensaje, $tipo = "error")
{
    $_SESSION['mensaje_password'] = $mensaje;
    $_SESSION['tipo_password'] = $tipo;
    header("Location: ../../cliente/cambiar_contrasena.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    volverConMensaje("Acceso no permitido.");
}

if (!isset($_SESSION['id_usuario'])) {
    volverConMensaje("No hay una sesión activa.");
}

$idUsuarioFormulario = $_POST['id_usuario'] ?? "";
$passwordActual = trim($_POST['password_actual'] ?? "");
$passwordNueva = trim($_POST['password_nueva'] ?? "");
$passwordConfirmar = trim($_POST['password_confirmar'] ?? "");

$idUsuarioSesion = $_SESSION['id_usuario'];

if ($idUsuarioFormulario != $idUsuarioSesion) {
    volverConMensaje("No tienes permiso para cambiar esta contraseña.");
}

if (empty($passwordActual) || empty($passwordNueva) || empty($passwordConfirmar)) {
    volverConMensaje("Todos los campos son obligatorios.");
}

if (strlen($passwordNueva) < 6) {
    volverConMensaje("La nueva contraseña debe tener mínimo 6 caracteres.");
}

if ($passwordNueva !== $passwordConfirmar) {
    volverConMensaje("La nueva contraseña y la confirmación no coinciden.");
}

$sqlBuscar = "SELECT password FROM usuario WHERE id_usuario = ?";
$stmtBuscar = $conn->prepare($sqlBuscar);

if (!$stmtBuscar) {
    volverConMensaje("Error al preparar la consulta.");
}

$stmtBuscar->bind_param("i", $idUsuarioSesion);
$stmtBuscar->execute();
$resultadoBuscar = $stmtBuscar->get_result();

if ($resultadoBuscar->num_rows == 0) {
    $stmtBuscar->close();
    volverConMensaje("No se encontró el usuario.");
}

$usuario = $resultadoBuscar->fetch_assoc();
$stmtBuscar->close();

if ($passwordActual !== $usuario['password']) {
    volverConMensaje("La contraseña actual es incorrecta.");
}

if ($passwordNueva === $passwordActual) {
    volverConMensaje("La nueva contraseña no puede ser igual a la actual.");
}

$sqlActualizar = "UPDATE usuario SET password = ? WHERE id_usuario = ?";
$stmtActualizar = $conn->prepare($sqlActualizar);

if (!$stmtActualizar) {
    volverConMensaje("Error al preparar la actualización.");
}

$stmtActualizar->bind_param("si", $passwordNueva, $idUsuarioSesion);

if ($stmtActualizar->execute()) {
    $stmtActualizar->close();
    volverConMensaje("Contraseña actualizada correctamente.", "success");
} else {
    $stmtActualizar->close();
    volverConMensaje("No se pudo actualizar la contraseña.");
}
?>