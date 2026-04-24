<?php
session_start();

require_once("../backend/verificar_sesion.php");
require_once("../backend/cliente/obtener_perfil.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cerrar sesión</title>

<link rel="stylesheet" href="../../_css/cerrar-sesion.css">

</head>
<body>

<header class="header-bar">

<div class="header-rol">
<?php echo htmlspecialchars($perfil['nombre_rol']); ?>
</div>

<div class="header-system">
Z-CONTAY - Galpón Aves del Paraíso
</div>

<div class="header-user">

<span class="icon">
<img src="../../img/campana.png" width="24">
</span>

<span>
<?php echo htmlspecialchars($perfil['nombre_completo']); ?>
</span>

<span class="icon">
<img src="../../img/perfil.png" width="24">
</span>

</div>

</header>



<div class="confirm-container">

<div class="confirm-box">

<img
src="../../img/cerrar-seccion.png"
class="icono"
alt="Cerrar sesión"
>

<h2>¿Deseas cerrar sesión?</h2>

<p>
Tu sesión actual se cerrará y volverás al inicio.
</p>


<div class="botones">

<button
class="btn-cancelar"
onclick="location.href='portafolio.php'">

Cancelar

</button>


<form
action="../backend/logout.php"
method="POST"
style="display:inline;"
>

<button
class="btn-salir"
type="submit"
>

Cerrar sesión

</button>

</form>

</div>

</div>

</div>


<div class="logo-footer">
Z-CONTAY
</div>

</body>
</html>