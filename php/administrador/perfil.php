<?php
 //iniciamos sesion 
  session_start();

  //incluimos el archivo que trae los datos del perfil 
  require_once("../backend/administrador/obtener_perfil.php");

  ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Administardor</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/Administrador-perfil.css"> 
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
            <img src="../../img/campana.png" width="24" alt="Notificaciones">
        </span>

        <span> 
         <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
        </span> 

        <span class="icon">
          <img src="../../img/usuario-gestion.png" width="24" alt="Usuario">
        </span>
    </div>

</header>


<div class="main-container">

    <!-- MENÚ DEL ADMINISTARDOR -->
    <nav class="sidebar">
        <ul>
            <li><a href="panel-control.php"><span class="icon"><img src="../../img/panel.jpg"></span>Panel principal</a></li>
            <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png"></span>Gestión de usuarios</a></li>
            <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png"></span>Roles y permisos</a></li>
             <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg"></span>Crear Opciones</a></li>
            <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png"></span>Contabilidad</a></li>
            <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png"></span>Ventas</a></li>
            <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png"></span>Productos</a></li>
            <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
            <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png"></span>Inventario</a></li>
            <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png"></span>Reportes</a></li>
            <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png"></span>Configuración</a></li>

            <li class="active-item">
                <a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span>Perfil</a>
            </li>

            <li><a href="../login/inicio-seccion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span>Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Mi perfil</h1>

        <div class="perfil-card">

            <img src="../../img/perfil.png" class="perfil-foto" alt="foto de perfil">

            <h2 class="perfil-nombre">
                <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
            </h2>

            <p>
                <strong>ROL:</strong>
             <?php echo htmlspecialchars($perfil['nombre_rol']); ?>
            </p>

            <p>
                <strong>Correo: </strong>
                <?php echo htmlspecialchars($perfil['correo_electronico']); ?>
            </p>

            <p>
                <strong>Docomento:</strong>
                <?php echo htmlspecialchars($perfil['tipo_documento'] . ' - ' . $perfil['numero_documento']); ?>
            </p>

            <p>
                <strong>Direccion:</strong>
                <?php echo htmlspecialchars($perfil['direccion']); ?>

           </p>

            <p>
                <strong>Estado:</strong>
                <?php echo htmlspecialchars($perfil['estado']== 'A')? 'A' : 'I'; ?>
            </p>



            <div class="perfil-botones">
                 <button class="btn-editar" onclick="location.href=`editar_perfil.php`">
                    editar perfil
                </button>
                 <button class="btn-contra" onclick="location.href=`cambiar_contraseña.php`">
                    cambiar contraseña
                </button> 
            </div>

        </div>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>
