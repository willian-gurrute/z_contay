<?php
// php/backend/administrador/usuario_crear.php
// Inserta un usuario nuevo en la tabla usuario

session_start();
require_once __DIR__ . "/../conexion.php";
require_once __DIR__ . "/../notificaciones_helper.php";

// Solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Recibir datos del formulario
$nombre = trim($_POST['nombre_completo'] ?? '');
$tipo_doc = trim($_POST['tipo_documento'] ?? '');
$num_doc = trim($_POST['numero_documento'] ?? '');
$correo = trim($_POST['correo_electronico'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$password = trim($_POST['password'] ?? '');
$id_rol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;

// Validación simple
if ($nombre === '' || $tipo_doc === '' || $num_doc === '' || $correo === '' || $password === '' || $id_rol <= 0) {
    header("Location: ../../administrador/crear_usuario.php?msg=bad");
    exit;
}

// Verificar si ya existe el correo o el documento
$stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE correo_electronico = ? OR numero_documento = ? LIMIT 1");
$stmt->bind_param("ss", $correo, $num_doc);
$stmt->execute();

$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    header("Location: ../../administrador/crear_usuario.php?msg=dup");
    exit;
}

// Insertar usuario
// Estado por defecto: 'A' (Activo)
$stmt = $conn->prepare("
    INSERT INTO usuario (tipo_documento, numero_documento, nombre_completo, correo_electronico, password, direccion, estado, id_rol)
    VALUES (?, ?, ?, ?, ?, ?, 'A', ?)
");

// OJO: guardamos password tal cual (para no dañar tu login actual)
$stmt->bind_param("ssssssi", $tipo_doc, $num_doc, $nombre, $correo, $password, $direccion, $id_rol);
$stmt->execute();


// 13 = Nuevo usuario registrado
notificarRol($conn, 13, 1);
// Si el rol es cliente (5), también guardarlo en tabla cliente
if ($id_rol == 5) {

    // Revisar si ya existe en cliente
    $stmtCheck = $conn->prepare("SELECT id_cliente FROM cliente WHERE numero_documento = ? LIMIT 1");
    $stmtCheck->bind_param("s", $num_doc);
    $stmtCheck->execute();
    $resCheck = $stmtCheck->get_result();

    // Si no existe, insertarlo
    if (!$resCheck || $resCheck->num_rows == 0) {
        $stmtCliente = $conn->prepare("
            INSERT INTO cliente (tipo_documento, numero_documento, nombre_completo, telefono, estado)
            VALUES (?, ?, ?, NULL, 'A')
        ");
        $stmtCliente->bind_param("sss", $tipo_doc, $num_doc, $nombre);
        $stmtCliente->execute();
    }
}

// Volver con mensaje ok
header("Location: ../../administrador/crear_usuario.php?msg=ok");
exit;