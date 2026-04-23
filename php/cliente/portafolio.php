<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";



verificarPermiso("portafolio");

require_once "../backend/cliente/portafolio.php";

$nombre = $_SESSION['nombre'] ?? 'Cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente - Portafolio de Productos</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/portafolio.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Cliente</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon">
            <img src="../../img/campana.png" alt="Notificaciones" width="24" height="24">
        </span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon">
            <img src="../../img/perfil.png" alt="Perfil de usuario" width="24" height="24">
        </span>
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li class="active-item">
                <a href="portafolio.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                    Portafolio
                </a>
            </li>

            <li>
                <a href="realizar_pedido.php">
                    <span class="icon"><img src="../../img/carrito.png" alt=""></span>
                    Realizar pedido
                </a>
            </li>

            <li>
                <a href="historial_pedidos.php">
                    <span class="icon"><img src="../../img/historial.png" alt=""></span>
                    Historial de pedidos
                </a>
            </li>

            <li>
                <a href="perfil.php">
                    <span class="icon"><img src="../../img/perfil.png" alt=""></span>
                    Perfil
                </a>
            </li>

            <li>
                <a href="cerrar_sesion.php">
                    <span class="icon"><img src="../../img/cerrar-seccion.png" alt=""></span>
                    Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

    <main class="content-area">
        <h1 class="h1-title">Portafolio</h1>
        <p class="subtitulo-portafolio">
            Consulta los productos disponibles y agrégalos a tu pedido. 
            El pedido mínimo sugerido es de 60 unidades.
        </p>

        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje-alerta <?php echo htmlspecialchars($tipoMensaje); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <div class="product-grid">

            <?php if (!empty($productos)) : ?>
                <?php foreach ($productos as $producto) : ?>
                    <?php
                        $disponible = ((int)$producto['cantidad_stock'] > 0 && $producto['estado_producto'] === 'A');
                        $textoEstado = $disponible ? 'Disponible' : 'Agotado';
                        $claseEstado = $disponible ? 'estado-disponible' : 'estado-agotado';
                    ?>

                    <div class="product-card">
                        <img class="product-img" src="../../img/huevo.png" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">

                        <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>

                        <p class="product-details">
                            Producto disponible en el sistema
                        </p>

                        <p class="product-price">
                            $ <?php echo number_format((float)$producto['precio'], 0, ',', '.'); ?> por unidad
                        </p>

                        <p class="data <?php echo $claseEstado; ?>">
                            <?php echo $textoEstado; ?>
                        </p>

                        <form action="../backend/cliente/agregar_al_pedido.php" method="POST">
                            <input type="hidden" name="id_producto" value="<?php echo (int)$producto['id_producto']; ?>">

                            <button type="submit" class="btn-agregar" <?php echo $disponible ? '' : 'disabled'; ?>>
                                Agregar al pedido
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No hay productos disponibles para mostrar.</p>
            <?php endif; ?>

        </div>

        <div class="logo-footer">Z-CONTAY</div>
    </main>
</div>

</body>
</html>