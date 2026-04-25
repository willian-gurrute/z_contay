<?php
// php/administrador/crear_opciones.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("crear_opciones");

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

// Mensaje por URL
$msg = $_GET['msg'] ?? '';

$mensaje = "";
$tipoMensaje = "";

if ($msg === 'ok') {
    $mensaje = "Opción creada correctamente.";
    $tipoMensaje = "success";
} elseif ($msg === 'actualizado') {
    $mensaje = "Opción actualizada correctamente.";
    $tipoMensaje = "success";
} elseif ($msg === 'existe') {
    $mensaje = "La opción ya existe.";
    $tipoMensaje = "error";
} elseif ($msg === 'error') {
  } elseif ($msg === 'bloqueada') { 
    $mensaje = "Esta opción es parte del sistema y no puede desactivarse.";
    $tipoMensaje = "error";
}

$catalogo = [

    "Administrador" => [
        ["nombre"=>"Panel principal", "controlador"=>"panel_control", "funcion"=>"ver"],
        ["nombre"=>"Gestión de usuarios", "controlador"=>"gestion_usuarios", "funcion"=>"ver"],
        ["nombre"=>"Roles y permisos", "controlador"=>"roles_permisos", "funcion"=>"ver"],
        ["nombre"=>"Crear opciones", "controlador"=>"crear_opciones", "funcion"=>"ver"],
        ["nombre"=>"Contabilidad", "controlador"=>"contabilidad", "funcion"=>"ver"],
        ["nombre"=>"Ventas", "controlador"=>"ventas", "funcion"=>"ver"],
        ["nombre"=>"Productos", "controlador"=>"gestion_productos", "funcion"=>"ver"],
        ["nombre"=>"Registrar Gasto", "controlador"=>"registrar_gasto", "funcion"=>"ver"],
        ["nombre"=>"Inventario", "controlador"=>"inventario", "funcion"=>"ver"],
        ["nombre"=>"Reportes", "controlador"=>"reportes", "funcion"=>"ver"],
        ["nombre"=>"Configuración", "controlador"=>"configuracion", "funcion"=>"ver"],
        ["nombre"=>"Perfil", "controlador"=>"perfil", "funcion"=>"ver"],
    ],

    "Encargado de Planta" => [
        ["nombre"=>"Panel principal Encargado", "controlador"=>"panel_control", "funcion"=>"ver"],
        ["nombre"=>"Gestión de despachos", "controlador"=>"gestion_despachos", "funcion"=>"ver"],
        ["nombre"=>"Zonas de entrega", "controlador"=>"zonas_entrega", "funcion"=>"ver"],
        ["nombre"=>"Control de inventario", "controlador"=>"control_inventario", "funcion"=>"ver"],
        ["nombre"=>"Perfil Encargado", "controlador"=>"perfil", "funcion"=>"ver"],
    ],

    "Transportador" => [
        ["nombre"=>"Panel principal transportador", "controlador"=>"panel_principal", "funcion"=>"ver"],
        ["nombre"=>"Zonas y despachos", "controlador"=>"zonas_despachos", "funcion"=>"ver"],
        ["nombre"=>"Listado de pedidos", "controlador"=>"listado_pedidos", "funcion"=>"ver"],
        ["nombre"=>"Reportes de despachos", "controlador"=>"reportes_despachos", "funcion"=>"ver"],
        ["nombre"=>"Perfil transportador", "controlador"=>"perfil", "funcion"=>"ver"],
    ],

    "Vendedor" => [
        ["nombre"=>"Panel principal vendedor", "controlador"=>"panel_principal", "funcion"=>"ver"],
        ["nombre"=>"Registrar venta", "controlador"=>"registrar_venta", "funcion"=>"ver"],
        ["nombre"=>"Pedidos de clientes", "controlador"=>"pedidos_clientes", "funcion"=>"ver"],
        ["nombre"=>"Reportes de venta", "controlador"=>"reportes_venta", "funcion"=>"ver"],
        ["nombre"=>"Perfil vendedor", "controlador"=>"perfil", "funcion"=>"ver"],
    ],

    "Cliente" => [
        ["nombre"=>"Portafolio cliente", "controlador"=>"portafolio", "funcion"=>"ver"],
        ["nombre"=>"Realizar pedido", "controlador"=>"realizar_pedido", "funcion"=>"ver"],
        ["nombre"=>"Historial de pedidos", "controlador"=>"historial_pedidos", "funcion"=>"ver"],
        ["nombre"=>"Perfil cliente", "controlador"=>"perfil", "funcion"=>"ver"],
    ],
];

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
  <a href="contabilidad.php">
    <span class="icon"><img src="../../img/contabilidad.png" alt=""></span>
    Contabilidad
  </a>
</li>
      
<li>
<a href="ventas.php">
<span class="icon"><img src="../../img/ventas.png" alt=""></span>
Ventas
</a>
</li>
            
<li>
<a href="gestion_productos.php">
<span class="icon"><img src="../../img/productos.png" alt="">
</span>
Productos
</a>
</li>
            
<li>
  <a href="registrar_gasto.php">
    <span class="icon"><img src="../../img/gastos.png" alt="">
  </span>
  Registrar Gasto
</a>
</li>
            
<li><a href="inventario.php">
  <span class="icon"><img src="../../img/inventario.png" alt="">
</span>
Inventario
</a>
</li>
            
<li><a href="reportes.php">
  <span class="icon"><img src="../../img/reportes.png" alt="">
</span>
Reportes
</a>
</li>
            
<li>
  <a href="configuracion.php">
    <span class="icon"><img src="../../img/configuracion.png" alt="">
  </span>
  Configuración
</a>
</li>
            
<li>
  <a href="perfil.php">
    <span class="icon"><img src="../../img/perfil.png" alt="">
  </span>
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

<h1 class="h1-title">Crear nueva opción</h1>
       

<?php if (!empty($mensaje)) : ?>
    <div class="mensaje-alerta <?php echo $tipoMensaje; ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>
<?php endif; ?>


<form action="../backend/administrador/opcion_guardar.php" method="POST" class="form-opcion">

  <label>Opción:</label>
<select name="opcion_key" required>
  <option value="">Seleccione...</option>

  <?php foreach ($catalogo as $modulo => $lista): ?>
    <optgroup label="<?= htmlspecialchars($modulo) ?>">
      <?php foreach ($lista as $op): ?>
        <?php
          $key = $modulo . "|" . $op["nombre"] . "|" . $op["controlador"] . "|" . $op["funcion"];
        ?>
        <option value="<?= htmlspecialchars($key) ?>">
          <?= htmlspecialchars($op["nombre"]) ?>
        </option>
      <?php endforeach; ?>
    </optgroup>
  <?php endforeach; ?>

</select>

  <label>Estado:</label>
  <select name="estado" required>
    <option value="A">Activo</option>
    <option value="I">Inactivo</option>
  </select>

  <div class="botones">
    <button type="submit" class="btn-guardar">Guardar</button>
    <a href="roles_permisos.php" class="btn-cancelar">Cancelar</a>
  </div>

</form>

<div class="logo-footer">Z-CONTAY</div>

</main>

</div>

<script>
setTimeout(function(){
    const alerta = document.querySelector(".mensaje-alerta");
    if(alerta){
        alerta.style.opacity = "0";
        alerta.style.transition = "0.5s";
        setTimeout(() => alerta.remove(), 500);
    }
},4000);
</script>

</body>
</html>