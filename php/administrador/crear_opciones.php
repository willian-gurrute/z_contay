<?php
// php/administrador/crear_opciones.php

session_start();
require_once "../backend/verificar_sesion.php";

// Si no hay sesión → login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Solo administrador
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Nombre del admin
$nombre = $_SESSION['nombre'] ?? 'Administrador';

// Mensaje simple
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Opción - Administrador</title>

<link rel="stylesheet" href="../../_css/admin-base.css">
<link rel="stylesheet" href="../../_css/crear-opciones.css">

</head>

<body>

<header class="header-bar">

<div class="header-rol">Administrador</div>
<div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

<div class="header-user">
<span class="icon"><img src="../../img/campana.png"></span>
<span><?= htmlspecialchars($nombre) ?></span>
<span class="icon"><img src="../../img/usuario-gestion.png"></span>
</div>

</header>

<div class="main-container">

<nav class="sidebar">
<ul>

<li>
<a href="panel_control.php">
<span class="icon"><img src="../../img/panel.jpg"></span>
Panel principal
</a>
</li>

<li>
<a href="gestion_usuarios.php">
<span class="icon"><img src="../../img/usuario-gestion.png"></span>
Gestión de usuarios
</a>
</li>

<li>
<a href="roles_permisos.php">
<span class="icon"><img src="../../img/roles-permisos.png"></span>
Roles y permisos
</a>
</li>

<li>
<a href="crear_opciones.php">
<span class="icon"><img src="../../img/opciones.jpg"></span>
Crear Opciones
</a>
</li>

<li>
<a href="../backend/logout.php">
<span class="icon"><img src="../../img/cerrar-seccion.png"></span>
Cerrar sesión
</a>
</li>

</ul>
</nav>

<main class="content-area">

<h1 class="h1-title">Crear nueva opción</h1>

<?php if ($msg == "ok"): ?>
<p style="color:green;">Opción creada correctamente</p>
<?php endif; ?>

<?php if ($msg == "error"): ?>
<p style="color:red;">Error al crear la opción</p>
<?php endif; ?>

<form action="../backend/administrador/opcion_guardar.php" method="POST" class="form-opcion">

<label>Nombre opción:</label>
<input type="text" name="nombre_opcion" required>

<label>Nombre controlador:</label>
<input type="text" name="nombre_controlador" required>

<label>Nombre función:</label>
<input type="text" name="nombre_funcion" required>

<label>Estado:</label>

<select name="estado" required>

<option value="">Seleccionar</option>
<option value="A">Activo</option>
<option value="I">Inactivo</option>

</select>

<div class="botones">

<button type="submit" class="btn-guardar">
Guardar
</button>

<a href="roles_permisos.php" class="btn-cancelar">
Cancelar
</a>

</div>

</form>

<div class="logo-footer">Z-CONTAY</div>

</main>

</div>

</body>
</html>