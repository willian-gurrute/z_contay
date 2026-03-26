<?php
session_start();

require_once "../conexion.php";

// recibir datos del formulario
$nombre_completo  = $_POST['nombre_completo'];
$tipo_documento   = $_POST['tipo_documento'];
$numero_documento = $_POST['numero_documento'];
$correo           = $_POST['correo_electronico'];
$password         = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

// validar campos vacios
if (
    empty($nombre_completo) ||
    empty($tipo_documento) ||
    empty($numero_documento) ||
    empty($correo) ||
    empty($password) ||
    empty($password_confirm)
) {

    $_SESSION['error'] = "Debe completar todos los campos";
    header("Location: ../login/crear-cuenta.php");
    exit;
}

// validar contraseñas iguales
if ($password !== $password_confirm) {

    $_SESSION['error'] = "Las contraseñas no coinciden";
    header("Location: ../login/crear-cuenta.php");
    exit;
}

// rol cliente
$id_rol = 5;

// verificar si ya existe el usuario
$sql = "SELECT id_usuario FROM usuario
        WHERE correo_electronico = ?
        OR numero_documento = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $correo, $numero_documento);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $_SESSION['error'] = "El usuario ya existe";
    header("Location: ../login/crear-cuenta.php");
    exit;
}

// insertar usuario
$sqlInsert = "INSERT INTO usuario
(tipo_documento, numero_documento, nombre_completo, correo_electronico, password, id_rol)
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sqlInsert);
$stmt->bind_param(
    "sssssi",
    $tipo_documento,
    $numero_documento,
    $nombre_completo,
    $correo,
    $password,
    $id_rol
);

if ($stmt->execute()) {

    $_SESSION['ok'] = "Cuenta creada correctamente";
    header("Location: ../../login/inicio-seccion.php");
    exit;

} else {

    $_SESSION['error'] = "Error al registrar";
    header("Location: ../login/crear-cuenta.php");
    exit;
}