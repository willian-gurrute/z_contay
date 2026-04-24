<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("realizar_pedido");

$nombre = $_SESSION['nombre'] ?? 'Cliente';

$carrito = $_SESSION['carrito_cliente'] ?? [];

$total_unidades = 0;
$total_pedido = 0;

foreach ($carrito as $item) {
    $total_unidades += (int)$item['cantidad'];
    $total_pedido += ((float)$item['precio'] * (int)$item['cantidad']);
}

$mensaje = $_SESSION['mensaje_pedido'] ?? "";
$tipoMensaje = $_SESSION['tipo_pedido'] ?? "";

unset($_SESSION['mensaje_pedido'], $_SESSION['tipo_pedido']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente - Realizar pedido</title>

    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/portafolio.css">
    <link rel="stylesheet" href="../../_css/realizar-pedido.css">
</head>

<body>

<header class="header-bar">
    <div class="header-rol">Cliente</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon">
            <img src="../../img/campana.png" alt="Notificaciones" width="24">
        </span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon">
            <img src="../../img/perfil.png" alt="Perfil" width="24">
        </span>
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li>
                <a href="portafolio.php">
                    <span class="icon"><img src="../../img/panel.jpg" alt=""></span>
                    Portafolio
                </a>
            </li>

            <li class="active-item">
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

        <h1 class="h1-title">Realizar pedido</h1>

        <p class="subtitulo-portafolio">
            Revisa los productos agregados, completa los datos de entrega y confirma tu pedido.
            El pedido mínimo es de 60 unidades.
        </p>

        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje-alerta <?php echo htmlspecialchars($tipoMensaje); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($carrito)) : ?>

            <p>No tienes productos agregados al pedido.</p>

            <a href="portafolio.php" class="btn-agregar">
                Ir al portafolio
            </a>

        <?php else : ?>

            <form action="../backend/cliente/actualizar_carrito.php" method="POST">

                <table class="tabla-pedido">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Quitar</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($carrito as $id_producto => $item) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nombre_producto']); ?></td>

                                <td>
                                    $<?php echo number_format((float)$item['precio'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        name="cantidades[<?php echo (int)$id_producto; ?>]"
                                        value="<?php echo (int)$item['cantidad']; ?>"
                                        min="1"
                                        class="input-cantidad"
                                        required
                                    >
                                </td>

                                <td>
                                    $<?php echo number_format((float)$item['precio'] * (int)$item['cantidad'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <input
                                        type="checkbox"
                                        name="eliminar[]"
                                        value="<?php echo (int)$id_producto; ?>"
                                    >
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <section class="datos-entrega">
    <h2>Datos de entrega</h2>

    <div class="datos-grid">
        <div class="campo-form">
            <label for="direccion">Dirección de entrega:</label>
            <input type="text" id="direccion" name="direccion" placeholder="Ej: Calle 10 # 5-20" required>
        </div>

        <div class="campo-form">
            <label for="barrio">Barrio:</label>
            <input type="text" id="barrio" name="barrio" placeholder="Ej: Centro" required>
        </div>

        <div class="campo-form">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" placeholder="Ej: 3001234567" required>
        </div>

        <div class="campo-form">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" placeholder="Ej: Casa azul, segundo piso">
        </div>

        <div class="campo-form">
            <label for="tipo_transferencia">Medio de transferencia:</label>
            <select id="tipo_transferencia" name="tipo_transferencia" required>
                <option value="">Seleccione...</option>
                <option value="Nequi">Nequi</option>
                <option value="Daviplata">Daviplata</option>
                <option value="Bancolombia">Bancolombia</option>
            </select>
        </div>
    </div>

    <input type="hidden" name="metodo_pago" value="tarjeta">
</section>
                <div class="resumen-pedido">
                    <p><strong>Total unidades:</strong> <?php echo $total_unidades; ?></p>
                    <p><strong>Total pedido:</strong> $<?php echo number_format($total_pedido, 0, ',', '.'); ?></p>

                    <?php if ($total_unidades < 60) : ?>
                        <p class="estado-agotado">
                            El pedido aún no cumple el mínimo de 60 unidades.
                        </p>
                    <?php else : ?>
                        <p class="estado-disponible">
                            El pedido cumple con el mínimo requerido.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="acciones-pedido">
                    <button type="submit" name="accion" value="actualizar" class="btn-agregar">
                        Actualizar pedido
                    </button>

                    <button  type="submit" name="accion" value="confirmar" class="btn-agregar" formaction="../backend/cliente/guardar_pedido.php">
                         Confirmar pedido
                    </button>

                    <a href="portafolio.php" class="btn-agregar">
                        Seguir agregando
                    </a>
                </div>

            </form>

        <?php endif; ?>

        <div class="logo-footer">Z-CONTAY</div>

    </main>
</div>

</body>
</html>