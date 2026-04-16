-- Se desactiva temporalmente la revisión de llaves foráneas [2]
SET FOREIGN_KEY_CHECKS = 0;

/* =================================================================
   NIVEL 1: TABLAS INDEPENDIENTES Y CATÁLOGOS
   (No dependen de ninguna otra tabla)
================================================================= */

-- Tabla: categoria [2]
DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `categoria_id` INT PRIMARY KEY,
  `nombre` VARCHAR(255),
  `descripcion` TEXT
);

-- Tabla: config [3, 4]
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `config_id` INT PRIMARY KEY,
  `nombre_empresa` VARCHAR(255),
  `direccion` TEXT,
  `ruta_logo` VARCHAR(255),
  `mensaje_ticket` TEXT,
  `iva` DECIMAL(10, 3)
);

-- Tabla: empleado [5, 6]
DROP TABLE IF EXISTS `empleado`;
CREATE TABLE `empleado` (
  `empleado_id` INT PRIMARY KEY,
  `nombre_completo` VARCHAR(255),
  `curp` VARCHAR(255),
  `telefono` VARCHAR(255),
  `puesto` VARCHAR(255),
  `activo` TINYINT(1),
  `salario_base` DECIMAL(10, 3),
  `fecha_contratacion` DATETIME
);

-- Tabla: insumo [7, 8]
DROP TABLE IF EXISTS `insumo`;
CREATE TABLE `insumo` (
  `insumo_id` INT PRIMARY KEY,
  `nombre` VARCHAR(255),
  `stock_actual` DECIMAL(10, 3),
  `stock_minimo` DECIMAL(10, 3),
  `unidad_medida` VARCHAR(255),
  `costo_unitario` DECIMAL(10, 3),
  `costo_promedio` DECIMAL(10, 3)
);

-- Tabla: mesa [9]
DROP TABLE IF EXISTS `mesa`;
CREATE TABLE `mesa` (
  `mesa_id` INT PRIMARY KEY,
  `numero_mesa` VARCHAR(255),
  `capacidad` VARCHAR(255),
  `estado` VARCHAR(255)
);

-- Tabla: permiso [10]
DROP TABLE IF EXISTS `permiso`;
CREATE TABLE `permiso` (
  `permiso_id` INT PRIMARY KEY,
  `nombre_permiso` VARCHAR(255),
  `descripcion` TEXT
);

-- Tabla: proveedor [11]
DROP TABLE IF EXISTS `proveedor`;
CREATE TABLE `proveedor` (
  `proveedor_id` INT PRIMARY KEY,
  `empresa` VARCHAR(255),
  `contacto_nombre` VARCHAR(255),
  `telefono` VARCHAR(255),
  `email` VARCHAR(255),
  `direccion` TEXT,
  `activo` TINYINT(1)
);

-- Tabla: rol [12]
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `rol_id` INT PRIMARY KEY,
  `nombre_rol` VARCHAR(255),
  `descripcion` TEXT
);


/* =================================================================
   NIVEL 2: TABLAS CON DEPENDENCIAS DE PRIMER GRADO
================================================================= */

-- Tabla: producto (Depende de categoria) [13, 14]
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `producto_id` INT PRIMARY KEY,
  `categoria_id` INT,
  `nombre` VARCHAR(255),
  `precio` DECIMAL(10, 3),
  `tamano` VARCHAR(255),
  `es_preparado` TINYINT(1),
  `activo` TINYINT(1),
  `es_recomendado` TINYINT(1)
);

-- Tabla: rol_permiso (Depende de rol y permiso) [15]
DROP TABLE IF EXISTS `rol_permiso`;
CREATE TABLE `rol_permiso` (
  `rol_id` INT,
  `permiso_id` INT,
  PRIMARY KEY (`rol_id`, `permiso_id`)
);

-- Tabla: usuario (Depende de empleado y rol, creada a partir de los registros de inserción) [1]
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_usuario` INT PRIMARY KEY,
  `empleado_id` INT,
  `nombre_completo` VARCHAR(255),
  `matricula` INT,
  `contrasena` VARCHAR(255),
  `id_rol` INT,
  `activo` TINYINT(1),
  `limite_descuento` DECIMAL(10, 3),
  `porcentaje_comision` DECIMAL(10, 3)
);


/* =================================================================
   NIVEL 3: TABLAS DE DETALLE Y TRANSACCIONES FINALES
================================================================= */

-- Tabla: pedido (Depende de mesa y usuario) [16]
DROP TABLE IF EXISTS `pedido`;
CREATE TABLE `pedido` (
  `pedido_id` INT PRIMARY KEY,
  `usuario_id` INT,
  `mesa_id` INT,
  `cliente_nombre` VARCHAR(255),
  `fecha_hora` DATETIME,
  `estado` VARCHAR(255),
  `total` DECIMAL(10, 3),
  `propina` DECIMAL(10, 3)
);

-- Tabla: compra (Depende de proveedor y usuario) [3]
DROP TABLE IF EXISTS `compra`;
CREATE TABLE `compra` (
  `compra_id` INT PRIMARY KEY,
  `proveedor_id` INT,
  `usuario_id` INT,
  `fecha_compra` DATETIME,
  `total_compra` DECIMAL(10, 3),
  `numero_factura` VARCHAR(255)
);

-- Tabla: detalle (Depende de pedido y producto) [17, 18]
DROP TABLE IF EXISTS `detalle`;
CREATE TABLE `detalle` (
  `detalle_id` INT PRIMARY KEY,
  `pedido_id` INT,
  `producto_id` INT,
  `cantidad` DECIMAL(10, 3),
  `precio_unitario` DECIMAL(10, 3),
  `comentarios` TEXT
);

-- Tabla: detalle_compra (Depende de compra e insumo) [17]
DROP TABLE IF EXISTS `detalle_compra`;
CREATE TABLE `detalle_compra` (
  `detalle_compra_id` INT PRIMARY KEY,
  `compra_id` INT,
  `insumo_id` INT,
  `cantidad` DECIMAL(10, 3),
  `costo_unitario` DECIMAL(10, 3)
);

-- Tabla: receta (Depende de producto e insumo) [19]
DROP TABLE IF EXISTS `receta`;
CREATE TABLE `receta` (
  `receta_id` INT PRIMARY KEY,
  `producto_id` INT,
  `insumo_id` INT,
  `cantidad_requerida` DECIMAL(10, 3)
);

-- Tabla: merma (Depende de insumo y usuario) [9]
DROP TABLE IF EXISTS `merma`;
CREATE TABLE `merma` (
  `merma_id` INT PRIMARY KEY,
  `insumo_id` INT,
  `usuario_id` INT,
  `cantidad` DECIMAL(10, 3),
  `motivo` TEXT,
  `fecha` DATETIME
);

-- Tabla: corte (Depende de usuario) [4, 17]
DROP TABLE IF EXISTS `corte`;
CREATE TABLE `corte` (
  `corte_id` INT PRIMARY KEY,
  `usuario_id` INT,
  `fecha_apertura` DATETIME,
  `fecha_cierre` DATETIME,
  `monto_inicial` DECIMAL(10, 3),
  `ventas_sistema` VARCHAR(255),
  `monto_real` DECIMAL(10, 3),
  `observaciones` TEXT
);

-- Tabla: bitacora (Depende de usuario) [2]
DROP TABLE IF EXISTS `bitacora`;
CREATE TABLE `bitacora` (
  `bitacora_id` INT PRIMARY KEY,
  `usuario_id` INT,
  `accion` VARCHAR(255),
  `tabla_afectada` VARCHAR(255),
  `fecha` DATETIME,
  `detalles` TEXT
);

-- Se vuelven a activar las restricciones de llaves foráneas [20]
SET FOREIGN_KEY_CHECKS = 1;