<?php
// php/administrador/reportes.php

session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
require_once "../backend/administrador/reportes.php";

// Permiso para esta pantalla
verificarPermiso("reportes");

// Nombre del usuario
$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/administrador-reportes.css">
</head>
<body>

    <!-- BARRA SUPERIOR -->
    <header class="header-bar">
        <div class="header-rol">Administrador</div>
        <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

        <div class="header-user">
            <span class="icon"><img src="../../img/campana.png" alt=""></span>
            <span><?= htmlspecialchars($nombre) ?></span>
            <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
        </div>
    </header>

    <div class="main-container">

        <!-- MENÚ IZQUIERDO -->
        <nav class="sidebar">
            <ul>
                <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span>Panel Principal</a></li>
                <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>
                <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a></li>
                <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg" alt=""></span>Crear Opciones</a></li>
                <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>
                <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>
                <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png" alt=""></span>Productos</a></li>
                <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
                <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span>Inventario</a></li>

                <li class="active-item">
                    <a href="reportes.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes</a>
                </li>

                <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png" alt=""></span>Configuración</a></li>
                <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>
                <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar sesión</a></li>
            </ul>
        </nav>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="content-area">

            <h1 class="h1-title">Reportes del sistema</h1>

            <!-- FILTRO GLOBAL -->
            <form class="global-filter" method="GET">

                <label>Tipo de reporte:</label>
                <select name="tipo_reporte">
                    <option value="usuarios" <?= ($tipo_reporte == 'usuarios') ? 'selected' : '' ?>>Usuarios</option>
                    <option value="inventario" <?= ($tipo_reporte == 'inventario') ? 'selected' : '' ?>>Inventario</option>
                    <option value="ventas" <?= ($tipo_reporte == 'ventas') ? 'selected' : '' ?>>Ventas</option>
                    <option value="financiero" <?= ($tipo_reporte == 'financiero') ? 'selected' : '' ?>>Financiero</option>
                    <option value="general" <?= ($tipo_reporte == 'general') ? 'selected' : '' ?>>General</option>
                </select>

                <label>Fecha desde:</label>
                <input type="date" name="fecha_desde" value="<?= htmlspecialchars($fecha_desde) ?>">

                <label>Fecha hasta:</label>
                <input type="date" name="fecha_hasta" value="<?= htmlspecialchars($fecha_hasta) ?>">

                <button type="submit" class="main-button">Generar reporte</button>

            </form>

            <!-- INFORMACIÓN DE REPORTES -->
            <div class="report-info">

                <div class="report-card">
                    <h2>Usuarios</h2>
                    <p>Listado completo de usuarios, roles y estado.</p>
                </div>

                <div class="report-card">
                    <h2>Inventario</h2>
                    <p>Existencias actuales, movimientos y alertas.</p>
                </div>

                <div class="report-card">
                    <h2>Ventas</h2>
                    <p>Historial de ventas.</p>
                </div>

                <div class="report-card">
                    <h2>Financiero</h2>
                    <p>Balance general, ingresos y egresos.</p>
                </div>

                <div class="report-card">
                    <h2>General</h2>
                    <p>Resumen completo del sistema.</p>
                </div>

            </div>

               <!-- RESULTADO DEL REPORTE -->
                <div class="card-container reporte-resultado">

                    <div class="reporte-header">

                     <h3>Resultado del reporte</h3>

                       <div class="acciones-reporte">
                         <button class="main-button" onclick="window.print()">
                          Imprimir
                          </button>
                           
                        </div>
                    </div>

                <?php if ($tipo_reporte == 'usuarios'): ?>

                    <table class="tabla-reportes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reporte_usuarios)): ?>
                                <tr><td colspan="5">No hay registros.</td></tr>
                            <?php else: ?>
                                <?php foreach ($reporte_usuarios as $u): ?>
                                    <tr>
                                        <td><?= (int)$u['id_usuario'] ?></td>
                                        <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                                        <td><?= htmlspecialchars($u['correo_electronico']) ?></td>
                                        <td><?= htmlspecialchars($u['rol']) ?></td>
                                        <td><?= ($u['estado'] == 'A') ? 'Activo' : 'Inactivo' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                <?php elseif ($tipo_reporte == 'inventario'): ?>

                    <table class="tabla-reportes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Stock mínimo</th>
                                <th>Última actualización</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reporte_inventario)): ?>
                                <tr><td colspan="5">No hay registros.</td></tr>
                            <?php else: ?>
                                <?php foreach ($reporte_inventario as $i): ?>
                                    <tr>
                                        <td><?= (int)$i['id_inventario'] ?></td>
                                        <td><?= htmlspecialchars($i['nombre_producto']) ?></td>
                                        <td><?= (int)$i['cantidad'] ?></td>
                                        <td><?= (int)$i['stock_minimo'] ?></td>
                                        <td><?= htmlspecialchars($i['ultima_actualizacion']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                <?php elseif ($tipo_reporte == 'ventas'): ?>

                    <table class="tabla-reportes">
                        <thead>
                            <tr>
                                <th>ID Factura</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Vendedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reporte_ventas)): ?>
                                <tr><td colspan="5">No hay registros.</td></tr>
                            <?php else: ?>
                                <?php foreach ($reporte_ventas as $v): ?>
                                    <tr>
                                        <td><?= (int)$v['id_factura'] ?></td>
                                        <td><?= htmlspecialchars($v['fecha']) ?></td>
                                        <td><?= htmlspecialchars($v['cliente']) ?></td>
                                        <td>$<?= number_format((float)$v['total'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($v['vendedor']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                <?php elseif ($tipo_reporte == 'financiero'): ?>

                    <div class="report-card">
                        <h2>Ingresos</h2>
                        <p>$<?= number_format($reporte_financiero['ingresos'], 0, ',', '.') ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Egresos</h2>
                        <p>$<?= number_format($reporte_financiero['egresos'], 0, ',', '.') ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Balance</h2>
                        <p>$<?= number_format($reporte_financiero['balance'], 0, ',', '.') ?></p>
                    </div>

                <?php elseif ($tipo_reporte == 'general'): ?>

                    <div class="report-card">
                        <h2>Total usuarios</h2>
                        <p><?= (int)$reporte_general['usuarios'] ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Total productos</h2>
                        <p><?= (int)$reporte_general['productos'] ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Total ventas</h2>
                        <p><?= (int)$reporte_general['ventas'] ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Total movimientos contables</h2>
                        <p><?= (int)$reporte_general['movimientos'] ?></p>
                    </div>

                <?php endif; ?>

            </div>

            <div class="logo-footer">Z-CONTAY</div>

        </main>

    </div>

</body>
</html>