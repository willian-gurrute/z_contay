<?php
// php/backend/verificar_permiso.php
// Verifica si el rol del usuario puede entrar a una pantalla

require_once __DIR__ . "/conexion.php";

function verificarPermiso(string $controlador): void
{
    // Si no hay usuario logueado, lo mandamos al login
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../login/inicio-seccion.php");
        exit;
    }

    $idRol = (int)($_SESSION['id_rol'] ?? 0);

    global $conn;

    // Consulta: ¿este rol tiene permiso para este controlador?
    $sql = "
        SELECT o.id_opciones
        FROM permisos p
        INNER JOIN opciones o ON o.id_opciones = p.id_opciones
        WHERE p.id_rol = ?
          AND o.nombre_controlador = ?
          AND o.estado = 'A'
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $idRol, $controlador);
    $stmt->execute();
    $res = $stmt->get_result();
    

    // Si no hay permiso, mostramos mensaje
    if (!$res || $res->num_rows === 0) {
    $rutaVolver = "../login/inicio-seccion.php";

    if ($idRol === 1) {
        $rutaVolver = "../administrador/panel_control.php";
    } elseif ($idRol === 2) {
        $rutaVolver = "../vendedor/panel_principal.php";
    } elseif ($idRol === 3) {
        $rutaVolver = "../encargado_planta/panel_control.php";
    } elseif ($idRol === 4) {
        $rutaVolver = "../transportador/panel_principal.php";
    } elseif ($idRol === 5) {
        $rutaVolver = "../cliente/portafolio.php";
    }

    echo "<h2>Acceso denegado</h2>";
    echo "<p>No tiene permiso para entrar a esta sección.</p>";
    echo "<a href='{$rutaVolver}'>Volver al panel</a>";
    exit;
   }
}