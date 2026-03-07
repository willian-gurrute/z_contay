<?php
// Iniciamos la sesión porque vamos a usar datos del usuario logueado
session_start();

// Incluimos la conexión a la base de datos
require_once __DIR__ . "/../conexion.php";

// Verificamos que el formulario haya sido enviado por método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Acceso no permitido.");
}

// Verificamos que exista una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    die("Error: no hay una sesión activa.");
}

// Recibimos los datos enviados desde el formulario
$idUsuarioFormulario = $_POST['id_usuario'];
$nombreCompleto = trim($_POST['nombre_completo']);
$correoElectronico = trim($_POST['correo_electronico']);
$direccion = trim($_POST['direccion']);

// Guardamos también el id del usuario logueado
$idUsuarioSesion = $_SESSION['id_usuario'];

// Medida de seguridad:
// verificamos que el usuario solo pueda editar su propio perfil
if ($idUsuarioFormulario != $idUsuarioSesion) {
    die("Error: no tienes permiso para modificar este perfil.");
}

// Validamos que los campos obligatorios no estén vacíos
if (empty($nombreCompleto) || empty($correoElectronico)) {
    die("Error: nombre y correo son obligatorios.");
}

// Validamos que el correo tenga formato correcto
if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
    die("Error: el correo electrónico no es válido.");
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
    die("Error: el correo ya está registrado por otro usuario.");
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
// s = string
// i = integer
$stmtActualizar->bind_param("sssi", $nombreCompleto, $correoElectronico, $direccion, $idUsuarioSesion);

// Ejecutamos la actualización
if ($stmtActualizar->execute()) {
    // Si se actualizó bien, redirigimos al perfil
    header("Location: ../../administrador/perfil.php?actualizado=1");
    exit();
} else {
    die("Error al actualizar el perfil: " . $stmtActualizar->error);
}

// Cerramos la consulta
$stmtActualizar->close();
?>