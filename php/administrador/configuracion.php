<?php
// php/administrador/configuracion.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Permiso pantalla
verificarPermiso("configuracion");

// nombre usuario
$nombre = $_SESSION['nombre'] ?? 'Administrador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Configuración - Administrador</title>

<link rel="stylesheet" href="../../_css/admin-base.css">
<link rel="stylesheet" href="../../_css/administrador-configuracion.css">

</head>

<body>

<header class="header-bar">

<div class="header-rol">Administrador</div>

<div class="header-system">
Z-CONTAY - Galpón Aves del Paraíso
</div>

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

<li>
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

<li class="active-item">
<a href="configuracion.php">
<span class="icon"><img src="../../img/configuracion.png"></span>
Configuración
</a>
</li>

<li>
<a href="perfil.php">
<span class="icon"><img src="../../img/perfil.png"></span>
Perfil
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

<main class="content-area">

<h1 class="h1-title">Configuración del sistema</h1>

<div class="config-grid">

<div class="config-card">

<h2>Ajustes generales</h2>

<p>
Información del negocio, logo, horarios y datos institucionales.
</p>

<button onclick="location.href='ajustes_generales.php'">
Modificar
</button>

</div>

<div class="config-card">

    <h2>Notificaciones</h2>

    <p>
        Administrar qué alertas estarán activas y a qué roles del sistema se les mostrarán.
    </p>

    <button onclick="location.href='/prototipo/php/administrador/notificaciones.php'">
        Administrar
    </button>

</div>

</div>

<div class="logo-footer">Z-CONTAY</div>

</main>

</div>

</body>
</html>