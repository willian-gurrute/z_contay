<?php
require_once __DIR__ . "/../conexion.php";

// Tomamos el id del vendedor logueado
$idUsuario = $_SESSION['id_usuario'] ?? 0;

// ===============================
// 1. Pedidos disponibles
// ===============================
$pedidosDisponibles = [];

$sqlDisponibles = "SELECT 
                        p.id_pedido,
                        p.fecha,
                        p.estado,
                        p.total,
                        c.nombre_completo AS cliente,
                        c.numero_documento
                   FROM pedido p
                   INNER JOIN cliente c ON p.id_cliente = c.id_cliente
                   WHERE p.id_usuario IS NULL
                   AND p.estado = 'pendiente'
                   ORDER BY p.fecha DESC";

$resDisponibles = $conn->query($sqlDisponibles);

if ($resDisponibles) {
    while ($fila = $resDisponibles->fetch_assoc()) {
        $pedidosDisponibles[] = $fila;
    }
}

// ===============================
// 2. Mis pedidos
// ===============================
$misPedidos = [];

$sqlMisPedidos = "SELECT 
                    p.id_pedido,
                    p.fecha,
                    p.estado,
                    p.total,
                    c.nombre_completo AS cliente,
                    c.numero_documento
                  FROM pedido p
                  INNER JOIN cliente c ON p.id_cliente = c.id_cliente
                  WHERE p.id_usuario = ?
                  ORDER BY p.fecha DESC";

$stmtMisPedidos = $conn->prepare($sqlMisPedidos);

if ($stmtMisPedidos) {
    $stmtMisPedidos->bind_param("i", $idUsuario);
    $stmtMisPedidos->execute();
    $resMisPedidos = $stmtMisPedidos->get_result();

    while ($fila = $resMisPedidos->fetch_assoc()) {
        $misPedidos[] = $fila;
    }

    $stmtMisPedidos->close();
}
?>