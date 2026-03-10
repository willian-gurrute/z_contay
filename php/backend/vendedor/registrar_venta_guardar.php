<?php
session_start();

require_once __DIR__ . "/../conexion.php";

// Función para regresar con mensaje
function volverConMensaje($codigo)
{
    header("Location: ../../vendedor/registrar_venta.php?msg=" . $codigo);
    exit;
}

// Verificar sesión activa
if (!isset($_SESSION['id_usuario'])) {
    volverConMensaje("sesion");
}

$idUsuario = $_SESSION['id_usuario'];

// Recibir datos del formulario
$idCliente = trim($_POST['id_cliente'] ?? '');
$documento = trim($_POST['documento'] ?? '');
$nombreCliente = trim($_POST['nombre_cliente'] ?? '');
$telefonoCliente = trim($_POST['telefono_cliente'] ?? '');
$direccionCliente = trim($_POST['direccion_cliente'] ?? '');
$tipoVenta = trim($_POST['tipo_venta'] ?? 'directa');
$metodoPago = trim($_POST['metodo_pago'] ?? 'efectivo');

$idProductos = $_POST['id_producto'] ?? [];
$cantidades = $_POST['cantidad'] ?? [];

// Validaciones básicas
if ($documento === '' || $nombreCliente === '') {
    volverConMensaje("cliente");
}

if (!in_array($tipoVenta, ['directa', 'pedido'], true)) {
    volverConMensaje("tipo_venta");
}

if (!in_array($metodoPago, ['efectivo', 'tarjeta'], true)) {
    volverConMensaje("metodo_pago");
}

if (empty($idProductos) || empty($cantidades)) {
    volverConMensaje("producto");
}

// ===============================
// Registrar o actualizar cliente
// ===============================
if (empty($idCliente)) {
    $sqlInsertCliente = "INSERT INTO cliente 
                         (tipo_documento, numero_documento, nombre_completo, telefono, estado)
                         VALUES ('CC', ?, ?, ?, 'A')";

    $stmtCliente = $conn->prepare($sqlInsertCliente);

    if (!$stmtCliente) {
        volverConMensaje("error");
    }

    $stmtCliente->bind_param("sss", $documento, $nombreCliente, $telefonoCliente);

    if (!$stmtCliente->execute()) {
        volverConMensaje("error");
    }

    $idCliente = $conn->insert_id;

    if ($direccionCliente !== '') {
        $sqlDireccion = "INSERT INTO cliente_direccion 
                         (id_cliente, direccion, es_principal, estado)
                         VALUES (?, ?, 1, 'A')";

        $stmtDireccion = $conn->prepare($sqlDireccion);

        if (!$stmtDireccion) {
            volverConMensaje("error");
        }

        $stmtDireccion->bind_param("is", $idCliente, $direccionCliente);

        if (!$stmtDireccion->execute()) {
            volverConMensaje("error");
        }
    }

} else {
    $sqlUpdateCliente = "UPDATE cliente
                         SET nombre_completo = ?, telefono = ?
                         WHERE id_cliente = ?";

    $stmtUpdateCliente = $conn->prepare($sqlUpdateCliente);

    if ($stmtUpdateCliente) {
        $stmtUpdateCliente->bind_param("ssi", $nombreCliente, $telefonoCliente, $idCliente);
        $stmtUpdateCliente->execute();
    }

    if ($direccionCliente !== '') {
        $sqlBuscarDireccion = "SELECT id_direccion
                               FROM cliente_direccion
                               WHERE id_cliente = ? AND es_principal = 1
                               LIMIT 1";

        $stmtBuscarDireccion = $conn->prepare($sqlBuscarDireccion);

        if ($stmtBuscarDireccion) {
            $stmtBuscarDireccion->bind_param("i", $idCliente);
            $stmtBuscarDireccion->execute();
            $resDireccion = $stmtBuscarDireccion->get_result();

            if ($resDireccion && $resDireccion->num_rows > 0) {
                $filaDireccion = $resDireccion->fetch_assoc();
                $idDireccion = $filaDireccion['id_direccion'];

                $sqlUpdateDireccion = "UPDATE cliente_direccion
                                       SET direccion = ?
                                       WHERE id_direccion = ?";

                $stmtUpdateDireccion = $conn->prepare($sqlUpdateDireccion);

                if ($stmtUpdateDireccion) {
                    $stmtUpdateDireccion->bind_param("si", $direccionCliente, $idDireccion);
                    $stmtUpdateDireccion->execute();
                }
            } else {
                $sqlInsertDireccion = "INSERT INTO cliente_direccion
                                       (id_cliente, direccion, es_principal, estado)
                                       VALUES (?, ?, 1, 'A')";

                $stmtInsertDireccion = $conn->prepare($sqlInsertDireccion);

                if ($stmtInsertDireccion) {
                    $stmtInsertDireccion->bind_param("is", $idCliente, $direccionCliente);
                    $stmtInsertDireccion->execute();
                }
            }
        }
    }
}

// ===============================
// Validar productos, stock y calcular totales
// ===============================
$detalleVenta = [];
$subtotalGeneral = 0;
$iva = 0;

for ($i = 0; $i < count($idProductos); $i++) {
    $idProducto = (int)($idProductos[$i] ?? 0);
    $cantidad = (int)($cantidades[$i] ?? 0);

    if ($idProducto <= 0 || $cantidad <= 0) {
        continue;
    }

    $sqlProducto = "SELECT 
                        p.nombre_producto,
                        p.precio,
                        i.cantidad AS stock_actual,
                        i.stock_minimo
                    FROM producto p
                    INNER JOIN inventario i ON p.id_producto = i.id_producto
                    WHERE p.id_producto = ?
                    AND p.estado = 'A'
                    LIMIT 1";

    $stmtProducto = $conn->prepare($sqlProducto);

    if (!$stmtProducto) {
        volverConMensaje("error");
    }

    $stmtProducto->bind_param("i", $idProducto);
    $stmtProducto->execute();
    $resProducto = $stmtProducto->get_result();

    if (!$resProducto || $resProducto->num_rows === 0) {
        volverConMensaje("producto");
    }

    $producto = $resProducto->fetch_assoc();

    $nombreProducto = $producto['nombre_producto'];
    $precioUnitario = (float)$producto['precio'];
    $stockActual = (int)$producto['stock_actual'];
    $stockMinimo = (int)$producto['stock_minimo'];

    // Validar que sí alcance el inventario
    if ($cantidad > $stockActual) {
        volverConMensaje("stock");
    }

    $subtotal = $precioUnitario * $cantidad;

    $detalleVenta[] = [
        'id_producto' => $idProducto,
        'nombre_producto' => $nombreProducto,
        'cantidad' => $cantidad,
        'precio_unitario' => $precioUnitario,
        'subtotal' => $subtotal,
        'stock_actual' => $stockActual,
        'stock_minimo' => $stockMinimo
    ];

    $subtotalGeneral += $subtotal;
}

if (empty($detalleVenta)) {
    volverConMensaje("producto");
}

// Calcular total
$total = $subtotalGeneral + $iva;

// ===============================
// Insertar factura
// ===============================
$sqlFactura = "INSERT INTO factura
               (fecha, subtotal, iva, total, metodo_pago, estado_factura, tipo_venta, id_cliente, id_usuario, id_empresa)
               VALUES (NOW(), ?, ?, ?, ?, 'emitida', ?, ?, ?, 1)";

$stmtFactura = $conn->prepare($sqlFactura);

if (!$stmtFactura) {
    volverConMensaje("error");
}

$stmtFactura->bind_param("dddssii", $subtotalGeneral, $iva, $total, $metodoPago, $tipoVenta, $idCliente, $idUsuario);

if (!$stmtFactura->execute()) {
    volverConMensaje("error");
}

$idFactura = $conn->insert_id;

// ===============================
// Registrar ingreso en movimiento_contable
// ===============================
$sqlMovimiento = "INSERT INTO movimiento_contable 
                  (fecha, tipo, descripcion, monto, id_usuario)
                  VALUES (NOW(), 'ingreso', 'Venta registrada desde módulo vendedor', ?, ?)";

$stmtMovimiento = $conn->prepare($sqlMovimiento);

if ($stmtMovimiento) {
    $stmtMovimiento->bind_param("di", $total, $idUsuario);
    $stmtMovimiento->execute();
}

// ===============================
// Insertar detalles de factura
// ===============================
$sqlDetalle = "INSERT INTO detalle_factura
               (id_factura, id_producto, cantidad, precio_unitario, subtotal)
               VALUES (?, ?, ?, ?, ?)";

$stmtDetalle = $conn->prepare($sqlDetalle);

if (!$stmtDetalle) {
    volverConMensaje("error");
}

// ===============================
// Descontar inventario y registrar movimiento
// ===============================
$sqlActualizarInventario = "UPDATE inventario
                            SET cantidad = cantidad - ?
                            WHERE id_producto = ?";

$stmtInventario = $conn->prepare($sqlActualizarInventario);

if (!$stmtInventario) {
    volverConMensaje("error");
}

$sqlMovimientoInventario = "INSERT INTO movimiento_inventario
                            (id_producto, tipo_movimiento, cantidad_movimiento, fecha, id_usuario)
                            VALUES (?, 'salida', ?, NOW(), ?)";

$stmtMovimientoInventario = $conn->prepare($sqlMovimientoInventario);

if (!$stmtMovimientoInventario) {
    volverConMensaje("error");
}

foreach ($detalleVenta as $item) {
    // Insertar detalle de factura
    $stmtDetalle->bind_param(
        "iiidd",
        $idFactura,
        $item['id_producto'],
        $item['cantidad'],
        $item['precio_unitario'],
        $item['subtotal']
    );

    if (!$stmtDetalle->execute()) {
        volverConMensaje("error");
    }

    // Descontar inventario
    $stmtInventario->bind_param(
        "ii",
        $item['cantidad'],
        $item['id_producto']
    );

    if (!$stmtInventario->execute()) {
        volverConMensaje("error");
    }

    // Registrar movimiento de inventario
    $stmtMovimientoInventario->bind_param(
        "iii",
        $item['id_producto'],
        $item['cantidad'],
        $idUsuario
    );

    if (!$stmtMovimientoInventario->execute()) {
        volverConMensaje("error");
    }
}

volverConMensaje("ok");
?>