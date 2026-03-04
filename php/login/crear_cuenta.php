<?php
// php/login/crear-cuenta.php
session_start();

// Capturamos mensajes y los borramos para que no se queden pegados al recargar
$error = $_SESSION['error'] ?? '';
$ok    = $_SESSION['ok'] ?? '';
unset($_SESSION['error'], $_SESSION['ok']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta</title>
    <link rel="stylesheet" href="../../_css/crear-cuenta.css">
</head>

<body>

    <h1 class="titulo-principal">Z-CONTAY</h1>

    <div class="registro-container">

        <h2>Registrar nuevo usuario</h2>

        <?php if ($error): ?>
            <div class="alerta-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($ok): ?>
            <div class="alerta-ok">
                <?= htmlspecialchars($ok) ?>
            </div>
        <?php endif; ?>

        
        <form action="../backend/crear_cuenta.php" method="POST">
            
            <label>Nombre completo:</label>
            <input type="text" name="nombre_completo" placeholder="Escribe tu nombre completo" required>

            <label>Tipo de Documento:</label>
            <select name="tipo_documento" required>
                <option value="">seleccione...</option>
                <option value="CC">Cédula de ciudadanía</option>
                <option value="TI">Tarjeta de identidad</option>
            </select>        

            <label>Número de documento:</label>
            <input type="text" name="numero_documento" placeholder="1234" required>

            <label>Correo electrónico:</label>
            <input type="email" name="correo_electronico" placeholder="Correo válido" required>

            <label>Contraseña:</label>
            <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>

            <label>Confirmar contraseña:</label>
            <input type="password" name="password_confirm" placeholder="Repite la contraseña" required>

            <button type="submit">Crear cuenta</button>
        </form>

        <p class="volver">
            <a href="inicio-seccion.php">← Ya tengo una cuenta</a>
        </p>

    </div>

</body>
</html>