<?php
// php/backend/administrador/usuario_actualizar.php
// Actualiza los datos del usuario (y la contraseña solo si la escriben)

session_start();
require_once __DIR__ . "/../conexion.php";

// Solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Recibir datos
$id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
$nombre = trim($_POST['nombre_completo'] ?? '');
$tipo_doc = trim($_POST['tipo_documento'] ?? '');
$num_doc = trim($_POST['numero_documento'] ?? '');
$correo = trim($_POST['correo_electronico'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$id_rol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
$password = trim($_POST['password'] ?? ''); // puede venir vacío

// Validación simple
if ($id_usuario <= 0 || $nombre === '' || $tipo_doc === '' || $num_doc === '' || $correo === '' || $id_rol <= 0) {
    header("Location: ../../administrador/editar_usuario.php?id=$id_usuario&msg=bad");
    exit;
}

// Evitar duplicados (correo o documento en otro usuario)
$stmt = $conn->prepare("
    SELECT id_usuario 
    FROM usuario 
    WHERE (correo_electronico = ? OR numero_documento = ?)
      AND id_usuario <> ?
    LIMIT 1
");
$stmt->bind_param("ssi", $correo, $num_doc, $id_usuario);
$stmt->execute();
$dup = $stmt->get_result();
if ($dup && $dup->num_rows > 0) {
    header("Location: ../../administrador/editar_usuario.php?id=$id_usuario&msg=dup");
    exit;
}

// Si escribió contraseña, la actualizamos también
if ($password !== '') {

    // OJO: aquí guardamos tal cual para no dañar tu login actual (nivel estudiante)
    $stmt = $conn->prepare("
        UPDATE usuario
        SET nombre_completo=?, tipo_documento=?, numero_documento=?, correo_electronico=?, direccion=?, id_rol=?, password=?
        WHERE id_usuario=?
    ");
    $stmt->bind_param("sssssisi", $nombre, $tipo_doc, $num_doc, $correo, $direccion, $id_rol, $password, $id_usuario);
    $stmt->execute();

} else {

    // Si la contraseña está vacía, NO la cambiamos
    $stmt = $conn->prepare("
        UPDATE usuario
        SET nombre_completo=?, tipo_documento=?, numero_documento=?, correo_electronico=?, direccion=?, id_rol=?
        WHERE id_usuario=?
    ");
    $stmt->bind_param("sssssis", $nombre, $tipo_doc, $num_doc, $correo, $direccion, $id_rol, $id_usuario);
    $stmt->execute();
}

header("Location: ../../administrador/editar_usuario.php?id=$id_usuario&msg=ok");
exit;