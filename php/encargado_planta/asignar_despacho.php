<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("gestion_despachos");

// 🔥 Backend que trae los datos reales
require_once "../backend/encargado_planta/asignar_despacho.php";

$nombre = $_SESSION['nombre'] ?? 'Encargado de Planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Despacho</title>

    <link rel="stylesheet" href="../../_css/control-inventario.css">
    <link rel="stylesheet" href="../../_css/encargado-registrar-despacho.css">
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
        <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg"></span> Panel Principal</a></li>
        <li><a href="control_inventario.php"><span class="icon"><img src="../../img/inventario.png"></span> Control de inventario</a></li>
        <li><a href="movimiento_entrada_salida.php"><span class="icon"><img src="../../img/entrada-salida.png"></span> Movimiento Entrada/Salida</a></li>
        <li class="active-item"><a href="gestion_despachos.php"><span class="icon"><img src="../../img/despachos.png"></span> Gestión de Despachos</a></li>
        <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span> Perfil</a></li>
        <li><a href="../backend/cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span> Cerrar sesión</a></li>
    </ul>
</nav>

<main class="content-area">

<h1 class="h1-title">Asignar Despacho</h1>
<h3 class="subtitulo">Asignación del pedido al proceso logístico</h3>

<?php if (isset($_GET['error'])): ?>
    <div class="error-msg">
        Debes seleccionar transportador y zona
    </div>
<?php endif; ?>

<form class="form-despacho" action="../backend/encargado_planta/asignar_despacho_guardar.php" method="POST">

    <!-- ID FACTURA -->
    <input type="hidden" name="id_factura" value="<?php echo $factura['id_factura']; ?>">

    <!-- ================= FACTURA ================= -->
    <div class="card-seccion">

    <h3>Información de la Factura</h3>

    <!-- FILA 1 -->
    <div class="grid-dos-columnas">
        <div>
            <label>Número de Factura</label>
            <input type="text" value="<?php echo $factura['id_factura']; ?>" readonly>
        </div>

        <div>
            <label>Cliente</label>
            <input type="text" value="<?php echo htmlspecialchars($factura['cliente']); ?>" readonly>
        </div>
    </div>

    <!-- FILA 2 -->
    <div class="grid-dos-columnas">
        <div>
            <label>Fecha</label>
            <input type="text" value="<?php echo date('d/m/Y', strtotime($factura['fecha'])); ?>" readonly>
        </div>

        <div>
            <label>Tipo de venta</label>
            <input type="text" value="<?php echo $factura['tipo_venta']; ?>" readonly>
        </div>
    </div>

    <!-- FILA 3 -->
    <div class="grid-dos-columnas">
        <div>
            <label>Total</label>
            <input type="text" value="$<?php echo number_format($factura['total'],0,',','.'); ?>" readonly>
        </div>

        <div>
            <label>Dirección de entrega</label>
            <input type="text" value="<?php echo htmlspecialchars($factura['direccion']); ?>" readonly>
        </div>
    </div>

</div>

    <!-- ================= PRODUCTOS ================= -->
    <div class="card-seccion">
        <h3>Detalle de Productos</h3>

        <table class="tabla-despachos">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>

                <?php if (!empty($detalle_productos)): ?>
                    <?php foreach ($detalle_productos as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['nombre_producto']); ?></td>
                            <td><?php echo $p['cantidad']; ?></td>
                            <td>$<?php echo number_format($p['precio_unitario'],0,',','.'); ?></td>
                            <td>$<?php echo number_format($p['subtotal'],0,',','.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay productos en esta factura</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <!-- ================= DESPACHO ================= -->
    <div class="card-seccion">
        <h3>Asignación del Despacho</h3>

        <div class="grid-dos-columnas">

            <div>
                <label>Seleccione el transportador</label>
                <select name="id_transportador" required>
                    <option value="">Seleccione un transportador</option>

                    <?php foreach ($transportadores as $t): ?>
                        <option value="<?php echo $t['id_transportador']; ?>">
                            <?php echo htmlspecialchars($t['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div>
                <label>Zona de entrega</label>
                <select name="zona_entrega" required>
                    <option value="">Seleccione una zona</option>
                    <option value="Norte">Zona Norte</option>
                    <option value="Sur">Zona Sur</option>
                    <option value="Centro">Zona Centro</option>
                    <option value="Oriente">Zona Oriente</option>
                    <option value="Occidente">Zona Occidente</option>
                </select>
            </div>

        </div>
    </div>

    <button type="submit" class="main-button">
        Confirmar Despacho
    </button>

</form>

<div class="logo-footer">Z-CONTAY</div>

</main>

</div>

</body>
</html>