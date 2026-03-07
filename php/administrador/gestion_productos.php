<?php
// php/administrador/gestion_productos.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Verifica permiso para esta pantalla
verificarPermiso("gestion_productos");

// Traer productos reales
require_once "../backend/administrador/gestion_productos.php";

// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/administrador-gestion-productos.css">
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

        <!-- SIDEBAR -->
        <nav class="sidebar">
            <ul>
                <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span>Panel Principal</a></li>
                <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>
                <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a></li>
                <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg" alt=""></span>Crear Opciones</a></li>
                <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>
                <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>

                <li class="active-item">
                    <a href="gestion_productos.php">
                        <span class="icon"><img src="../../img/productos.png" alt=""></span>Productos
                    </a>
                </li>

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

            <h1 class="h1-title">Gestión de Productos</h1>
            <p class="subtitulo">Productos fijos (A, AA, AAA, Jumbo). Solo se edita precio y estado.</p>

            <div class="card-container">
                <h3>Listado de productos</h3>

                <table class="tabla-productos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Precio unitario</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="5">No hay productos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td><?= (int)$p['id_producto'] ?></td>
                                    <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
                                    <td>$<?= number_format((float)$p['precio'], 0, ',', '.') ?></td>

                                    <td class="<?= ($p['estado'] === 'A') ? 'estado-disponible' : 'estado-nodisponible' ?>">
                                        <?= ($p['estado'] === 'A') ? 'Disponible' : 'No disponible' ?>
                                    </td>

                                    <td>
                                        <a class="main-button" href="editar_producto.php?id=<?= (int)$p['id_producto'] ?>">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="logo-footer">Z-CONTAY</div>

        </main>
    </div>

</body>
</html>