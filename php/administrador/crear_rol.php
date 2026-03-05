<?php
// php/administrador/crear_rol.php

session_start();
require_once "../backend/verificar_sesion.php";

// Si no hay sesión, al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Solo Administrador
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Conexión y traer opciones (para mostrarlas como checkboxes)
require_once "../backend/conexion.php";

$opciones = [];
$res = $conn->query("SELECT id_opciones, nombre_opcion FROM opciones WHERE estado='A' ORDER BY id_opciones ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $opciones[] = $row;
    }
}

// Nombre del admin
$nombreAdmin = $_SESSION['nombre'] ?? 'Administrador';

// Mensajes simples
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Rol - Administrador</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/crear-rol.css">
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
            <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>

            <li class="active-item">
                <a href="roles_permisos.php">
                    <span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>
                    Roles y permisos
                </a>
            </li>

            <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg" alt=""></span>Crear Opciones</a></li>

            <li><a href="../backend/logout.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Crear Nuevo Rol</h1>
        <p class="subtitulo">Define el rol y selecciona las opciones que podrá utilizar.</p>

        <?php if ($msg === 'ok'): ?>
            <p style="margin-bottom:15px; color:green;">✅ Rol creado correctamente</p>
        <?php elseif ($msg === 'dup'): ?>
            <p style="margin-bottom:15px; color:red;">❌ Ya existe un rol con ese nombre</p>
        <?php elseif ($msg === 'bad'): ?>
            <p style="margin-bottom:15px; color:red;">❌ Revisa los datos del formulario</p>
        <?php endif; ?>

        <!-- Enviamos al backend -->
        <form class="form-rol" method="POST" action="../backend/administrador/rol_crear.php">

            <!-- DATOS DEL ROL -->
            <label>Nombre del Rol:</label>
            <input type="text" name="nombre_rol" required>

            <label>Descripción:</label>
            <textarea name="descripcion" rows="3" placeholder="(Opcional) Esto no se guarda aún"></textarea>

            <label>Estado:</label>
            <select name="estado" required>
                <option value="A">Activo</option>
                <option value="I">Inactivo</option>
            </select>

            <!-- PERMISOS -->
            <h3 style="margin-top:25px;">Asignación de Permisos</h3>

            <div class="permisos-container">

                <?php if (empty($opciones)): ?>
                    <p>No hay opciones creadas. Ve a <b>Crear Opciones</b> primero.</p>
                <?php else: ?>

                    <?php foreach ($opciones as $op): ?>
                        <label>
                            <input type="checkbox" name="opciones[]" value="<?= (int)$op['id_opciones'] ?>">
                            <?= htmlspecialchars($op['nombre_opcion']) ?>
                        </label>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

            <!-- BOTONES -->
            <div class="botones">
                <button type="submit" class="btn-guardar">Guardar Rol</button>
                <a href="roles_permisos.php" class="btn-cancelar">Cancelar</a>
            </div>

        </form>

        <div class="logo-footer">Z-CONTAY</div>

    </main>

</div>

</body>
</html>