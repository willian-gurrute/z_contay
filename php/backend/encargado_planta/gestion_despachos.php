<?php

require_once __DIR__ . "/../conexion.php";

/*
    ARRAYS QUE USARÁ EL FRONTEND
*/
$pedidos_listos = [];
$despachos_gestionados = [];

/* 
   1. PEDIDOS LISTOS PARA ASIGNAR DESPACHO
   Trae facturas tipo pedido, pagadas, que todavía
   no tienen despacho creado.
*/
$sqlPedidosListos = "SELECT 
                        f.id_factura,
                        f.fecha,
                        c.nombre_completo AS cliente,
                        COALESCE(cd.direccion, 'Sin dirección registrada') AS direccion
                     FROM factura f
                     INNER JOIN cliente c 
                        ON f.id_cliente = c.id_cliente
                     LEFT JOIN cliente_direccion cd 
                        ON cd.id_cliente = c.id_cliente
                        AND cd.es_principal = 1
                        AND cd.estado = 'A'
                     LEFT JOIN despacho d 
                        ON d.id_factura = f.id_factura
                     WHERE f.tipo_venta = 'pedido'
                     AND f.estado_factura = 'pagada'
                     AND d.id_despacho IS NULL
                     ORDER BY f.fecha DESC";

$resPedidosListos = $conn->query($sqlPedidosListos);

if ($resPedidosListos) {
    while ($fila = $resPedidosListos->fetch_assoc()) {
        $pedidos_listos[] = $fila;
    }
}

/* 
   2. DESPACHOS GESTIONADOS
   Trae despachos ya creados, con cliente, transportador
   y el encargado de planta que lo gestionó.
*/
$sqlDespachosGestionados = "SELECT
                                d.id_despacho,
                                d.fecha_creacion AS fecha,
                                c.nombre_completo AS cliente,
                                COALESCE(u_trans.nombre_completo, 'Sin asignar') AS transportador,
                                d.estado,
                                COALESCE(u_planta.nombre_completo, 'No registrado') AS gestionado_por
                            FROM despacho d
                            INNER JOIN factura f
                                ON d.id_factura = f.id_factura
                            INNER JOIN cliente c
                                ON f.id_cliente = c.id_cliente
                            LEFT JOIN transportador t
                                ON d.id_transportador = t.id_transportador
                            LEFT JOIN usuario u_trans
                                ON t.id_usuario = u_trans.id_usuario
                            LEFT JOIN usuario u_planta
                                ON d.id_usuario = u_planta.id_usuario
                            ORDER BY d.fecha_creacion DESC";

$resDespachosGestionados = $conn->query($sqlDespachosGestionados);

if ($resDespachosGestionados) {
    while ($fila = $resDespachosGestionados->fetch_assoc()) {
        $despachos_gestionados[] = $fila;
    }
}

?>