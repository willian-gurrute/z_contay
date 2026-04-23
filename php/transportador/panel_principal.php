<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("panel_principal");

require_once "../backend/transportador/panel_principal.php";

$nombre = $_SESSION['nombre'] ?? 'transportador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transportador - Panel principal</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/transportador-panel-principal.css">
</head>

<body>
    <header class="header-bar">
        <div class="header-rol">Transportador</div>
        <div class="header-system">Z-CONTAY - Galpon Aves del Paraiso</div>

        <div class="header-user">
            <span class="icon">
                <img src="../../img/campana.png" alt="Notificaciones">
            </span>
            <span><?php echo htmlspecialchars($nombre); ?></span>
            <span class="icon">
                <img src="../../img/usuario-gestion.png" alt="Perfil">
            </span>
        </div>
    </header>

    <div class="main-container">
        <nav class="sidebar">
            <ul>
                <li class="active-item">
                    <a href="panel_principal.php">
                        <span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel principal
                    </a>
                </li>

                <li>
                    <a href="zonas_despachos.php">
                        <span class="icon"><img src="../../img/despachos.png" alt=""></span> Zonas y despachos
                    </a>
                </li>

                <li>
                    <a href="listado_pedidos.php">
                        <span class="icon"><img src="../../img/listado-pedidos.png" alt=""></span> Listado de pedidos
                    </a>
                </li>

                <li>
                    <a href="reportes_despachos.php">
                        <span class="icon"><img src="../../img/reportes.png" alt=""></span> Reportes de despachos
                    </a>
                </li>

                <li>
                    <a href="perfil.php">
                        <span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil
                    </a>
                </li>

                <li>
                    <a href="cerrar_sesion.php">
                        <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesion
                    </a>
                </li>
            </ul>
        </nav>

        <main class="content-area">

            <h1 class="h1-title">Panel principal</h1>

            <div class="cards-container">

                <div class="card-indicador">
                    <h2>Despachos asignados</h2>
                    <p class="valor"><?php echo $despachos_asignados; ?></p>
                </div>

                <div class="card-indicador">
                    <h2>Pendientes por entregar</h2>
                    <p class="valor"><?php echo $pendientes_por_entregar; ?></p>
                </div>

                <div class="card-indicador">
                    <h2>Entregados hoy</h2>
                    <p class="valor"><?php echo $entregados_hoy; ?></p>
                </div>

                <div class="card-indicador">
                    <h2>Incidencias reportadas</h2>
                    <p class="valor"><?php echo $incidencias_reportadas; ?></p>
                </div>

            </div>

            <h3 class="subtitulo">Resumen del dia</h3>

            <table class="tabla-resumen">
                <thead>
                    <tr>
                        <th>Proceso</th>
                        <th>Cantidad</th>
                        <th>Ultima actualizacion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Despachos asignados</td>
                        <td><?php echo $despachos_asignados; ?></td>
                        <td><?php echo htmlspecialchars($ultima_actualizacion_asignados); ?></td>
                    </tr>
                    <tr>
                        <td>Pendientes por entregar</td>
                        <td><?php echo $pendientes_por_entregar; ?></td>
                        <td><?php echo htmlspecialchars($ultima_actualizacion_pendientes); ?></td>
                    </tr>
                    <tr>
                        <td>Entregados hoy</td>
                        <td><?php echo $entregados_hoy; ?></td>
                        <td><?php echo htmlspecialchars($ultima_actualizacion_entregados); ?></td>
                    </tr>
                    <tr>
                        <td>Incidencias reportadas</td>
                        <td><?php echo $incidencias_reportadas; ?></td>
                        <td><?php echo htmlspecialchars($ultima_actualizacion_incidencias); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="logo-footer">Z-CONTAY</div>

        </main>
    </div>
</body>
</html>