<?php
// php/backend/administrador/gestion_usuarios.php
// Trae lista de usuarios + rol para gestion_usuarios.php


//inicia sesion si no esta iniciada.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//conexion a la base de datos.
require_once __DIR__ . "/../conexion.php";

// se verifica que el usuario este logueado y que tenga rol administrador.
if (!isset($_SESSION['id_usuario']) || (($_SESSION['id_rol'] ?? 0) != 1)) {
    header("Location: ../../login/inicio-seccion.php");
    exit;
}

//arreglo donde se guarda los usuarios.
$usuarios = [];

//consulta para traer usuarios y su rol.
$sql = "
SELECT 
    u.id_usuario,
    u.nombre_completo,
    u.correo_electronico,
    u.numero_documento,
    u.estado,
    r.nombre AS nombre_rol
FROM usuario u
JOIN rol r ON r.id_rol = u.id_rol
ORDER BY u.id_usuario DESC
";

//ejecutar consulta 
$res = $conn->query($sql);

//guardar resultados en el arreglo.
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $usuarios[] = $row;
    }
}