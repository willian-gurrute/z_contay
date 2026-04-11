<?php
session_start();

require_once "../backend/verificar_sesion.php";
require_once "../backend/verificar_permiso.php";

verificarPermiso("registrar_venta");

require_once "../backend/vendedor/datos_registrar_venta.php";

$nombre = $_SESSION['nombre'] ?? 'Vendedor';

$msg = $_GET['msg'] ?? '';
$idFactura = $_GET['id_factura'] ?? '';

$mensaje = "";
$tipoMensaje = "";

if ($msg === 'ok') {
    $mensaje = "Venta registrada correctamente.";
    $tipoMensaje = "success";
} elseif ($msg === 'stock') {
    $mensaje = "No hay stock suficiente para uno de los productos.";
    $tipoMensaje = "error";
} elseif ($msg === 'cliente') {
    $mensaje = "Debes completar los datos del cliente.";
    $tipoMensaje = "error";
} elseif ($msg === 'producto') {
    $mensaje = "Debes agregar al menos un producto válido.";
    $tipoMensaje = "error";
} elseif ($msg === 'tipo_venta') {
    $mensaje = "El tipo de venta no es válido.";
    $tipoMensaje = "error";
} elseif ($msg === 'metodo_pago') {
    $mensaje = "El método de pago no es válido.";
    $tipoMensaje = "error";
} elseif ($msg === 'sesion') {
    $mensaje = "La sesión no está activa.";
    $tipoMensaje = "error";
} elseif ($msg === 'error') {
    $mensaje = "Ocurrió un error al registrar la venta.";
    $tipoMensaje = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Nueva Venta</title>
    <link rel="stylesheet" href="../../_css/admin-base.css">
    <link rel="stylesheet" href="../../_css/vendedor-registrar-venta.css">
</head>
<body>

<header class="header-bar">
    <div class="header-rol">Vendedor</div>
    <div class="header-system">Z-CONTAY - Galpón Aves del Paraíso</div>

    <div class="header-user">
        <span class="icon"><img src="../../img/campana.png" alt="Notificaciones"></span>
        <span><?php echo htmlspecialchars($nombre); ?></span>
        <span class="icon"><img src="../../img/usuario-gestion.png" alt="Perfil"></span>
    </div>
</header>

<div class="main-container">

    <nav class="sidebar">
        <ul>
            <li><a href="panel_principal.php"><span class="icon"><img src="../../img/panel.jpg"></span> Panel Principal</a></li>
            <li class="active-item"><a href="registrar_venta.php"><span class="icon"><img src="../../img/ventas.png"></span> Registrar venta</a></li>
            <li><a href="pedidos_clientes.php"><span class="icon"><img src="../../img/pedidos.png"></span> Pedidos de clientes</a></li>
            <li><a href="reportes_venta.php"><span class="icon"><img src="../../img/reportes.png"></span> Reportes de venta</a></li>
            <li><a href="perfil.php"><span class="icon"><img src="../../img/perfil.png"></span> Perfil</a></li>
            <li><a href="cerrar_sesion.php"><span class="icon"><img src="../../img/cerrar-seccion.png"></span> Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="content-area">
        <h1 class="h1-title">Registrar Nueva Venta</h1>

        <?php if (!empty($mensaje)) : ?>
            <div class="mensaje-alerta <?php echo $tipoMensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <h3 class="subtitulo">Completa los datos del cliente y la venta</h3>

        <!-- ===============================
             formulario: buscar cliente
        ================================ -->
        <form method="GET" action="registrar_venta.php">
            <label for="documento">Documento del cliente:</label>
            <div class="form-row">
                <input 
                    type="text" 
                    id="documento" 
                    name="documento"
                    placeholder="Ej: 12345678"
                    value="<?php echo htmlspecialchars($documentoCliente); ?>"
                >
                <button type="submit" class="main-button">Buscar cliente</button>
            </div>
        </form>

        <?php if (!empty($mensajeCliente)) : ?>
            <p style="margin-top: 10px; margin-bottom: 15px; font-weight: bold; color: <?php echo $clienteEncontrado ? 'green' : 'red'; ?>;">
                <?php echo htmlspecialchars($mensajeCliente); ?>
            </p>
        <?php endif; ?>

        <!-- ===============================
             formulario registrar venta
        ================================ -->
        <form class="form-venta" method="POST" action="../backend/vendedor/registrar_venta_guardar.php">

            <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($idCliente); ?>">
            <input type="hidden" name="documento" value="<?php echo htmlspecialchars($documentoCliente); ?>">

            <!-- Datos del cliente -->
            <div class="form-row">
                <div class="form-col">
                    <label for="nombre">Nombre:</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre_cliente"
                        placeholder="Nombre del cliente"
                        value="<?php echo htmlspecialchars($nombreCliente); ?>"
                    >
                </div>

                <div class="form-col">
                    <label for="telefono">Teléfono:</label>
                    <input 
                        type="text" 
                        id="telefono" 
                        name="telefono_cliente"
                        placeholder="Teléfono del cliente"
                        value="<?php echo htmlspecialchars($telefonoCliente); ?>"
                    >
                </div>
            </div>

            <label for="direccion_cliente">Dirección:</label>
            <input 
                type="text" 
                id="direccion_cliente" 
                name="direccion_cliente"
                placeholder="Dirección del cliente"
                value="<?php echo htmlspecialchars($direccionCliente); ?>"
            >

            <label for="barrio_cliente">Barrio:</label>
            <input
                type="text"
                name="barrio_cliente"
                id="barrio_cliente"
                placeholder="Ej: Centro"
                value="<?php echo htmlspecialchars($barrioCliente); ?>"
            >

            <label for="ciudad_cliente">Ciudad:</label>
            <input
                type="text"
                name="ciudad_cliente"
                id="ciudad_cliente"
                placeholder="Ej: Popayán"
                value="<?php echo htmlspecialchars($ciudadCliente); ?>"
            >

            <label for="referencia_cliente">Referencia:</label>
            <input
                type="text"
                name="referencia_cliente"
                id="referencia_cliente"
                placeholder="Ej: Casa esquinera, portón azul"
                value="<?php echo htmlspecialchars($referenciaCliente); ?>"
            >

            <label for="tipo_venta">Tipo de venta:</label>
            <select id="tipo_venta" name="tipo_venta">
                <option value="directa">Directa</option>
                <option value="pedido">Pedido</option>
            </select>

            <label for="metodo_pago">Método de pago:</label>
            <select id="metodo_pago" name="metodo_pago" required>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
            </select>

            <!-- Productos -->
            <h3 class="subtitulo" style="margin-top: 20px;">Productos</h3>

            <table class="tabla-productos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Valor Unitario</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="tabla-productos-body">
                    <tr class="fila-producto">
                        <td>
                            <select class="producto" name="id_producto[]" required>
                                <option value="">Seleccione...</option>
                                <?php if (!empty($productos)) : ?>
                                    <?php foreach ($productos as $producto) : ?>
                                        <option 
                                            value="<?php echo $producto['id_producto']; ?>"
                                            data-precio="<?php echo $producto['precio']; ?>"
                                        >
                                            <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="">No hay productos disponibles</option>
                                <?php endif; ?>
                            </select>
                        </td>

                        <td>
                            <input type="number" class="cantidad" name="cantidad[]" value="1" min="1" required>
                        </td>

                        <td class="valor-unitario">0</td>
                        <td class="subtotal-producto">0</td>
                        <td>
                            <button type="button" class="btn-eliminar">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="main-button" id="btn-agregar-producto">Agregar producto</button>

            <!-- Totales -->
            <div class="totales">
                <p><strong>Subtotal:</strong> <span id="subtotal-general">0</span></p>
                <p><strong>IVA (0%):</strong> <span id="iva-general">0</span></p>
                <h2>Total a pagar: <span id="total-general">0</span></h2>
            </div>

            <!-- Botones de acción -->
            <div class="acciones-venta">
                <button type="submit" class="main-button">Registrar Venta</button>
                <button type="button" class="main-button" onclick="imprimirFactura()">Imprimir Factura</button>
            </div>

        </form>

        <div class="logo-footer">Z-CONTAY</div>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.getElementById("tabla-productos-body");
    const btnAgregar = document.getElementById("btn-agregar-producto");

    function formatearNumero(valor) {
        return new Intl.NumberFormat("es-CO").format(valor);
    }

    function actualizarFila(fila) {
        const selectProducto = fila.querySelector(".producto");
        const inputCantidad = fila.querySelector(".cantidad");
        const celdaValorUnitario = fila.querySelector(".valor-unitario");
        const celdaSubtotal = fila.querySelector(".subtotal-producto");

        if (!selectProducto || !inputCantidad) return;

        const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
        const precio = parseFloat(opcionSeleccionada.dataset.precio || 0);
        const cantidad = parseInt(inputCantidad.value) || 0;
        const subtotal = precio * cantidad;

        celdaValorUnitario.textContent = formatearNumero(precio);
        celdaSubtotal.textContent = formatearNumero(subtotal);
    }

    function actualizarTotales() {
        const filas = document.querySelectorAll(".fila-producto");
        let subtotalGeneral = 0;

        filas.forEach(function (fila) {
            const selectProducto = fila.querySelector(".producto");
            const inputCantidad = fila.querySelector(".cantidad");

            if (!selectProducto || !inputCantidad) return;

            const opcionSeleccionada = selectProducto.options[selectProducto.selectedIndex];
            const precio = parseFloat(opcionSeleccionada.dataset.precio || 0);
            const cantidad = parseInt(inputCantidad.value) || 0;

            subtotalGeneral += precio * cantidad;
        });

        const iva = 0;
        const total = subtotalGeneral + iva;

        document.getElementById("subtotal-general").textContent = formatearNumero(subtotalGeneral);
        document.getElementById("iva-general").textContent = formatearNumero(iva);
        document.getElementById("total-general").textContent = formatearNumero(total);
    }

    function actualizarTodo() {
        document.querySelectorAll(".fila-producto").forEach(function (fila) {
            actualizarFila(fila);
        });
        actualizarTotales();
    }

    function conectarEventosFila(fila) {
        const selectProducto = fila.querySelector(".producto");
        const inputCantidad = fila.querySelector(".cantidad");
        const botonEliminar = fila.querySelector(".btn-eliminar");

        if (selectProducto) {
            selectProducto.addEventListener("change", function () {
                actualizarFila(fila);
                actualizarTotales();
            });
        }

        if (inputCantidad) {
            inputCantidad.addEventListener("input", function () {
                actualizarFila(fila);
                actualizarTotales();
            });
        }

        if (botonEliminar) {
            botonEliminar.addEventListener("click", function () {
                const filas = document.querySelectorAll(".fila-producto");

                if (filas.length > 1) {
                    fila.remove();
                } else {
                    selectProducto.selectedIndex = 0;
                    inputCantidad.value = 1;
                    actualizarFila(fila);
                }

                actualizarTotales();
            });
        }
    }

    function crearNuevaFila() {
        const primeraFila = document.querySelector(".fila-producto");
        const nuevaFila = primeraFila.cloneNode(true);

        const selectProducto = nuevaFila.querySelector(".producto");
        const inputCantidad = nuevaFila.querySelector(".cantidad");
        const celdaValorUnitario = nuevaFila.querySelector(".valor-unitario");
        const celdaSubtotal = nuevaFila.querySelector(".subtotal-producto");

        selectProducto.selectedIndex = 0;
        inputCantidad.value = 1;
        celdaValorUnitario.textContent = "0";
        celdaSubtotal.textContent = "0";

        conectarEventosFila(nuevaFila);
        tbody.appendChild(nuevaFila);
        actualizarTotales();
    }

    btnAgregar.addEventListener("click", crearNuevaFila);

    document.querySelectorAll(".fila-producto").forEach(function (fila) {
        conectarEventosFila(fila);
    });

    actualizarTodo();
});
</script>

<script>
setTimeout(function () {
    const alerta = document.querySelector(".mensaje-alerta");

    if (alerta) {
        alerta.style.transition = "opacity 0.5s";
        alerta.style.opacity = "0";

        setTimeout(function () {
            alerta.remove();
        }, 500);
    }
}, 4000);

function imprimirFactura() {
    const idFactura = "<?php echo $idFactura; ?>";

    if (!idFactura || idFactura == 0) {
        alert("Primero debes registrar la venta.");
        return;
    }

    window.open("factura_imprimir.php?id=" + idFactura, "_blank");
}
</script>

</body>
</html>