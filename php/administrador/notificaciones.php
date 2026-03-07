<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// permiso
verificarPermiso("configuracion");

// backend
require_once "../backend/administrador/notificaciones.php";

// nombre usuario
$nombre = $_SESSION['nombre'] ?? 'Administrador';

// mensaje
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/administrador-configuracion.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Administrador</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <img src="../../img/campana.png" alt="">
        <span><?= htmlspecialchars($nombre) ?></span>
        <img src="../../img/usuario-gestion.png" alt="">
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg"></span>Panel Principal</a></li>
            <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png"></span>Gestión de usuarios</a></li>
            <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png"></span>Roles y permisos</a></li>
            <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg"></span>Crear Opciones</a></li>
            <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png"></span>Contabilidad</a></li>
            <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png"></span>Ventas</a></li>
            <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png"></span>Productos</a></li>
            <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
            <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png"></span>Inventario</a></li>
            <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png"></span>Reportes</a></li>
            <li><a href="configuracion.php" class="active-item"><span class="icon"><img src="../../img/configuracion.png"></span>Configuración</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span>Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">
        <h1 class="h1-title">Notificaciones</h1>
        <p class="subtitulo">Control de alertas del sistema y roles que las reciben.</p>

        <?php if ($msg == "ok"): ?>
            <p style="color:green;">Notificación actualizada correctamente.</p>
        <?php endif; ?>

        <?php if ($msg == "error"): ?>
            <p style="color:red;">No se pudo actualizar la notificación.</p>
        <?php endif; ?>

        <div class="card-container" style="margin-top:25px;">
            <h3>Listado de notificaciones</h3>

            <table class="tabla-reportes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Rol asignado</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($notificaciones)): ?>
                        <tr>
                            <td colspan="7">No hay notificaciones registradas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($notificaciones as $n): ?>
                            <tr>
                                <td><?= (int)$n['id_notificacion'] ?></td>
                                <td><?= htmlspecialchars($n['nombre']) ?></td>
                                <td><?= htmlspecialchars($n['descripcion']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($n['tipo'])) ?></td>
                                <td><?= htmlspecialchars($n['nombre_rol']) ?></td>
                                <td><?= ($n['estado'] === 'A') ? 'Activa' : 'Inactiva' ?></td>
                                <td>
                                    <form action="/prototipo/php/backend/administrador/notificacion_estado.php" method="POST">
                                        <input type="hidden" name="id_notificacion" value="<?= (int)$n['id_notificacion'] ?>">

                                        <?php if ($n['estado'] === 'A'): ?>
                                            <input type="hidden" name="accion" value="desactivar">
                                            <button type="submit" class="btn-desactivar">Desactivar</button>
                                        <?php else: ?>
                                            <input type="hidden" name="accion" value="activar">
                                            <button type="submit" class="btn-activar">Activar</button>
                                        <?php endif; ?>
                                    </form>
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