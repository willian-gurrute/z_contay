<?php
session_start();

// reutilizamos los datos del usuario logueado
require_once("../backend/administrador/obtener_perfil.php");



$mensaje = $_SESSION['mensaje_password'] ?? "";
$tipoMensaje = $_SESSION['tipo_password'] ?? "";

// Borramos el mensaje para que solo salga una vez
unset($_SESSION['mensaje_password']);
unset($_SESSION['tipo_password']);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/cliente-cambiar contraseña.css">
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
           <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
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

           <li>
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

              <h1 class="h1-title">Cambiar contraseña</h1>

                   <?php if (!empty($mensaje)) : ?>
                       <div class="mensaje-alerta <?php echo $tipoMensaje; ?>">
                          <?php echo htmlspecialchars($mensaje); ?>
                       </div>
                    <?php endif; ?>

                <form class="form-pass" action="../backend/administrador/cambiar_contrasena.php" method="POST">

               <input type="hidden" name="id_usuario" value="<?php echo $perfil['id_usuario']; ?>">

                    <label>Contraseña actual:</label>
                <input type="password" name="password_actual" required>

                   <label>Nueva contraseña:</label>
               <input type="password" name="password_nueva" required>

                   <label>Confirmar nueva contraseña:</label>
               <input type="password" name="password_confirmar" required>

                  <div class="pass-botones">
                     <button class="btn-guardar" type="submit">Guardar</button>
                     <button type="button" class="btn-cancelar" onclick="location.href='perfil.php'">Cancelar</button>
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