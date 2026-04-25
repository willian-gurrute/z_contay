<?php
// Función para enviar una notificación a todos los usuarios de un rol
function notificarRol($conn, $idNotificacion, $idRol)
{
    // Verificar que la notificación esté activa
    $sql = "SELECT estado FROM notificacion WHERE id_notificacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNotificacion);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        $stmt->close();
        return;
    }

    $notificacion = $resultado->fetch_assoc();
    $stmt->close();

    if ($notificacion['estado'] !== 'A') {
        return;
    }

    // Buscar usuarios activos del rol indicado
    $sqlUsuarios = "SELECT id_usuario 
                   FROM usuario 
                   WHERE id_rol = ? 
                   AND estado = 'A'";

    $stmtUsuarios = $conn->prepare($sqlUsuarios);
    $stmtUsuarios->bind_param("i", $idRol);
    $stmtUsuarios->execute();
    $usuarios = $stmtUsuarios->get_result();

    // Insertar una notificación para cada usuario
    while ($usuario = $usuarios->fetch_assoc()) {
        $idUsuario = $usuario['id_usuario'];

        $sqlInsertar = "INSERT INTO notificacion_usuario 
                        (id_notificacion, id_usuario, leida)
                        VALUES (?, ?, 0)";

        $stmtInsertar = $conn->prepare($sqlInsertar);
        $stmtInsertar->bind_param("ii", $idNotificacion, $idUsuario);
        $stmtInsertar->execute();
        $stmtInsertar->close();
    }

    $stmtUsuarios->close();
}


// Función para enviar una notificación a un usuario específico
function notificarUsuario($conn, $idNotificacion, $idUsuario)
{
    // Verificar que la notificación esté activa
    $sql = "SELECT estado FROM notificacion WHERE id_notificacion = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNotificacion);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 0) {
        $stmt->close();
        return;
    }

    $notificacion = $resultado->fetch_assoc();
    $stmt->close();

    if ($notificacion['estado'] !== 'A') {
        return;
    }

    // Insertar notificación para un usuario específico
    $sqlInsertar = "INSERT INTO notificacion_usuario 
                    (id_notificacion, id_usuario, leida)
                    VALUES (?, ?, 0)";

    $stmtInsertar = $conn->prepare($sqlInsertar);
    $stmtInsertar->bind_param("ii", $idNotificacion, $idUsuario);
    $stmtInsertar->execute();
    $stmtInsertar->close();
}
?>