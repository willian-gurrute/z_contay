<?php

// Iniciamos sesión 
session_start();

/*
mensajes  de inicio de  sesión.

$_SESSION['error'] = "Contraseña incorrecta";
$_SESSION['ok']    = "Cuenta creada correctamente";
*/

$error = $_SESSION['error'] ?? '';
$ok    = $_SESSION['ok'] ?? '';

/*Después de leer los mensajes los eliminamos*/

unset($_SESSION['error'], $_SESSION['ok']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Z-CONTAY - Galpón Aves del Paraíso</title>

    <!-- CSS de la pantalla -->
    <link rel="stylesheet" href="../../_css/inicio-sesion.css">
</head>

<body>


<h1 class="titulo-principal">
    Z-CONTAY - Galpón Aves del Paraíso
</h1>


<div class="login-container">

    <h2>Iniciar Sesión</h2>


    <!--Si el backend envía un error o mensaje, se mostrará aquí arriba del formulario.-->

    <p id="msg-error" class="error-msg">
      <?= htmlspecialchars($error) ?>
    </p>

    <p id="msg-ok" class="ok-msg">
     <?= htmlspecialchars($ok) ?>
    </p>



    <!-- FORMULARIO DE LOGIN -->
    <form action="../backend/login.php" method="POST">

        <!-- Correo -->
        <label for="correo_electronico">
            Correo electrónico:
        </label>

        <input
            type="email"
            id="correo_electronico"
            name="correo_electronico"
            placeholder="Ingrese su correo electrónico"
            required
        >

        <br><br>


        <!-- Contraseña -->
        <label for="password">
            Contraseña:
        </label>

        <input
            type="password"
            id="password"
            name="password"
            placeholder="Ingrese su contraseña"
            required
        >

        <br><br>


        <!-- Botón Login -->
        <button type="submit">
            Ingresar
        </button>

    </form>



    <!-- Enlaces -->

    <p style="text-align:center; margin-top:15px;">

       <a href="olvidastes_contraseña.php" class="link">
           ¿Olvidaste tu contraseña?
       </a>

    </p>

    <p style="text-align:center;">

     <a href="crear_cuenta.php" class="link">
         ¿Crear cuenta?
     </a>

    </p>


</div>

</body>
</html>