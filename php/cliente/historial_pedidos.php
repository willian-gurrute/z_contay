<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("historial_pedidos");

require_once "../backend/cliente/historial_pedidos.php";

$nombre = $_SESSION['nombre'] ?? 'Cliente';

$mensaje = $_SESSION['mensaje_pedido'] ?? "";
$tipoMensaje = $_SESSION['tipo_pedido'] ?? "";

unset($_SESSION['mensaje_pedido'], $_SESSION['tipo_pedido']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente - Historial de pedidos</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/realizar-pedido.css">
</head>

<body>

<header class="header-bar">

    <div class="header-rol">
        Cliente
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/perfil.png" width="24" alt="Perfil">
        </span>

    </div>

</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li>
                <a href="portafolio.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                    Portafolio
                </a>
            </li>

            <li>
                <a href="realizar_pedido.php">
                    <span class="icon"><img src="../../img/carrito.png" alt=""></span>
                    Realizar pedido
                </a>
            </li>

            <li class="active-item">
                <a href="historial_pedidos.php">
                    <span class="icon"><img src="../../img/historial.png" alt=""></span>
                    Historial de pedidos
                </a>
            </li>

            <li>
                <a href="perfil.php">
                    <span class="icon"><img src="../../img/perfil.png" alt=""></span>
                    Perfil
                </a>
            </li>

            <li>
                <a href="cerrar_sesion.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>
                    Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Historial de pedidos</h1>

        <p class="subtitulo-portafolio">
            Aquí puedes consultar los pedidos realizados desde tu cuenta.
        </p>

        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje-alerta <?php echo htmlspecialchars($tipoMensaje); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($pedidos)) : ?>

            <p>No tienes pedidos registrados.</p>

            <a href="portafolio.php" class="btn-agregar">
                Ir al portafolio
            </a>

        <?php else : ?>

           <table class="tabla-pedido">
    <thead>
        <tr>
            <th>Código</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado del pedido</th>
            <th>Estado de entrega</th>
            <th>Acción</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($pedidos as $pedido) : ?>
            <?php
    $claseEstadoEntrega = "estado-sin-despacho";

    if (!empty($pedido['estado_despacho'])) {

        switch ($pedido['estado_despacho']) {
            case 'pendiente':
                $estadoEntrega = "En preparación";
                $claseEstadoEntrega = "estado-preparacion";
                break;

            case 'asignado':
                $estadoEntrega = "En camino";
                $claseEstadoEntrega = "estado-camino";
                break;

            case 'entregado':
                $estadoEntrega = "Entregado";
                $claseEstadoEntrega = "estado-entregado";
                break;

            case 'incidencia':
                $estadoEntrega = "Con problema";
                $claseEstadoEntrega = "estado-problema";
                break;

            default:
                $estadoEntrega = "Sin despacho";
                $claseEstadoEntrega = "estado-sin-despacho";
        }

    } elseif (!empty($pedido['id_factura'])) {
        $estadoEntrega = "Facturado";
        $claseEstadoEntrega = "estado-facturado";
    } else {
        $estadoEntrega = "Pendiente";
        $claseEstadoEntrega = "estado-pendiente";
    }
?>

            <tr>
                <td>Pedido #<?php echo (int)$pedido['id_pedido']; ?></td>

                <td><?php echo htmlspecialchars($pedido['fecha']); ?></td>

                <td>
                    $<?php echo number_format((float)$pedido['total'], 0, ',', '.'); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars(ucfirst($pedido['estado_pedido'])); ?>
                </td>

                <td>
                   <span class="estado-entrega <?php echo htmlspecialchars($claseEstadoEntrega); ?>">
                    <?php echo htmlspecialchars($estadoEntrega); ?>
                  </span>
                </td>

                <td>
                    <?php if (!empty($pedido['id_factura'])) : ?>
                        <a href="ver_factura.php?id=<?php echo (int)$pedido['id_factura']; ?>" class="btn-agregar">
                            Ver factura
                        </a>
                    <?php else : ?>
                        <a href="detalle_pedido.php?id=<?php echo (int)$pedido['id_pedido']; ?>" class="btn-agregar">
                            Ver detalle
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

        <?php endif; ?>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

<script>
setTimeout(function () {
    const mensaje = document.querySelector('.mensaje-alerta');
    if (mensaje) {
        mensaje.style.transition = 'opacity 0.5s ease';
        mensaje.style.opacity = '0';

        setTimeout(function () {
            mensaje.remove();
        }, 500);
    }
}, 4000);
</script>

</body>
</html>