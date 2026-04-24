<?php
// php/administrador/reportes.php

session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
require_once "../backend/administrador/reportes.php";

verificarPermiso("reportes");

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

    <main class="content-area">

        <h1 class="h1-title">Reportes del sistema</h1>

        <form class="global-filter" method="GET">

            <label>Seleccione reporte:</label>
            <select name="tipo_reporte" onchange="this.form.submit()">
                <option value="usuarios" <?= ($tipo_reporte == 'usuarios') ? 'selected' : '' ?>>Usuarios</option>
                <option value="inventario" <?= ($tipo_reporte == 'inventario') ? 'selected' : '' ?>>Inventario</option>
                <option value="ventas" <?= ($tipo_reporte == 'ventas') ? 'selected' : '' ?>>Ventas</option>
                <option value="financiero" <?= ($tipo_reporte == 'financiero') ? 'selected' : '' ?>>Financiero</option>
                <option value="general" <?= ($tipo_reporte == 'general') ? 'selected' : '' ?>>General</option>
            </select>

            <?php if ($tipo_reporte == 'usuarios'): ?>

                <label>Rol:</label>
                <select name="rol">
                    <option value="">Todos</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= (int)$rol['id_rol'] ?>" <?= ($filtro_rol == $rol['id_rol']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($rol['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Estado:</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="A" <?= ($filtro_estado == 'A') ? 'selected' : '' ?>>Activos</option>
                    <option value="I" <?= ($filtro_estado == 'I') ? 'selected' : '' ?>>Inactivos</option>
                </select>

            <?php elseif ($tipo_reporte == 'inventario'): ?>

                <label>Producto:</label>
                <select name="producto">
                    <option value="">Todos</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= (int)$producto['id_producto'] ?>" <?= ($filtro_producto == $producto['id_producto']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($producto['nombre_producto']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Estado stock:</label>
                <select name="stock">
                    <option value="">Todos</option>
                    <option value="bajo" <?= ($filtro_stock == 'bajo') ? 'selected' : '' ?>>Stock bajo</option>
                    <option value="normal" <?= ($filtro_stock == 'normal') ? 'selected' : '' ?>>Stock normal</option>
                </select>

            <?php elseif ($tipo_reporte == 'ventas'): ?>

                <label>Fecha desde:</label>
                <input type="date" name="fecha_desde" value="<?= htmlspecialchars($fecha_desde) ?>">

                <label>Fecha hasta:</label>
                <input type="date" name="fecha_hasta" value="<?= htmlspecialchars($fecha_hasta) ?>">

                <label>Vendedor:</label>
                <select name="vendedor">
                    <option value="">Todos</option>
                    <?php foreach ($vendedores as $vendedor): ?>
                        <option value="<?= (int)$vendedor['id_usuario'] ?>" <?= ($filtro_vendedor == $vendedor['id_usuario']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($vendedor['nombre_completo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Tipo venta:</label>
                <select name="tipo_venta">
                    <option value="">Todas</option>
                    <option value="directa" <?= ($filtro_tipo_venta == 'directa') ? 'selected' : '' ?>>Directa</option>
                    <option value="pedido" <?= ($filtro_tipo_venta == 'pedido') ? 'selected' : '' ?>>Pedido</option>
                </select>

            <?php elseif ($tipo_reporte == 'financiero'): ?>

                <label>Fecha desde:</label>
                <input type="date" name="fecha_desde" value="<?= htmlspecialchars($fecha_desde) ?>">

                <label>Fecha hasta:</label>
                <input type="date" name="fecha_hasta" value="<?= htmlspecialchars($fecha_hasta) ?>">

                <label>Tipo movimiento:</label>
                <select name="tipo_movimiento">
                    <option value="">Todos</option>
                    <option value="ingreso" <?= ($filtro_tipo_movimiento == 'ingreso') ? 'selected' : '' ?>>Ingreso</option>
                    <option value="egreso" <?= ($filtro_tipo_movimiento == 'egreso') ? 'selected' : '' ?>>Egreso</option>
                </select>

            <?php endif; ?>

            <div class="filter-action">
               <button type="submit" class="main-button">Generar reporte</button>
            </div>

        </form>

        <div class="card-container reporte-resultado">

            <div class="reporte-header">
                <h3>Resultado del reporte</h3>

               <a href="reporte_general_pdf.php?tipo_reporte=<?= urlencode($tipo_reporte) ?>&fecha_desde=<?= urlencode($fecha_desde) ?>&fecha_hasta=<?= urlencode($fecha_hasta) ?>&rol=<?= urlencode($filtro_rol) ?>&estado=<?= urlencode($filtro_estado) ?>&producto=<?= urlencode($filtro_producto) ?>&stock=<?= urlencode($filtro_stock) ?>&vendedor=<?= urlencode($filtro_vendedor) ?>&tipo_venta=<?= urlencode($filtro_tipo_venta) ?>&tipo_movimiento=<?= urlencode($filtro_tipo_movimiento) ?>" class="main-button">
                  Descargar PDF
               </a>
            </div>

            <?php if ($tipo_reporte == 'usuarios'): ?>

                <div class="report-info">
                    <div class="report-card">
                        <h2>Total usuarios</h2>
                        <p><?= (int)$totalUsuarios ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Activos</h2>
                        <p><?= (int)$totalUsuariosActivos ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Inactivos</h2>
                        <p><?= (int)$totalUsuariosInactivos ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Clientes</h2>
                        <p><?= (int)$totalClientes ?></p>
                    </div>
                </div>

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

                <div class="report-info">
                    <div class="report-card">
                        <h2>Productos</h2>
                        <p><?= (int)$totalProductosInventario ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Stock bajo</h2>
                        <p><?= (int)$totalStockBajo ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Total unidades</h2>
                        <p><?= (int)$totalUnidades ?></p>
                    </div>
                </div>

                <table class="tabla-reportes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Stock mínimo</th>
                            <th>Estado stock</th>
                            <th>Última actualización</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($reporte_inventario)): ?>
                            <tr><td colspan="6">No hay registros.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reporte_inventario as $i): ?>
                                <?php
                                    $estadoStock = ((int)$i['cantidad'] <= (int)$i['stock_minimo'])
                                        ? 'Stock bajo'
                                        : 'Stock normal';
                                ?>
                                <tr>
                                    <td><?= (int)$i['id_inventario'] ?></td>
                                    <td><?= htmlspecialchars($i['nombre_producto']) ?></td>
                                    <td><?= (int)$i['cantidad'] ?></td>
                                    <td><?= (int)$i['stock_minimo'] ?></td>
                                    <td><?= $estadoStock ?></td>
                                    <td><?= htmlspecialchars($i['ultima_actualizacion']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php elseif ($tipo_reporte == 'ventas'): ?>

                <div class="report-info">
                    <div class="report-card">
                        <h2>Total ventas</h2>
                        <p><?= (int)$totalVentas ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Total vendido</h2>
                        <p>$<?= number_format($totalVendido, 0, ',', '.') ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Ventas directas</h2>
                        <p><?= (int)$totalVentasDirectas ?></p>
                    </div>

                    <div class="report-card">
                        <h2>Ventas por pedido</h2>
                        <p><?= (int)$totalVentasPedido ?></p>
                    </div>
                </div>

                <table class="tabla-reportes">
                    <thead>
                        <tr>
                            <th>ID Factura</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Tipo venta</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($reporte_ventas)): ?>
                            <tr><td colspan="7">No hay registros.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reporte_ventas as $v): ?>
                                <tr>
                                    <td><?= (int)$v['id_factura'] ?></td>
                                    <td><?= htmlspecialchars($v['fecha']) ?></td>
                                    <td><?= htmlspecialchars($v['cliente']) ?></td>
                                    <td><?= htmlspecialchars($v['vendedor']) ?></td>
                                    <td><?= htmlspecialchars($v['tipo_venta']) ?></td>
                                    <td><?= htmlspecialchars($v['estado_factura']) ?></td>
                                    <td>$<?= number_format((float)$v['total'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php elseif ($tipo_reporte == 'financiero'): ?>

                <div class="report-info">
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
                </div>

                <table class="tabla-reportes">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Monto</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($reporte_movimientos)): ?>
                            <tr><td colspan="4">No hay registros.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reporte_movimientos as $m): ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['fecha']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($m['tipo'])) ?></td>
                                    <td><?= htmlspecialchars($m['descripcion']) ?></td>
                                    <td>$<?= number_format((float)$m['monto'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php elseif ($tipo_reporte == 'general'): ?>

                <div class="report-info">
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
                        <h2>Movimientos contables</h2>
                        <p><?= (int)$reporte_general['movimientos'] ?></p>
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <div class="logo-footer">Z-CONTAY</div>

    </main>

</div>

</body>
</html>