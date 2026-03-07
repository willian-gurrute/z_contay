<?php 
 session_start();

 //reutilizamos el archivo que trae los datos del usuario logueado 
 require_once("../backend/administrador/obtener_perfil.php");

  $mensaje = $_SESSION['mensaje_perfil'] ?? "";
 $tipoMensaje = $_SESSION['tipo_perfil'] ?? "";

// Borramos el mensaje para que solo salga una vez
 unset($_SESSION['mensaje_perfil']); 
 unset($_SESSION['tipo_perfil']);

?>

 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Administrador</title>
      
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/cliente-editar perfil.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">
        <?php echo htmlspecialchars($perfil['nombre_rol']); ?>
    </div>

    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon">
            <img src="../../img/campana.png" width="24" alt="Notificaciones">
        </span>

        <span>
            <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
        </span>

        <span class="icon">
            <img src="../../img/perfil.png" width="24" alt="Perfil">
        </span>
    </div>
</header>

<div class="main-container">
    
        <nav class="sidebar">
           <ul>

               <li><a href="panel_control.php">
                      <span class="icon"><img src="../../img/panel.jpg"></span>
                      Panel Principal
                    </a>
               </li>

               <li>
                    <a href="gestion_usuarios.php">
                      <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
                         Gestión de usuarios
                   </a>
               </li>

                <li>
                    <a href="roles_permisos.php">
                       <span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>
                        Roles y permisos
                    </a>
                </li>

                <li>
                    <a href="crear_opciones.php">
                       <span class="icon"><img src="../../img/opciones.jpg" alt=""></span>
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
                      <span class="icon"><img src="../../img/productos.png" alt=""></span>
                       Productos
                    </a>
                </li>

                <li>
                    <a href="registrar_gasto.php">
                      <span class="icon"><img src="../../img/gastos.png" alt=""></span>
                      Registrar Gasto
                    </a>
                </li>

                <li>
                    <a href="inventario.php">
                      <span class="icon"><img src="../../img/inventario.png" alt=""></span>
                       Inventario
                    </a>
                </li>

                <li>
                    <a href="reportes.php">
                      <span class="icon"><img src="../../img/reportes.png" alt=""></span>
                      Reportes
                    </a>
                </li>

                <li>
                    <a href="configuracion.php">
                      <span class="icon"><img src="../../img/configuracion.png" alt=""></span>
                     Configuración
                   </a>
               </li>

               <li class="active-item">
                    <a href="perfil.php">
                      <span class="icon"><img src="../../img/perfil.png" alt=""></span>
                      Perfil
                    </a>
               </li>

               <li>
                    <a href="cerrar_sesion.php">
                      <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>
                      Cerrar sesión
                   </a>
               </li>

            </ul>
        </nav>


    <!-- CONTENIDO -->
    <main class="content-area">

        <h1 class="h1-title">Editar Perfil</h1>


               <?php if (!empty($mensaje)) : ?>
                   <div class="mensaje-alerta <?php echo $tipoMensaje; ?>">
                      <?php echo htmlspecialchars($mensaje); ?>
                   </div>
                <?php endif; ?>

        <form class="perfil-form" action="../backend/administrador/actualizar_perfil.php" method="POST">
              <!--enviamos el id del usuario de forma oculta.-->
              <input type="hidden" name="id_usuario" value="<?php echo $perfil[ 'id_usuario']; ?>">


               <label for="nombre_completo">Nombre completo: </label>
               <input

                   type="text" 
                   id="nombre_completo"
                   name="nombre_completo"
                   value="<?php echo htmlspecialchars($perfil['nombre_completo']); ?>"
                   required
                >

                 <label for= "numero_documento">Documento de identificación:</label>
                 <input 
                    type="text"
                    id="numero_documento"
                    name="numero_documento"
                    value="<?php echo htmlspecialchars($perfil['numero_documento']); ?>"
                    readonly
                >   

                  <label for="correo_electronico">Correo electrónico:</label>
                  <input
                  
                  type="email"
                  id="correo_electronico"
                  name="correo_electronico"
                  value="<?php echo htmlspecialchars($perfil['correo_electronico']); ?>"
                  required
                
                >

                  <label for="direccion">Direccion:</label>
                    <input
                    
                     type="text" 
                     id="direccion"
                     name="direccion"
                     value="<?php echo htmlspecialchars($perfil['direccion']); ?>"
                >     

            

               <div class="perfil-botones">
                <button class="btn-guardar" type="submit">Guardar cambios</button>
                <button class="btn-cancelar" onclick="location.href='perfil.php'" type="button">Cancelar</button>
            </div>

        </form>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

<script>
    setTimeout(function () {
        const mensaje = document.querySelector('.mensaje-alerta');
        if (mensaje) {
            mensaje.style.transition = 'opacity 0.5s ease';
            mensaje.style.opacity = '0';

            setTimeout(function () {
                mensaje.remove();
            }, 500);
        }
    }, 4000);
</script>

</body>
</html>
