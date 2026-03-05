<?php
// login/olvidastes_contrasena.php

session_start();

$error = $_SESSION['error'] ?? '';
$ok    = $_SESSION['ok'] ?? '';
unset($_SESSION['error'], $_SESSION['ok']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../../_css/olvidastes-contraseña.css">
</head>

<body>

    <h1 class="titulo-principal">Z-CONTAY - Recuperar Contraseña</h1>

    <div class="recuperar-container">

        <h2>¿Olvidaste tu contraseña?</h2>
        <p class="texto-info">
            Ingresa tu correo y continuaremos con el restablecimiento de contraseña.
        </p>

        <!-- Mensajes -->

        <?php if ($error): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($ok): ?>
            <p class="ok-msg"><?= htmlspecialchars($ok) ?></p>
        <?php endif; ?>

        <!-- action al backend -->
         
        <form action="../backend/login/olvidastes_contrasena.php" method="POST">
            <label>Correo:</label>
            <input type="email" name="correo_electronico" placeholder="Escribe tu correo" required>

            <button type="submit">Continuar</button>
        </form>

        <p class="volver">
            <a href="inicio-seccion.php">← Volver al inicio de sesión</a>
        </p>
    </div>

</body>
</html>