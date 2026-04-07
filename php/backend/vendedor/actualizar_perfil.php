<?php
// Iniciamos la sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluimos la conexión a la base de datos
require_once __DIR__ . "/../conexion.php";

// Función para volver con mensaje
function volverConMensaje($mensaje, $tipo = "error")
{
    $_SESSION['mensaje_perfil'] = $mensaje;
    $_SESSION['tipo_perfil'] = $tipo;
    header("Location: ../../vendedor/editar_perfil.php");
    exit();
}

// Verificamos que el formulario haya sido enviado por método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Acceso no permitido.");
}

// Verificamos que exista una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    die("Error: no hay una sesión activa.");
}

// Recibimos los datos enviados desde el formulario
$idUsuarioFormulario = $_POST['id_usuario'] ?? 0;
$nombreCompleto = trim($_POST['nombre_completo'] ?? '');
$correoElectronico = trim($_POST['correo_electronico'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

// Guardamos también el id del usuario logueado
$idUsuarioSesion = $_SESSION['id_usuario'];

// Medida de seguridad:
// verificamos que el usuario solo pueda editar su propio perfil
if ($idUsuarioFormulario != $idUsuarioSesion) {
    die("Error: no tienes permiso para modificar este perfil.");
}

// Validamos que los campos obligatorios no estén vacíos
if (empty($nombreCompleto) || empty($correoElectronico)) {
    volverConMensaje("Nombre y correo son obligatorios.", "error");
}

// Validamos que el correo tenga formato correcto
if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
    volverConMensaje("El correo electrónico no es válido.", "error");
}

// Antes de actualizar, revisamos si el correo ya existe en otro usuario
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

// Consulta para actualizar los datos del perfil
$sqlActualizar = "UPDATE usuario 
                  SET nombre_completo = ?, 
                      correo_electronico = ?, 
                      direccion = ?
                  WHERE id_usuario = ?";

// Preparamos la consulta
$stmtActualizar = $conn->prepare($sqlActualizar);

// Verificamos si hubo error
if (!$stmtActualizar) {
    die("Error al preparar la actualización: " . $conn->error);
}

// Asociamos los parámetros
$stmtActualizar->bind_param("sssi", $nombreCompleto, $correoElectronico, $direccion, $idUsuarioSesion);

// Ejecutamos la actualización
if ($stmtActualizar->execute()) {
    // Actualizar nombre en sesión para que cambie en el header
    $_SESSION['nombre'] = $nombreCompleto;

    $stmtActualizar->close();
    volverConMensaje("Perfil actualizado correctamente.", "success");
} else {
    die("Error al actualizar el perfil: " . $stmtActualizar->error);
}
?>