<?php
// php/backend/administrador/ajustes_generales.php
// Trae los datos de la empresa

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Seguridad: solo administrador
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

$empresa = [];

// Tomamos la primera empresa registrada
$sql = "
    SELECT id_empresa, nombre, nit, direccion, telefono, correo, logo, ciudad, departamento, horario_atencion
    FROM empresa
    ORDER BY id_empresa ASC
    LIMIT 1
";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $empresa = $res->fetch_assoc();
}