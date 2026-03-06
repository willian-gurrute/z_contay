<?php
// php/administrador/contabilidad.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Permiso: pantalla del menú
verificarPermiso("contabilidad");

// Traer datos reales
require_once "../backend/administrador/contabilidad.php";

// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contabilidad - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/contabilidad.css">
</head>

<body>

    <!-- BARRA SUPERIOR -->
    <header class="header-bar">
        <div class="header-rol">Administrador</div>
        <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

        <div class="header-user">
            <span class="icon"><img src="../../img/campana.png" alt="notificaciones"></span>
            <span><?= htmlspecialchars($nombre) ?></span>
            <span class="icon"><img src="../../img/usuario-gestion.png" alt="perfil"></span>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="main-container">

        <!-- SIDEBAR -->
        <nav class="sidebar">
            <ul>
                <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg"></span>Panel Principal</a></li>
                <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png"></span>Gestión de usuarios</a></li>
                <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png"></span>Roles y permisos</a></li>
                <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg"></span>Crear Opciones</a></li>

                <li class="active-item">
                    <a href="contabilidad.php">
                        <span class="icon"><img src="../../img/contabilidad.png"></span>
                        Contabilidad
                    </a>
                </li>

                <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png"></span>Ventas</a></li>
                <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png"></span>Productos</a></li>
                <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
                <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png"></span>Inventario</a></li>
                <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png"></span>Reportes</a></li>
                <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png"></span>Configuración</a></li>
                <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span>Perfil</a></li>
                <li><a href="../backend/logout.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span>Cerrar sesión</a></li>
            </ul>
        </nav>

        <!-- CONTENIDO CENTRAL -->
        <main class="content-area">

            <h1 class="h1-title">Contabilidad general</h1>
            <p class="subtitulo">Resumen financiero del negocio</p>

            <!-- TARJETAS DE RESUMEN -->
           <div class="card-grid">

              <div class="info-card">
                <div class="card-icon">
                  <img src="../../img/ganancias.png" alt="">
                </div>
                <h2>Ganancias del mes</h2>
                <div class="data">$<?= number_format($resumen['ingresos_mes'], 0, ',', '.') ?></div>
              </div>

              <div class="info-card">
                <div class="card-icon">
                  <img src="../../img/gastos.png" alt="">
                </div>
                <h2>Gastos operativos</h2>
                <div class="data">$<?= number_format($resumen['egresos_mes'], 0, ',', '.') ?></div>
              </div>

              <div class="info-card">
                <div class="card-icon">
                  <img src="../../img/balance.png" alt="">
                </div>
                <h2>Balance general</h2>
                <div class="data">$<?= number_format($resumen['balance_mes'], 0, ',', '.') ?></div>
              </div>

           </div>

            <!-- TABLA DETALLADA -->
            <h2 class="titulo-tabla">Movimientos contables</h2>

            <?php if (empty($movimientos)): ?>
                <p>No hay movimientos contables registrados.</p>
            <?php else: ?>
            <table class="tabla-contabilidad">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['fecha']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($m['tipo'])) ?></td>
                        <td><?= htmlspecialchars($m['descripcion']) ?></td>
                        <td>$<?= number_format((float)$m['monto'], 0, ',', '.') ?></td>
                        
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <!-- FILTRO (simple) -->
            <div class="filtro-reporte">

                <label><strong>Mes:</strong></label>
                <select disabled>
                    <option><?= htmlspecialchars($resumen['mes_texto']) ?></option>
                </select>

                <label><strong>Tipo:</strong></label>
                <select disabled>
                    <option>Todos</option>
                </select>

                <button class="btn-generar" disabled>Generar Reporte PDF</button>

            </div>

            <div class="logo-footer">Z-CONTAY</div>

        </main>

    </div>

</body>
</html>