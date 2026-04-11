<?php
session_start();

$msg = $_GET['msg'] ?? '';
$idFactura = $_GET['id_factura'] ?? '';

$mensaje = "";
$tipoMensaje = "";

if ($msg === 'ok') {
    $mensaje = "Venta registrada correctamente.";
    $tipoMensaje = "success";
} elseif ($msg === 'stock') {
    $mensaje = "No hay stock suficiente para uno de los productos.";
    $tipoMensaje = "error";
} elseif ($msg === 'cliente') {
    $mensaje = "Debes completar los datos del cliente.";
    $tipoMensaje = "error";
} elseif ($msg === 'producto') {
    $mensaje = "Debes agregar al menos un producto válido.";
    $tipoMensaje = "error";
} elseif ($msg === 'tipo_venta') {
    $mensaje = "El tipo de venta no es válido.";
    $tipoMensaje = "error";
} elseif ($msg === 'metodo_pago') {
    $mensaje = "El método de pago no es válido.";
    $tipoMensaje = "error";
} elseif ($msg === 'sesion') {
    $mensaje = "La sesión no está activa.";
    $tipoMensaje = "error";
} elseif ($msg === 'error') {
    $mensaje = "Ocurrió un error al registrar la venta.";
    $tipoMensaje = "error";
}

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
$barrioCliente = trim($_POST['barrio_cliente'] ?? '');
$ciudadCliente = trim($_POST['ciudad_cliente'] ?? '');
$referenciaCliente = trim($_POST['referencia_cliente'] ?? '');
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
                         (id_cliente, direccion, barrio, ciudad, referencia, es_principal, estado)
                         VALUES (?, ?, ?, ?, ?, 1, 'A')";

        $stmtDireccion = $conn->prepare($sqlDireccion);

        if (!$stmtDireccion) {
            volverConMensaje("error");
        }

        $stmtDireccion->bind_param(
            "issss",
            $idCliente,
            $direccionCliente,
            $barrioCliente,
            $ciudadCliente,
            $referenciaCliente
        );

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
                                       SET direccion = ?, barrio = ?, ciudad = ?, referencia = ?
                                       WHERE id_direccion = ?";

                $stmtUpdateDireccion = $conn->prepare($sqlUpdateDireccion);

                if ($stmtUpdateDireccion) {
                    $stmtUpdateDireccion->bind_param(
                        "ssssi",
                        $direccionCliente,
                        $barrioCliente,
                        $ciudadCliente,
                        $referenciaCliente,
                        $idDireccion
                    );
                    $stmtUpdateDireccion->execute();
                }
            } else {
                $sqlInsertDireccion = "INSERT INTO cliente_direccion
                                       (id_cliente, direccion, barrio, ciudad, referencia, es_principal, estado)
                                       VALUES (?, ?, ?, ?, ?, 1, 'A')";

                $stmtInsertDireccion = $conn->prepare($sqlInsertDireccion);

                if ($stmtInsertDireccion) {
                    $stmtInsertDireccion->bind_param(
                        "issss",
                        $idCliente,
                        $direccionCliente,
                        $barrioCliente,
                        $ciudadCliente,
                        $referenciaCliente
                    );
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
               VALUES (NOW(), ?, ?, ?, ?, 'pagada', ?, ?, ?, 1)";

$stmtFactura = $conn->prepare($sqlFactura);

if (!$stmtFactura) {
    volverConMensaje("error");
}

$stmtFactura->bind_param(
    "dddssii",
    $subtotalGeneral,
    $iva,
    $total,
    $metodoPago,
    $tipoVenta,
    $idCliente,
    $idUsuario
);

if (!$stmtFactura->execute()) {
    volverConMensaje("error");
}

$idFactura = $conn->insert_id;

// ===============================
// Si la venta es tipo pedido, también crear registro en pedido
// ===============================
if ($tipoVenta === 'pedido') {

    $observacionesPedido = "Pedido generado desde venta del vendedor. Factura N° " . $idFactura;

    $sqlPedido = "INSERT INTO pedido
                  (fecha, id_cliente, id_usuario, estado, estado_pago, observaciones, subtotal, iva, total)
                  VALUES (NOW(), ?, ?, 'facturado', 'pagado', ?, ?, ?, ?)";

    $stmtPedido = $conn->prepare($sqlPedido);

    if (!$stmtPedido) {
        volverConMensaje("error");
    }

    $stmtPedido->bind_param(
        "iisddd",
        $idCliente,
        $idUsuario,
        $observacionesPedido,
        $subtotalGeneral,
        $iva,
        $total
    );

    if (!$stmtPedido->execute()) {
        volverConMensaje("error");
    }

    // Obtener el id del pedido recién creado
    $idPedido = $conn->insert_id;

    // Actualizar la factura para enlazarla con el pedido
    $sqlActualizarFacturaPedido = "UPDATE factura
                                   SET id_pedido = ?
                                   WHERE id_factura = ?";

    $stmtActualizarFacturaPedido = $conn->prepare($sqlActualizarFacturaPedido);

    if (!$stmtActualizarFacturaPedido) {
        volverConMensaje("error");
    }

    $stmtActualizarFacturaPedido->bind_param("ii", $idPedido, $idFactura);

    if (!$stmtActualizarFacturaPedido->execute()) {
        volverConMensaje("error");
    }
}

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

// Redirigir al formulario de venta enviando el ID de la factura recién creada
header("Location: ../../vendedor/registrar_venta.php?msg=ok&id_factura=" . $idFactura);
exit;
?>