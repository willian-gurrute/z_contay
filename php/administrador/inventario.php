<?php
// ===============================
// PANTALLA INVENTARIO ADMIN
// ===============================

session_start();

// verifica que exista sesión
require_once "../backend/verificar_sesion.php";

// verifica permisos
require_once "../backend/verificar_permiso.php";

// backend inventario
require_once "../backend/administrador/inventario.php";

// permiso para esta pantalla
verificarPermiso("inventario");

// nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Inventario - Administrador</title>

<link rel="stylesheet" href="../../_css/admin-base.css">
<link rel="stylesheet" href="../../_css/inventario.css">

</head>

<body>

<!-- ================================= -->
<!-- BARRA SUPERIOR -->
<!-- ================================= -->

<header class="header-bar">

<div class="header-rol">
Administrador
</div>

<div class="header-system">
Z-CONTAY - Galpón Aves del Paraíso
</div>

<div class="header-user">

<span class="icon">
<img src="../../img/campana.png">
</span>

<span>
<?= htmlspecialchars($nombre) ?>
</span>

<span class="icon">
<img src="../../img/usuario-gestion.png">
</span>

</div>

</header>


<div class="main-container">


<!-- ================================= -->
<!-- MENÚ LATERAL -->
<!-- ================================= -->

<nav class="sidebar">

<ul>

<li>
<a href="panel_control.php">
<span class="icon"><img src="../../img/panel.jpg"></span>
Panel Principal
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
<a href="contabilidad.php">
<span class="icon"><img src="../../img/contabilidad.png"></span>
Contabilidad
</a>
</li>

<li>
<a href="ventas.php">
<span class="icon"><img src="../../img/ventas.png"></span>
Ventas
</a>
</li>

<li>
<a href="gestion_productos.php">
<span class="icon"><img src="../../img/productos.png"></span>
Productos
</a>
</li>

<li>
<a href="registrar_gasto.php">
<span class="icon"><img src="../../img/gastos.png"></span>
Registrar Gasto
</a>
</li>

<li class="active-item">
<a href="inventario.php">
<span class="icon"><img src="../../img/inventario.png"></span>
Inventario
</a>
</li>

<li>
<a href="reportes.php">
<span class="icon"><img src="../../img/reportes.png"></span>
Reportes
</a>
</li>

<li><a href="configuracion.php">
    <span class="icon"><img src="../../img/configuracion.png" alt=""></span>
    Configuración
</a>
</li>

<li>
<a href="cerrar_sesion.php">
<span class="icon"><img src="../../img/cerrar-seccion.png"></span>
Cerrar sesión
</a>
</li>

</ul>

</nav>


<!-- ================================= -->
<!-- CONTENIDO CENTRAL -->
<!-- ================================= -->

<main class="content-area">

<h1 class="h1-title">Inventario general</h1>

<p class="subtitulo">
Información detallada del inventario actual
</p>


<!-- ================================= -->
<!-- TARJETAS RESUMEN INVENTARIO -->
<!-- ================================= -->

<div class="inventario-grid">

<?php foreach ($resumen as $r): ?>

<div class="card">

<img src="../../img/huevo.png" class="icon-card">

<h2>
<?= htmlspecialchars($r['nombre_producto']) ?>
</h2>

<p class="valor">
<?= (int)$r['cantidad'] ?>
</p>

</div>

<?php endforeach; ?>

</div>


<!-- ================================= -->
<!-- TABLA INVENTARIO -->
<!-- ================================= -->

<h2 class="titulo-tabla">
Inventario detallado
</h2>


<table class="tabla-inventario">

<thead>

<tr>

<th>Código</th>

<th>Producto</th>

<th>Cantidad</th>

<th>Última actualización</th>

</tr>

</thead>


<tbody>

<?php if (empty($inventario)): ?>

<tr>
<td colspan="4">
No hay registros en inventario.
</td>
</tr>

<?php else: ?>

<?php foreach ($inventario as $row): ?>

<tr>

<td>
<?= (int)$row['id_inventario'] ?>
</td>

<td>
<?= htmlspecialchars($row['nombre_producto']) ?>
</td>

<td>
<?= (int)$row['cantidad'] ?>
</td>

<td>
<?= htmlspecialchars($row['ultima_actualizacion']) ?>
</td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>


<div class="logo-footer">
Z-CONTAY © 2026
</div>


</main>

</div>

</body>
</html>