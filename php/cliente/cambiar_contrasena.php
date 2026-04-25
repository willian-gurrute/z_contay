<?php
session_start();
$nombre = $_SESSION['nombre'] ?? 'Cliente';

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("perfil");

// Datos del cliente logueado
require_once "../backend/cliente/obtener_perfil.php";

$mensaje = $_SESSION['mensaje_password'] ?? "";
$tipoMensaje = $_SESSION['tipo_password'] ?? "";

// borrar mensaje después de mostrarlo
unset($_SESSION['mensaje_password']);
unset($_SESSION['tipo_password']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cambiar contraseña - Cliente</title>

<link rel="stylesheet" href="../../_css/admin-base.css">
<link rel="stylesheet" href="../../_css/cliente-cambiar contraseña.css">

</head>
<body>

<header class="header-bar">

    <div class="header-rol">
        Cliente
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/perfil.png" width="24" alt="Perfil">
        </span>

    </div>

</header>

<div class="main-container">

<nav class="sidebar">
<ul>

<li>
<a href="portafolio.php">
<span class="icon">
<img src="../../img/panel.jpg">
</span>
Portafolio
</a>
</li>

<li>
<a href="realizar_pedido.php">
<span class="icon">
<img src="../../img/carrito.png">
</span>
Realizar pedido
</a>
</li>

<li>
<a href="historial_pedidos.php">
<span class="icon">
<img src="../../img/historial.png">
</span>
Historial de pedidos
</a>
</li>

<li class="active-item">
<a href="perfil.php">
<span class="icon">
<img src="../../img/perfil.png">
</span>
Perfil
</a>
</li>

<li>
<a href="cerrar_sesion.php">
<span class="icon">
<img src="../../img/cerrar-seccion.png">
</span>
Cerrar sesión
</a>
</li>

</ul>
</nav>


<main class="content-area">

<h1 class="h1-title">Cambiar contraseña</h1>


<?php if (!empty($mensaje)) : ?>
<div class="mensaje-alerta <?php echo htmlspecialchars($tipoMensaje); ?>">
    <?php echo htmlspecialchars($mensaje); ?>
</div>
<?php endif; ?>


<form class="form-pass"
action="../backend/cliente/cambiar_contrasena.php"
method="POST">

<input
type="hidden"
name="id_usuario"
value="<?php echo (int)$perfil['id_usuario']; ?>"
>


<label>Contraseña actual:</label>

<input
type="password"
name="password_actual"
required
>


<label>Nueva contraseña:</label>

<input
type="password"
name="password_nueva"
required
>


<label>Confirmar nueva contraseña:</label>

<input
type="password"
name="password_confirmar"
required
>


<div class="pass-botones">

<button class="btn-guardar" type="submit">
Guardar
</button>

<button
type="button"
class="btn-cancelar"
onclick="location.href='perfil.php'">
Cancelar
</button>

</div>

</form>

<div class="logo-footer">
Z-CONTAY
</div>

</main>

</div>


<script>
setTimeout(function(){

let mensaje=document.querySelector('.mensaje-alerta');

if(mensaje){

mensaje.style.transition='opacity 0.5s ease';
mensaje.style.opacity='0';

setTimeout(function(){
mensaje.remove();
},500);

}

},4000);
</script>

</body>
</html>