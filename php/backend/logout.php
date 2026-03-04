<?php

//inicia la sesion
session_start();

//elimina todas las variables de sesion
session_unset();

//destruye la sesion
session_destroy();

//redirige al login
header("Location: ../login/inicio-seccion.php");
exit;