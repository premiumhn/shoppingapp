-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 28-05-2020 a las 15:23:39
-- Versión del servidor: 5.7.23
-- Versión de PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `shoppingapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Carrito`
--

CREATE TABLE `Carrito` (
  `PK_Carrito` int(11) NOT NULL,
  `Cantidad` double DEFAULT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Pedido` int(11) DEFAULT NULL,
  `FK_Talla` int(11) DEFAULT NULL,
  `FK_Color` int(11) DEFAULT NULL,
  `FK_Cliente` int(11) DEFAULT NULL,
  `FechaHoraAgregado` datetime DEFAULT NULL,
  `FK_TipoPedido` int(11) DEFAULT NULL,
  `FK_Destinatario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Carrito`
--

INSERT INTO `Carrito` (`PK_Carrito`, `Cantidad`, `FK_Producto`, `FK_Pedido`, `FK_Talla`, `FK_Color`, `FK_Cliente`, `FechaHoraAgregado`, `FK_TipoPedido`, `FK_Destinatario`) VALUES
(37, 228, 2, NULL, 1, 1, 1, '2020-05-21 15:14:56', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Categorias`
--

CREATE TABLE `Categorias` (
  `PK_Categoria` int(11) NOT NULL,
  `NombreCategoria` varchar(50) DEFAULT NULL,
  `Descripcion` varchar(400) DEFAULT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Categorias`
--

INSERT INTO `Categorias` (`PK_Categoria`, `NombreCategoria`, `Descripcion`, `Imagen`, `Estado`) VALUES
(1, 'Calzado', 'Calzado general', 'Calzado_1590187771_blog_35.jpg', 1),
(2, 'Tecnología', 'Tecnología', 'Tecnología_1590183253_mejores-gadgets-2019-z.jpg', 1),
(5, 'Ropa', 'Ropa', 'Ropa_1590606955_ropa-medioambiente-t.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Ciudades`
--

CREATE TABLE `Ciudades` (
  `PK_Ciudad` int(11) NOT NULL,
  `NombreCiudad` varchar(80) DEFAULT NULL,
  `FK_Pais` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Ciudades`
--

INSERT INTO `Ciudades` (`PK_Ciudad`, `NombreCiudad`, `FK_Pais`) VALUES
(1, 'Tegucigalpa', 1),
(2, 'Choluteca', 1),
(3, 'San Pedro Sula', 1),
(4, 'Managua', 2),
(5, 'Leon', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Clientes`
--

CREATE TABLE `Clientes` (
  `PK_Cliente` int(11) NOT NULL,
  `FK_Usuario` int(11) NOT NULL,
  `PrimerNombre` varchar(50) DEFAULT NULL,
  `SegundoNombre` varchar(50) DEFAULT NULL,
  `PrimerApellido` varchar(50) DEFAULT NULL,
  `SegundoApellido` varchar(50) DEFAULT NULL,
  `Direccion1` varchar(200) DEFAULT NULL,
  `Direccion2` varchar(200) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `FK_Ciudad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Clientes`
--

INSERT INTO `Clientes` (`PK_Cliente`, `FK_Usuario`, `PrimerNombre`, `SegundoNombre`, `PrimerApellido`, `SegundoApellido`, `Direccion1`, `Direccion2`, `Telefono`, `FK_Ciudad`) VALUES
(1, 3, 'Juan', '', 'Perez', '', 'barrio la cruz', 'calle del registro', '23443344', 5),
(14, 28, '', '', '', '', '', NULL, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Colores`
--

CREATE TABLE `Colores` (
  `PK_Color` int(11) NOT NULL,
  `Color` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Colores`
--

INSERT INTO `Colores` (`PK_Color`, `Color`) VALUES
(1, 'Rojo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Correos`
--

CREATE TABLE `Correos` (
  `PK_Correo` int(11) NOT NULL,
  `Correo` varchar(200) DEFAULT NULL,
  `Contrasena` varchar(50) DEFAULT NULL,
  `CorreosEnviados` int(11) DEFAULT NULL,
  `Turno` int(11) DEFAULT NULL,
  `Actual` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Correos`
--

INSERT INTO `Correos` (`PK_Correo`, `Correo`, `Contrasena`, `CorreosEnviados`, `Turno`, `Actual`) VALUES
(1, 'shoppingappworld@gmail.com', 'shoppingapp1234!', 2, 1, 1),
(2, 'shoppingapp.mails@gmail.com', 'shoppingapp1234!', 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Destinatarios`
--

CREATE TABLE `Destinatarios` (
  `PK_Destinatario` int(11) NOT NULL,
  `NombresDestinatario` varchar(100) DEFAULT NULL,
  `ApellidosDestinatario` varchar(100) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Departamento` varchar(100) DEFAULT NULL,
  `Direccion1` varchar(200) DEFAULT NULL,
  `Direccion2` varchar(200) DEFAULT NULL,
  `CodigoPostal` varchar(10) DEFAULT NULL,
  `FK_Cliente` int(11) NOT NULL,
  `FK_Ciudad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Destinatarios`
--

INSERT INTO `Destinatarios` (`PK_Destinatario`, `NombresDestinatario`, `ApellidosDestinatario`, `Telefono`, `Departamento`, `Direccion1`, `Direccion2`, `CodigoPostal`, `FK_Cliente`, `FK_Ciudad`) VALUES
(1, 'Kevin', 'Canales', '33712740', 'Choluteca', 'Bario la cruz', 'Calle del registro', '52102', 1, 2),
(2, 'Anthony', 'Canales', '32959545', 'Francisco Morazán', 'Barrio el centro', 'Calle 8', '53189', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetallePedidos`
--

CREATE TABLE `DetallePedidos` (
  `PK_DetallePedido` int(11) NOT NULL,
  `Cantidad` double DEFAULT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Pedido` int(11) DEFAULT NULL,
  `FK_Talla` int(11) DEFAULT NULL,
  `FK_Color` int(11) DEFAULT NULL,
  `FK_Cliente` int(11) DEFAULT NULL,
  `FechaHoraAgregado` datetime DEFAULT NULL,
  `FK_TipoPedido` int(11) DEFAULT NULL,
  `FK_Destinatario` int(11) DEFAULT NULL,
  `Estado` tinyint(4) DEFAULT NULL,
  `FechaHoraCompletado` date DEFAULT NULL,
  `Valoracion` int(11) DEFAULT '0',
  `CodigoDetallePedido` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `DetallePedidos`
--

INSERT INTO `DetallePedidos` (`PK_DetallePedido`, `Cantidad`, `FK_Producto`, `FK_Pedido`, `FK_Talla`, `FK_Color`, `FK_Cliente`, `FechaHoraAgregado`, `FK_TipoPedido`, `FK_Destinatario`, `Estado`, `FechaHoraCompletado`, `Valoracion`, `CodigoDetallePedido`) VALUES
(26, 1, 2, 7, 1, 1, 1, '2020-05-03 02:02:17', 1, NULL, 1, NULL, 1, NULL),
(27, 1, 3, 7, NULL, NULL, 1, '2020-05-03 02:02:17', 1, NULL, 0, NULL, 0, NULL),
(28, 1, 2, 8, 1, 1, 1, '2020-05-03 02:04:56', 2, NULL, 1, '2020-08-05', 1, NULL),
(29, 1, 3, 9, NULL, NULL, 1, '2020-05-03 02:37:01', 1, NULL, 0, NULL, 0, NULL),
(30, 2, 2, 10, 1, 1, 1, '2020-05-03 14:57:10', 2, NULL, 0, NULL, 0, NULL),
(31, 1, 3, 10, NULL, NULL, 1, '2020-05-03 14:57:10', 1, NULL, 0, NULL, 0, NULL),
(32, 1, 2, 11, 1, 1, 1, '2020-05-03 15:00:34', 2, NULL, 0, NULL, 0, NULL),
(33, 1, 2, 12, 1, 1, 1, '2020-05-07 16:16:51', 1, NULL, 0, NULL, 0, NULL),
(34, 2, 2, 13, 1, 1, 1, '2020-05-07 22:01:56', 2, NULL, 1, '2020-05-17', 0, NULL),
(35, 1, 2, 14, 1, 1, 1, '2020-05-07 22:07:08', 2, 2, 1, NULL, 2, NULL),
(36, 1, 2, 15, 1, 1, 1, '2020-05-12 22:05:06', 1, NULL, 0, NULL, 0, NULL),
(37, 1, 2, 16, 1, 1, 1, '2020-05-12 22:14:24', 1, NULL, 0, NULL, 0, NULL),
(38, 1, 2, 17, 1, 1, 1, '2020-05-12 22:24:38', 1, NULL, 0, NULL, 0, NULL),
(39, 1, 3, 18, NULL, NULL, 1, '2020-05-12 22:50:24', 1, NULL, 0, NULL, 0, NULL),
(40, 1, 3, 19, NULL, NULL, 1, '2020-05-12 22:51:57', 1, NULL, 0, NULL, 0, NULL),
(41, 1, 3, 20, NULL, NULL, 1, '2020-05-12 22:53:44', 1, NULL, 0, NULL, 0, NULL),
(42, 1, 2, 21, 1, 1, 1, '2020-05-12 22:55:11', 1, NULL, 0, NULL, 0, NULL),
(43, 1, 2, 22, 1, 1, 1, '2020-05-12 22:57:11', 1, NULL, 0, NULL, 0, NULL),
(44, 1, 2, 23, 1, 1, 1, '2020-05-13 14:41:38', 1, NULL, 0, NULL, 0, NULL),
(45, 1, 2, 24, 1, 1, 1, '2020-05-13 14:45:07', 1, NULL, 1, '2020-05-13', 0, 'P2H070514A2020D0513'),
(46, 1, 2, 26, 1, 1, 1, '2020-05-13 14:47:35', 1, NULL, 1, '2020-05-13', 0, 'P3-2H350514A2020D0513'),
(47, 1, 2, 27, 1, 1, 1, '2020-05-13 15:20:08', 1, NULL, 0, NULL, 0, 'P3-20805152005130064\n'),
(48, 1, 2, 28, 1, 1, 1, '2020-05-13 15:27:33', 1, NULL, 0, NULL, 0, 'P3-2330515Y20D05132000\n'),
(49, 1, 2, 28, 1, 1, 1, '2020-05-13 15:27:33', 1, NULL, 0, NULL, 0, 'P3-2330515Y20D05132144\n'),
(50, 1, 3, 28, NULL, NULL, 1, '2020-05-13 15:27:33', 1, NULL, 0, NULL, 0, 'P3-3330515Y20D05133888\n'),
(51, 1, 2, 29, 1, 1, 1, '2020-05-17 16:04:58', 1, NULL, 0, NULL, 0, 'P3-2580516Y20D05179104\n'),
(52, 1, 3, 29, NULL, NULL, 1, '2020-05-17 16:04:58', 1, NULL, 0, NULL, 0, 'P3-3580516Y20D05179152\n'),
(53, 1, 2, 30, 1, 1, 1, '2020-05-17 16:24:26', 1, NULL, 0, NULL, 0, 'P3-2260516Y20D05177120\n'),
(54, 1, 2, 31, 1, 1, 1, '2020-05-25 20:50:32', 1, NULL, 0, NULL, 0, 'P3-2320520Y20D05258928\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetalleProducto`
--

CREATE TABLE `DetalleProducto` (
  `PK_DetalleProducto` int(11) NOT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Talla` int(11) DEFAULT NULL,
  `FK_Color` int(11) DEFAULT NULL,
  `Peso` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `DetalleProducto`
--

INSERT INTO `DetalleProducto` (`PK_DetalleProducto`, `FK_Producto`, `FK_Talla`, `FK_Color`, `Peso`) VALUES
(1, 2, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Idiomas`
--

CREATE TABLE `Idiomas` (
  `PK_Idioma` int(11) NOT NULL,
  `idioma` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Idiomas`
--

INSERT INTO `Idiomas` (`PK_Idioma`, `idioma`) VALUES
(1, 'Ingles'),
(2, 'Español');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `LogUsuarios`
--

CREATE TABLE `LogUsuarios` (
  `PK_LogUsuarios` int(11) NOT NULL,
  `Transaccion` varchar(250) DEFAULT NULL,
  `FechaHoraTransaccion` datetime DEFAULT NULL,
  `FK_Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pago_solouno_temp`
--

CREATE TABLE `Pago_solouno_temp` (
  `PK_Pago` varchar(50) NOT NULL,
  `Cantidad` double DEFAULT NULL,
  `FK_Producto` int(11) NOT NULL,
  `FK_Pedido` int(11) DEFAULT NULL,
  `FK_Talla` int(11) DEFAULT NULL,
  `FK_Color` int(11) DEFAULT NULL,
  `FK_Cliente` int(11) DEFAULT NULL,
  `FechaHoraAgregado` datetime DEFAULT NULL,
  `FK_TipoPedido` int(11) DEFAULT NULL,
  `FK_Destinatario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Pago_solouno_temp`
--

INSERT INTO `Pago_solouno_temp` (`PK_Pago`, `Cantidad`, `FK_Producto`, `FK_Pedido`, `FK_Talla`, `FK_Color`, `FK_Cliente`, `FechaHoraAgregado`, `FK_TipoPedido`, `FK_Destinatario`) VALUES
('1_2020-04-30_17:24:26_2', 1, 2, NULL, 1, 1, 1, '2020-04-30 17:24:26', 1, NULL),
('1_2020-04-30_17:25:41_2', 1, 2, NULL, 1, 1, 1, '2020-04-30 17:25:41', 1, NULL),
('1_2020-04-30_17:32:36_2', 1, 2, NULL, 1, 1, 1, '2020-04-30 17:32:36', 1, NULL),
('1_2020-04-30_19:08:24_2', 2, 2, NULL, 1, 1, 1, '2020-04-30 19:08:24', 1, NULL),
('1_2020-05-01_02:20:15_2', 1, 2, NULL, 1, 1, 1, '2020-05-01 02:20:15', 1, NULL),
('1_2020-05-01_03:03:59_2', 1, 2, NULL, 1, 1, 1, '2020-05-01 03:03:59', 1, NULL),
('1_2020-05-01_14:34:46_2', 1, 2, NULL, 1, 1, 1, '2020-05-01 14:34:46', 1, NULL),
('1_2020-05-01_19:25:14_2', 1, 2, NULL, 1, 1, 1, '2020-05-01 19:25:14', 1, NULL),
('1_2020-05-01_19:53:17_2', 1, 2, NULL, 1, 1, 1, '2020-05-01 19:53:17', 1, NULL),
('1_2020-05-02_15:05:30_2', 1, 2, NULL, 1, 1, 1, '2020-05-02 15:05:30', 1, NULL),
('1_2020-05-02_15:23:14_2', 1, 2, NULL, 1, 1, 1, '2020-05-02 15:23:14', 1, NULL),
('1_2020-05-07_15:38:23_2', 1, 2, NULL, 1, 1, 1, '2020-05-07 15:38:23', 1, NULL),
('1_2020-05-07_19:51:46_2', 2, 2, NULL, 1, 1, 1, '2020-05-07 19:51:46', 2, 1),
('1_2020-05-07_21:50:22_2', 2, 2, NULL, 1, 1, 1, '2020-05-07 21:50:22', 2, 1),
('1_2020-05-14_17:32:39_2', 1, 2, NULL, 1, 1, 1, '2020-05-14 17:32:39', 1, NULL),
('1_2020-05-17_15:29:52_2', 1, 2, NULL, 1, 1, 1, '2020-05-17 15:29:52', 1, NULL),
('1_2020-05-17_15:30:25_2', 1, 2, NULL, 1, 1, 1, '2020-05-17 15:30:25', 1, NULL),
('1_2020-05-17_16:05:26_2', 1, 2, NULL, 1, 1, 1, '2020-05-17 16:05:26', 1, NULL),
('1_2020-05-17_16:22:09_3', 1, 3, NULL, NULL, NULL, 1, '2020-05-17 16:22:09', 1, NULL),
('1_2020-05-17_18:19:19_4', 1, 4, NULL, NULL, NULL, 1, '2020-05-17 18:19:19', 1, NULL),
('1_2020-05-21_15:10:52_2', 1, 2, NULL, 1, 1, 1, '2020-05-21 15:10:52', 1, NULL),
('1_2020-05-21_15:11:45_2', 1, 2, NULL, 1, 1, 1, '2020-05-21 15:11:45', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Paises`
--

CREATE TABLE `Paises` (
  `PK_Pais` int(11) NOT NULL,
  `NombrePais` varchar(50) DEFAULT NULL,
  `Logo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Paises`
--

INSERT INTO `Paises` (`PK_Pais`, `NombrePais`, `Logo`) VALUES
(1, 'Honduras', NULL),
(2, 'Nicaragua', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Pedidos`
--

CREATE TABLE `Pedidos` (
  `PK_Pedido` int(11) NOT NULL,
  `FK_Cliente` int(11) NOT NULL,
  `FK_Tienda` int(11) NOT NULL,
  `NumeroPedido` varchar(200) DEFAULT NULL,
  `FechaHoraOrden` datetime DEFAULT NULL,
  `FechaHoraCompra` datetime DEFAULT NULL,
  `FechaHoraEnvio` datetime DEFAULT NULL,
  `FechaHoraEntrega` datetime DEFAULT NULL,
  `Estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Pedidos`
--

INSERT INTO `Pedidos` (`PK_Pedido`, `FK_Cliente`, `FK_Tienda`, `NumeroPedido`, `FechaHoraOrden`, `FechaHoraCompra`, `FechaHoraEnvio`, `FechaHoraEntrega`, `Estado`) VALUES
(7, 1, 1, 'PAYID-L2XCMFA3S6282772C248352S', '2020-05-03 02:02:17', '2020-05-03 02:02:17', NULL, NULL, 1),
(8, 1, 1, 'PAYID-L2XCM7Q5VX89952HE740311V', '2020-05-03 02:04:56', '2020-05-03 02:04:56', NULL, NULL, 1),
(9, 1, 1, 'PAYID-L2XC2GQ1H178847W3513540Y', '2020-05-03 02:37:01', '2020-05-03 02:37:01', NULL, NULL, 1),
(10, 1, 1, 'PAYID-L2XNXHY4BR83753SR5400506', '2020-05-03 14:57:10', '2020-05-03 14:57:10', NULL, NULL, 1),
(11, 1, 1, 'PAYID-L2XNZAI2LE35200KW186660C', '2020-05-03 15:00:34', '2020-05-03 15:00:34', NULL, NULL, 1),
(12, 1, 1, 'PAYID-L22DIYQ3UE34712GU963902U', '2020-05-07 16:16:51', '2020-05-07 16:16:51', NULL, NULL, 1),
(13, 1, 1, 'PAYID-L22IKKA9XN64722VD705471C', '2020-05-07 22:01:56', '2020-05-07 22:01:56', NULL, NULL, 0),
(14, 1, 1, 'PAYID-L22IM6I25N86130EK635894N', '2020-05-07 22:07:08', '2020-05-07 22:07:08', NULL, NULL, 0),
(15, 1, 1, 'PAYID-L25RYYQ29R99123DL6144308', '2020-05-12 22:05:06', '2020-05-12 22:05:06', NULL, NULL, 0),
(16, 1, 1, 'PAYID-L25R7IA3XY74072AN7802930', '2020-05-12 22:14:24', '2020-05-12 22:14:24', NULL, NULL, 0),
(17, 1, 1, 'PAYID-L25SD7Y9YD66164BR334604T', '2020-05-12 22:24:38', '2020-05-12 22:24:38', NULL, NULL, 0),
(18, 1, 1, 'PAYID-L25SQHI3GU83737NV871951K', '2020-05-12 22:50:24', '2020-05-12 22:50:24', NULL, NULL, 0),
(19, 1, 1, 'PAYID-L25SQ7Y3NU89630E57022122', '2020-05-12 22:51:57', '2020-05-12 22:51:57', NULL, NULL, 0),
(20, 1, 1, 'PAYID-L25SRYA0WX84252H01250212', '2020-05-12 22:53:44', '2020-05-12 22:53:44', NULL, NULL, 0),
(21, 1, 1, 'PAYID-L25SSQI7MJ95452K7222134N', '2020-05-12 22:55:11', '2020-05-12 22:55:11', NULL, NULL, 0),
(22, 1, 1, 'PAYID-L25STNQ8VH78852SV420194V', '2020-05-12 22:57:11', '2020-05-12 22:57:11', NULL, NULL, 0),
(23, 1, 1, 'PAYID-L26AN5A3MM67880KK359871E', '2020-05-13 14:41:38', '2020-05-13 14:41:38', NULL, NULL, 0),
(24, 1, 1, 'PAYID-L26APFI72S255473W203134B', '2020-05-13 14:43:49', '2020-05-13 14:43:49', NULL, NULL, 0),
(25, 1, 1, 'PAYID-L26APFI72S255473W203134B', '2020-05-13 14:45:07', '2020-05-13 14:45:07', NULL, NULL, 0),
(26, 1, 1, 'PAYID-L26AQ6A02763280ML0051714', '2020-05-13 14:47:35', '2020-05-13 14:47:35', NULL, NULL, 0),
(27, 1, 1, 'PAYID-L26BAEQ3A6343579U690740R', '2020-05-13 15:20:08', '2020-05-13 15:20:08', NULL, NULL, 0),
(28, 1, 1, 'PAYID-L26BDVI8270803464450010R', '2020-05-13 15:27:33', '2020-05-13 15:27:33', NULL, NULL, 0),
(29, 1, 1, 'PAYID-L3AWAOQ5MR238953H2208933', '2020-05-17 16:04:58', '2020-05-17 16:04:58', NULL, NULL, 0),
(30, 1, 1, 'PAYID-L3AWKJA1JT84973MV432202P', '2020-05-17 16:24:26', '2020-05-17 16:24:26', NULL, NULL, 0),
(31, 1, 1, 'PAYID-L3GC6ZY6VY29919CR519220F', '2020-05-25 20:50:32', '2020-05-25 20:50:32', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Productos`
--

CREATE TABLE `Productos` (
  `PK_Producto` int(11) NOT NULL,
  `NombreProducto` varchar(200) DEFAULT NULL,
  `Descripcion` varchar(12000) DEFAULT NULL,
  `CantidadPorUnidad` int(11) DEFAULT NULL,
  `PrecioUnitario` double DEFAULT NULL,
  `PrecioEnvio` double DEFAULT NULL,
  `Descuento` double DEFAULT NULL,
  `UnidadesDisponibles` int(11) DEFAULT NULL,
  `UnidadesVendidas` int(11) DEFAULT NULL,
  `Estado` tinyint(1) DEFAULT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Ranking` double DEFAULT NULL,
  `Nota` varchar(400) DEFAULT NULL,
  `FK_Tienda` int(11) NOT NULL,
  `FK_Categoria` int(11) NOT NULL,
  `Adomicilio` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Productos`
--

INSERT INTO `Productos` (`PK_Producto`, `NombreProducto`, `Descripcion`, `CantidadPorUnidad`, `PrecioUnitario`, `PrecioEnvio`, `Descuento`, `UnidadesDisponibles`, `UnidadesVendidas`, `Estado`, `Imagen`, `Ranking`, `Nota`, `FK_Tienda`, `FK_Categoria`, `Adomicilio`) VALUES
(2, 'Sandalias de colores', '\r\nManufacturer Model: CoDrone\r\nFun and educational drone\r\nPerfect for beginners learning programming\r\nArduino Compatible controller\r\nUse your Apple or Android smart phone to fly, battle, voice control CoDrone\r\nEasily removable/replaceable motors\r\nDescription\r\nLearning to code is fast and simple with CoDrone, a fully programmable drone. Simply unbox your CoDrone, watch our tutorials, and start coding within minutes. Then watch your code take flight! Start from introductory basics to gaining hands-on experience with real world programming for hardware.\r\n\r\n', 1, 2, 1, 10, 227, 58, 1, 'uploads/img/productos/product.jpg', 1, 'Nuevas', 1, 1, 1),
(3, 'Zapatillas para hombre de cuero genuino', 'Características del artículo\r\nEstado:	\r\nNuevo en caja: Un artículo completamente nuevo, que no fue utilizado ni tiene desgaste (incluidos los hechos a mano) en su envase original (como la caja o la bolsa originales) y/o con las etiquetas originales. Ver todas las definiciones de estado	Estado del artículo:	Nuevo en caja\r\nMens Moccasin Shoes:	Mens Moccasin Shoes	Material de la plantilla:	EVA\r\nCasual Shoes:	Casual Shoes	Genuine Leather S:	Genuine Leather Shoes\r\nMen Flats:	Men Flats	Nombre del departamento:	Adulto\r\nSupervisión materiales:	Cuero geniuno	Talla de calzado (USA): hombre:	4\r\nLoafers:	Loafers	Marca:	Sin marca\r\nEstilo:	Baseball Shoes	Material del revestimiento:	Sintético\r\nTipo de artículo:	Varios zapatos', 1, 10, NULL, NULL, 5, 1, 1, 'uploads/img/productos/product2.jpg', NULL, 'nada', 1, 1, 0),
(4, 'Robolink CoDrone Pro programables', 'Robolink CoDrone Pro programables', 1, 200, 10, 10, 300, 3, 1, 'uploads/img/productos/dron.jpg', 2, NULL, 1, 2, 1),
(6, 'Camiseta De Manga Larga Calavera ', 'Camiseta De Manga Larga Calavera ', 1, 20, 2, NULL, 20, 1, 1, 'uploads/img/productos/camisa.jpg', 4, NULL, 7, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RegionesEnvio`
--

CREATE TABLE `RegionesEnvio` (
  `PK_RegionEnvio` int(11) NOT NULL,
  `FK_Ciudad` int(11) NOT NULL,
  `FK_Tienda` int(11) NOT NULL,
  `PrecioEnvio` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `RegionesEnvio`
--

INSERT INTO `RegionesEnvio` (`PK_RegionEnvio`, `FK_Ciudad`, `FK_Tienda`, `PrecioEnvio`) VALUES
(4, 1, 1, 3),
(5, 4, 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tallas`
--

CREATE TABLE `Tallas` (
  `PK_Talla` int(11) NOT NULL,
  `Talla` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Tallas`
--

INSERT INTO `Tallas` (`PK_Talla`, `Talla`) VALUES
(1, '40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Temp`
--

CREATE TABLE `Temp` (
  `PK` int(11) NOT NULL,
  `Datos` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tiendas`
--

CREATE TABLE `Tiendas` (
  `PK_Tienda` int(11) NOT NULL,
  `NombreTienda` varchar(200) DEFAULT NULL,
  `NombreContacto` varchar(50) DEFAULT NULL,
  `ApellidoContacto` varchar(50) DEFAULT NULL,
  `Direccion1` varchar(200) DEFAULT NULL,
  `Direccion2` varchar(200) DEFAULT NULL,
  `SitioWeb` varchar(100) DEFAULT NULL,
  `Correo` varchar(80) DEFAULT NULL,
  `IDClientePaypal` varchar(200) DEFAULT NULL,
  `Logo` varchar(200) DEFAULT NULL,
  `Adomicilio` tinyint(4) DEFAULT NULL,
  `FK_Ciudad` int(11) NOT NULL,
  `FK_Usuario` int(11) NOT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Portada` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Tiendas`
--

INSERT INTO `Tiendas` (`PK_Tienda`, `NombreTienda`, `NombreContacto`, `ApellidoContacto`, `Direccion1`, `Direccion2`, `SitioWeb`, `Correo`, `IDClientePaypal`, `Logo`, `Adomicilio`, `FK_Ciudad`, `FK_Usuario`, `Telefono`, `Portada`) VALUES
(1, 'Mi Tienda', 'kevin ', 'canales TEMP', '2fre TEMP', 'erf TEMP', 'opticadelrey.ml', 'noe@noe.com', 'AfD5UDBgvoCWjA2v1oEmxVJgBUqDo_bSB6ywQcs71MG6NTe64DTomwuf9Obw35BgjsmPsZQM_hUPMPk_', 'tienda_2_logo.jpg', NULL, 1, 2, '234444', 'tienda_2_portada.jpg'),
(4, 'Tienda de prueba', 'Noe', 'Montoya', 'dire', 'dire 2', '', 'test@test.com', 'AfD5UDBgvoCWjA2v1oEmxVJgBUqDo_bSB6ywQcs71MG6NTe64DTomwuf9Obw35BgjsmPsZQM_hUPMPk', 'tienda_2_logo.jpg', 1, 1, 8, '345', 'tienda_8_portada.jpg'),
(7, 'tienda_test', 'kevin', 'canales', 'feriofjerf', 'erferferfref', '', 'noe_k@outlook.com', 'AfD5UDBgvoCWjA2v1oEmxVJgBUqDo_bSB6ywQcs71MG6NTe64DTomwuf9Obw35BgjsmPsZQM_hUPMPk_', 'tienda_14_20200515224038.jpg', NULL, 4, 14, '3455345', 'tienda_14_20200515221833.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TiendasTipoPago`
--

CREATE TABLE `TiendasTipoPago` (
  `FK_Tienda` int(11) NOT NULL,
  `FK_TipoPago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TiposPago`
--

CREATE TABLE `TiposPago` (
  `PK_TipoPago` int(11) NOT NULL,
  `TipoPago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TiposPedido`
--

CREATE TABLE `TiposPedido` (
  `PK_TipoPedido` int(11) NOT NULL,
  `TipoPedido` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `TiposPedido`
--

INSERT INTO `TiposPedido` (`PK_TipoPedido`, `TipoPedido`) VALUES
(1, 'En tienda'),
(2, 'A domicilio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TipoUsuario`
--

CREATE TABLE `TipoUsuario` (
  `PK_TipoUsuario` int(11) NOT NULL,
  `TipoUsuario` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `TipoUsuario`
--

INSERT INTO `TipoUsuario` (`PK_TipoUsuario`, `TipoUsuario`) VALUES
(1, 'Cliente'),
(2, 'Tienda'),
(3, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `PK_Usuario` int(11) NOT NULL,
  `NombreUsuario` varchar(50) DEFAULT NULL,
  `Contrasena` varchar(50) DEFAULT NULL,
  `Correo` varchar(80) DEFAULT NULL,
  `Estado` tinyint(1) DEFAULT NULL,
  `FK_TipoUsuario` int(11) NOT NULL,
  `FK_Idioma` int(11) NOT NULL,
  `Foto` varchar(100) DEFAULT NULL,
  `CodigoConfirmacion` varchar(100) DEFAULT NULL,
  `EstadoCorreo` tinyint(4) NOT NULL DEFAULT '0',
  `CodRestContrasena` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`PK_Usuario`, `NombreUsuario`, `Contrasena`, `Correo`, `Estado`, `FK_TipoUsuario`, `FK_Idioma`, `Foto`, `CodigoConfirmacion`, `EstadoCorreo`, `CodRestContrasena`) VALUES
(1, 'kevine1', 'eNxbTbYbHFCdi36ieolpyg==', 'noe@noe', 1, 1, 1, NULL, NULL, 1, NULL),
(2, 'noe@noe.com', 'eNxbTbYbHFCdi36ieolpyg==', 'noe@noe.com TEMP', 1, 2, 2, 'tienda_2_logo.jpg', NULL, 1, NULL),
(3, 'cliente1', 'eNxbTbYbHFCdi36ieolpyg==', 'cliente@cliente.com', 1, 1, 1, 'user_3_20200517182451.jpg', NULL, 1, 'U3C150636d20200526'),
(8, 'test@test.com', 'eNxbTbYbHFCdi36ieolpyg==', 'test@test.com', 1, 2, 1, 'tienda_8_logo.jpg', NULL, 0, NULL),
(14, 'noe_k@outlook.com', '0tmsk97Jd3Q86Tef5cwRSA==', 'noe_k@ou.com', 1, 2, 1, 'tienda_14_20200515224038.jpg', 'C012224d20200510', 1, 'U14C221131d20200516'),
(28, 'test', 'eNxbTbYbHFCdi36ieolpyg==', 'kncm.js@gmail.com', 1, 1, 1, '', 'C195834d20200526', 0, 'U28C210004d20200526'),
(32, 'admin', 'eNxbTbYbHFCdi36ieolpyg==', 'hola@fioef.com', 1, 3, 1, 'user_admin_foto_perfil.jpg', 'C161905d20200527', 0, NULL),
(36, 'nuevoadmin', 'eNxbTbYbHFCdi36ieolpyg==', 'nuevo@nuevo.com', 1, 3, 1, '', 'C183641d20200527', 0, NULL),
(37, 'admin2', 'eNxbTbYbHFCdi36ieolpyg==', 'admin@gmail.com', 1, 3, 1, 'user_admin2_foto_perfil.jpg', 'C183836d20200527', 0, NULL),
(38, 'prueba_admin', 'eNxbTbYbHFCdi36ieolpyg==', 'prueba@gmail.com', 1, 3, 1, '', 'C184408d20200527', 0, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Carrito`
--
ALTER TABLE `Carrito`
  ADD PRIMARY KEY (`PK_Carrito`),
  ADD KEY `FK_Producto` (`FK_Producto`),
  ADD KEY `FK_Pedido` (`FK_Pedido`),
  ADD KEY `FK_Talla` (`FK_Talla`),
  ADD KEY `FK_Color` (`FK_Color`),
  ADD KEY `carrito_ibfk_3` (`FK_Cliente`),
  ADD KEY `carrito_ibfk_4` (`FK_TipoPedido`),
  ADD KEY `carrito_ibfk_5` (`FK_Destinatario`);

--
-- Indices de la tabla `Categorias`
--
ALTER TABLE `Categorias`
  ADD PRIMARY KEY (`PK_Categoria`);

--
-- Indices de la tabla `Ciudades`
--
ALTER TABLE `Ciudades`
  ADD PRIMARY KEY (`PK_Ciudad`),
  ADD KEY `FK_Pais` (`FK_Pais`);

--
-- Indices de la tabla `Clientes`
--
ALTER TABLE `Clientes`
  ADD PRIMARY KEY (`PK_Cliente`,`FK_Usuario`),
  ADD KEY `FK_Usuario` (`FK_Usuario`),
  ADD KEY `FK_Ciudad` (`FK_Ciudad`);

--
-- Indices de la tabla `Colores`
--
ALTER TABLE `Colores`
  ADD PRIMARY KEY (`PK_Color`);

--
-- Indices de la tabla `Correos`
--
ALTER TABLE `Correos`
  ADD PRIMARY KEY (`PK_Correo`);

--
-- Indices de la tabla `Destinatarios`
--
ALTER TABLE `Destinatarios`
  ADD PRIMARY KEY (`PK_Destinatario`),
  ADD KEY `FK_Cliente` (`FK_Cliente`),
  ADD KEY `FK_Ciudad` (`FK_Ciudad`);

--
-- Indices de la tabla `DetallePedidos`
--
ALTER TABLE `DetallePedidos`
  ADD PRIMARY KEY (`PK_DetallePedido`),
  ADD KEY `FK_Producto` (`FK_Producto`),
  ADD KEY `FK_Pedido` (`FK_Pedido`),
  ADD KEY `FK_Talla` (`FK_Talla`),
  ADD KEY `FK_Color` (`FK_Color`),
  ADD KEY `carrito_ibfk_3` (`FK_Cliente`),
  ADD KEY `carrito_ibfk_4` (`FK_TipoPedido`),
  ADD KEY `carrito_ibfk_5` (`FK_Destinatario`);

--
-- Indices de la tabla `DetalleProducto`
--
ALTER TABLE `DetalleProducto`
  ADD PRIMARY KEY (`PK_DetalleProducto`),
  ADD KEY `FK_Producto` (`FK_Producto`),
  ADD KEY `FK_Talla` (`FK_Talla`),
  ADD KEY `FK_Color` (`FK_Color`);

--
-- Indices de la tabla `Idiomas`
--
ALTER TABLE `Idiomas`
  ADD PRIMARY KEY (`PK_Idioma`);

--
-- Indices de la tabla `LogUsuarios`
--
ALTER TABLE `LogUsuarios`
  ADD PRIMARY KEY (`PK_LogUsuarios`),
  ADD KEY `FK_Usuario` (`FK_Usuario`);

--
-- Indices de la tabla `Pago_solouno_temp`
--
ALTER TABLE `Pago_solouno_temp`
  ADD PRIMARY KEY (`PK_Pago`),
  ADD KEY `FK_Producto` (`FK_Producto`),
  ADD KEY `FK_Pedido` (`FK_Pedido`),
  ADD KEY `FK_Talla` (`FK_Talla`),
  ADD KEY `FK_Color` (`FK_Color`),
  ADD KEY `carrito_ibfk_3` (`FK_Cliente`),
  ADD KEY `carrito_ibfk_4` (`FK_TipoPedido`),
  ADD KEY `carrito_ibfk_5` (`FK_Destinatario`);

--
-- Indices de la tabla `Paises`
--
ALTER TABLE `Paises`
  ADD PRIMARY KEY (`PK_Pais`);

--
-- Indices de la tabla `Pedidos`
--
ALTER TABLE `Pedidos`
  ADD PRIMARY KEY (`PK_Pedido`),
  ADD KEY `FK_Cliente` (`FK_Cliente`),
  ADD KEY `FK_Tienda` (`FK_Tienda`);

--
-- Indices de la tabla `Productos`
--
ALTER TABLE `Productos`
  ADD PRIMARY KEY (`PK_Producto`),
  ADD KEY `FK_Tienda` (`FK_Tienda`),
  ADD KEY `FK_Categoria` (`FK_Categoria`);
ALTER TABLE `Productos` ADD FULLTEXT KEY `Descripcion` (`Descripcion`);
ALTER TABLE `Productos` ADD FULLTEXT KEY `Descripcion_2` (`Descripcion`);

--
-- Indices de la tabla `RegionesEnvio`
--
ALTER TABLE `RegionesEnvio`
  ADD PRIMARY KEY (`PK_RegionEnvio`),
  ADD KEY `regionesenvio_ibfk_1` (`FK_Ciudad`),
  ADD KEY `regionesenvio_ibfk_2` (`FK_Tienda`);

--
-- Indices de la tabla `Tallas`
--
ALTER TABLE `Tallas`
  ADD PRIMARY KEY (`PK_Talla`);

--
-- Indices de la tabla `Temp`
--
ALTER TABLE `Temp`
  ADD PRIMARY KEY (`PK`);

--
-- Indices de la tabla `Tiendas`
--
ALTER TABLE `Tiendas`
  ADD PRIMARY KEY (`PK_Tienda`),
  ADD KEY `FK_Ciudad` (`FK_Ciudad`),
  ADD KEY `FK_Usuario` (`FK_Usuario`);

--
-- Indices de la tabla `TiendasTipoPago`
--
ALTER TABLE `TiendasTipoPago`
  ADD PRIMARY KEY (`FK_Tienda`,`FK_TipoPago`),
  ADD KEY `FK_TipoPago` (`FK_TipoPago`);

--
-- Indices de la tabla `TiposPago`
--
ALTER TABLE `TiposPago`
  ADD PRIMARY KEY (`PK_TipoPago`);

--
-- Indices de la tabla `TiposPedido`
--
ALTER TABLE `TiposPedido`
  ADD PRIMARY KEY (`PK_TipoPedido`);

--
-- Indices de la tabla `TipoUsuario`
--
ALTER TABLE `TipoUsuario`
  ADD PRIMARY KEY (`PK_TipoUsuario`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`PK_Usuario`),
  ADD UNIQUE KEY `NombreUsuario` (`NombreUsuario`),
  ADD KEY `FK_TipoUsuario` (`FK_TipoUsuario`),
  ADD KEY `FK_Idioma` (`FK_Idioma`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Carrito`
--
ALTER TABLE `Carrito`
  MODIFY `PK_Carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `Categorias`
--
ALTER TABLE `Categorias`
  MODIFY `PK_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `Ciudades`
--
ALTER TABLE `Ciudades`
  MODIFY `PK_Ciudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `Clientes`
--
ALTER TABLE `Clientes`
  MODIFY `PK_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `Colores`
--
ALTER TABLE `Colores`
  MODIFY `PK_Color` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `Correos`
--
ALTER TABLE `Correos`
  MODIFY `PK_Correo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `Destinatarios`
--
ALTER TABLE `Destinatarios`
  MODIFY `PK_Destinatario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `DetallePedidos`
--
ALTER TABLE `DetallePedidos`
  MODIFY `PK_DetallePedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `DetalleProducto`
--
ALTER TABLE `DetalleProducto`
  MODIFY `PK_DetalleProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `Idiomas`
--
ALTER TABLE `Idiomas`
  MODIFY `PK_Idioma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `LogUsuarios`
--
ALTER TABLE `LogUsuarios`
  MODIFY `PK_LogUsuarios` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Paises`
--
ALTER TABLE `Paises`
  MODIFY `PK_Pais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `Pedidos`
--
ALTER TABLE `Pedidos`
  MODIFY `PK_Pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `Productos`
--
ALTER TABLE `Productos`
  MODIFY `PK_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `RegionesEnvio`
--
ALTER TABLE `RegionesEnvio`
  MODIFY `PK_RegionEnvio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `Tallas`
--
ALTER TABLE `Tallas`
  MODIFY `PK_Talla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `Temp`
--
ALTER TABLE `Temp`
  MODIFY `PK` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Tiendas`
--
ALTER TABLE `Tiendas`
  MODIFY `PK_Tienda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `TiposPago`
--
ALTER TABLE `TiposPago`
  MODIFY `PK_TipoPago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TiposPedido`
--
ALTER TABLE `TiposPedido`
  MODIFY `PK_TipoPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `TipoUsuario`
--
ALTER TABLE `TipoUsuario`
  MODIFY `PK_TipoUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `PK_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Carrito`
--
ALTER TABLE `Carrito`
  ADD CONSTRAINT `FK_Color` FOREIGN KEY (`FK_Color`) REFERENCES `Colores` (`PK_Color`),
  ADD CONSTRAINT `FK_Talla` FOREIGN KEY (`FK_Talla`) REFERENCES `Tallas` (`PK_Talla`),
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`FK_Producto`) REFERENCES `Productos` (`PK_Producto`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`FK_Pedido`) REFERENCES `Pedidos` (`PK_Pedido`),
  ADD CONSTRAINT `carrito_ibfk_3` FOREIGN KEY (`FK_Cliente`) REFERENCES `Clientes` (`PK_Cliente`),
  ADD CONSTRAINT `carrito_ibfk_4` FOREIGN KEY (`FK_TipoPedido`) REFERENCES `TiposPedido` (`PK_TipoPedido`),
  ADD CONSTRAINT `carrito_ibfk_5` FOREIGN KEY (`FK_Destinatario`) REFERENCES `Destinatarios` (`PK_Destinatario`);

--
-- Filtros para la tabla `Ciudades`
--
ALTER TABLE `Ciudades`
  ADD CONSTRAINT `ciudades_ibfk_1` FOREIGN KEY (`FK_Pais`) REFERENCES `Paises` (`PK_Pais`);

--
-- Filtros para la tabla `Clientes`
--
ALTER TABLE `Clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`FK_Usuario`) REFERENCES `Usuarios` (`PK_Usuario`),
  ADD CONSTRAINT `clientes_ibfk_2` FOREIGN KEY (`FK_Ciudad`) REFERENCES `Ciudades` (`PK_Ciudad`);

--
-- Filtros para la tabla `Destinatarios`
--
ALTER TABLE `Destinatarios`
  ADD CONSTRAINT `destinatarios_ibfk_1` FOREIGN KEY (`FK_Ciudad`) REFERENCES `Ciudades` (`PK_Ciudad`),
  ADD CONSTRAINT `destinatarios_ibfk_2` FOREIGN KEY (`FK_Cliente`) REFERENCES `Clientes` (`PK_Cliente`);

--
-- Filtros para la tabla `DetallePedidos`
--
ALTER TABLE `DetallePedidos`
  ADD CONSTRAINT `detallepedidos_ibfk_1` FOREIGN KEY (`FK_Color`) REFERENCES `Colores` (`PK_Color`),
  ADD CONSTRAINT `detallepedidos_ibfk_2` FOREIGN KEY (`FK_Talla`) REFERENCES `Tallas` (`PK_Talla`),
  ADD CONSTRAINT `detallepedidos_ibfk_3` FOREIGN KEY (`FK_Producto`) REFERENCES `Productos` (`PK_Producto`),
  ADD CONSTRAINT `detallepedidos_ibfk_4` FOREIGN KEY (`FK_Pedido`) REFERENCES `Pedidos` (`PK_Pedido`),
  ADD CONSTRAINT `detallepedidos_ibfk_5` FOREIGN KEY (`FK_Cliente`) REFERENCES `Clientes` (`PK_Cliente`),
  ADD CONSTRAINT `detallepedidos_ibfk_6` FOREIGN KEY (`FK_TipoPedido`) REFERENCES `TiposPedido` (`PK_TipoPedido`),
  ADD CONSTRAINT `detallepedidos_ibfk_7` FOREIGN KEY (`FK_Destinatario`) REFERENCES `Destinatarios` (`PK_Destinatario`);

--
-- Filtros para la tabla `DetalleProducto`
--
ALTER TABLE `DetalleProducto`
  ADD CONSTRAINT `detalleproducto_ibfk_1` FOREIGN KEY (`FK_Producto`) REFERENCES `Productos` (`PK_Producto`),
  ADD CONSTRAINT `detalleproducto_ibfk_2` FOREIGN KEY (`FK_Talla`) REFERENCES `Tallas` (`PK_Talla`),
  ADD CONSTRAINT `detalleproducto_ibfk_3` FOREIGN KEY (`FK_Color`) REFERENCES `Colores` (`PK_Color`);

--
-- Filtros para la tabla `LogUsuarios`
--
ALTER TABLE `LogUsuarios`
  ADD CONSTRAINT `logusuarios_ibfk_1` FOREIGN KEY (`FK_Usuario`) REFERENCES `Usuarios` (`PK_Usuario`);

--
-- Filtros para la tabla `Pago_solouno_temp`
--
ALTER TABLE `Pago_solouno_temp`
  ADD CONSTRAINT `Pago_solouno_temp_ibfk_1` FOREIGN KEY (`FK_Color`) REFERENCES `Colores` (`PK_Color`),
  ADD CONSTRAINT `Pago_solouno_temp_ibfk_2` FOREIGN KEY (`FK_Talla`) REFERENCES `Tallas` (`PK_Talla`),
  ADD CONSTRAINT `Pago_solouno_temp_ibfk_3` FOREIGN KEY (`FK_Producto`) REFERENCES `Productos` (`PK_Producto`),
  ADD CONSTRAINT `Pago_solouno_temp_ibfk_4` FOREIGN KEY (`FK_Pedido`) REFERENCES `Pedidos` (`PK_Pedido`),
  ADD CONSTRAINT `Pago_solouno_temp_ibfk_5` FOREIGN KEY (`FK_Cliente`) REFERENCES `Clientes` (`PK_Cliente`),
  ADD CONSTRAINT `Pago_solouno_temp_ibfk_6` FOREIGN KEY (`FK_TipoPedido`) REFERENCES `TiposPedido` (`PK_TipoPedido`),
  ADD CONSTRAINT `Pago_solouno_temp_ibfk_7` FOREIGN KEY (`FK_Destinatario`) REFERENCES `Destinatarios` (`PK_Destinatario`);

--
-- Filtros para la tabla `Pedidos`
--
ALTER TABLE `Pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`FK_Cliente`) REFERENCES `Clientes` (`PK_Cliente`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`FK_Tienda`) REFERENCES `Tiendas` (`PK_Tienda`);

--
-- Filtros para la tabla `Productos`
--
ALTER TABLE `Productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`FK_Tienda`) REFERENCES `Tiendas` (`PK_Tienda`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`FK_Categoria`) REFERENCES `Categorias` (`PK_Categoria`);

--
-- Filtros para la tabla `RegionesEnvio`
--
ALTER TABLE `RegionesEnvio`
  ADD CONSTRAINT `regionesenvio_ibfk_1` FOREIGN KEY (`FK_Ciudad`) REFERENCES `Ciudades` (`PK_Ciudad`),
  ADD CONSTRAINT `regionesenvio_ibfk_2` FOREIGN KEY (`FK_Tienda`) REFERENCES `Tiendas` (`PK_Tienda`);

--
-- Filtros para la tabla `Tiendas`
--
ALTER TABLE `Tiendas`
  ADD CONSTRAINT `tiendas_ibfk_1` FOREIGN KEY (`FK_Ciudad`) REFERENCES `Ciudades` (`PK_Ciudad`),
  ADD CONSTRAINT `tiendas_ibfk_2` FOREIGN KEY (`FK_Usuario`) REFERENCES `Usuarios` (`PK_Usuario`);

--
-- Filtros para la tabla `TiendasTipoPago`
--
ALTER TABLE `TiendasTipoPago`
  ADD CONSTRAINT `tiendastipopago_ibfk_1` FOREIGN KEY (`FK_TipoPago`) REFERENCES `TiposPago` (`PK_TipoPago`),
  ADD CONSTRAINT `tiendastipopago_ibfk_2` FOREIGN KEY (`FK_Tienda`) REFERENCES `Tiendas` (`PK_Tienda`);

--
-- Filtros para la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`FK_TipoUsuario`) REFERENCES `TipoUsuario` (`PK_TipoUsuario`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`FK_Idioma`) REFERENCES `Idiomas` (`PK_Idioma`);
