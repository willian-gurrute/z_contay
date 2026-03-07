<?php
// php/administrador/editar_usuario.php

session_start();
require_once "../backend/verificar_sesion.php";

// Solo si hay sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Solo Administrador (rol 1)
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Tomar el id del usuario desde la URL: editar_usuario.php?id=2
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: gestion_usuarios.php");
    exit;
}

// Traer datos del usuario y lista de roles
require_once "../backend/administrador/editar_usuario_datos.php";

// Si no encontró el usuario en BD
if (empty($usuario)) {
    header("Location: gestion_usuarios.php?msg=noexiste");
    exit;
}

// Nombre del admin logueado
$nombreAdmin = $_SESSION['nombre'] ?? 'Administrador';

// Mensaje simple
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/crear-usuario.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Administrador</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png" alt=""></span>
        <span><?= htmlspecialchars($nombreAdmin) ?></span>
        <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
    </div>
</header>

<div class="main-container">

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <ul>
            <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span>Panel Principal</a></li>
            <li class="active-item"><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>
            <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a></li>
            <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>
            <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>
            <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png" alt=""></span>Productos</a></li>
            <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
            <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span>Inventario</a></li>
            <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes</a></li>
            <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png" alt=""></span>Configuración</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar Sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Editar Usuario</h1>

        <?php if ($msg === 'ok'): ?>
            <p style="margin-bottom:15px;">✅ Usuario actualizado correctamente.</p>
        <?php elseif ($msg === 'bad'): ?>
            <p style="margin-bottom:15px;">❌ Datos inválidos. Revisa el formulario.</p>
        <?php elseif ($msg === 'dup'): ?>
            <p style="margin-bottom:15px;">❌ Ya existe ese correo o documento en otro usuario.</p>
        <?php endif; ?>

        <!-- Enviamos el formulario al backend que actualiza -->
        <form class="form-usuario" method="POST" action="../backend/administrador/usuario_actualizar.php">

            <!-- Enviamos el id oculto para saber qué usuario actualizar -->
            <input type="hidden" name="id_usuario" value="<?= (int)$usuario['id_usuario'] ?>">

            <label>Nombre completo:</label>
            <input type="text" name="nombre_completo"
                   value="<?= htmlspecialchars($usuario['nombre_completo']) ?>"
                   required>

            <label>Tipo de documento:</label>
            <select name="tipo_documento" required>
                <option value="CC" <?= ($usuario['tipo_documento'] === 'CC') ? 'selected' : '' ?>>Cédula de ciudadanía</option>
                <option value="CE" <?= ($usuario['tipo_documento'] === 'CE') ? 'selected' : '' ?>>Cédula de extranjería</option>
            </select>

            <input type="text" name="numero_documento"
                   value="<?= htmlspecialchars($usuario['numero_documento']) ?>"
                   required>

            <label>Correo electrónico:</label>
            <input type="email" name="correo_electronico"
                   value="<?= htmlspecialchars($usuario['correo_electronico']) ?>"
                   required>

            <label>Dirección:</label>
            <input type="text" name="direccion"
                   value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>">

            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Deja vacío si no deseas cambiarla">
            <small>Deja vacío si no deseas cambiarla</small>

            <label>Rol del usuario:</label>
            <select name="id_rol" required>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= (int)$r['id_rol'] ?>" <?= ((int)$usuario['id_rol'] === (int)$r['id_rol']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="botones">
                <button type="submit" class="btn-guardar">Actualizar</button>
                <a href="gestion_usuarios.php" class="btn-cancelar">Cancelar</a>
            </div>

        </form>

        <div class="logo-footer">Z-CONTAY</div>

    </main>

</div>

</body>
</html>