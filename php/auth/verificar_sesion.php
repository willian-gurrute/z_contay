<?php

//inicia la sesion
session_start();

//verifica si el usuario ha iniciado sesion
if(!isset($_SESSION['id_usuario'])){

    //si no ha iniciado sesion lo envia al login
    header("Location: ../login/inicio-seccion.php");
    exit;

}