<?php
// Incluimos la conexión a la base de datos
require_once __DIR__ . "/../conexion.php";

// Verificamos si existe una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    die("Error: no hay una sesión activa.");
}

// Guardamos el id del usuario logueado
$idUsuario = $_SESSION['id_usuario'];

// Consulta para traer los datos del perfil del cliente
$sql = "SELECT 
            u.id_usuario,
            u.tipo_documento,
            u.numero_documento,
            u.nombre_completo,
            u.correo_electronico,
            u.direccion,
            u.fecha_registro,
            u.estado,
            r.nombre AS nombre_rol
        FROM usuario u
        INNER JOIN rol r ON u.id_rol = r.id_rol
        WHERE u.id_usuario = ?";

// Preparamos la consulta
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $perfil = $resultado->fetch_assoc();
} else {
    die("Error: no se encontró la información del cliente.");
}

$stmt->close();
?>