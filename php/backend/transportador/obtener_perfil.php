<?php
require_once __DIR__ . "/../conexion.php";

$idUsuario = $_SESSION['id_usuario'] ?? 0;
$perfil = [];

$sqlPerfil = "SELECT 
                u.id_usuario,
                u.nombre_completo,
                u.correo_electronico,
                u.tipo_documento,
                u.numero_documento,
                u.direccion,
                u.estado,
                r.nombre AS nombre_rol
              FROM usuario u
              INNER JOIN rol r ON u.id_rol = r.id_rol
              WHERE u.id_usuario = ?
              LIMIT 1";

$stmtPerfil = $conn->prepare($sqlPerfil);

if ($stmtPerfil) {
    $stmtPerfil->bind_param("i", $idUsuario);
    $stmtPerfil->execute();
    $resPerfil = $stmtPerfil->get_result();

    if ($resPerfil && $resPerfil->num_rows > 0) {
        $perfil = $resPerfil->fetch_assoc();
    }

    $stmtPerfil->close();
}
?>