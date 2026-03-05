<?php
//Backend: actualizar rol + actualizar permisos

// Actualiza nombre/estado del rol y sus permisos

session_start();
require_once __DIR__ . "/../conexion.php";

// Solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Datos del formulario
$id_rol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
$nombre = trim($_POST['nombre_rol'] ?? '');
$estado = trim($_POST['estado_rol'] ?? 'A');

// Checkboxes (puede venir vacío)
$opciones = $_POST['opciones'] ?? [];

// Validación básica
if ($id_rol <= 0 || $nombre === '' || !in_array($estado, ['A','I'], true)) {
    header("Location: ../../administrador/editar_rol.php?id=$id_rol&msg=bad");
    exit;
}

// 1) Actualizar rol
$stmt = $conn->prepare("UPDATE rol SET nombre=?, estado=? WHERE id_rol=?");
$stmt->bind_param("ssi", $nombre, $estado, $id_rol);
$stmt->execute();

// 2) Borrar permisos actuales
$stmt = $conn->prepare("DELETE FROM permisos WHERE id_rol=?");
$stmt->bind_param("i", $id_rol);
$stmt->execute();

// 3) Insertar permisos marcados
if (!empty($opciones)) {
    $stmtPerm = $conn->prepare("INSERT INTO permisos (id_rol, id_opciones) VALUES (?, ?)");

    foreach ($opciones as $id_op) {
        $id_op = (int)$id_op;
        if ($id_op > 0) {
            $stmtPerm->bind_param("ii", $id_rol, $id_op);
            $stmtPerm->execute();
        }
    }
}

header("Location: ../../administrador/editar_rol.php?id=$id_rol&msg=ok");
exit;