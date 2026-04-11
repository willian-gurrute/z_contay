<?php
require_once __DIR__ . "/../conexion.php";

// ===============================
// VARIABLES INICIALES
// ===============================
$idCliente = '';
$documentoCliente = trim($_GET['documento'] ?? '');
$nombreCliente = '';
$telefonoCliente = '';
$direccionCliente = '';
$barrioCliente = '';
$ciudadCliente = '';
$referenciaCliente = '';

$mensajeCliente = '';
$clienteEncontrado = false;

// ===============================
// BUSCAR CLIENTE POR DOCUMENTO
// ===============================
if ($documentoCliente !== '') {
    $sqlCliente = "SELECT 
                        c.id_cliente,
                        c.numero_documento,
                        c.nombre_completo,
                        c.telefono,
                        cd.direccion,
                        cd.barrio,
                        cd.ciudad,
                        cd.referencia
                   FROM cliente c
                   LEFT JOIN cliente_direccion cd
                        ON cd.id_cliente = c.id_cliente
                        AND cd.es_principal = 1
                   WHERE c.numero_documento = ?
                   LIMIT 1";

    $stmtCliente = $conn->prepare($sqlCliente);

    if ($stmtCliente) {
        $stmtCliente->bind_param("s", $documentoCliente);
        $stmtCliente->execute();
        $resCliente = $stmtCliente->get_result();

        if ($resCliente && $resCliente->num_rows > 0) {
            $cliente = $resCliente->fetch_assoc();

            $idCliente = $cliente['id_cliente'] ?? '';
            $documentoCliente = $cliente['numero_documento'] ?? '';
            $nombreCliente = $cliente['nombre_completo'] ?? '';
            $telefonoCliente = $cliente['telefono'] ?? '';
            $direccionCliente = $cliente['direccion'] ?? '';
            $barrioCliente = $cliente['barrio'] ?? '';
            $ciudadCliente = $cliente['ciudad'] ?? '';
            $referenciaCliente = $cliente['referencia'] ?? '';

            $clienteEncontrado = true;
            $mensajeCliente = "Cliente encontrado correctamente.";
        } else {
            $mensajeCliente = "Cliente no encontrado. Puedes registrarlo manualmente.";
        }

        $stmtCliente->close();
    } else {
        $mensajeCliente = "Ocurrió un error al buscar el cliente.";
    }
}

// ===============================
// TRAER PRODUCTOS ACTIVOS
// ===============================
$productos = [];

$sqlProductos = "SELECT 
                    id_producto,
                    nombre_producto,
                    precio
                 FROM producto
                 WHERE estado = 'A'
                 ORDER BY nombre_producto ASC";

$resProductos = $conn->query($sqlProductos);

if ($resProductos) {
    while ($fila = $resProductos->fetch_assoc()) {
        $productos[] = $fila;
    }
}
?>