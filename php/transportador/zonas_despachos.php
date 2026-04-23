<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("zonas_despachos");

require_once "../backend/transportador/zonas_despachos.php";

$nombre = $_SESSION['nombre'] ?? 'Transportador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Transportador - Zonas y despachos</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/transportador-rutas-despachos.css">
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

            <li class="active-item">
                <a href="zonas_despachos.php">
                    <span class="icon"><img src="../../img/despachos.png" alt=""></span>
                    Zonas y despachos
                </a>
            </li>

            <li>
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

        <h1 class="h1-title">Zonas y despachos</h1>
        <h3 class="subtitulo">Despachos asignados al transportador</h3>

        <?php if (!empty($mensaje)): ?>
            <div class="status-banner <?php echo htmlspecialchars($tipoMensaje); ?>">
                <span><?php echo htmlspecialchars($mensaje); ?></span>
            </div>
        <?php endif; ?>

        <table class="tabla-despachos">
            <thead>
                <tr>
                    <th>N° despacho</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Dirección</th>
                    <th>Zona</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($despachos)): ?>
                    <?php foreach ($despachos as $despacho): ?>
                        <tr>
                            <td><?php echo (int)$despacho['id_despacho']; ?></td>
                            <td><?php echo htmlspecialchars($despacho['cliente']); ?></td>
                            <td><?php echo htmlspecialchars($despacho['productos']); ?></td>
                            <td><?php echo htmlspecialchars($despacho['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($despacho['zona_entrega']); ?></td>
                            <td class="estado estado-<?php echo strtolower($despacho['estado']); ?>">
                              <?php echo htmlspecialchars(ucfirst($despacho['estado'])); ?>
                            </td>
                           
                           
                           <td class="acciones">
                               <?php if ($despacho['estado'] === 'asignado'): ?>
                                   <a href="gestion_entrega.php?id=<?php echo (int)$despacho['id_despacho']; ?>" class="btn-confirmar">
                                       Gestionar
                                    </a>
                                <?php else: ?>
                                    <span class="accion-incidencia">Sin acciones</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No tienes despachos asignados en este momento.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="logo-footer">Z-CONTAY</div>
    </main>
</div>

</body>
</html>