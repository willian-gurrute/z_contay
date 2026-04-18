<?php

require_once __DIR__ . "/../conexion.php";

$zonas = ['Norte', 'Sur', 'Centro', 'Oriente', 'Occidente'];

$sql = "SELECT 
            t.id_transportador,
            u.nombre_completo,
            t.telefono,
            t.tipo_licencia,
            t.zona_asignada,
            t.estado
        FROM transportador t
        INNER JOIN usuario u ON t.id_usuario = u.id_usuario
";

$res = $conn->query($sql);

$transportadores = [];

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {

        // estado visual
        $estadoClase = ($row['estado'] == 'A') ? 'estado-activo' : 'estado-inactivo';

        $transportadores[] = [
            'id' => $row['id_transportador'],
            'nombre' => $row['nombre_completo'],
            'telefono' => $row['telefono'],
            'licencia' => $row['tipo_licencia'],
            'zona' => $row['zona_asignada'] ?? 'Sin asignar',
            'estado' => $row['estado'],
            'estado_clase' => $estadoClase
        ];
    }
}