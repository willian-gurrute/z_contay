<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("zonas_despachos");

require_once "../backend/transportador/gestion_entrega.php";

$nombre = $_SESSION['nombre'] ?? 'Transportador';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestionar entrega</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/transportador-panel-principal.css">
</head>

<body>

<header class="header-bar">

    <div class="header-rol">
        Transportador
    </div>

    <div class="header-system">
        Z-CONTAY - Galpón Aves del Paraíso
    </div>

    <div class="header-user">

        <span>
            <?php echo htmlspecialchars($nombre); ?>
        </span>

        <span class="icon">
            <img src="../../img/perfil.png" width="24" alt="Perfil">
        </span>

    </div>

</header>

<div class="main-container">

<nav class="sidebar">
    <ul>
        <li>
            <a href="panel_principal.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel principal</a>
        </li>

        <li class="active-item">
            <a href="zonas_despachos.php"><span class="icon"><img src="../../img/despachos.png" alt=""></span> Zonas y despachos</a>
        </li>

        <li>
            <a href="listado_pedidos.php"><span class="icon"><img src="../../img/listado-pedidos.png" alt=""></span> Listado de pedidos</a>
        </li>

        <li>
            <a href="reportes_despachos.php"><span class="icon"><img src="../../img/reportes.png" alt=""></span> Reportes</a>
        </li>

        <li>
            <a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil</a>
        </li>

        <li>
            <a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión</a>
        </li>
    </ul>
</nav>

<main class="content-area">

<h1 class="h1-title">Gestionar despacho</h1>

<div class="gestion-card">
    <h2 class="gestion-subtitle">Información del despacho</h2>

    <div class="gestion-grid">
        <div class="dato-item">
            <span class="dato-label">Cliente</span>
            <span class="dato-valor"><?php echo htmlspecialchars($despacho['cliente']); ?></span>
        </div>

        <div class="dato-item">
            <span class="dato-label">Dirección</span>
            <span class="dato-valor"><?php echo htmlspecialchars($despacho['direccion']); ?></span>
        </div>

        <div class="dato-item">
            <span class="dato-label">Zona</span>
            <span class="dato-valor"><?php echo htmlspecialchars($despacho['zona_entrega']); ?></span>
        </div>

        <div class="dato-item">
            <span class="dato-label">Estado</span>
            <span class="dato-valor estado estado-<?php echo strtolower($despacho['estado']); ?>">
                <?php echo htmlspecialchars(ucfirst($despacho['estado'])); ?>
            </span>
        </div>

        <div class="dato-item dato-item-full">
            <span class="dato-label">Productos</span>
            <span class="dato-valor"><?php echo htmlspecialchars($despacho['productos']); ?></span>
        </div>
    </div>
</div>

<div class="acciones-gestion">

    <div class="accion-box">
        <h3 class="accion-titulo">Confirmar entrega</h3>
        <p class="accion-texto">Marca este despacho como entregado al cliente.</p>

        <form action="../backend/transportador/gestion_entrega.php" method="POST">
            <input type="hidden" name="accion" value="confirmar">
            <input type="hidden" name="id_despacho" value="<?php echo (int)$despacho['id_despacho']; ?>">

            <button type="submit" class="btn-accion btn-confirmar-entrega">
                Confirmar entrega
            </button>
        </form>
    </div>

    <div class="accion-box">
        <h3 class="accion-titulo">Reportar incidencia</h3>
        <p class="accion-texto">Si hubo algún problema, regístralo aquí.</p>

        <form action="../backend/transportador/gestion_entrega.php" method="POST" class="form-incidencia">
            <input type="hidden" name="accion" value="incidencia">
            <input type="hidden" name="id_despacho" value="<?php echo (int)$despacho['id_despacho']; ?>">

            <select name="id_tipoIncidencia" required class="input-gestion">
                <option value="">Seleccione incidencia</option>
                <?php foreach ($tipos_incidencia as $tipo): ?>
                    <option value="<?php echo (int)$tipo['id_tipoIncidencia']; ?>">
                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="text" name="observaciones" placeholder="Escriba una observación" required class="input-gestion">

            <button type="submit" class="btn-accion btn-reportar-incidencia">
                Reportar incidencia
            </button>
        </form>
    </div>

</div>

<div class="logo-footer">Z-CONTAY</div>

</main>
</div>
</body>
</html>