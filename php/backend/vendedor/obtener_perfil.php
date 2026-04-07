<?php
// Incluimos la conexión a la base de datos
require_once __DIR__ . "/../conexion.php";

// Verificamos si existe una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    die("Error: no hay una sesión activa.");
}

// Guardamos el id del usuario logueado
$idUsuario = $_SESSION['id_usuario'];

// Consulta para traer los datos del perfil
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

// Verificamos si hubo error al preparar
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Asociamos el id del usuario al signo ?
$stmt->bind_param("i", $idUsuario);

// Ejecutamos la consulta
$stmt->execute();

// Obtenemos el resultado
$resultado = $stmt->get_result();

// Verificamos si encontró el usuario
if ($resultado->num_rows > 0) {
    $perfil = $resultado->fetch_assoc();
} else {
    die("Error: no se encontró la información del usuario.");
}

// Cerramos la consulta
$stmt->close();
?>
