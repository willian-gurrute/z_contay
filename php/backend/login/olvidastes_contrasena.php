<?php
// php/backend/olvidastes_contrasena.php
session_start();
require_once "../conexion.php";

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../login/olvidastes_contrasena.php");
    exit;
}

$correo = trim($_POST['correo_electronico'] ?? '');

if ($correo === '') {
    $_SESSION['error'] = "Debe escribir el correo.";
    header("Location: ../../login/olvidastes_contrasena.php");
    exit;
}

// Buscar usuario activo
$sql = "SELECT id_usuario
        FROM usuario
        WHERE correo_electronico = ?
        AND estado = 'A'
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error'] = "El correo no existe o el usuario está inactivo.";
    header("Location: ../../login/olvidastes_contrasena.php");
    exit;
}

//  Si existe, guardar y redirigir a la otra pantalla
$_SESSION['correo_reset'] = $correo;

header("Location: ../../login/nueva_contrasena.php");
exit;