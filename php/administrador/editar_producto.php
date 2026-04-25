<?php
// php/administrador/editar_producto.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Verifica permiso de esta pantalla
verificarPermiso("gestion_productos");

// Tomar id del producto desde la URL
$id = $_GET['id'] ?? 0;

// Traer datos del producto
require_once "../backend/administrador/editar_producto.php";
// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';

// Mensaje simple
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - Administrador</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/administrador-editar-producto.css">
</head>

<body>

    <!-- BARRA SUPERIOR -->
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

        <!-- MENÚ LATERAL -->
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
                        <span class="icon"><img src="../../img/productos.png" alt=""></span>
                        Productos
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

        <!-- CONTENIDO CENTRAL -->
        <main class="content-area">

            <h1 class="h1-title">Editar producto</h1>
            <p class="subtitulo">Modificar precio unitario y estado del producto</p>

            <?php if ($msg == "ok"): ?>
                <p style="color:green;">Producto actualizado correctamente.</p>
            <?php endif; ?>

            <?php if ($msg == "error"): ?>
                <p style="color:red;">No se pudo actualizar el producto.</p>
            <?php endif; ?>

            <?php if (!empty($producto)): ?>
            <form class="form-producto" method="POST" action="../backend/administrador/actualizar_producto.php">

                <!-- ID oculto -->
                <input type="hidden" name="id_producto" value="<?= (int)$producto['id_producto'] ?>">

                <label>Nombre del producto:</label>
                <input type="text" value="<?= htmlspecialchars($producto['nombre_producto']) ?>" readonly>

                <label>Precio unitario:</label>
                <input type="number" name="precio" step="0.01" min="0"
                       value="<?= htmlspecialchars($producto['precio']) ?>" required>

                <label>Estado:</label>
                <select name="estado" required>
                    <option value="A" <?= ($producto['estado'] == 'A') ? 'selected' : '' ?>>Disponible</option>
                    <option value="I" <?= ($producto['estado'] == 'I') ? 'selected' : '' ?>>No disponible</option>
                </select>

                <div class="botones">
                    <button type="submit" class="btn-guardar">Guardar</button>
                    <a href="gestion_productos.php" class="btn-cancelar">Cancelar</a>
                </div>

            </form>
            <?php else: ?>
                <p style="color:red;">Producto no encontrado.</p>
            <?php endif; ?>

            <div class="logo-footer">Z-CONTAY</div>

        </main>
    </div>

</body>
</html>