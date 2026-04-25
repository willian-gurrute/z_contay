<?php
require_once __DIR__ . "/../conexion.php";

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_factura = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$factura = null;
$detalle_factura = [];

if ($id_factura <= 0) {
    header("Location: ../../cliente/historial_pedidos.php");
    exit;
}

/* Buscar documento del usuario logueado */
$sqlUsuario = "SELECT numero_documento
               FROM usuario
               WHERE id_usuario = ?
               LIMIT 1";

$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $id_usuario);
$stmtUsuario->execute();
$resUsuario = $stmtUsuario->get_result();

$numero_documento = "";

if ($filaUsuario = $resUsuario->fetch_assoc()) {
    $numero_documento = $filaUsuario['numero_documento'];
}

$stmtUsuario->close();

/* Traer factura solo si pertenece al cliente logueado */
$sqlFactura = "SELECT 
                    f.id_factura,
                    f.fecha,
                    f.subtotal,
                    f.iva,
                    f.total,
                    f.metodo_pago,
                    f.estado_factura,
                    f.tipo_venta,
                    c.nombre_completo AS cliente,
                    c.numero_documento,
                    c.telefono
               FROM factura f
               INNER JOIN cliente c 
                    ON f.id_cliente = c.id_cliente
               WHERE f.id_factura = ?
               AND c.numero_documento = ?
               LIMIT 1";

$stmtFactura = $conn->prepare($sqlFactura);
$stmtFactura->bind_param("is", $id_factura, $numero_documento);
$stmtFactura->execute();
$resFactura = $stmtFactura->get_result();

if ($resFactura && $resFactura->num_rows > 0) {
    $factura = $resFactura->fetch_assoc();
}

$stmtFactura->close();

if (!$factura) {
    header("Location: ../../cliente/historial_pedidos.php");
    exit;
}

/* Detalle de productos */
$sqlDetalle = "SELECT 
                    p.nombre_producto,
                    df.cantidad,
                    df.precio_unitario,
                    df.subtotal
               FROM detalle_factura df
               INNER JOIN producto p 
                    ON df.id_producto = p.id_producto
               WHERE df.id_factura = ?";

$stmtDetalle = $conn->prepare($sqlDetalle);
$stmtDetalle->bind_param("i", $id_factura);
$stmtDetalle->execute();
$resDetalle = $stmtDetalle->get_result();

while ($fila = $resDetalle->fetch_assoc()) {
    $detalle_factura[] = $fila;
}

$stmtDetalle->close();


/* Datos de la empresa */
$sqlEmpresa = "
    SELECT nombre, nit, direccion, telefono, ciudad, departamento, horario_atencion
    FROM empresa
    WHERE id_empresa = 1
    LIMIT 1
";

$resEmpresa = $conn->query($sqlEmpresa);
$empresa = $resEmpresa ? $resEmpresa->fetch_assoc() : null;

?>