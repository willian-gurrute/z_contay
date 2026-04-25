<?php
session_start();

/* 
   Verificamos sesión y permiso
*/
require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";
verificarPermiso("control_inventario");

/* 
   Cargamos los productos desde backend
*/
require_once "../backend/encargado_planta/movimiento_entrada_salida.php";

/* 
   Nombre del usuario logueado
*/
$nombre = $_SESSION['nombre'] ?? 'Encargado de planta';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimiento Entrada/Salida</title>

    <link rel="stylesheet" href="../../_css/control-inventario.css">
    <link rel="stylesheet" href="../../_css/encargado-movimiento-entrada-salida.css">
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
            <li><a href="panel_control.php"><span class="icon"><img src="../../img/panel.jpg" alt=""></span> Panel Principal</a></li>

            <li><a href="control_inventario.php"><span class="icon"><img src="../../img/inventario.png" alt=""></span> Control de Inventario</a></li>

            <li><a href="gestion_despachos.php"><span class="icon"><img src="../../img/despachos.png" alt=""></span> Gestión de Despachos</a></li>

            <li><a href="zonas_entrega.php"><span class="icon"><img src="../../img/rutas.png" alt=""></span> Zonas de Entrega</a></li>

            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png" alt=""></span> Perfil</a></li>

            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">

        <!-- MENSAJES -->
        <?php if (isset($_GET['ok'])): ?>
            <div class="success-msg">
                Movimiento registrado correctamente.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">
                <?php
                if ($_GET['error'] === 'stock') {
                    echo "No hay suficiente stock para registrar la salida.";
                } elseif ($_GET['error'] === 'datos') {
                    echo "Debe completar todos los campos correctamente.";
                } else {
                    echo "Ocurrió un error al registrar el movimiento.";
                }
                ?>
            </div>
        <?php endif; ?>

        <h1 class="h1-title">Registrar Movimiento</h1>
        <h3 class="subtitulo">Entrada o salida de producto</h3>

        <form class="form-movimiento" action="../backend/encargado_planta/movimiento_entrada_salida_guardar.php" method="POST">

            <label>Tipo de movimiento:</label>
            <select name="tipo_movimiento" required>
                <option value="">Seleccione una opción</option>
                <option value="entrada">Entrada</option>
                <option value="salida">Salida</option>
            </select>

            <label>Producto:</label>
            <select name="id_producto" required>
                <option value="">Seleccione un producto</option>
                <?php if (!empty($productos)) : ?>
                    <?php foreach ($productos as $producto) : ?>
                        <option value="<?php echo (int)$producto['id_producto']; ?>">
                            <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <label>Cantidad:</label>
            <input type="number" name="cantidad" min="1" placeholder="Ej: 50" required>

            <button type="submit" class="main-button">
                Guardar Movimiento
            </button>

        </form>

        <div class="logo-footer">Z-CONTAY</div>
    </main>
</div>

<script>
setTimeout(() => {
    const msgOk = document.querySelector('.success-msg');
    const msgError = document.querySelector('.error-msg');
    if (msgOk) msgOk.style.display = 'none';
    if (msgError) msgError.style.display = 'none';
}, 3000);
</script>

</body>
</html>