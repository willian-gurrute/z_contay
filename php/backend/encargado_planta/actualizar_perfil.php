<?php

/* 
   Iniciamos sesión solo si no está iniciada
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 
   Conexión a la base de datos
*/
require_once __DIR__ . "/../conexion.php";

/* 
   Función para volver a editar perfil con mensaje
*/
function volverConMensaje($mensaje, $tipo = "error")
{
    $_SESSION['mensaje_perfil'] = $mensaje;
    $_SESSION['tipo_perfil'] = $tipo;
    header("Location: ../../encargado_planta/editar_perfil.php");
    exit();
}

/* 
   Solo permitimos acceso por método POST
*/
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Acceso no permitido.");
}

/* 
   Verificamos que haya sesión activa
*/
if (!isset($_SESSION['id_usuario'])) {
    die("Error: no hay una sesión activa.");
}

/* 
   Recibimos datos del formulario
*/
$idUsuarioFormulario = $_POST['id_usuario'] ?? 0;
$nombreCompleto = trim($_POST['nombre_completo'] ?? '');
$correoElectronico = trim($_POST['correo_electronico'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

/* 
   También obtenemos el id del usuario logueado
*/
$idUsuarioSesion = $_SESSION['id_usuario'];

/* 
   Seguridad:
   el usuario solo puede editar su propio perfil
*/
if ($idUsuarioFormulario != $idUsuarioSesion) {
    die("Error: no tienes permiso para modificar este perfil.");
}

/* 
   Validamos campos obligatorios
*/
if (empty($nombreCompleto) || empty($correoElectronico)) {
    volverConMensaje("Nombre y correo son obligatorios.", "error");
}

/* 
   Validamos formato del correo
*/
if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
    volverConMensaje("El correo electrónico no es válido.", "error");
}

/* 
   Verificamos que el correo no esté registrado por otro usuario
*/
$sqlValidarCorreo = "SELECT id_usuario
                     FROM usuario
                     WHERE correo_electronico = ?
                     AND id_usuario != ?";

$stmtValidar = $conn->prepare($sqlValidarCorreo);

if (!$stmtValidar) {
    die("Error al preparar la validación del correo: " . $conn->error);
}

$stmtValidar->bind_param("si", $correoElectronico, $idUsuarioSesion);
$stmtValidar->execute();

$resultadoValidar = $stmtValidar->get_result();

if ($resultadoValidar->num_rows > 0) {
    $stmtValidar->close();
    volverConMensaje("El correo ya está registrado por otro usuario.", "error");
}

$stmtValidar->close();

/* 
   Actualizamos datos del perfil
*/
$sqlActualizar = "UPDATE usuario
                  SET nombre_completo = ?,
                      correo_electronico = ?,
                      direccion = ?
                  WHERE id_usuario = ?";

$stmtActualizar = $conn->prepare($sqlActualizar);

if (!$stmtActualizar) {
    die("Error al preparar la actualización: " . $conn->error);
}

$stmtActualizar->bind_param("sssi", $nombreCompleto, $correoElectronico, $direccion, $idUsuarioSesion);

/* 
   Ejecutamos actualización
*/
if ($stmtActualizar->execute()) {

    /* 
       Actualizamos el nombre en sesión
       para que cambie también en el header
    */
    $_SESSION['nombre'] = $nombreCompleto;

    $stmtActualizar->close();
    volverConMensaje("Perfil actualizado correctamente.", "success");
} else {
    die("Error al actualizar el perfil: " . $stmtActualizar->error);
}
?>