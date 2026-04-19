<?php

/* 
   Conexión a la base de datos
*/
require_once __DIR__ . "/../conexion.php";

/* 
   Tomamos el id del usuario desde la sesión
*/
$id_usuario = $_SESSION['id_usuario'] ?? 0;

/* 
   Variable donde guardaremos la información del perfil
*/
$perfil = [];

/* 
   Consulta:
   - Trae datos del usuario
   - Une con la tabla rol para mostrar el nombre del rol
*/
$sql = "SELECT 
            u.nombre_completo,
            u.correo_electronico,
            u.tipo_documento,
            u.numero_documento,
            u.direccion,
            u.estado,
            r.nombre AS nombre_rol
        FROM usuario u
        INNER JOIN rol r 
            ON u.id_rol = r.id_rol
        WHERE u.id_usuario = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

/* 
   Si encontramos el usuario, guardamos sus datos
*/
if ($resultado && $resultado->num_rows > 0) {
    $perfil = $resultado->fetch_assoc();
} else {
    die("No se pudo cargar la información del perfil.");
}

$stmt->close();
?>