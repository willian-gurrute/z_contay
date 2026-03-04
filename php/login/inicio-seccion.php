<?php
// Iniciamos sesión para poder leer los mensajes de error
session_start();
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



    <!-- MOSTRAR MENSAJE DE ERROR 

    <?php
    // Si existe un error guardado en sesión lo mostramos
    if(isset($_SESSION['error'])){

        echo "<p class='error-msg'>".$_SESSION['error']."</p>";

        // eliminamos el error para que no aparezca otra vez
        unset($_SESSION['error']);
    }
    ?>



     FORMULARIO DE LOGIN-->
    

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


        <!---Contraseña-->

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


        <!--Boton Login -->

        <button type="submit">
            Ingresar
        </button>

    </form>



     <!---enlaces-->


    <p style="text-align:center; margin-top:15px;">

       <a href="olvidastes_contraseña.php" class="link">¿Olvidaste tu contraseña?</a>

    </p>

    <p style="text-align:center;">

     <a href="crear_cuenta.php" class="link">¿Crear cuenta?</a>

    </p>


</div>

</body>
</html>