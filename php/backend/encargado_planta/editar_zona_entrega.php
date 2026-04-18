<?php

/*conexion base de datos*/ 


require_once __DIR__ . "/../conexion.php";

//variable donde gurdaremos los datos del transportador//

$transportador = null;

//recibimos el id desde la url ejemplo: editar_zona_entrega.php?id=1//

$id_transportador = $_GET['id'] ?? 0;

//validamos que venga un id correcto //

if (!$id_transportador || !is_numeric($id_transportador)) {
    die("transportador no valido.");
}

//consulta para traer la informacion del transportador junto con el nombre del usuario relacionado//
$sql = "SELECT
             t.id_transportador,
             t.telefono,
             t.tipo_licencia,
             t.estado,
             t.zona_asignada,
             u.nombre_completo
        FROM transportador t
        INNER JOIN usuario u
             ON t.id_usuario = u.id_usuario
        WHERE t.id_transportador = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_transportador);
$stmt->execute();
$res = $stmt->get_result();

//si existe, gurdamos sus datos
//si no, mostranos werror

if ($res && $res->num_rows > 0) {
    $transportador = $res->fetch_assoc();
    }else{
        die("No se encontro el transpotador.");
    }

    $stmt->close();
    ?>