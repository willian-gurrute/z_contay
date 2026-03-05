<?php
// php/backend/verificar_sesion.php

//session_start() para iniciar sesión del usuario.
//session_status() para verificar si la sesión ya existe.

if (session_status() === PHP_SESSION_NONE) {
    // Si la sesión NO está iniciada, la iniciamos
    session_start();
}

//  si existe el id del usuario en la sesión se guarda información.
if (!isset($_SESSION['id_usuario'])) {
        // Si no existe la sesión significa que el usuario no ha iniciado sesión
    // Lo redirigimos nuevamente a la pantalla de login
    header("Location: ../login/inicio-seccion.php");

     // exit detiene la ejecución del script
    exit;
}

//Si el usuario sí tiene sesión activa,
//el sistema continúa cargando normalmente la página protegida.