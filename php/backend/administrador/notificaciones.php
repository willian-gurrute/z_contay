<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// seguridad
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: /prototipo/php/login/inicio-seccion.php");
    exit;
}

$notificaciones = [];

$sql = "
    SELECT 
        n.id_notificacion,
        n.nombre,
        n.descripcion,
        n.tipo,
        n.estado,
        r.nombre AS nombre_rol
    FROM notificacion n
    INNER JOIN notificacion_rol nr ON nr.id_notificacion = n.id_notificacion
    INNER JOIN rol r ON r.id_rol = nr.id_rol
    ORDER BY n.id_notificacion ASC, r.nombre ASC
";

$res = $conn->query($sql);

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $notificaciones[] = $row;
    }
}