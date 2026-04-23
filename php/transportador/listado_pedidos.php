<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("listado_pedidos");

require_once "../backend/transportador/listado_pedidos.php";

$nombre = $_SESSION['nombre'] ?? 'Transportador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Transportador - Listado de pedidos</title>

    <link rel="stylesheet" href="/prototipo/_css/admin-base.css">
    <link rel="stylesheet" href="/prototipo/_css/transportador-rutas-despachos.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">Transportador</div>
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
                <a href="panel_principal.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                    Panel principal
                </a>
            </li>

            <li>
                <a href="zonas_despachos.php">
                    <span class="icon"><img src="../../img/despachos.png" alt=""></span>
                    Zonas y despachos
                </a>
            </li>

            <li class="active-item">
                <a href="listado_pedidos.php">
                    <span class="icon"><img src="../../img/listado-pedidos.png" alt=""></span>
                    Listado de pedidos
                </a>
            </li>

            <li>
                <a href="reportes_despachos.php">
                    <span class="icon"><img src="../../img/reportes.png" alt=""></span>
                    Reportes de despachos
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

        <h1 class="h1-title">Listado de pedidos</h1>
        <h3 class="subtitulo">Historial de pedidos asignados al transportador</h3>

        <table class="tabla-despachos">
            <thead>
                <tr>
                    <th>N° despacho</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($listado_pedidos)): ?>
                    <?php foreach ($listado_pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo (int)$pedido['id_despacho']; ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_creacion']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['direccion']); ?></td>
                            <td>$<?php echo number_format((float)$pedido['total'], 0, ',', '.'); ?></td>
                            <td class="estado estado-<?php echo strtolower($pedido['estado']); ?>">
                                <?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?>
                            </td>
                            <td class="acciones">
                                <a href="detalle_pedido.php?id=<?php echo (int)$pedido['id_despacho']; ?>" class="btn-confirmar">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay pedidos asignados para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>