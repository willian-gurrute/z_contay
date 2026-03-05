<?php
// php/login/nueva_contrasena.php
// Pantalla para escribir la nueva contraseña (versión PHP).

session_start();

/*
Mensajes que pueden venir del backend:
$_SESSION['error'] = "..."
$_SESSION['ok']    = "..."
*/
$error = $_SESSION['error'] ?? '';
$ok    = $_SESSION['ok'] ?? '';

// Borramos mensajes para que no se repitan al recargar
unset($_SESSION['error'], $_SESSION['ok']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="../../_css/olvidastes-contraseña.css">
</head>

<body>

    <h1 class="titulo-principal">Z-CONTAY - Restablecer Contraseña</h1>

    <div class="recuperar-container">

        <h2>Crear nueva contraseña</h2>

        <p class="texto-info">
            Escribe tu nueva contraseña y confírmala para guardarla.
        </p>

        <!-- Mensajes -->
        <?php if ($error): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($ok): ?>
            <p class="ok-msg"><?= htmlspecialchars($ok) ?></p>
        <?php endif; ?>

        <!-- Formulario: envía al backend -->

        <form action="../backend/login/nueva_contrasena.php" method="POST">

            <label>Nueva contraseña:</label>
            <input type="password" name="password" placeholder="Nueva contraseña" required>

            <label>Confirmar contraseña:</label>
            <input type="password" name="password_confirm" placeholder="Repite la contraseña" required>

            <button type="submit">Guardar contraseña</button>
        </form>

        <p class="volver">
            <a href="inicio-seccion.php">← Volver al inicio de sesión</a>
        </p>

    </div>

</body>
</html>