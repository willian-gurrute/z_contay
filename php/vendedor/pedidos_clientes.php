<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("pedidos_clientes");

require_once "../backend/vendedor/pedidos_clientes.php";

$nombre = $_SESSION['nombre'] ?? 'Vendedor';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Clientes</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/vendedor-pedidos-clientes.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Vendedor</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png" alt="Notificaciones"></span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon"><img src="../../img/usuario-gestion.png" alt="Perfil"></span>
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li><a href="panel_principal.php"><span class="icon"><img src="../../img/panel.jpg"></span> Panel Principal</a></li>
            <li><a href="registrar_venta.php"><span class="icon"><img src="../../img/ventas.png"></span> Registrar venta</a></li>
            <li class="active-item"><a href="pedidos_clientes.php"><span class="icon"><img src="../../img/pedidos.png"></span> Pedidos de clientes</a></li>
            <li><a href="reportes_venta.php"><span class="icon"><img src="../../img/reportes.png"></span> Reportes de venta</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span> Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Pedidos de Clientes</h1>
        <h3 class="subtitulo">Gestiona los pedidos disponibles y los pedidos que ya has tomado</h3>
          <?php if (isset($_GET['mensaje'])) : ?>
          <div class="mensaje-sistema 
          <?php 
            if ($_GET['mensaje'] == 'tomado') {
                echo 'mensaje-info';
            } elseif ($_GET['mensaje'] == 'facturado') {
                echo 'mensaje-exito';
            } elseif ($_GET['mensaje'] == 'error_factura') {
                echo 'mensaje-error';
            } elseif ($_GET['mensaje'] == 'ya_facturado') {
                echo 'mensaje-advertencia';
            } elseif ($_GET['mensaje'] == 'liberado') {
                echo 'mensaje-info';
            } elseif ($_GET['mensaje'] == 'error_liberar') {
                echo 'mensaje-error';
            }
            
            ?>">
            <?php
            if ($_GET['mensaje'] == 'tomado') {
                echo "Pedido tomado correctamente.";
            } elseif ($_GET['mensaje'] == 'facturado') {
                echo "Factura generada correctamente.";
            } elseif ($_GET['mensaje'] == 'error_factura') {
                echo "Ocurrió un error al generar la factura.";
            } elseif ($_GET['mensaje'] == 'ya_facturado') {
                echo "Este pedido ya tenía una factura registrada.";
            } elseif ($_GET['mensaje'] == 'liberado') {
                echo "El pedido fue liberado y volvió a quedar disponible.";
            } elseif ($_GET['mensaje'] == 'error_liberar') {
                echo "No se pudo liberar el pedido.";
            }

            ?>
         </div>
         <?php endif; ?>

        <section class="seccion-pedidos">
            <h2 class="subtitulo">Pedidos disponibles</h2>

            <table class="tabla-pedidos">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pedidosDisponibles)) : ?>
                        <?php foreach ($pedidosDisponibles as $pedido) : ?>
                            <tr>
                                <td><?php echo $pedido['id_pedido']; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['numero_documento']); ?></td>
                                <td>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>
                                    <form action="../backend/vendedor/tomar_pedido.php" method="POST">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <button class="main-button" type="submit" >Tomar pedido</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">No hay pedidos disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="seccion-pedidos" style="margin-top: 40px;">
            <h2 class="subtitulo">Mis pedidos</h2>

            <table class="tabla-pedidos">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($misPedidos)) : ?>
                        <?php foreach ($misPedidos as $pedido) : ?>
                            <tr>
                                <td><?php echo $pedido['id_pedido']; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($pedido['fecha'])); ?></td>
                                <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($pedido['numero_documento']); ?></td>
                                <td>$<?php echo number_format($pedido['total'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                                <td>
                                     <?php if (!empty($pedido['id_factura'])) : ?>
                                        <a href="factura_imprimir.php?id=<?php echo $pedido['id_factura']; ?>" class="main-button" target="_blank">
                                           Ver detalle
                                        </a>
                                     <?php else : ?>
                                          <span>No disponible</span>
                                     <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">Aún no tienes pedidos facturados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <div class="logo-footer">Z-CONTAY</div>

    </main>

</div>


<script>
    setTimeout(function() {
        const mensaje = document.querySelector('.mensaje-sistema');
        if (mensaje) {
            mensaje.style.display = 'none';
        }
    }, 4000); // 4 segundos
</script>

</body>
</html>