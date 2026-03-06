<?php
// php/administrador/ajustes_generales.php

session_start();
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

// Permiso
verificarPermiso("configuracion");

// Traer datos de empresa
require_once "../backend/administrador/ajustes_generales.php";

// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';

// Mensaje simple
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ajustes Generales - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/ajustes-generales.css">
   
</head>

<body>

    <!-- BARRA SUPERIOR -->
    <header class="header-bar">
        <div class="header-rol">Administrador</div>
        <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

        <div class="header-user">
            <span class="icon"><img src="../../img/campana.png" alt=""></span>
            <span><?= htmlspecialchars($nombre) ?></span>
            <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
        </div>
    </header>

    <div class="main-container">

        <!-- MENÚ LATERAL -->
        <nav class="sidebar">
            <ul>
                <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg"></span>Panel Principal</a></li>
                <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png"></span>Gestión de usuarios</a></li>
                <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png"></span>Roles y permisos</a></li>
                <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg"></span>Crear Opciones</a></li>
                <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png"></span>Contabilidad</a></li>
                <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png"></span>Ventas</a></li>
                <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png"></span>Productos</a></li>
                <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
                <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png"></span>Inventario</a></li>
                <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png"></span>Reportes</a></li>
                <li><a href="configuracion.php" class="active-item"><span class="icon"><img src="../../img/configuracion.png"></span>Configuración</a></li>
                <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span>Perfil</a></li>
                <li><a href="../backend/logout.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span>Cerrar sesión</a></li>
            </ul>
        </nav>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="content-area">

            <h1 class="h1-title">Ajustes Generales</h1>
            <p class="subtitulo">Modifica la información del negocio, logo, horarios y datos institucionales</p>

            <?php if ($msg == "ok"): ?>
                <p style="color:green;">Datos actualizados correctamente.</p>
            <?php endif; ?>

            <?php if ($msg == "error"): ?>
                <p style="color:red;">No se pudieron actualizar los datos.</p>
            <?php endif; ?>

            <!-- FORMULARIO -->
            <form class="form-ajustes" method="POST" action="../backend/administrador/actualizar_ajustes_generales.php" enctype="multipart/form-data">

                <input type="hidden" name="id_empresa" value="<?= (int)$empresa['id_empresa'] ?>">

                <label for="nombre-negocio">Nombre del negocio:</label>
                <input type="text" id="nombre-negocio" name="nombre" placeholder="Nombre del negocio"
                       value="<?= htmlspecialchars($empresa['nombre'] ?? '') ?>">

                <label for="nit">NIT:</label>
                <input type="text" id="nit" name="nit" placeholder="NIT del negocio"
                       value="<?= htmlspecialchars($empresa['nit'] ?? '') ?>">

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" placeholder="Dirección del negocio"
                       value="<?= htmlspecialchars($empresa['direccion'] ?? '') ?>">

                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad"
                       value="<?= htmlspecialchars($empresa['ciudad'] ?? '') ?>">

                <label for="departamento">Departamento:</label>
                <input type="text" id="departamento" name="departamento" placeholder="Departamento"
                       value="<?= htmlspecialchars($empresa['departamento'] ?? '') ?>">

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" placeholder="Teléfono"
                       value="<?= htmlspecialchars($empresa['telefono'] ?? '') ?>">

                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" placeholder="Correo electrónico"
                       value="<?= htmlspecialchars($empresa['correo'] ?? '') ?>">

                <label for="horario">Horario de atención:</label>
                <input type="text" id="horario" name="horario_atencion"
                       placeholder="Ej: Lunes a Viernes 7:00-17:00"
                       value="<?= htmlspecialchars($empresa['horario_atencion'] ?? '') ?>">

                <label for="logo">Logo del negocio:</label>
                <input type="file" id="logo" name="logo">

                <?php if (!empty($empresa['logo'])): ?>
                    <p>Logo actual: <?= htmlspecialchars($empresa['logo']) ?></p>
                <?php endif; ?>

                <button type="submit" class="main-button">Guardar cambios</button>

            </form>

            <div class="logo-footer">Z-CONTAY</div>

        </main>

    </div>

</body>
</html>