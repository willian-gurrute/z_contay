<?php
session_start();

require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../verificar_permiso.php";

verificarPermiso("gestion_despachos");

$id_factura = $_POST['id_factura'] ?? null;
$id_despacho = $_POST['id_despacho'] ?? null;
$id_transportador = $_POST['id_transportador'] ?? null;
$zona_entrega = $_POST['zona_entrega'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_factura || !$id_transportador || !$zona_entrega) {
    header("Location: ../../encargado_planta/gestion_despachos.php?error=1");
    exit();
}

/* =========================================
   1. VALIDAR QUE EL TRANSPORTADOR PERTENEZCA A ESA ZONA
========================================= */
$sqlValidar = "SELECT id_transportador
               FROM transportador
               WHERE id_transportador = ?
               AND zona_asignada = ?
               AND estado = 'A'
               LIMIT 1";

$stmtValidar = $conn->prepare($sqlValidar);
$stmtValidar->bind_param("is", $id_transportador, $zona_entrega);
$stmtValidar->execute();
$resValidar = $stmtValidar->get_result();

if (!$resValidar || $resValidar->num_rows === 0) {
    die("El transportador seleccionado no pertenece a la zona elegida.");
}

$stmtValidar->close();

/* =========================================
OBTENER USUARIO DEL TRANSPORTADOR
Y CLIENTE DE LA FACTURA
========================================= */

$idUsuarioTransportador = null;
$idUsuarioCliente = null;

$sqlDatos = "SELECT 
                t.id_usuario AS usuario_transportador,
                u.id_usuario AS usuario_cliente
             FROM factura f
             INNER JOIN cliente c
                ON f.id_cliente = c.id_cliente
             INNER JOIN usuario u
                ON u.numero_documento = c.numero_documento
             INNER JOIN transportador t
                ON t.id_transportador = ?
             WHERE f.id_factura = ?
             LIMIT 1";

$stmtDatos = $conn->prepare($sqlDatos);
$stmtDatos->bind_param("ii", $id_transportador, $id_factura);
$stmtDatos->execute();

$resDatos = $stmtDatos->get_result();

if($filaDatos = $resDatos->fetch_assoc()){
    $idUsuarioTransportador = $filaDatos['usuario_transportador'];
    $idUsuarioCliente = $filaDatos['usuario_cliente'];
}

$stmtDatos->close();

/* =========================================
   2. SI ES REASIGNACIÓN, ACTUALIZAR
========================================= */
if (!empty($id_despacho)) {
    $sqlUpdate = "UPDATE despacho
                  SET id_transportador = ?, zona_entrega = ?, estado = 'asignado', id_usuario = ?
                  WHERE id_despacho = ?";

    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("isii", $id_transportador, $zona_entrega, $id_usuario, $id_despacho);

    if ($stmtUpdate->execute()) {
        header("Location: ../../encargado_planta/gestion_despachos.php?ok=2");
        exit();

    } else {
        die("Error al reasignar el despacho.");
    }
}

/* =========================================
   3. SI ES NUEVO, VERIFICAR DUPLICADO
========================================= */
$sql_verificar = "SELECT id_despacho FROM despacho WHERE id_factura = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("i", $id_factura);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows > 0) {
    die("Este pedido ya tiene un despacho asignado.");
}

$stmt_verificar->close();

/* =========================================
   4. INSERTAR NUEVO DESPACHO
========================================= */
$sql_insertar = "INSERT INTO despacho 
    (id_factura, id_usuario, id_transportador, estado, fecha_creacion, zona_entrega)
    VALUES (?, ?, ?, 'asignado', NOW(), ?)";

$stmt_insertar = $conn->prepare($sql_insertar);
$stmt_insertar->bind_param("iiis", $id_factura, $id_usuario, $id_transportador, $zona_entrega);

if ($stmt_insertar->execute()) {

    header("Location: ../../encargado_planta/gestion_despachos.php?ok=1");
    exit();

} else {
    die("Error al crear el despacho.");
}
?>