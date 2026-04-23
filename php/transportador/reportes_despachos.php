<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("reportes_despachos");

require_once "../backend/transportador/reportes_despachos.php";

$nombre = $_SESSION['nombre'] ?? 'Transportador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de despachos - Transportador</title>

    <link rel="stylesheet" href="/prototipo/_css/admin-base.css">
    <link rel="stylesheet" href="/prototipo/_css/transportador-rutas-despachos.css">
    <link rel="stylesheet" href="/prototipo/_css/transportador-panel-principal.css">
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

            <li>
                <a href="listado_pedidos.php">
                    <span class="icon"><img src="../../img/listado-pedidos.png" alt=""></span>
                    Listado de pedidos
                </a>
            </li>

            <li class="active-item">
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

        <h1 class="h1-title">Reportes de despachos</h1>
        <h3 class="subtitulo">Consulta del historial de despachos del transportador</h3>

        <div class="cards-container">
            <div class="card-indicador">
                <h2>Total despachos</h2>
                <p class="valor"><?php echo $resumen_total; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Entregados</h2>
                <p class="valor"><?php echo $resumen_entregados; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Con incidencia</h2>
                <p class="valor"><?php echo $resumen_incidencias; ?></p>
            </div>

            <div class="card-indicador">
                <h2>Asignados</h2>
                <p class="valor"><?php echo $resumen_asignados; ?></p>
            </div>
        </div>

        <div class="gestion-card">
            <h2 class="gestion-subtitle">Filtros</h2>

            <form method="GET" action="reportes_despachos.php" class="form-incidencia">
            
                <select name="estado" class="input-gestion">
                       <option value="">Todos los estados</option>
                       <option value="asignado" <?php echo ($estado_filtro === 'asignado') ? 'selected' : ''; ?>>Asignado</option>
                       <option value="entregado" <?php echo ($estado_filtro === 'entregado') ? 'selected' : ''; ?>>Entregado</option>
                       <option value="incidencia" <?php echo ($estado_filtro === 'incidencia') ? 'selected' : ''; ?>>Incidencia</option>
                </select>

                <input type="date" name="fecha_desde" value="<?php echo htmlspecialchars($fecha_desde); ?>" class="input-gestion">
                <input type="date" name="fecha_hasta" value="<?php echo htmlspecialchars($fecha_hasta); ?>" class="input-gestion">

                <div class="acciones">
                    <button type="submit" class="btn-accion btn-confirmar-entrega">Filtrar</button>
                    <a href="reportes_despachos.php" class="btn-confirmar">Limpiar</a>
                    <a href="reporte_despachos_pdf.php?estado=<?php echo urlencode($estado_filtro); ?>&fecha_desde=<?php echo urlencode($fecha_desde); ?>&fecha_hasta=<?php echo urlencode($fecha_hasta); ?>" class="btn-confirmar">
                         Descargar PDF
                    </a>
                </div>
            </form>
        </div>

        <div class="gestion-card">
            <h2 class="gestion-subtitle">Historial de despachos</h2>

            <?php if (empty($reportes_despachos)): ?>
                <p>No hay despachos para mostrar con los filtros seleccionados.</p>
            <?php else: ?>
                <table class="tabla-despachos">
                    <thead>
                        <tr>
                            <th>N° despacho</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Zona</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportes_despachos as $item): ?>
                            <tr>
                                <td><?php echo (int)$item['id_despacho']; ?></td>
                                <td><?php echo htmlspecialchars($item['fecha_creacion']); ?></td>
                                <td><?php echo htmlspecialchars($item['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($item['zona_entrega']); ?></td>
                                <td>$<?php echo number_format((float)$item['total'], 0, ',', '.'); ?></td>
                                <td class="estado estado-<?php echo strtolower($item['estado']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($item['estado'])); ?>
                                </td>
                               
                                <td class="acciones">
                                    <a href="detalle_pedido.php?id=<?php echo (int)$item['id_despacho']; ?>" class="btn-confirmar">
                                        Ver detalle
                                    </a>
                                </td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="logo-footer">Z-CONTAY</div>
    </main>
</div>

</body>
</html>