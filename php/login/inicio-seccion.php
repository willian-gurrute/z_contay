<?php
session_start();

// Tomar mensaje de error o éxito (si viene del backend)
$error = isset($_SESSION['error']) ? trim($_SESSION['error']) : '';
$ok    = isset($_SESSION['ok']) ? trim($_SESSION['ok']) : '';

// Limpiarlos para que no se queden pegados
unset($_SESSION['error'], $_SESSION['ok']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Z-CONTAY - Galpón Aves del Paraíso</title>
    <link rel="stylesheet" href="../../_css/inicio-sesion.css">
</head>

<body>

<h1 class="titulo-principal">Z-CONTAY - Galpón Aves del Paraíso</h1>

<div class="login-container">

    <h2>Iniciar Sesión</h2>

    <!-- Mensajes (solo aparecen si vienen con texto) -->

    <?php if ($error !== ''): ?>
      <div class="alert error-msg">
        <span class="alert-icon"></span>
        <span class="alert-text"><?= htmlspecialchars($error) ?></span>
      </div>
    <?php endif; ?>

    <?php if ($ok !== ''): ?>
      <div class="ok-msg">
        <span class="alert-icon">✓</span>
        <span class="alert-text"><?= htmlspecialchars($ok) ?></span>
      </div>
    <?php endif; ?>

    <!-- FORMULARIO DE LOGIN -->
    <form action="../backend/login.php" method="POST">

        <label for="correo_electronico">Correo electrónico:</label>
        <input type="email" id="correo_electronico" name="correo_electronico"
               placeholder="Ingrese su correo electrónico" required>

        <br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password"
               placeholder="Ingrese su contraseña" required>

        <br><br>

        <button type="submit">Ingresar</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        <a href="olvidastes_contrasena.php" class="link">¿Olvidaste tu contraseña?</a>
    </p>

    <p style="text-align:center;">
        <a href="crear_cuenta.php" class="link">¿Crear cuenta?</a>
    </p>

</div>

<!-- Opcional: que se oculte solo en 3 segundos -->
<script>
  setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => el.style.display = 'none');
  }, 3000);
</script>

</body>
</html>