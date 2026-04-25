<?php
// php/administrador/crear_usuario.php

session_start();
require_once "../backend/verificar_sesion.php";


// Si no hay sesión, al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Solo Administrador (rol 1)
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Conexión y traer roles desde BD (para no escribirlos a mano)
require_once "../backend/conexion.php";

$roles = [];
$res = $conn->query("SELECT id_rol, nombre FROM rol WHERE estado='A' ORDER BY id_rol ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $roles[] = $row;
    }
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
    <title>Crear Usuario - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/crear-usuario.css">
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
           
        <?php echo htmlspecialchars($nombreAdmin); ?>
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

        <h1 class="h1-title">Crear nuevo usuario</h1>

        <!-- Mensajes simples -->
        <?php if ($msg === 'ok'): ?>
            <p style="margin-bottom:15px;">✅ Usuario creado correctamente.</p>
        <?php elseif ($msg === 'bad'): ?>
            <p style="margin-bottom:15px;">❌ Faltan datos. Revisa el formulario.</p>
        <?php elseif ($msg === 'dup'): ?>
            <p style="margin-bottom:15px;">❌ Ya existe ese correo o documento.</p>
        <?php endif; ?>

        <!-- Formulario: envía al backend -->
        <form class="form-usuario" method="POST" action="../backend/administrador/crear_usuario.php">

            <label>Nombre completo:</label>
            <input type="text" name="nombre_completo" placeholder="Ingresa el nombre completo" required>

            <label>Tipo de documento:</label>
            <select name="tipo_documento" required>
                <option value="">Seleccione...</option>
                <option value="CC">Cédula de ciudadanía</option>
                <option value="CE">Cédula de extranjería</option>
            </select>

            <input type="text" name="numero_documento" placeholder="1234" required>

            <label>Correo electrónico:</label>
            <input type="email" name="correo_electronico" placeholder="Correo válido" required>

            <label>Dirección:</label>
            <input type="text" name="direccion" placeholder="Opcional">

            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>

            <label>Rol del usuario:</label>
            <select name="id_rol" required>
                <option value="">-- Seleccionar rol --</option>

                <!-- Roles desde la BD -->
                <?php foreach ($roles as $r): ?>
                    <option value="<?= (int)$r['id_rol'] ?>">
                        <?= htmlspecialchars($r['nombre']) ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <div class="botones">
                <button type="submit" class="btn-guardar">Guardar</button>
                <a href="gestion_usuarios.php" class="btn-cancelar">Cancelar</a>
            </div>

        </form>

        <div class="logo-footer">Z-CONTAY</div>

    </main>

</div>

</body>
</html>