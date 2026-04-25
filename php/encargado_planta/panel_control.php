<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("panel_control");

require_once "../backend/encargado_planta/panel_control.php";

$nombre = $_SESSION['nombre'] ?? 'Encargado de Planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encargado de Planta - Panel Principal</title>
    <link rel="stylesheet" href="../../_css/encargado-panel-control.css">
</head>
<body>

<header class="header-bar">

    <div class="header-rol">
        Encargado de planta
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/usuario-gestion.png" alt="">
        </span>

    </div>

</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li class="active-item">
                <a href="panel_control.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel Principal
                </a>
            </li>

            <li>
                <a href="control_inventario.php">
                    <span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de inventario
                </a>
            </li>

            <li>
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

        <h1 class="h1-title">Panel Principal</h1>

        <div class="cards-container">

            <div class="card-indicador">
                <h2>Pedidos pendientes por asignar</h2>
                <p class="valor"><?php echo $pedidos_pendientes_asignar; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Pedidos por entregar</h2>
                <p class="valor"><?php echo $pedidos_por_entregar; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Movimientos hoy</h2>
                <p class="valor"><?php echo $movimientos_hoy; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Productos con stock bajo</h2>
                <p class="valor"><?php echo $productos_stock_bajo; ?></p>
            </div>

        </div>

        <h3 class="subtitulo">Resumen general</h3>

        <table class="tabla-resumen">
            <thead>
                <tr>
                    <th>Proceso</th>
                    <th>Cantidad</th>
                    <th>Última actualización</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pedidos pendientes por asignar</td>
                    <td><?php echo $pedidos_pendientes_asignar; ?></td>
                    <td><?php echo htmlspecialchars($ultima_actualizacion_pendientes); ?></td>
                </tr>
                <tr>
                    <td>Pedidos por entregar</td>
                    <td><?php echo $pedidos_por_entregar; ?></td>
                    <td><?php echo htmlspecialchars($ultima_actualizacion_entregas); ?></td>
                </tr>
                <tr>
                    <td>Movimientos registrados hoy</td>
                    <td><?php echo $movimientos_hoy; ?></td>
                    <td><?php echo htmlspecialchars($ultima_actualizacion_movimientos); ?></td>
                </tr>
                <tr>
                    <td>Productos con stock bajo</td>
                    <td><?php echo $productos_stock_bajo; ?></td>
                    <td><?php echo htmlspecialchars($ultima_actualizacion_stock); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>