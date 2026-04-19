<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("gestion_despachos");

require_once "../backend/encargado_planta/asignar_despacho.php";

$nombre = $_SESSION['nombre'] ?? 'Encargado de Planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $modo_reasignacion ? 'Reasignar Despacho' : 'Asignar Despacho'; ?></title>

    <link rel="stylesheet" href="../../_css/control-inventario.css">
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
        <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel Principal</a></li>
        <li><a href="control_inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de inventario</a></li>
        <li><a href="movimiento_entrada_salida.php"><span class="icon"><img src="../../img/entrada-salida.png" alt=""></span> Movimiento Entrada/Salida</a></li>
        <li class="active-item"><a href="gestion_despachos.php"><span class="icon"><img src="../../img/despachos.png" alt=""></span> Gestión de Despachos</a></li>
        <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil</a></li>
        <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión</a></li>
    </ul>
</nav>

<main class="content-area">

<h1 class="h1-title"><?php echo $modo_reasignacion ? 'Reasignar Despacho' : 'Asignar Despacho'; ?></h1>
<h3 class="subtitulo">Asignación del pedido al proceso logístico</h3>

<form class="form-despacho" action="../backend/encargado_planta/asignar_despacho_guardar.php" method="POST">

    <input type="hidden" name="id_factura" value="<?php echo $factura['id_factura']; ?>">
    <input type="hidden" name="id_despacho" value="<?php echo $despacho_actual['id_despacho'] ?? ''; ?>">

    <div class="card-seccion">
        <h3>Información de la Factura</h3>

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

  <div class="card-seccion">
    <h3>Asignación del Despacho</h3>

    <div class="grid-dos-columnas">
        <div>
            <label>Zona de entrega</label>
            <select name="zona_entrega" id="zona_entrega" required>
                <option value="">Seleccione una zona</option>
                <option value="Norte" <?php echo (isset($despacho_actual['zona_entrega']) && $despacho_actual['zona_entrega'] === 'Norte') ? 'selected' : ''; ?>>Zona Norte</option>
                <option value="Sur" <?php echo (isset($despacho_actual['zona_entrega']) && $despacho_actual['zona_entrega'] === 'Sur') ? 'selected' : ''; ?>>Zona Sur</option>
                <option value="Centro" <?php echo (isset($despacho_actual['zona_entrega']) && $despacho_actual['zona_entrega'] === 'Centro') ? 'selected' : ''; ?>>Zona Centro</option>
                <option value="Oriente" <?php echo (isset($despacho_actual['zona_entrega']) && $despacho_actual['zona_entrega'] === 'Oriente') ? 'selected' : ''; ?>>Zona Oriente</option>
                <option value="Occidente" <?php echo (isset($despacho_actual['zona_entrega']) && $despacho_actual['zona_entrega'] === 'Occidente') ? 'selected' : ''; ?>>Zona Occidente</option>
            </select>
        </div>

        <div>
            <label>Seleccione el transportador</label>
            <select name="id_transportador" id="id_transportador" required>
                <option value="">Seleccione un transportador</option>
                <?php foreach ($transportadores as $t): ?>
                    <option 
                        value="<?php echo $t['id_transportador']; ?>"
                        data-zona="<?php echo htmlspecialchars($t['zona_asignada'] ?? ''); ?>"
                        <?php echo (isset($despacho_actual['id_transportador']) && $despacho_actual['id_transportador'] == $t['id_transportador']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($t['nombre_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

    <button type="submit" class="main-button">
        <?php echo $modo_reasignacion ? 'Guardar reasignación' : 'Confirmar despacho'; ?>
    </button>

</form>

<div class="logo-footer">Z-CONTAY</div>

</main>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const zonaSelect = document.getElementById("zona_entrega");
    const transportadorSelect = document.getElementById("id_transportador");

    const opcionesOriginales = Array.from(transportadorSelect.querySelectorAll("option"));

    function filtrarTransportadores() {
        const zonaSeleccionada = zonaSelect.value;

        transportadorSelect.innerHTML = "";

        const opcionDefault = document.createElement("option");
        opcionDefault.value = "";
        opcionDefault.textContent = "Seleccione un transportador";
        transportadorSelect.appendChild(opcionDefault);

        opcionesOriginales.forEach(opcion => {
            const zona = opcion.getAttribute("data-zona");

            if (opcion.value === "") {
                return;
            }

            if (zonaSeleccionada !== "" && zona === zonaSeleccionada) {
                transportadorSelect.appendChild(opcion.cloneNode(true));
            }
        });
    }

    zonaSelect.addEventListener("change", filtrarTransportadores);

    // Si ya viene una zona seleccionada (por reasignación), filtrar al cargar
    if (zonaSelect.value !== "") {
        filtrarTransportadores();

        <?php if (isset($despacho_actual['id_transportador'])): ?>
        const transportadorActual = "<?php echo $despacho_actual['id_transportador']; ?>";
        transportadorSelect.value = transportadorActual;
        <?php endif; ?>
    }
});
</script>

</body>
</html>