<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../conexion.php";
require_once __DIR__ . "/../notificaciones_helper.php";

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$id_despacho = $_GET['id'] ?? $_POST['id_despacho'] ?? 0;

/* =========================================================
   Buscar id_transportador según el usuario logueado
========================================================= */
$id_transportador = 0;

$sqlTransportador = "SELECT id_transportador
                     FROM transportador
                     WHERE id_usuario = ? AND estado = 'A'
                     LIMIT 1";

$stmtTransportador = $conn->prepare($sqlTransportador);

if ($stmtTransportador) {
    $stmtTransportador->bind_param("i", $id_usuario);
    $stmtTransportador->execute();
    $resTransportador = $stmtTransportador->get_result();

    if ($filaTransportador = $resTransportador->fetch_assoc()) {
        $id_transportador = (int)$filaTransportador['id_transportador'];
    }

    $stmtTransportador->close();
}

/* =========================================================
   Obtener despacho
========================================================= */
$sql = "SELECT d.id_despacho, d.estado, d.zona_entrega,
               c.nombre_completo AS cliente,
               COALESCE(cd.direccion,'Sin direccion') AS direccion,
               GROUP_CONCAT(CONCAT(p.nombre_producto,' x ',df.cantidad) SEPARATOR ', ') AS productos
        FROM despacho d
        INNER JOIN factura f ON d.id_factura = f.id_factura
        INNER JOIN cliente c ON f.id_cliente = c.id_cliente
        LEFT JOIN cliente_direccion cd ON cd.id_cliente = c.id_cliente AND cd.es_principal = 1 AND cd.estado = 'A'
        INNER JOIN detalle_factura df ON f.id_factura = df.id_factura
        INNER JOIN producto p ON df.id_producto = p.id_producto
        WHERE d.id_despacho = ?
        GROUP BY d.id_despacho, d.estado, d.zona_entrega, c.nombre_completo, cd.direccion";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_despacho);
$stmt->execute();
$res = $stmt->get_result();
$despacho = $res->fetch_assoc();
$stmt->close();

/* =========================================================
   Tipos de incidencia
========================================================= */
$tipos_incidencia = [];

$resTipos = $conn->query("SELECT id_tipoIncidencia, nombre FROM tipo_incidencia WHERE estado='A'");

if ($resTipos) {
    while ($row = $resTipos->fetch_assoc()) {
        $tipos_incidencia[] = $row;
    }
}

/* =========================================================
   Obtener usuarios para notificar
========================================================= */
$idUsuarioCliente = null;
$idUsuarioEncargado = null;

$sqlUsuariosNotificar = "SELECT 
                            u_cliente.id_usuario AS usuario_cliente,
                            d.id_usuario AS usuario_encargado
                         FROM despacho d
                         INNER JOIN factura f ON d.id_factura = f.id_factura
                         INNER JOIN cliente c ON f.id_cliente = c.id_cliente
                         INNER JOIN usuario u_cliente ON u_cliente.numero_documento = c.numero_documento
                         WHERE d.id_despacho = ?
                         LIMIT 1";

$stmtUsuariosNotificar = $conn->prepare($sqlUsuariosNotificar);
$stmtUsuariosNotificar->bind_param("i", $id_despacho);
$stmtUsuariosNotificar->execute();
$resUsuariosNotificar = $stmtUsuariosNotificar->get_result();

if ($filaUsuarios = $resUsuariosNotificar->fetch_assoc()) {
    $idUsuarioCliente = $filaUsuarios['usuario_cliente'];
    $idUsuarioEncargado = $filaUsuarios['usuario_encargado'];
}

$stmtUsuariosNotificar->close();

/* =========================================================
   Acciones
========================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';

    if ($accion === 'confirmar') {

        $sqlConfirmar = "UPDATE despacho
                         SET estado = 'entregado', fecha_entrega = NOW()
                         WHERE id_despacho = ? AND id_transportador = ?";

        $stmtConfirmar = $conn->prepare($sqlConfirmar);

        if ($stmtConfirmar) {
            $stmtConfirmar->bind_param("ii", $id_despacho, $id_transportador);
            $stmtConfirmar->execute();
            $stmtConfirmar->close();

               // 32 = Pedido entregado para cliente
             notificarUsuario($conn, 32, $idUsuarioCliente);

              // 19 = Entrega confirmada para encargado
              notificarUsuario($conn, 19, $idUsuarioEncargado);
        }

        header("Location: ../../transportador/zonas_despachos.php?msg=entregado");
        exit;
    }

    if ($accion === 'incidencia') {

        $tipo = isset($_POST['id_tipoIncidencia']) ? (int)$_POST['id_tipoIncidencia'] : 0;
        $obs = trim($_POST['observaciones'] ?? '');

        if ($id_transportador <= 0) {
            die("No se encontró el transportador asociado al usuario.");
        }

        if ($tipo > 0 && $obs !== '') {

            $sqlIncidencia = "INSERT INTO incidencia
                              (id_despacho, id_transportador, id_tipoIncidencia, observaciones)
                              VALUES (?, ?, ?, ?)";

            $stmtIncidencia = $conn->prepare($sqlIncidencia);

            if ($stmtIncidencia) {
                $stmtIncidencia->bind_param("iiis", $id_despacho, $id_transportador, $tipo, $obs);
                $stmtIncidencia->execute();
                $stmtIncidencia->close();
            }

            $sqlActualizar = "UPDATE despacho
                              SET estado = 'incidencia'
                              WHERE id_despacho = ? AND id_transportador = ?";

            $stmtActualizar = $conn->prepare($sqlActualizar);

            if ($stmtActualizar) {
                $stmtActualizar->bind_param("ii", $id_despacho, $id_transportador);
                $stmtActualizar->execute();
                $stmtActualizar->close();

                // 20 = Incidencia reportada en despacho para encargado
               notificarUsuario($conn, 20, $idUsuarioEncargado);

               // 26 = Incidencia registrada correctamente para transportador
                notificarUsuario($conn, 26, $id_usuario);
           }

            header("Location: ../../transportador/zonas_despachos.php?msg=incidencia");
            exit;
        } else {
            header("Location: ../../transportador/zonas_despachos.php?msg=error");
            exit;
        }
    }
}
?>