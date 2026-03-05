<?php
// php/backend/administrador/usuario_editar_datos.php
// Trae el usuario por id y lista de roles

require_once __DIR__ . "/../conexion.php";

$usuario = [];
$roles = [];

// 1) Traer usuario por id
$stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) {
    $usuario = $res->fetch_assoc();
}

// 2) Traer roles activos
$r = $conn->query("SELECT id_rol, nombre FROM rol WHERE estado = 'A' ORDER BY id_rol ASC");
if ($r) {
    while ($row = $r->fetch_assoc()) {
        $roles[] = $row;
    }
}