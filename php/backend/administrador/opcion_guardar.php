<?php
// Guarda una opción seleccionada desde crear_opciones.php (select)

session_start();
require_once __DIR__ . "/../conexion.php";

// Seguridad: solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// Datos del formulario
$estado = $_POST['estado'] ?? 'A';
$opcion_key = $_POST['opcion_key'] ?? '';

// Validación básica
if ($opcion_key === '' || !in_array($estado, ['A','I'], true)) {
    header("Location: ../../administrador/crear_opciones.php?msg=error");
    exit;
}

// Separar: modulo|nombre|controlador|funcion
$partes = explode("|", $opcion_key);

if (count($partes) !== 4) {
    header("Location: ../../administrador/crear_opciones.php?msg=error");
    exit;
}

$modulo = trim($partes[0]);
$nombre_opcion = trim($partes[1]);
$nombre_controlador = trim($partes[2]);
$nombre_funcion = trim($partes[3]);

// No permitir desactivar la opción Crear Opciones
if ($nombre_controlador === "crear_opciones" && $estado === "I") {
    header("Location: ../../administrador/crear_opciones.php?msg=bloqueada");
    exit;
}

// Evitar duplicados
$stmt = $conn->prepare("SELECT id_opciones FROM opciones WHERE nombre_controlador = ? LIMIT 1");
$stmt->bind_param("s", $nombre_controlador);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    // Si la opción ya existe, actualizamos su estado
    $stmtUpdate = $conn->prepare("
        UPDATE opciones
        SET estado = ?, modulo = ?, nombre_opcion = ?, nombre_funcion = ?
        WHERE nombre_controlador = ?
    ");

    if (!$stmtUpdate) {
        header("Location: ../../administrador/crear_opciones.php?msg=error");
        exit;
    }

    $stmtUpdate->bind_param("sssss", $estado, $modulo, $nombre_opcion, $nombre_funcion, $nombre_controlador);

    if ($stmtUpdate->execute()) {
        header("Location: ../../administrador/crear_opciones.php?msg=actualizado");
    } else {
        header("Location: ../../administrador/crear_opciones.php?msg=error");
    }
    exit;
}

// Insertar en BD (5 campos)
$stmt = $conn->prepare("
    INSERT INTO opciones (modulo, nombre_opcion, nombre_controlador, nombre_funcion, estado)
    VALUES (?,?,?,?,?)
");

$stmt->bind_param("sssss", $modulo, $nombre_opcion, $nombre_controlador, $nombre_funcion, $estado);

if ($stmt->execute()) {
    header("Location: ../../administrador/crear_opciones.php?msg=ok");
} else {
    header("Location: ../../administrador/crear_opciones.php?msg=error");
}
exit;