<?php
// Lista roles y cuántos permisos tiene cada uno

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";

// Solo admin
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

$roles = [];

// Traer roles y contar permisos por rol
$sql = "
SELECT 
    r.id_rol,
    r.nombre,
    r.estado,
    COUNT(p.id_permisos) AS total_permisos
FROM rol r
LEFT JOIN permisos p ON p.id_rol = r.id_rol
GROUP BY r.id_rol, r.nombre, r.estado
ORDER BY r.id_rol ASC
";

$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $roles[] = $row;
    }
}