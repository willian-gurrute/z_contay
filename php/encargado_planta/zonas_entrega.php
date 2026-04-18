<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("zonas_entrega");

require_once "../backend/encargado_planta/zonas_entrega.php";

$nombre = $_SESSION['nombre'] ?? 'Encargado de planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Zonas de Entrega</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/encargado-zonas-entrega.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">Encargado de planta</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png"></span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon"><img src="../../img/usuario-gestion.png"></span>
    </div>
</header>

<div class="main-container">

<nav class="sidebar">
    <ul>
        <li>
            <a href="panel_control.php">
                <span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel Principal
            </a>
        </li>

        <li>
            <a href="control_inventario.php">
                <span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de inventario
            </a>
        </li>

        <li>
            <a href="movimiento_entrada_salida.php">
                <span class="icon"><img src="../../img/entrada-salida.png" alt=""></span> Movimiento Entrada/Salida
            </a>
        </li>

        <li>
            <a href="gestion_despachos.php">
                <span class="icon"><img src="../../img/despachos.png" alt=""></span> Gestión de Despachos
            </a>
        </li>

        <li class="active-item">
            <a href="zonas_entrega.php">
                <span class="icon"><img src="../../img/rutas.png" alt=""></span> Zonas de Entrega
            </a>
        </li>

        <li>
            <a href="perfil.php">
                <span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil
            </a>
        </li>

        <li>
            <a href="../backend/cerrar_sesion.php">
                <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión
            </a>
        </li>
    </ul>
</nav>

<main class="content-area">

    <h1 class="h1-title">Zonas de Entrega</h1>
    <h3 class="subtitulo">Asignación de zonas a transportadores</h3>

    <table class="tabla-rutas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Transportador</th>
                <th>Teléfono</th>
                <th>Licencia</th>
                <th>Zona</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($transportadores)) : ?>
                <?php foreach ($transportadores as $t) : ?>
                    <tr>
                        <td><?php echo $t['id']; ?></td>
                        <td><?php echo htmlspecialchars($t['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($t['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($t['licencia']); ?></td>

                        <td>
                            <?php echo $t['zona'] ?: 'Sin asignar'; ?>
                        </td>

                        <td class="<?php echo $t['estado_clase']; ?>">
                            <?php echo ($t['estado'] == 'A') ? 'Activo' : 'Inactivo'; ?>
                        </td>

                        <td>
                            <button class="main-button btn-tabla"
                                onclick="location.href='editar_zona_entrega.php?id=<?php echo $t['id']; ?>'">
                                Editar zona
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">No hay transportadores registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="logo-footer">Z-CONTAY</div>

</main>
</div>

</body>
</html>