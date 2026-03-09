<?php
// php/backend/administrador/crear_rol.php
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
if (!$stmt) {
    die("Error al preparar inserción del rol: " . $conn->error);
}

$stmt->bind_param("ss", $nombre, $estado);

// AQUÍ FALTABA EJECUTAR
if (!$stmt->execute()) {
    die("Error al insertar el rol: " . $stmt->error);
}

// Tomamos el id del rol recién creado
$id_rol_nuevo = $conn->insert_id;

// Verificamos que exista
if (empty($id_rol_nuevo)) {
    die("Error: no se pudo obtener el id del rol nuevo.");
}

// 2) Insertar permisos marcados, solo si la opción está activa
if (!empty($opciones)) {
    
    // Consulta para validar que la opción esté activa
    $stmtValidarOpcion = $conn->prepare("
        SELECT id_opciones
        FROM opciones
        WHERE id_opciones = ? AND estado = 'A'
    ");

    if (!$stmtValidarOpcion) {
        die("Error al preparar validación de opción: " . $conn->error);
    }

    // Consulta para insertar el permiso
    $stmtPerm = $conn->prepare("
        INSERT INTO permisos (id_rol, id_opciones)
        VALUES (?, ?)
    ");

    if (!$stmtPerm) {
        die("Error al preparar inserción de permisos: " . $conn->error);
    }

    foreach ($opciones as $id_op) {
        $id_op = (int)$id_op;

        if ($id_op > 0) {
            // Validamos si la opción está activa
            $stmtValidarOpcion->bind_param("i", $id_op);
            $stmtValidarOpcion->execute();
            $resValidar = $stmtValidarOpcion->get_result();

            // Solo insertamos si la opción sí está activa
            if ($resValidar && $resValidar->num_rows > 0) {
                $stmtPerm->bind_param("ii", $id_rol_nuevo, $id_op);
                $stmtPerm->execute();
            }
        }
    }
}

// Listo
header("Location: ../../administrador/crear_rol.php?msg=ok");
exit;