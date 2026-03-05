<?php
// php/administrador/editar_rol.php

session_start();
require_once "../backend/verificar_sesion.php";

// Si no hay sesión -> login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// Solo admin
if (($_SESSION['id_rol'] ?? 0) != 1) {
    header("Location: ../login/inicio-seccion.php");
    exit;
}

// ID del rol viene por URL: editar_rol.php?id=2
$id_rol = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_rol <= 0) {
    header("Location: roles_permisos.php");
    exit;
}

// Traer datos del rol + opciones + permisos
require_once "../backend/administrador/rol_editar_datos.php";

// Si no existe el rol
if (empty($rol)) {
    header("Location: roles_permisos.php?msg=bad");
    exit;
}

// Nombre del admin logueado
$nombreAdmin = $_SESSION['nombre'] ?? 'Administrador';

// Mensaje simple
$msg = $_GET['msg'] ?? '';

// Función simple: revisar si una opción ya está asignada
function estaMarcado($id_opcion, $lista) {
    return in_array((int)$id_opcion, $lista, true);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Rol - Administrador</title>

  <link rel="stylesheet" href="../../_css/admin-base.css" />
  <link rel="stylesheet" href="../../_css/editar-rol.css" />
</head>

<body>

<header class="header-bar">
  <div class="header-rol">Administrador</div>
  <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

  <div class="header-user">
    <span class="icon"><img src="../../img/campana.png" alt=""></span>
    <span><?= htmlspecialchars($nombreAdmin) ?></span>
    <span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>
  </div>
</header>

<div class="main-container">

  <nav class="sidebar">
    <ul>
      <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span>Panel Principal</a></li>
      <li><a href="gestion_usuarios.php"><span class="icon"><img src="../../img/usuario-gestion.png" alt=""></span>Gestión de usuarios</a></li>

      <li class="active-item">
        <a href="roles_permisos.php"><span class="icon"><img src="../../img/roles-permisos.png" alt=""></span>Roles y permisos</a>
      </li>

      <li><a href="crear_opciones.php"><span class="icon"><img src="../../img/opciones.jpg" alt=""></span>Crear Opciones</a></li>
      <li><a href="contabilidad.php"><span class="icon"><img src="../../img/contabilidad.png" alt=""></span>Contabilidad</a></li>
      <li><a href="ventas.php"><span class="icon"><img src="../../img/ventas.png" alt=""></span>Ventas</a></li>
      <li><a href="gestion_productos.php"><span class="icon"><img src="../../img/productos.png" alt=""></span>Productos</a></li>
      <li><a href="registrar_gasto.php"><span class="icon"><img src="../../img/gastos.png" alt=""></span>Registrar Gasto</a></li>
      <li><a href="inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span>Inventario</a></li>
      <li><a href="reportes.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span>Reportes</a></li>
      <li><a href="configuracion.php"><span class="icon"><img src="../../img/configuracion.png" alt=""></span>Configuración</a></li>
      <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span>Perfil</a></li>
      <li><a href="../backend/logout.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>Cerrar sesión</a></li>
    </ul>
  </nav>

  <main class="content-area">

    <h1 class="h1-title">Editar Rol</h1>
    <p class="subtitulo">Actualiza los datos del rol y los permisos asignados</p>

    <?php if ($msg === 'ok'): ?>
      <p style="margin-bottom:15px; color:green;">✅ Rol actualizado</p>
    <?php elseif ($msg === 'bad'): ?>
      <p style="margin-bottom:15px; color:red;">❌ Error al actualizar</p>
    <?php endif; ?>

    <div class="card-wrapper form-card">

      <div class="rol-meta">
        <div class="meta-item">
          <span class="meta-label">ID Rol:</span>
          <span class="meta-value"><?= (int)$rol['id_rol'] ?></span>
        </div>
      </div>



      <!-- Form envía al backend que actualiza -->
      <form class="form-rol" method="POST" action="../backend/administrador/rol_actualizar.php">

        <!-- Enviamos el id del rol oculto -->
        <input type="hidden" name="id_rol" value="<?= (int)$rol['id_rol'] ?>">

        <div class="seccion-form">
          <h3>Datos del Rol</h3>

          <div class="form-grid">
            <div class="form-group">
              <label for="nombre_rol">Nombre del Rol:</label>
              <input type="text" id="nombre_rol" name="nombre_rol"
                     value="<?= htmlspecialchars($rol['nombre']) ?>" required>
            </div>

            <div class="form-group">
              <label for="estado_rol">Estado:</label>
              <select id="estado_rol" name="estado_rol" required>
                <option value="A" <?= ($rol['estado'] === 'A') ? 'selected' : '' ?>>Activo</option>
                <option value="I" <?= ($rol['estado'] === 'I') ? 'selected' : '' ?>>Inactivo</option>
              </select>
            </div>

          </div>
        </div>

        <div class="seccion-form">
          <h3>Permisos del Rol</h3>
          <p class="instruccion">Marca las opciones que este rol podrá usar.</p>

          <div class="permisos-grid">

            <?php if (empty($opciones)): ?>
              <p>No hay opciones en la tabla <b>opciones</b>. Crea opciones primero.</p>
            <?php else: ?>
              <?php foreach ($opciones as $op): ?>
                <?php
                  $id_op = (int)$op['id_opciones'];
                  $checked = estaMarcado($id_op, $permisos_del_rol) ? 'checked' : '';
                ?>
                <label class="chk" style="display:block; margin-bottom:10px;">
                  <input type="checkbox" name="opciones[]" value="<?= $id_op ?>" <?= $checked ?>>
                  <span><?= htmlspecialchars($op['nombre_opcion']) ?></span>
                </label>
              <?php endforeach; ?>
            <?php endif; ?>

          </div>
        </div>

        <div class="form-actions">

          <button type="submit" class="main-button primary-button">Actualizar Rol</button>

          <a href="roles_permisos.php" class="main-button secondary-button">Cancelar</a>

          <!-- Eliminar rol (con confirmación) -->
          <form action="../backend/administrador/rol_eliminar.php" method="POST" style="display:inline;">
            <input type="hidden" name="id_rol" value="<?= (int)$rol['id_rol'] ?>">
            <button type="submit" class="main-button danger-button"
                    onclick="return confirm('¿Seguro que deseas eliminar este rol?');">
              Eliminar Rol
            </button>
          </form>

        </div>

      </form>
    </div>

    <div class="logo-footer">Z-CONTAY</div>

  </main>
</div>

</body>
</html>