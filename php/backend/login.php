<?php
//inicia el sistema.
session_start();

//trae el archivo coneccion a la base de datos.
require_once "conexion.php";

//recibe datos del formulario.

// capturamos lo que el usuario escribio en el formulario.
$correo   = $_POST['correo_electronico'] ?? '';
$password = $_POST['password'] ?? '';

//se valida campos vacios.
// si alguno esta vacio no sigue.
if (empty($correo) || empty($password)) {

    //guardamos mensaje de error en sesion.
    $_SESSION['error'] = "Debe completar todos los campos";

    //redirigimos al login.
    header("Location: ../login/inicio-seccion.php");

    //se detiene el codigo.
    exit;
}

//para buscar usuario en la base de datos.

//solo se busca usuarios activos.
$sql = "SELECT * FROM usuario
        WHERE correo_electronico = ?
        AND estado = 'A'
        LIMIT 1";

//para preparar la consulta.
$stmt = $conn->prepare($sql);

//remplazamos el ? por el correo.
$stmt->bind_param("s", $correo);

//ejecutamos la consulta.
$stmt->execute();

//se obtine el resultado.
$result = $stmt->get_result();

//se verifica si el usuario existe.
if ($result->num_rows === 1) {

    //convertimos el resultado en un arreglo asociativo.
    $usuario = $result->fetch_assoc();

    //se valida contraseña.
    //comparamos la contraseña escrita con la base de datos.
    if ($password === $usuario['password']) {

        //crear seccion.
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre']     = $usuario['nombre_completo'];
        $_SESSION['id_rol']     = $usuario['id_rol'];

        //se redirecciona segun rol.
        switch ($usuario['id_rol']) {

            case 1: //administrador.
                header("Location: ../administrador/panel_control.php");
                break;

            case 2: //vendedor.
                header("Location: ../vendedor/panel_principal.php");
                break;

            case 3: //encargado de planta.
                header("Location: ../encargado_planta/panel_control.php");
                break;

            case 4: //transportador.
                header("Location: ../transportador/panel_principal.php");
                break;

            case 5: //cliente.
                header("Location: ../cliente/panel_principal.php");
                break;

            default:
                //si el no esta definido.
                header("Location: ../login/inicio-seccion.php");
                break;
        }

        // siempre detener despues de redirigir.
        exit;

    } else {

        //si la contraseña no coincide.
        $_SESSION['error'] = "Contraseña incorrecta";
        header("Location: ../login/inicio-seccion.php");
        exit;
    }

} else {

    //si no encontro ningun usuario con ese correo activo.
    $_SESSION['error'] = "El usuario no existe o esta inactivo";
    header("Location: ../login/inicio-seccion.php");
    exit;
}