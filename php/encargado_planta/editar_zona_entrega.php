<?php
session_start();

/* 
   Verificamos que el usuario haya iniciado sesión
   y que tenga permiso para entrar a esta pantalla
*/
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("zonas_entrega");

/* 
   Cargamos el backend que trae los datos del transportador
*/
require_once "../backend/encargado_planta/editar_zona_entrega.php";

/* 
   Nombre del usuario logueado para mostrarlo en la parte superior
*/
$nombre = $_SESSION['nombre'] ?? 'Encargado de planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Zona de Entrega</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/encargado-registrar-despacho.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Encargado de planta</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png" alt=""></span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
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
                <a href="cerrar_sesion.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Editar Zona de Entrega</h1>
        <h3 class="subtitulo">Asignar o cambiar la zona del transportador</h3>

        <form class="form-despacho" action="../backend/encargado_planta/editar_zona_entrega_guardar.php" method="POST">

            <!-- 
                Enviamos oculto el id del transportador
                para saber cuál vamos a actualizar
            -->
            <input type="hidden" name="id_transportador" value="<?php echo (int)$transportador['id_transportador']; ?>">

            <div class="card-seccion">
                <h3>Información del Transportador</h3>

                <div class="grid-dos-columnas">
                    <div>
                        <label>ID Transportador</label>
                        <input type="text" value="<?php echo (int)$transportador['id_transportador']; ?>" readonly>
                    </div>

                    <div>
                        <label>Nombre</label>
                        <input type="text" value="<?php echo htmlspecialchars($transportador['nombre_completo']); ?>" readonly>
                    </div>
                </div>

                <div class="grid-dos-columnas">
                    <div>
                        <label>Teléfono</label>
                        <input type="text" value="<?php echo htmlspecialchars($transportador['telefono']); ?>" readonly>
                    </div>

                    <div>
                        <label>Licencia</label>
                        <input type="text" value="<?php echo htmlspecialchars($transportador['tipo_licencia']); ?>" readonly>
                    </div>
                </div>

                <div class="grid-dos-columnas">
                    <div>
                        <label>Estado</label>
                        <input type="text" value="<?php echo ($transportador['estado'] === 'A') ? 'Activo' : 'Inactivo'; ?>" readonly>
                    </div>

                    <div>
                        <label>Zona actual</label>
                        <input type="text" value="<?php echo htmlspecialchars($transportador['zona_asignada'] ?: 'Sin asignar'); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="card-seccion">
                <h3>Nueva Zona</h3>

                <div class="grid-dos-columnas">
                    <div>
                        <label>Seleccione la zona</label>
                        <select name="zona_asignada" required>
                            <option value="">Seleccione una zona</option>
                            <option value="Norte" <?php echo ($transportador['zona_asignada'] === 'Norte') ? 'selected' : ''; ?>>Zona Norte</option>
                            <option value="Sur" <?php echo ($transportador['zona_asignada'] === 'Sur') ? 'selected' : ''; ?>>Zona Sur</option>
                            <option value="Centro" <?php echo ($transportador['zona_asignada'] === 'Centro') ? 'selected' : ''; ?>>Zona Centro</option>
                            <option value="Oriente" <?php echo ($transportador['zona_asignada'] === 'Oriente') ? 'selected' : ''; ?>>Zona Oriente</option>
                            <option value="Occidente" <?php echo ($transportador['zona_asignada'] === 'Occidente') ? 'selected' : ''; ?>>Zona Occidente</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="main-button">
                Guardar zona
            </button>

        </form>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>