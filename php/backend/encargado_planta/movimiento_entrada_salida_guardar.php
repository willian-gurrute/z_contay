<?php
session_start();

/* 
   Cargamos conexión y validaciones
*/
require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../verificar_permiso.php";

verificarPermiso("control_inventario");

/* 
   Recibimos datos del formulario
*/
$tipo_movimiento = $_POST['tipo_movimiento'] ?? null;
$id_producto = $_POST['id_producto'] ?? null;
$cantidad = $_POST['cantidad'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

/* 
   Validamos datos básicos
*/
if (!$tipo_movimiento || !$id_producto || !$cantidad || !is_numeric($cantidad) || $cantidad <= 0) {
    header("Location: ../../encargado_planta/movimiento_entrada_salida.php?error=datos");
    exit();
}

/* 
   Paso 1: revisar si el producto ya existe en inventario
*/
$sqlInventario = "SELECT id_inventario, cantidad
                  FROM inventario
                  WHERE id_producto = ?
                  LIMIT 1";

$stmtInventario = $conn->prepare($sqlInventario);
$stmtInventario->bind_param("i", $id_producto);
$stmtInventario->execute();
$resInventario = $stmtInventario->get_result();

$inventarioExiste = false;
$cantidadActual = 0;

if ($resInventario && $resInventario->num_rows > 0) {
    $filaInventario = $resInventario->fetch_assoc();
    $inventarioExiste = true;
    $cantidadActual = (int)$filaInventario['cantidad'];
}

$stmtInventario->close();

/* 
   Paso 2: calcular nueva cantidad
*/
$nuevaCantidad = $cantidadActual;

if ($tipo_movimiento === 'entrada') {
    $nuevaCantidad += (int)$cantidad;
} elseif ($tipo_movimiento === 'salida') {
    if ($cantidadActual < (int)$cantidad) {
        header("Location: ../../encargado_planta/movimiento_entrada_salida.php?error=stock");
        exit();
    }
    $nuevaCantidad -= (int)$cantidad;
} else {
    header("Location: ../../encargado_planta/movimiento_entrada_salida.php?error=datos");
    exit();
}

/* 
   Paso 3: actualizar o insertar inventario
*/
if ($inventarioExiste) {
    $sqlActualizar = "UPDATE inventario
                      SET cantidad = ?, ultima_actualizacion = NOW()
                      WHERE id_producto = ?";

    $stmtActualizar = $conn->prepare($sqlActualizar);
    $stmtActualizar->bind_param("ii", $nuevaCantidad, $id_producto);
    $stmtActualizar->execute();
    $stmtActualizar->close();
} else {
    $sqlInsertarInventario = "INSERT INTO inventario (id_producto, cantidad, ultima_actualizacion)
                              VALUES (?, ?, NOW())";

    $stmtInsertarInventario = $conn->prepare($sqlInsertarInventario);
    $stmtInsertarInventario->bind_param("ii", $id_producto, $nuevaCantidad);
    $stmtInsertarInventario->execute();
    $stmtInsertarInventario->close();
}

/* 
   Paso 4: registrar el movimiento en historial
*/
$sqlMovimiento = "INSERT INTO movimiento_inventario
                  (id_producto, tipo_movimiento, cantidad_movimiento, id_usuario, fecha)
                  VALUES (?, ?, ?, ?, NOW())";

$stmtMovimiento = $conn->prepare($sqlMovimiento);
$stmtMovimiento->bind_param("isii", $id_producto, $tipo_movimiento, $cantidad, $id_usuario);

if ($stmtMovimiento->execute()) {
    header("Location: ../../encargado_planta/control_inventario.php?ok=1");
    exit();
} else {
    header("Location: ../../encargado_planta/movimiento_entrada_salida.php?error=general");
    exit();
}
?>