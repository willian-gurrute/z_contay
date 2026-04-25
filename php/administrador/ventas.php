<?php
// php/administrador/ventas.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Verificar permiso de esta pantalla
verificarPermiso("ventas");

// Traer datos reales
require_once "../backend/administrador/ventas.php";


// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas - Administrador</title>

    <!-- CSS base -->
    <link rel="stylesheet" href="../../_css/admin-base.css">

    <!-- CSS de esta pantalla -->
    <link rel="stylesheet" href="../../_css/administrador-ventas.css">
</head>

<body>

<header class="header-bar">

    <div class="header-rol">
        Administrador
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/usuario-gestion.png" width="24" alt="Usuario">
        </span>

    </div>

</header>

<div class="main-container">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <ul>
            <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span>Panel Principal</a></li>
            <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>
            <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a></li>
            <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg" alt=""></span>Crear Opciones</a></li>
            <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>

            <li class="active-item"><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>

            <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png" alt=""></span>Productos</a></li>
            <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
            <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span>Inventario</a></li>
            <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes</a></li>
            <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png" alt=""></span>Configuración</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Ventas registradas</h1>
        <h3 class="subtitulo">Consulta detallada de ventas del sistema</h3>

        <!-- FILTROS -->
        <form class="filtros-ventas" method="GET">
            <div class="campo">
                <label>Fecha inicial:</label>
                <input type="date" name="fecha_inicial" value="<?= htmlspecialchars($fecha_inicial) ?>">
            </div>

            <div class="campo">
                <label>Fecha final:</label>
                <input type="date" name="fecha_final" value="<?= htmlspecialchars($fecha_final) ?>">
            </div>

            <div class="campo">
                <label>Vendedor:</label>
                <select name="id_vendedor">
                    <option value="">Todos</option>
                    <?php foreach ($vendedores as $v): ?>
                        <option value="<?= (int)$v['id_usuario'] ?>" <?= ($id_vendedor == $v['id_usuario']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['nombre_completo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

           <button type="submit" class="btn-filtrar">Filtrar</button>
            </form>

           <?php if (!empty($ventas)): ?>
              <div class="acciones-reporte"> 
                  <a href="reporte_ventas_pdf.php?fecha_inicial=<?= urlencode($fecha_inicial) ?>&fecha_final=<?= urlencode($fecha_final) ?>&id_vendedor=<?= urlencode($id_vendedor) ?>" class="btn-filtrar">
                     Descargar PDF
                   </a>
              </div>
            <?php endif; ?>

<!-- TABLA -->
<table class="tabla-ventas">

        <!-- TABLA -->
       
            <table class="tabla-ventas">
              <thead>
          <tr>
            <th>ID Factura</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Tipo venta</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Vendedor</th>
            <th>Acciones</th>
          </tr>
          </thead>

        <tbody>
            <?php if (empty($ventas)): ?>
            <tr>
                <td colspan="8">No hay ventas registradas.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= (int)$venta['id_factura'] ?></td>
                    <td><?= htmlspecialchars($venta['fecha']) ?></td>
                    <td><?= htmlspecialchars($venta['cliente']) ?></td>
                    <td><?= htmlspecialchars($venta['tipo_venta']) ?></td>
                    <td><?= htmlspecialchars($venta['estado_factura']) ?></td>
                    <td>$<?= number_format((float)$venta['total'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($venta['vendedor']) ?></td>
                    <td>
                        <a href="detalle_factura.php?id=<?= (int)$venta['id_factura'] ?>" class="btn-detalle">
                            Ver detalle
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

        <div class="logo-footer">Z-CONTAY</div>

    </main>

</div>

</body>
</html>