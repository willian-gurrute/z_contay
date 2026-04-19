<?php
session_start();
require_once("../backend/administrador/obtener_perfil.php");
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

        <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

        <div class="header-user">
            <span class="icon">
                <img src="../../img/campana.png" alt="Notificaciones">
            </span>

            <span>
                <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
            </span>

            <span class="icon">
                <img src="../../img/usuario-gestion.png" alt="Usuario">
            </span>
        </div>
    </header>

    <div class="confirm-container">
        <div class="confirm-box">
            <img src="../../img/cerrar-seccion.png" class="icono" alt="Salir">

            <h2>¿Deseas cerrar sesión?</h2>
            <p>Tu sesión actual se cerrará y volverás a la pantalla de inicio.</p>

            <div class="botones">
              <button class="btn-cancelar" onclick="location.href='panel_control.php'">
                Cancelar
               </button>

              <form action="../backend/logout.php" method="POST" style="display:inline;">
                 <button class="btn-salir" type="submit">
                   Cerrar sesión
                   </button>

                </form>
             
            </div>
    </div>

    <div class="logo-footer">Z-CONTAY</div>

</body>
</html>