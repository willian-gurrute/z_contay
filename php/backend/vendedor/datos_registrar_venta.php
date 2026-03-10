<?php
// php/backend/vendedor/datos_registrar_venta.php

require_once __DIR__ . "/../conexion.php";

// ===============================
// Productos activos
// ===============================
$productos = [];

$sqlProductos = "SELECT id_producto, nombre_producto, precio
                 FROM producto
                 WHERE estado = 'A'
                 ORDER BY nombre_producto ASC";

$resProductos = $conn->query($sqlProductos);

if ($resProductos) {
    while ($fila = $resProductos->fetch_assoc()) {
        $productos[] = $fila;
    }
}

// ===============================
// Cliente buscado por documento
// ===============================
$clienteEncontrado = false;
$idCliente = "";
$documentoCliente = $_GET['documento'] ?? "";
$nombreCliente = "";
$telefonoCliente = "";
$direccionCliente = "";
$mensajeCliente = "";

// Solo buscamos si el documento no viene vacío
if (!empty($documentoCliente)) {

    $sqlCliente = "SELECT 
                        c.id_cliente,
                        c.numero_documento,
                        c.nombre_completo,
                        c.telefono,
                        cd.direccion
                   FROM cliente c
                   LEFT JOIN cliente_direccion cd 
                        ON c.id_cliente = cd.id_cliente 
                        AND cd.es_principal = 1
                   WHERE c.numero_documento = ?
                   AND c.estado = 'A'
                   LIMIT 1";

    $stmtCliente = $conn->prepare($sqlCliente);

    if ($stmtCliente) {
        $stmtCliente->bind_param("s", $documentoCliente);
        $stmtCliente->execute();
        $resCliente = $stmtCliente->get_result();

        if ($resCliente && $resCliente->num_rows > 0) {
            $cliente = $resCliente->fetch_assoc();

            $clienteEncontrado = true;
            $idCliente = $cliente['id_cliente'];
            $documentoCliente = $cliente['numero_documento'];
            $nombreCliente = $cliente['nombre_completo'];
            $telefonoCliente = $cliente['telefono'] ?? "";
            $direccionCliente = $cliente['direccion'] ?? "";
            $mensajeCliente = "Cliente encontrado correctamente.";
        } else {
            $mensajeCliente = "Cliente no encontrado. Puedes registrarlo manualmente en este formulario.";
        }

        $stmtCliente->close();
    }
}
?>