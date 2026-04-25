<?php
session_start();

/* Seguridad */
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("control_inventario");

/* Backend */
require_once "../backend/encargado_planta/control_inventario.php";

/* Usuario */
$nombre = $_SESSION['nombre'] ?? 'Encargado de planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Inventario</title>

    <link rel="stylesheet" href="../../_css/control-inventario.css">
    <link rel="stylesheet" href="../../_css/admin-base.css">
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

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <ul>
            <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg"></span> Panel Principal</a></li>

            <li class="active-item">
                <a href="control_inventario.php">
                    <span class="icon"><img src="../../img/inventario.png"></span> Control de inventario
                </a>
            </li>

            <li><a href="gestion_despachos.php"><span class="icon"><img src="../../img/despachos.png"></span> Gestión de Despachos</a></li>

            <li><a href="zonas_entrega.php"><span class="icon"><img src="../../img/rutas.png"></span> Zonas de Entrega</a></li>

            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span> Perfil</a></li>

            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- CONTENIDO -->
    <main class="content-area">

        <!-- MENSAJE DE ÉXITO -->
        <?php if (isset($_GET['ok'])): ?>
            <div class="success-msg">
                Movimiento registrado correctamente
            </div>
        <?php endif; ?>

        <h1 class="h1-title">Control de Inventario</h1>
        <h3 class="subtitulo">Inventario actual</h3>

        <table class="tabla-inventario">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre Producto</th>
                    <th>Cantidad</th>
                    <th>Última actualización</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($productos)) : ?>
                    <?php foreach ($productos as $p) : ?>
                        <tr>
                            <td><?php echo (int)$p['id_producto']; ?></td>

                            <td><?php echo htmlspecialchars($p['nombre_producto']); ?></td>

                            <td>
                                <?php 
                                /* Si no hay inventario, mostrar 0 */
                                echo (int)($p['cantidad'] ?? 0); 
                                ?>
                            </td>

                            <td>
                                <?php 
                                if (!empty($p['ultima_actualizacion'])) {
                                    echo date("d/m/Y h:i A", strtotime($p['ultima_actualizacion']));
                                } else {
                                    echo "Sin movimientos";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- BOTÓN -->
        <button class="main-button"
            onclick="location.href='movimiento_entrada_salida.php'">
            Registrar movimiento
        </button>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

<!-- Script para ocultar mensaje -->
<script>
setTimeout(() => {
    const msg = document.querySelector('.success-msg');
    if (msg) msg.style.display = 'none';
}, 3000);
</script>

</body>
</html>