<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 
   Conexión
*/
require_once __DIR__ . "/../conexion.php";

/* 
   Función para regresar con mensaje
*/
function volverConMensaje($mensaje, $tipo = "error")
{
    $_SESSION['mensaje_password'] = $mensaje;
    $_SESSION['tipo_password'] = $tipo;
    header("Location: ../../encargado_planta/cambiar_contrasena.php");
    exit();
}

/* 
   Validar método
*/
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    volverConMensaje("Acceso no permitido.");
}

/* 
   Validar sesión
*/
if (!isset($_SESSION['id_usuario'])) {
    volverConMensaje("No hay una sesión activa.");
}

/* 
   Recibir datos
*/
$idUsuarioFormulario = $_POST['id_usuario'] ?? "";
$passwordActual = trim($_POST['password_actual'] ?? "");
$passwordNueva = trim($_POST['password_nueva'] ?? "");
$passwordConfirmar = trim($_POST['password_confirmar'] ?? "");

$idUsuarioSesion = $_SESSION['id_usuario'];

/* 
   Seguridad
*/
if ($idUsuarioFormulario != $idUsuarioSesion) {
    volverConMensaje("No tienes permiso para cambiar esta contraseña.");
}

/* 
   Validaciones
*/
if (empty($passwordActual) || empty($passwordNueva) || empty($passwordConfirmar)) {
    volverConMensaje("Todos los campos son obligatorios.");
}

if (strlen($passwordNueva) < 6) {
    volverConMensaje("La nueva contraseña debe tener mínimo 6 caracteres.");
}

if ($passwordNueva !== $passwordConfirmar) {
    volverConMensaje("Las contraseñas no coinciden.");
}

/* 
   Buscar contraseña actual
*/
$sql = "SELECT password FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuarioSesion);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    volverConMensaje("Usuario no encontrado.");
}

$usuario = $resultado->fetch_assoc();
$stmt->close();

/* 
   Validar contraseña actual
*/
if ($passwordActual !== $usuario['password']) {
    volverConMensaje("La contraseña actual es incorrecta.");
}

/* 
   Validar que no sea la misma
*/
if ($passwordNueva === $passwordActual) {
    volverConMensaje("La nueva contraseña no puede ser igual a la anterior.");
}

/* 
   Actualizar contraseña
*/
$sqlUpdate = "UPDATE usuario SET password = ? WHERE id_usuario = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("si", $passwordNueva, $idUsuarioSesion);

if ($stmtUpdate->execute()) {
    volverConMensaje("Contraseña actualizada correctamente.", "success");
} else {
    volverConMensaje("Error al actualizar la contraseña.");
}
?>