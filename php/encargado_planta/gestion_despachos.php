<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Cambia este nombre si en tus opciones lo guardaste diferente
verificarPermiso("gestion_despachos");

require_once "../backend/encargado_planta/gestion_despachos.php";

$nombre = $_SESSION['nombre'] ?? 'Encargado de Planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Despachos</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/encargado-gestion-despachos.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">Encargado de planta</div>
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
            <li>
                <a href="panel_control.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel Principal
                </a>
            </li>

            <li>
                <a href="control_inventario.php">
                    <span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de inventario
                </a>
            </li>

            <li class="active-item">
                <a href="gestion_despachos.php">
                    <span class="icon"><img src="../../img/despachos.png" alt=""></span> Gestión de Despachos
                </a>
            </li>

            <li>
                <a href="zonas_entrega.php">
                    <span class="icon"><img src="../../img/rutas.png" alt=""></span> Zonas de Entrega
                </a>
            </li>

            <li>
                <a href="perfil.php">
                    <span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil
                </a>
            </li>

            <li>
                <a href="cerrar_sesion.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>



    <main class="content-area">

     <?php if (isset($_GET['ok'])): ?>
      <div class="success-msg">
         Despacho asignado correctamente
     </div>
     <?php endif; ?>

        <h1 class="h1-title">Gestión de Despachos</h1>

        <h3 class="subtitulo">Pedidos listos para asignar despacho</h3>

        <table class="tabla-despachos">
            <thead>
                <tr>
                    <th>Factura</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pedidos_listos)) : ?>
                    <?php foreach ($pedidos_listos as $pedido) : ?>
                        <tr>
                            <td><?php echo (int)$pedido['id_factura']; ?></td>
                            <td><?php echo date("d/m/Y h:i A", strtotime($pedido['fecha'])); ?></td>
                            <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                            <td class="estado-pendiente">Pendiente por asignar</td>
                            <td>
                                <button class="btn-despachar"
                                    onclick="location.href='asignar_despacho.php?id_factura=<?php echo (int)$pedido['id_factura']; ?>'">
                                    Asignar despacho
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">No hay pedidos disponibles para asignar despacho.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="subtitulo  subtitulo-segundo">Despachos gestionados</h3>

        <table class="tabla-despachos">
            <thead>
                <tr>
                    <th>ID Despacho</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Transportador</th>
                    <th>Estado</th>
                    <th>Gestionado por</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($despachos_gestionados)) : ?>
                    <?php foreach ($despachos_gestionados as $despacho) : ?>
                        <tr>
                            <td><?php echo (int)$despacho['id_despacho']; ?></td>
                            <td><?php echo date("d/m/Y h:i A", strtotime($despacho['fecha'])); ?></td>
                            <td><?php echo htmlspecialchars($despacho['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($despacho['transportador'] ?? 'Sin asignar'); ?></td>

                            <td class="<?php
                                if ($despacho['estado'] === 'pendiente') {
                                    echo 'estado-pendiente';
                                } elseif ($despacho['estado'] === 'asignado') {
                                    echo 'estado-asignado';
                                } elseif ($despacho['estado'] === 'incidencia') {
                                    echo 'estado-incidencia';
                                } elseif ($despacho['estado'] === 'entregado') {
                                    echo 'estado-entregado';
                                }
                            ?>">
                                <?php echo ucfirst(htmlspecialchars($despacho['estado'])); ?>
                            </td>

                            <td><?php echo htmlspecialchars($despacho['gestionado_por']); ?></td>

                            <td>
                                <?php if ($despacho['estado'] === 'pendiente') : ?>
                                    <button class="btn-despachar"
                                        onclick="location.href='asignar_despacho.php?id_despacho=<?php echo (int)$despacho['id_despacho']; ?>'">
                                        Asignar
                                    </button>

                                <?php elseif ($despacho['estado'] === 'asignado') : ?>
                                    <button class="btn-detalle"
                                        onclick="location.href='detalle_despacho.php?id=<?php echo (int)$despacho['id_despacho']; ?>'">
                                        Ver detalle
                                    </button>

                                <?php elseif ($despacho['estado'] === 'incidencia') : ?>
                                    <button class="btn-incidencia"
                                        onclick="location.href='ver_incidencia.php?id=<?php echo (int)$despacho['id_despacho']; ?>'">
                                        Ver incidencia
                                    </button>

                                <?php elseif ($despacho['estado'] === 'entregado') : ?>
                                    <button class="btn-detalle"
                                        onclick="location.href='detalle_despacho.php?id=<?php echo (int)$despacho['id_despacho']; ?>'">
                                        Ver detalle
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No hay despachos gestionados todavía.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>
<script>
setTimeout(() => {
    const msg = document.querySelector('.success-msg');
    if (msg) msg.style.display = 'none';
}, 3000);
</script>
</body>
</html>