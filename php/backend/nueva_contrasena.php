<?php
// backend/nueva_contraseña.php
// actualiza la contraseña del usuario que está recuperando su cuenta.

session_start();
require_once "conexion.php";

// 1) Verificar que exista el correo en sesión (si no, el proceso es inválido)
if (!isset($_SESSION['correo_reset'])) {
    $_SESSION['error'] = "Proceso inválido. Intenta recuperar la contraseña de nuevo.";
    header("Location: ../login/olvidastes_contraseña.php");
    exit;
}

$correo = $_SESSION['correo_reset'];

// 2) Recibir las contraseñas del formulario
$password         = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// 3) Validar campos vacíos
if (empty($password) || empty($password_confirm)) {
    $_SESSION['error'] = "Debe completar todos los campos.";
    header("Location: ../login/nueva_contraseña.php");
    exit;
}

// 4) Validar mínimo de caracteres
if (strlen($password) < 6) {
    $_SESSION['error'] = "La contraseña debe tener mínimo 6 caracteres.";
    header("Location: ../login/nueva_contraseña.php");
    exit;
}

// 5) Validar que coincidan
if ($password !== $password_confirm) {
    $_SESSION['error'] = "Las contraseñas no coinciden.";
    header("Location: ../login/nueva_contraseña.php");
    exit;
}

// 6) Actualizar contraseña en la base de datos (texto plano para tu proyecto)
$sql = "UPDATE usuario
        SET password = ?
        WHERE correo_electronico = ?
        AND estado = 'A'
        LIMIT 1";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $_SESSION['error'] = "Error al preparar la actualización.";
    header("Location: ../login/nueva_contraseña.php");
    exit;
}

$stmt->bind_param("ss", $password, $correo);

// 7) Ejecutar
if ($stmt->execute()) {

    // terminar proceso
    unset($_SESSION['correo_reset']);

    // mensaje para el login
    $_SESSION['ok'] = "Contraseña actualizada. Ahora inicia sesión.";

    header("Location: ../login/inicio-seccion.php");
    exit;

} else {

    $_SESSION['error'] = "No se pudo actualizar la contraseña.";
    header("Location: ../login/nueva_contraseña.php");
    exit;
}