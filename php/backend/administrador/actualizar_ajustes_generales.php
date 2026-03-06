<?php
// php/backend/administrador/actualizar_ajustes_generales.php
// Actualiza los datos de la empresa

session_start();
require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Recibir datos
$id_empresa = $_POST['id_empresa'] ?? 0;
$nombre = trim($_POST['nombre'] ?? '');
$nit = trim($_POST['nit'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$departamento = trim($_POST['departamento'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$horario_atencion = trim($_POST['horario_atencion'] ?? '');

// Validación simple
if ($id_empresa == 0 || $nombre == '' || $nit == '') {
    header("Location: ../../administrador/ajustes_generales.php?msg=error");
    exit;
}

// Mantener logo actual por defecto
$nombreLogo = null;

// Si subieron archivo
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {

    $nombreLogo = basename($_FILES['logo']['name']);
    $rutaDestino = "../../../img/" . $nombreLogo;

    move_uploaded_file($_FILES['logo']['tmp_name'], $rutaDestino);

    // Actualiza también el logo
    $stmt = $conn->prepare("
        UPDATE empresa
        SET nombre = ?, nit = ?, direccion = ?, telefono = ?, correo = ?, logo = ?, ciudad = ?, departamento = ?, horario_atencion = ?
        WHERE id_empresa = ?
    ");

    $stmt->bind_param(
        "sssssssssi",
        $nombre,
        $nit,
        $direccion,
        $telefono,
        $correo,
        $nombreLogo,
        $ciudad,
        $departamento,
        $horario_atencion,
        $id_empresa
    );

} else {

    // Si no subieron logo, no lo cambiamos
    $stmt = $conn->prepare("
        UPDATE empresa
        SET nombre = ?, nit = ?, direccion = ?, telefono = ?, correo = ?, ciudad = ?, departamento = ?, horario_atencion = ?
        WHERE id_empresa = ?
    ");

    $stmt->bind_param(
        "ssssssssi",
        $nombre,
        $nit,
        $direccion,
        $telefono,
        $correo,
        $ciudad,
        $departamento,
        $horario_atencion,
        $id_empresa
    );
}

if ($stmt->execute()) {
    header("Location: ../../administrador/ajustes_generales.php?msg=ok");
} else {
    header("Location: ../../administrador/ajustes_generales.php?msg=error");
}
exit;