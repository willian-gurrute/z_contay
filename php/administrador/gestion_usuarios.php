<?php
// php/administrador/gestion_usuarios.php

//iniciar session
session_start();

//verificar session.
require_once "../backend/verificar_sesion.php";

require_once "../backend/verificar_permiso.php"; // archivo que revisa permisos

verificarPermiso("gestion_usuarios");// nombre del controlador

// Si no hay usuario logueado enviar al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Solo permitir acceso al Administrador (rol 1)
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Traer lista real desde el backend
require_once "../backend/administrador/gestion_usuarios.php";

// Nombre del usuario logueado
$nombre = $_SESSION['nombre'] ?? 'Administrador';

//funcion para mostrar texto del estado.
function textoEstado(string $e): string {
    return ($e === 'A') ? 'Activo' : 'Inactivo';
}
function claseEstado(string $e): string {
    return ($e === 'A') ? 'activo' : 'inactivo';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - Administrador</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/gestion-usuarios.css">
</head>

<body>

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

    <nav class="sidebar">
        <ul>
            <li>
                <a href="panel_control.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                    Panel Principal
                </a>
            </li>

            <li>
                <a href="gestion_usuarios.php">
                    <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
                    Gestión de usuarios
                </a>
            </li>

            <li><a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a></li>
            <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg" alt=""></span>Crear Opciones</a></li>
            <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>
            <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>
            <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png" alt=""></span>Productos</a></li>
            <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
            <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span>Inventario</a></li>
            <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes</a></li>
            <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png" alt=""></span>Configuración</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>

            <li>
                <a href="../backend/logout.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>
                    Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

    <main class="content-area">

        <h1 class="h1-title">Gestión de Usuarios</h1>

        <!-- BOTÓN AGREGAR -->
        <button class="main-button admin-button" onclick="location.href='crear_usuario.php'">
            Crear usuario
        </button>

        <!-- TABLA DE USUARIOS -->
        <table class="tabla-usuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

            <!--verificamos si el areglo $usuarios esta vacio.
            viene del backend si esta vacio es por que no hay usuarios-->

            <?php if (empty($usuarios)): ?>
                <tr>
                    <!--significa que la celda ocupara las 5 columnas de la tabla-->
                    <td colspan="5">No hay usuarios registrados.</td>
                </tr>
            <?php else: ?>

                <!--si hay usuarios, usamos foreach para rrecorelos.
                foreach signinifica: recorrer elemento por elemento.
                $usuarios= lista completa
                $u=usuario actual dentro del recorrido -->

                <?php foreach ($usuarios as $u): ?>
                    <?php
                    //mostramos id del usuario. int convierte el valor en numero entero por seguridad.
                        $id = (int)$u['id_usuario'];
                        $estado = (string)$u['estado'];
                        $esActivo = ($estado === 'A');
                    ?>

                    <tr>
                        <td><?= $id ?></td>

                        <!--Mostramos el nombre del usuario.
                        htmlspecialchars evita problemas de seguridad si alguien
                        intentara guardar código HTML en la base de datos.-->
                        <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                        
                        <!--Mostramos el nombre del rol ejemplo:cliente-->
                        <td><?= htmlspecialchars($u['nombre_rol']) ?></td>

                        <!--Aquí mostramos el estado del usuario. La clase CSS cambia dependiendo si está activo o inactivo.
                        Esto permite poner colores diferentes en el CSS.-->
                        <td class="estado <?= claseEstado($estado) ?>">
                        
                        <!--Esta función convierte la letra del estado en texto.
                        A  = Activo
                        I  = Inactivo -->
                           <?= textoEstado($estado) ?>
                        </td>
                        <td class="acciones">


                          <!--BOTÓN EDITar
                          Cuando se presiona este botón se abre la pantalla editar_usuario.php
                          y se envía el ID del usuario por la URL.
                          Ejemplo de URL:
                          editar_usuario.php?id=5-->
                            <a href="editar_usuario.php?id=<?= $id ?>" class="btn-editar">Editar</a>
                            
                            <!--Aquí verificamos el estado del usuario.Si el estado es 'A' significa que está ACTIVO.-->
                            <?php if ($esActivo): ?>
                                
                                <!--Si está activo mostramos el botón DESACTIVAR-->
                                <form action="../backend/administrador/usuario_estado.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

                                    <!--Campo oculto que envía el ID del usuario al backend-->
                                    <input type="hidden" name="id_usuario" value="<?= $id ?>">

                                    <!--Campo oculto que indica qué acción se quiere hacer-->
                                    <input type="hidden" name="accion" value="desactivar">
                                    <button type="submit" class="btn-desactivar">Desactivar</button>
                                </form>
                            <?php else: ?>

                                <!--Si el usuario está inactivo mostramos el botón ACTIVAR-->

                                <form action="../backend/administrador/usuario_estado.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                    <input type="hidden" name="id_usuario" value="<?= $id ?>">
                                    <input type="hidden" name="accion" value="activar">
                                    <button type="submit" class="btn-activar">Activar</button>
                                </form>
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>