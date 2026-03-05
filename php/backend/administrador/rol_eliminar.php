<?php
// Elimina un rol (y primero elimina sus permisos)

session_start();
require_once __DIR__ . "/../conexion.php";

// Solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

$id_rol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;

if ($id_rol <= 0) {
    header("Location: ../../administrador/roles_permisos.php?msg=bad");
    exit;
}

// (Opcional) No permitir borrar el rol Administrador
if ($id_rol === 1) {
    header("Location: ../../administrador/roles_permisos.php?msg=bad");
    exit;
}

// 1) Borrar permisos del rol
$stmt = $conn->prepare("DELETE FROM permisos WHERE id_rol = ?");
$stmt->bind_param("i", $id_rol);
$stmt->execute();

// 2) Borrar el rol
$stmt = $conn->prepare("DELETE FROM rol WHERE id_rol = ?");
$stmt->bind_param("i", $id_rol);
$stmt->execute();

header("Location: ../../administrador/roles_permisos.php?msg=ok");
exit;