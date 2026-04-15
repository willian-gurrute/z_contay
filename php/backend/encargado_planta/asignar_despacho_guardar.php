<?php
session_start();

require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../verificar_permiso.php";

verificarPermiso("gestion_despachos");

/*
========================================
1. VALIDAR DATOS DEL FORMULARIO
========================================
*/

$id_factura = $_POST['id_factura'] ?? null;
$id_transportador = $_POST['id_transportador'] ?? null;
$zona_entrega = $_POST['zona_entrega'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_factura || !$id_transportador || !$zona_entrega) {
    header("Location: ../../encargado_planta/asignar_despacho.php?error=1");
    exit();
}

/*
========================================
2. EVITAR DUPLICADOS (MUY IMPORTANTE)
========================================
No permitir que un pedido tenga dos despachos
*/

$sql_verificar = "SELECT id_despacho FROM despacho WHERE id_factura = ?";
$stmt_verificar = $conexion->prepare($sql_verificar);

$stmt_verificar->bind_param("i", $id_factura);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows > 0) {
    die("Este pedido ya tiene un despacho asignado");
}

/*
========================================
3. INSERTAR DESPACHO
========================================
*/

$sql_insertar = "INSERT INTO despacho 
(id_factura, id_usuario, id_transportador, estado, fecha_creacion, zona_entrega)
VALUES (?, ?, ?, 'pendiente', NOW(), ?)";
$stmt_insertar = $conexion->prepare($sql_insertar);

$stmt_insertar->bind_param("iiis", $id_factura, $id_usuario, $id_transportador, $zona_entrega);

if ($stmt_insertar->execute()) {

    /*
    ========================================
    4. (OPCIONAL PERO RECOMENDADO)
    Guardar relación con transportador
    ========================================
    */

    $id_despacho = $stmt_insertar->insert_id;

    // Aquí puedes actualizar la ruta si después la usas
    // o guardar en otra tabla si decides separar transportador

    $sql_asignar_transportador = "UPDATE despacho 
                               
                                 WHERE id_despacho = ?";

  

    $stmt_update = $conexion->prepare($sql_asignar_transportador);
    $stmt_update->bind_param("ii", $id_transportador, $id_despacho);
    $stmt_update->execute();

    /*
    ========================================
    5. REDIRECCIÓN
    ========================================
    */

    header("Location: ../../encargado_planta/gestion_despachos.php?ok=1");
    exit();

} else {
    echo "Error al crear el despacho";
}