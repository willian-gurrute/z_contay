<?php
// Trae datos necesarios para editar_rol.php

require_once __DIR__ . "/../conexion.php";

$rol = [];
$opciones = [];
$permisos_del_rol = []; // ids de opciones asignadas al rol

// 1) Traer el rol por id
$stmt = $conn->prepare("SELECT id_rol, nombre, estado FROM rol WHERE id_rol = ?");
$stmt->bind_param("i", $id_rol);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows > 0) {
    $rol = $res->fetch_assoc();
}

// 2) Traer opciones activas (para mostrarlas como checkboxes)
$r = $conn->query("SELECT id_opciones, nombre_opcion FROM opciones WHERE estado='A' ORDER BY id_opciones ASC");
if ($r) {
    while ($row = $r->fetch_assoc()) {
        $opciones[] = $row;
    }
}

// 3) Traer permisos actuales del rol
$stmt = $conn->prepare("SELECT id_opciones FROM permisos WHERE id_rol = ?");
$stmt->bind_param("i", $id_rol);
$stmt->execute();
$res2 = $stmt->get_result();
if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        $permisos_del_rol[] = (int)$row['id_opciones'];
    }
}