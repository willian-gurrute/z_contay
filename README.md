# Z-CONTAY – Galpón Aves del Paraíso

Sistema web desarrollado para la gestión contable, logística y administrativa del negocio “Galpón Aves del Paraíso”.

---

## Descripción

Z-CONTAY permite controlar las ventas, pedidos, inventario y procesos operativos del negocio, integrando diferentes roles dentro del sistema.



---

## Tecnologías utilizadas

- PHP
- MySQL / MariaDB
- HTML
- CSS
- XAMPP (localhost)
- Git y GitHub

---

## Estructura del proyecto

/prototipo  
│  
├── /php  
│   ├── /administrador  
│   ├── /vendedor  
│   ├── /encargado_planta  
    |__/transportador
    |
│   ├── /backend  
│   │   ├── /administrador  
│   │   ├── /vendedor  
│   │   └── /login  
|   |   └── /encargado_planta       
│       └── /transportador
├── /_css  
├── /img  

---

## Roles del sistema

- Administrador  
- Vendedor  
- Encargado de planta  
- Transportador
- (En desarrollo:  Cliente)

---

## Módulos implementados

###  Administrador
- panel principal 
- Gestión de usuarios, roles y permisos  
- contabilidad
- ventas
- productos
- registral gatos
- inventario
- Reportes  
- configuracion
- Perfil

###  Vendedor
- panel principal
- Registro de ventas (directas y tipo pedido)  
- Facturación completa  
- Integración con inventario  
- Gestión de pedidos (tomar, facturar, liberar)  
- Validación de stock  
- Reportes de venta  

###  Encargado de planta
- Visualización de pedidos del sistema (web y presenciales)  
- Control de pedidos pendientes por procesar  
- Integración con inventario  
- Preparación para logística y despachos  
- Perfil

### Transportador
- Panel principal
- zonas y despachos
- listado de pedidos
- Reportes de despachos
- Perfil


---

## 🔄 Integración del sistema

El sistema funciona de la siguiente manera:

- El cliente realiza pedidos (web o presencial)  
- El vendedor gestiona ventas y pedidos  
- Los pedidos pasan al encargado de planta  
- El inventario se actualiza automáticamente  
- La contabilidad registra ingresos y movimientos  y panel del administrador 

---

##  Estado del proyecto

✔ Administrador completo  
✔ Vendedor completo  
✔ Encargado de planta completo
✔ Transportador

🔄 En desarrollo: Cliente  

---

##  Nota
 Se seguirán integrando nuevos módulos y mejoras.

---