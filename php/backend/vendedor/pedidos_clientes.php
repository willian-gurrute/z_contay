<?php
require_once __DIR__ . "/../conexion.php";

// Tomamos el id del vendedor logueado
$idUsuario = $_SESSION['id_usuario'] ?? 0;

// ===============================
// 1. Pedidos disponibles
// Solo pedidos del cliente:
// - pendientes
// - pagados
// - sin vendedor asignado
// - sin factura creada
// ===============================
$pedidosDisponibles = [];

$sqlDisponibles = "SELECT 
                        p.id_pedido,
                        p.fecha,
                        p.estado,
                        p.estado_pago,
                        p.total,
                        c.nombre_completo AS cliente,
                        c.numero_documento,
                        f.id_factura
                   FROM pedido p
                   INNER JOIN cliente c ON p.id_cliente = c.id_cliente
                   LEFT JOIN factura f ON f.id_pedido = p.id_pedido
                   WHERE p.id_usuario IS NULL
                   AND p.estado = 'pendiente'
                   AND p.estado_pago = 'pagado'
                   AND f.id_factura IS NULL
                   ORDER BY p.fecha DESC";

$resDisponibles = $conn->query($sqlDisponibles);

if ($resDisponibles) {
    while ($fila = $resDisponibles->fetch_assoc()) {
        $pedidosDisponibles[] = $fila;
    }
}

// ===============================
// 2. Mis pedidos
// Mostrar todos los pedidos del vendedor logueado
// Traemos también factura si existe
// ===============================
$misPedidos = [];

$sqlMisPedidos = "SELECT 
                    p.id_pedido,
                    p.fecha,
                    p.estado,
                    p.estado_pago,
                    p.total,
                    c.nombre_completo AS cliente,
                    c.numero_documento,
                    f.id_factura
                  FROM pedido p
                  INNER JOIN cliente c ON p.id_cliente = c.id_cliente
                  LEFT JOIN factura f ON f.id_pedido = p.id_pedido
                  WHERE p.id_usuario = ?
                  AND p.estado = 'facturado'
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