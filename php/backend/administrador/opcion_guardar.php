<?php
// Guarda una opción seleccionada desde crear_opciones.php (select)

session_start();
require_once __DIR__ . "/../conexion.php";

// 1) Seguridad: solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

// 2) Datos que vienen del formulario
$estado = $_POST['estado'] ?? 'A';
$opcion_key = $_POST['opcion_key'] ?? ''; // ejemplo: Usuarios|Gestión de usuarios|gestion_usuarios|ver

// Validación básica
if ($opcion_key === '' || !in_array($estado, ['A','I'], true)) {
    header("Location: ../../administrador/crear_opciones.php?msg=error");
    exit;
}

// 3) Separar el texto "opcion_key" en partes
// Formato: modulo|nombre|controlador|funcion
$partes = explode("|", $opcion_key);

if (count($partes) !== 4) {
    header("Location: ../../administrador/crear_opciones.php?msg=error");
    exit;
}

$modulo = trim($partes[0]); // por ahora NO se guarda (tu tabla no tiene campo modulo)
$nombre_opcion = trim($partes[1]);
$nombre_controlador = trim($partes[2]);
$nombre_funcion = trim($partes[3]);

// 4) Evitar duplicados: si ya existe esa opción, no la vuelve a crear
$stmt = $conn->prepare("SELECT id_opciones FROM opciones WHERE nombre_controlador = ? LIMIT 1");
$stmt->bind_param("s", $nombre_controlador);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    // Ya existe, lo mandamos con mensaje
    header("Location: ../../administrador/crear_opciones.php?msg=existe");
    exit;
}

// 5) Insertar en BD
$stmt = $conn->prepare("
INSERT INTO opciones (modulo,nombre_opcion,nombre_controlador,nombre_funcion,estado)
VALUES (?,?,?,?,?)
");

$stmt->bind_param(
"sssss",
$modulo,
$nombre_opcion,
$nombre_controlador,
$nombre_funcion,
$estado
);

$stmt->bind_param("ssss", $nombre_opcion, $nombre_controlador, $nombre_funcion, $estado);

if ($stmt->execute()) {
    header("Location: ../../administrador/crear_opciones.php?msg=ok");
} else {
    header("Location: ../../administrador/crear_opciones.php?msg=error");
}
exit;