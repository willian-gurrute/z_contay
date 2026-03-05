<?php
// php/backend/administrador/rol_crear.php
// Crea rol y guarda permisos en la tabla permisos

session_start();
require_once __DIR__ . "/../conexion.php";

// Solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Recibir datos
$nombre = trim($_POST['nombre_rol'] ?? '');
$estado = trim($_POST['estado'] ?? 'A');

// Checkboxes (puede venir vacío)
$opciones = $_POST['opciones'] ?? [];

// Validación simple
if ($nombre === '' || !in_array($estado, ['A', 'I'], true)) {
    header("Location: ../../administrador/crear_rol.php?msg=bad");
    exit;
}

// Evitar duplicado de nombre de rol
$stmt = $conn->prepare("SELECT id_rol FROM rol WHERE nombre = ? LIMIT 1");
$stmt->bind_param("s", $nombre);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    header("Location: ../../administrador/crear_rol.php?msg=dup");
    exit;
}

// 1) Insertar el rol
$stmt = $conn->prepare("INSERT INTO rol (nombre, estado) VALUES (?, ?)");
$stmt->bind_param("ss", $nombre, $estado);
$stmt->execute();

// Tomar el id del rol nuevo
$id_rol_nuevo = $conn->insert_id;

// 2) Insertar permisos marcados (si marcaron)
if (!empty($opciones)) {

    $stmtPerm = $conn->prepare("INSERT INTO permisos (id_rol, id_opciones) VALUES (?, ?)");

    foreach ($opciones as $id_op) {
        $id_op = (int)$id_op;
        if ($id_op > 0) {
            $stmtPerm->bind_param("ii", $id_rol_nuevo, $id_op);
            $stmtPerm->execute();
        }
    }
}

// Listo
header("Location: ../../administrador/crear_rol.php?msg=ok");
exit;