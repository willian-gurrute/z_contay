<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("gestion_despachos");

require_once "../backend/encargado_planta/ver_incidencia.php";

$nombre = $_SESSION['nombre'] ?? 'Encargado de planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Incidencia</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/encargado-registrar-despacho.css">
</head>
<body>

<header class="header-bar">

    <div class="header-rol">
        Encargado de planta
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/usuario-gestion.png" alt="">
        </span>

    </div>

</header>

<div class="main-container">
    <nav class="sidebar">
        <ul>
            <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel principal</a></li>
            <li><a href="control_inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de inventario</a></li>
            <li><a href="movimiento_entrada_salida.php"><span class="icon"><img src="../../img/entrada-salida.png" alt=""></span> Movimiento Entrada/Salida</a></li>
            <li class="active-item"><a href="gestion_despachos.php"><span class="icon"><img src="../../img/despachos.png" alt=""></span> Gestión de Despachos</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">
        <h1 class="h1-title">Detalle de Incidencia</h1>
        <h3 class="subtitulo">Información del despacho con novedad</h3>

        <div class="card-seccion">
            <h3>Información del Despacho</h3>

            <div class="grid-dos-columnas">
                <div>
                    <label>ID Despacho</label>
                    <input type="text" value="<?php echo (int)$incidencia['id_despacho']; ?>" readonly>
                </div>
                <div>
                    <label>Estado</label>
                    <input type="text" value="<?php echo htmlspecialchars(ucfirst($incidencia['estado_despacho'])); ?>" readonly>
                </div>
            </div>

            <div class="grid-dos-columnas">
                <div>
                    <label>Cliente</label>
                    <input type="text" value="<?php echo htmlspecialchars($incidencia['cliente']); ?>" readonly>
                </div>
                <div>
                    <label>Transportador</label>
                    <input type="text" value="<?php echo htmlspecialchars($incidencia['transportador']); ?>" readonly>
                </div>
            </div>

            <div class="grid-dos-columnas">
                <div>
                    <label>Dirección</label>
                    <input type="text" value="<?php echo htmlspecialchars($incidencia['direccion'] ?? 'Sin dirección registrada'); ?>" readonly>
                </div>
                <div>
                    <label>Zona de entrega</label>
                    <input type="text" value="<?php echo htmlspecialchars($incidencia['zona_entrega'] ?? 'Sin zona registrada'); ?>" readonly>
                </div>
            </div>
        </div>

        <div class="card-seccion">
            <h3>Información de la Incidencia</h3>

            <div class="grid-dos-columnas">
                <div>
                    <label>Tipo de incidencia</label>
                    <input type="text" value="<?php echo htmlspecialchars($incidencia['tipo_incidencia'] ?? 'No registrada'); ?>" readonly>
                </div>
                <div>
                    <label>Fecha del reporte</label>
                    <input type="text" value="<?php echo !empty($incidencia['fecha_reporte']) ? date('d/m/Y h:i A', strtotime($incidencia['fecha_reporte'])) : 'Sin registro'; ?>" readonly>
                </div>
            </div>

            <div>
                <label>Observaciones</label>
                <textarea readonly><?php echo htmlspecialchars($incidencia['observaciones'] ?? 'Sin observaciones'); ?></textarea>
            </div>
        </div>

        <div class="card-seccion">
            <h3>Acciones</h3>

            <div style="display:flex; gap:15px; flex-wrap:wrap;">
                <a href="asignar_despacho.php?id_despacho=<?php echo (int)$incidencia['id_despacho']; ?>"
                   class="main-button"
                   style="display:inline-block; text-align:center;">
                    Reasignar despacho
                </a>

                <a href="gestion_despachos.php"
                   class="main-button"
                   style="display:inline-block; text-align:center; background:#6c757d;">
                    Volver
                </a>
            </div>
        </div>

        <div class="logo-footer">Z-CONTAY</div>
    </main>
</div>

</body>
</html>