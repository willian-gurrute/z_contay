<?php
session_start();

require_once __DIR__ . "/../conexion.php";

// Mostrar errores de MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Validar sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../vendedor/pedidos_clientes.php");
    exit();
}

// Validar id del pedido
if (!isset($_POST['id_pedido']) || empty($_POST['id_pedido'])) {
    header("Location: ../../vendedor/pedidos_clientes.php");
    exit();
}

$idUsuario = intval($_SESSION['id_usuario']);
$idPedido  = intval($_POST['id_pedido']);

try {
    // Iniciar transacción
    $conn->begin_transaction();

    // =========================================
    // 1. CONSULTAR EL PEDIDO
    // =========================================
    $sqlPedido = "SELECT *
                  FROM pedido
                  WHERE id_pedido = ?";

    $stmtPedido = $conn->prepare($sqlPedido);
    $stmtPedido->bind_param("i", $idPedido);
    $stmtPedido->execute();
    $resPedido = $stmtPedido->get_result();

    if ($resPedido->num_rows === 0) {
        throw new Exception("El pedido no existe.");
    }

    $pedido = $resPedido->fetch_assoc();
    $stmtPedido->close();

    // =========================================
    // 2. VALIDAR QUE NO EXISTA FACTURA
    // =========================================
    $sqlFacturaExistente = "SELECT id_factura
                            FROM factura
                            WHERE id_pedido = ?";

    $stmtFacturaExistente = $conn->prepare($sqlFacturaExistente);
    $stmtFacturaExistente->bind_param("i", $idPedido);
    $stmtFacturaExistente->execute();
    $resFacturaExistente = $stmtFacturaExistente->get_result();

    if ($resFacturaExistente->num_rows > 0) {
    $stmtFacturaExistente->close();
    $conn->close();
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=ya_facturado");
    exit();
    }

    $stmtFacturaExistente->close();

    // =========================================
    // 3. OBTENER DETALLE DEL PEDIDO
    // =========================================
    $sqlDetallePedido = "SELECT id_producto, cantidad, precio_unitario, subtotal
                         FROM detalle_pedido
                         WHERE id_pedido = ?";

    $stmtDetallePedido = $conn->prepare($sqlDetallePedido);
    $stmtDetallePedido->bind_param("i", $idPedido);
    $stmtDetallePedido->execute();
    $resDetallePedido = $stmtDetallePedido->get_result();

    $productos = [];
    while ($fila = $resDetallePedido->fetch_assoc()) {
        $productos[] = $fila;
    }
    $stmtDetallePedido->close();

    if (empty($productos)) {
        throw new Exception("El pedido no tiene productos en detalle_pedido.");
    }

    // =========================================
    // 4. CREAR FACTURA
    // =========================================
    $sqlInsertFactura = "INSERT INTO factura (
                            subtotal,
                            iva,
                            total,
                            metodo_pago,
                            estado_factura,
                            tipo_venta,
                            id_cliente,
                            id_usuario,
                            id_empresa,
                            id_pedido
                        ) VALUES (?, ?, ?, 'tarjeta', 'pagada', 'pedido', ?, ?, 1, ?)";

    $stmtInsertFactura = $conn->prepare($sqlInsertFactura);
    $stmtInsertFactura->bind_param(
        "dddiii",
        $pedido['subtotal'],
        $pedido['iva'],
        $pedido['total'],
        $pedido['id_cliente'],
        $idUsuario,
        $idPedido
    );
    $stmtInsertFactura->execute();

    $idFactura = $stmtInsertFactura->insert_id;
    $stmtInsertFactura->close();

    // =========================================
    // 5. INSERTAR DETALLE DE FACTURA
    // =========================================
    $sqlInsertDetalleFactura = "INSERT INTO detalle_factura (
                                    id_factura,
                                    id_producto,
                                    cantidad,
                                    precio_unitario,
                                    subtotal
                                ) VALUES (?, ?, ?, ?, ?)";

    $stmtInsertDetalleFactura = $conn->prepare($sqlInsertDetalleFactura);

    foreach ($productos as $producto) {
        $stmtInsertDetalleFactura->bind_param(
            "iiidd",
            $idFactura,
            $producto['id_producto'],
            $producto['cantidad'],
            $producto['precio_unitario'],
            $producto['subtotal']
        );
        $stmtInsertDetalleFactura->execute();
    }

    $stmtInsertDetalleFactura->close();

    // =========================================
    // 6. REGISTRAR MOVIMIENTO CONTABLE
    // =========================================
    $sqlMovimientoContable = "INSERT INTO movimiento_contable (
                                tipo,
                                descripcion,
                                monto,
                                id_usuario
                            ) VALUES ('ingreso', 'Pedido web facturado desde módulo vendedor', ?, ?)";

    $stmtMovimientoContable = $conn->prepare($sqlMovimientoContable);
    $stmtMovimientoContable->bind_param("di", $pedido['total'], $idUsuario);
    $stmtMovimientoContable->execute();
    $stmtMovimientoContable->close();

    // =========================================
    // 7. REGISTRAR MOVIMIENTO DE INVENTARIO
    // Y DESCONTAR INVENTARIO
    // =========================================
    $sqlMovimientoInventario = "INSERT INTO movimiento_inventario (
                                    id_producto,
                                    tipo_movimiento,
                                    cantidad_movimiento,
                                    id_usuario
                                ) VALUES (?, 'salida', ?, ?)";

    $stmtMovimientoInventario = $conn->prepare($sqlMovimientoInventario);

    $sqlActualizarInventario = "UPDATE inventario
                                SET cantidad = cantidad - ?
                                WHERE id_producto = ?";

    $stmtActualizarInventario = $conn->prepare($sqlActualizarInventario);

    foreach ($productos as $producto) {
        $stmtMovimientoInventario->bind_param(
            "iii",
            $producto['id_producto'],
            $producto['cantidad'],
            $idUsuario
        );
        $stmtMovimientoInventario->execute();

        $stmtActualizarInventario->bind_param(
            "ii",
            $producto['cantidad'],
            $producto['id_producto']
        );
        $stmtActualizarInventario->execute();
    }

    $stmtMovimientoInventario->close();
    $stmtActualizarInventario->close();

    // =========================================
    // 8. ACTUALIZAR PEDIDO
    // =========================================
    $sqlActualizarPedido = "UPDATE pedido
                            SET estado = 'facturado',
                                id_usuario = ?
                            WHERE id_pedido = ?";

    $stmtActualizarPedido = $conn->prepare($sqlActualizarPedido);
    $stmtActualizarPedido->bind_param("ii", $idUsuario, $idPedido);
    $stmtActualizarPedido->execute();
    $stmtActualizarPedido->close();

    // Confirmar transacción
    $conn->commit();
    $conn->close();

    header("Location: ../../vendedor/procesar_pedido_vendedor.php?id=" . $idPedido . "&mensaje=tomado");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    header("Location: ../../vendedor/pedidos_clientes.php?mensaje=error_factura");
    exit();
}
?>