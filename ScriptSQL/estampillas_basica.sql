-- phpMyAdmin SQL Dump
-- version 4.3.3
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-03-2015 a las 12:21:58
-- Versión del servidor: 5.5.40
-- Versión de PHP: 5.4.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `estampillas_arauca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_aplicaciones`
--

CREATE TABLE IF NOT EXISTS `adm_aplicaciones` (
  `apli_id` int(10) unsigned NOT NULL,
  `apli_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `apli_descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `apli_procesoid` int(11) NOT NULL,
  `apli_estadoid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_aplicaciones`
--

INSERT INTO `adm_aplicaciones` (`apli_id`, `apli_nombre`, `apli_descripcion`, `apli_procesoid`, `apli_estadoid`) VALUES
(1, 'Parámetros', '', 1, 1),
(2, 'Contratos', '', 1, 1),
(3, 'Parámetros', '', 2, 1),
(4, 'Estampillas', '', 2, 1),
(6, 'Liquidaciones', '', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_estados`
--

CREATE TABLE IF NOT EXISTS `adm_estados` (
  `esta_id` int(11) NOT NULL,
  `esta_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_estados`
--

INSERT INTO `adm_estados` (`esta_id`, `esta_nombre`) VALUES
(1, 'Activo'),
(4, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_logactividades`
--

CREATE TABLE IF NOT EXISTS `adm_logactividades` (
  `loga_id` int(10) unsigned NOT NULL,
  `loga_fecha` datetime NOT NULL,
  `loga_tabla` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `logacodigonombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loga_codigoid` int(11) NOT NULL,
  `loga_valoresanteriores` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `loga_valoresnuevos` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `loga_accion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loga_ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loga_usuarioid` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10923 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_logactividades`
--

INSERT INTO `adm_logactividades` (`loga_id`, `loga_fecha`, `loga_tabla`, `logacodigonombre`, `loga_codigoid`, `loga_valoresanteriores`, `loga_valoresnuevos`, `loga_accion`, `loga_ip`, `loga_usuarioid`) VALUES
(10899, '2015-03-10 12:09:48', 'session', 'no_aplica', 0, 'no_aplica', 'no_aplica', 'log_in', '192.168.77.1', 1),
(10900, '2015-03-10 12:14:22', 'con_tiposcontratos', 'tico_id', 11, '{"tico_id":"11","tico_nombre":"Convenios universidades","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10901, '2015-03-10 12:15:10', 'con_tiposcontratos', 'tico_id', 8, '{"tico_id":"8","tico_nombre":"Honorarios","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10902, '2015-03-10 12:15:31', 'con_tiposcontratos', 'tico_id', 10, '{"tico_id":"10","tico_nombre":"Convenios universidades-pasantes","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10903, '2015-03-10 12:15:49', 'con_tiposcontratos', 'tico_id', 12, '{"tico_id":"12","tico_nombre":"Convenios para aportar","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10904, '2015-03-10 12:15:58', 'con_tiposcontratos', 'tico_id', 13, '{"tico_id":"13","tico_nombre":"Convenios para recibir","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10905, '2015-03-10 12:16:08', 'con_tiposcontratos', 'tico_id', 14, '{"tico_id":"14","tico_nombre":"Convenios marco","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10906, '2015-03-10 12:16:17', 'con_tiposcontratos', 'tico_id', 15, '{"tico_id":"15","tico_nombre":"Convenio de aportes con organismos internacionales","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10907, '2015-03-10 12:16:52', 'con_tiposcontratos', 'tico_id', 22, '{"tico_id":"22","tico_nombre":"Cofinanciaci\\u00f3n","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10908, '2015-03-10 12:17:11', 'con_tiposcontratos', 'tico_id', 24, '{"tico_id":"24","tico_nombre":"Consultor\\u00eda 2","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10909, '2015-03-10 12:17:31', 'con_tiposcontratos', 'tico_id', 26, '{"tico_id":"26","tico_nombre":"Comisi\\u00f3n","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10910, '2015-03-10 12:17:46', 'con_tiposcontratos', 'tico_id', 28, '{"tico_id":"28","tico_nombre":"Externo","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10911, '2015-03-10 12:18:02', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"APOYO LOGISTICO","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1),
(10912, '2015-03-10 12:18:12', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"CONVENIO INTERADMINISTRATIVO","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1),
(10913, '2015-03-10 12:18:20', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"CONVENIO INTERINSTITUCIONAL","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1),
(10914, '2015-03-10 12:18:27', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"CONTRATO INTERADMINISTRATIVO","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1),
(10915, '2015-03-10 12:19:24', 'con_tiposcontratos', 'tico_id', 29, '{"tico_id":"29","tico_nombre":"APOYO LOGISTICO","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10916, '2015-03-10 12:19:30', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"Apoyo Logistico","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1),
(10917, '2015-03-10 12:19:54', 'con_tiposcontratos', 'tico_id', 30, '{"tico_id":"30","tico_nombre":"CONVENIO INTERADMINISTRATIVO","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10918, '2015-03-10 12:19:58', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"Convenio Interadministrativo","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1),
(10919, '2015-03-10 12:20:29', 'con_tiposcontratos', 'tico_id', 31, '{"tico_id":"31","tico_nombre":"CONVENIO INTERINSTITUCIONAL","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10920, '2015-03-10 12:20:33', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"Convenio Interinstitucional","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1),
(10921, '2015-03-10 12:21:04', 'con_tiposcontratos', 'tico_id', 32, '{"tico_id":"32","tico_nombre":"CONTRATO INTERADMINISTRATIVO","tico_descripcion":""}', '[]', 'DELETE', '192.168.77.1', 1),
(10922, '2015-03-10 12:21:08', 'con_tiposcontratos', 'tico_id', 0, '', '{"tico_nombre":"Contrato Interadministrativo","tico_descripcion":""}', 'INSERT', '192.168.77.1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_menus`
--

CREATE TABLE IF NOT EXISTS `adm_menus` (
  `menu_id` int(10) unsigned NOT NULL,
  `menu_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `menu_descripcion` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `menu_controlador` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `menu_metodo` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `menu_moduloid` int(11) NOT NULL,
  `menu_estadoid` int(11) NOT NULL,
  `menu_ruta` varchar(257) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_menus`
--

INSERT INTO `adm_menus` (`menu_id`, `menu_nombre`, `menu_descripcion`, `menu_controlador`, `menu_metodo`, `menu_moduloid`, `menu_estadoid`, `menu_ruta`) VALUES
(16, 'Listar contratistas', '', 'contratistas', 'manage', 3, 1, 'contratistas/manage'),
(17, 'Nuevo contratista', '', 'contratistas', 'add', 3, 1, 'contratistas/add'),
(20, 'Listar contratos', '', 'contratos', 'manage', 4, 1, 'contratos/manage'),
(21, 'Nuevo contrato', '', 'contratos', 'add', 4, 1, 'contratos/add'),
(24, 'Listar tipos de contratos', '', 'tiposcontratos', 'manage', 5, 1, 'tiposcontratos/manage'),
(25, 'Nuevo tipo de contrato', '', 'tiposcontratos', 'add', 5, 1, 'tiposcontratos/add'),
(32, 'Lista de impesiones', '', 'impresiones', 'manage', 10, 1, 'impresiones/manage'),
(33, 'Anular papel', '', 'impresiones', 'add', 10, 1, 'impresiones/add'),
(34, 'Lista de estampillas pro', '', 'estampillas', 'manage', 9, 1, 'estampillas/manage'),
(35, 'agregar estampillas pro', '', 'estampillas', 'add', 9, 1, 'estampillas/add'),
(38, 'Lista de tipos de régimen', '', 'regimenes', 'manage', 1, 1, 'regimenes/manage'),
(39, 'agregar tipo de régimen', '', 'regimenes', 'add', 1, 1, 'regimenes/add'),
(42, 'Papelería ingresada', '', 'papeles', 'manage', 10, 1, 'papeles/manage'),
(43, 'Ingresar papelería', '', 'papeles', 'add', 10, 1, 'papeles/add'),
(44, 'Importar contratos', '', 'contratos', 'importarcontratos', 4, 1, 'contratos/importarcontratos'),
(45, 'Agregar trámite', '', 'tramites', 'add', 11, 1, 'tramites/add'),
(47, 'Lista de trámites', '', 'tramites', 'manage', 11, 1, 'tramites/manage'),
(49, 'Cargar archivo de pagos', '', 'pagos', 'add', 12, 1, 'pagos/add'),
(50, 'Contratos', '', 'liquidaciones', 'liquidar', 7, 1, 'liquidaciones/liquidar'),
(52, 'Reasignar Papeleria', 'Da acceso a la interfaz que permite re-asignar papeleria de estampillas a otros liquidadores', 'papeles', 'getReassign', 10, 1, 'papeles/getReassign'),
(53, 'Tramites', '', 'liquidaciones', 'liquidartramites', 7, 1, 'liquidaciones/liquidartramites'),
(54, 'Consultar', '', 'liquidaciones', 'consultar', 7, 1, 'liquidaciones/consultar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_modulos`
--

CREATE TABLE IF NOT EXISTS `adm_modulos` (
  `modu_id` int(11) NOT NULL,
  `modu_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `modu_descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `modu_aplicacionid` int(11) unsigned NOT NULL,
  `modu_estadoid` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_modulos`
--

INSERT INTO `adm_modulos` (`modu_id`, `modu_nombre`, `modu_descripcion`, `modu_aplicacionid`, `modu_estadoid`) VALUES
(1, 'Tipos de régimen', '', 1, 1),
(2, 'Tipos tributarios', '', 1, 1),
(3, 'Contratistas', '', 2, 1),
(4, 'Contratos', '', 2, 1),
(5, 'Tipos de contrato', '', 1, 1),
(6, 'Tipos de estampilla', '', 3, 1),
(7, 'Liquidar', '', 6, 1),
(8, 'impresiones', '', 4, 1),
(9, 'Estampillas pro', '', 4, 1),
(10, 'Inventario', '', 4, 1),
(11, 'Trámites', '', 4, 1),
(12, 'Pagos', '', 4, 1),
(13, 'Consultar', '', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_parametros`
--

CREATE TABLE IF NOT EXISTS `adm_parametros` (
  `para_id` int(11) NOT NULL,
  `para_redondeo` int(11) NOT NULL,
  `para_salariominimo` double NOT NULL,
  `para_codigodepartamento` varchar(45) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_parametros`
--

INSERT INTO `adm_parametros` (`para_id`, `para_redondeo`, `para_salariominimo`,`para_codigodepartamento`) VALUES
(1, 2, 644336, '0073');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_perfiles`
--

CREATE TABLE IF NOT EXISTS `adm_perfiles` (
  `perf_id` int(10) unsigned NOT NULL,
  `perf_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `perf_descripcion` text COLLATE utf8_unicode_ci,
  `perf_estado` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_perfiles`
--

INSERT INTO `adm_perfiles` (`perf_id`, `perf_nombre`, `perf_descripcion`, `perf_estado`) VALUES
(1, 'Administrador', NULL, 1),
(4, 'Liquidador', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_perfiles_menus`
--

CREATE TABLE IF NOT EXISTS `adm_perfiles_menus` (
  `peme_id` int(11) unsigned NOT NULL,
  `peme_perfilid` int(11) unsigned NOT NULL,
  `peme_menuid` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_procesos`
--

CREATE TABLE IF NOT EXISTS `adm_procesos` (
  `proc_id` int(11) NOT NULL,
  `proc_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `proc_descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `proc_estadoid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_procesos`
--

INSERT INTO `adm_procesos` (`proc_id`, `proc_nombre`, `proc_descripcion`, `proc_estadoid`) VALUES
(1, 'Contratación', '', 1),
(2, 'Estampillas', '', 1),
(3, 'Liquidador', 'Reemplazo del menu quemado, para los liquidadores', 1),
(4, 'proc_nombre', 'proc_descripcion', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adm_usuarios_menus`
--

CREATE TABLE IF NOT EXISTS `adm_usuarios_menus` (
  `usme_id` int(11) unsigned NOT NULL,
  `usme_usuarioid` int(11) unsigned NOT NULL,
  `usme_menuid` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `adm_usuarios_menus`
--

INSERT INTO `adm_usuarios_menus` (`usme_id`, `usme_usuarioid`, `usme_menuid`) VALUES
(42, 1, 17),
(43, 1, 16),
(47, 1, 20),
(48, 1, 21),
(51, 1, 24),
(52, 1, 42),
(53, 1, 33),
(54, 1, 32),
(55, 1, 44),
(56, 1, 45),
(57, 1, 43),
(59, 1, 47),
(61, 1, 49),
(62, 1, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_contratistas`
--

CREATE TABLE IF NOT EXISTS `con_contratistas` (
  `cont_id` int(11) unsigned NOT NULL,
  `cont_nombre` varchar(255) DEFAULT '',
  `cont_nit` double NOT NULL DEFAULT '0',
  `cont_verificacionid` tinyint(4) DEFAULT NULL,
  `cont_representante` varchar(200) NOT NULL,
  `cont_direccion` varchar(200) DEFAULT NULL,
  `cont_telefono` varchar(100) NOT NULL,
  `cont_regimenid` int(11) unsigned NOT NULL,
  `cont_estado` char(1) NOT NULL DEFAULT '',
  `cont_tributarioid` int(11) unsigned NOT NULL,
  `cont_municipioid` int(11) unsigned NOT NULL,
  `cont_tipocontratistaid` int(11) unsigned NOT NULL,
  `cont_fecha` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=913 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_contratos`
--

CREATE TABLE IF NOT EXISTS `con_contratos` (
  `cntr_id` int(11) unsigned NOT NULL,
  `cntr_numero` int(11) NOT NULL DEFAULT '0',
  `cntr_vigencia` int(4) DEFAULT NULL,
  `cntr_objeto` text,
  `cntr_valor` double(14,2) DEFAULT NULL,
  `cntr_fecha_legalizacion` date DEFAULT NULL,
  `cntr_fecha_firma` date DEFAULT NULL,
  `cntr_fecha_aprobacion` date DEFAULT NULL,
  `cntr_formalidad` int(1) NOT NULL DEFAULT '0',
  `cntr_calculo_vencimiento` tinyint(4) NOT NULL DEFAULT '0',
  `cntr_fecha_vencimiento` date DEFAULT NULL,
  `cntr_acta_inicio` tinyint(1) NOT NULL DEFAULT '0',
  `cntr_acta_liquidacion` tinyint(4) DEFAULT NULL,
  `cntr_plazo_mes` int(11) DEFAULT '0',
  `cntr_plazo_dias` int(11) DEFAULT NULL,
  `cntr_anticipo` tinyint(4) DEFAULT NULL,
  `cntr_porcentaje_anticipo` tinyint(4) DEFAULT NULL,
  `cntr_valor_anticipo` double(14,2) DEFAULT NULL,
  `cntr_cdp` tinyint(4) NOT NULL DEFAULT '0',
  `usuario_insercion` varchar(100) NOT NULL DEFAULT '',
  `fecha_insercion` datetime DEFAULT NULL,
  `id_clase` int(11) DEFAULT NULL,
  `id_subclase` int(11) DEFAULT NULL,
  `cntr_tipocontratoid` int(11) DEFAULT NULL,
  `id_tipo_contratacion` int(11) DEFAULT NULL,
  `cntr_contratistaid` int(11) unsigned NOT NULL,
  `cntr_dependenciaid` int(11) DEFAULT NULL,
  `id_interventor` int(11) DEFAULT NULL,
  `id_ordenador` int(11) DEFAULT NULL,
  `id_depordenadora` int(11) NOT NULL,
  `id_ejecutador` int(11) NOT NULL,
  `cntr_estadoid` int(11) unsigned DEFAULT NULL,
  `cntr_estadolocalid` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1518 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_cuantias`
--

CREATE TABLE IF NOT EXISTS `con_cuantias` (
  `cuan_id` int(11) NOT NULL,
  `cuan_vigencia` int(4) NOT NULL,
  `cuan_minima` double NOT NULL,
  `cuan_menor` double NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_estados`
--

CREATE TABLE IF NOT EXISTS `con_estados` (
  `esta_id` int(11) NOT NULL,
  `esta_descripcion` varchar(4) DEFAULT NULL,
  `esta_nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_estadoslocales`
--

CREATE TABLE IF NOT EXISTS `con_estadoslocales` (
  `eslo_id` int(11) NOT NULL,
  `eslo_nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `con_estadoslocales`
--

INSERT INTO `con_estadoslocales` (`eslo_id`, `eslo_nombre`) VALUES
(1, 'Liquidado'),
(2, 'Legalizado'),
(3, 'Terminado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_regimenes`
--

CREATE TABLE IF NOT EXISTS `con_regimenes` (
  `regi_id` int(11) unsigned NOT NULL,
  `regi_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `regi_descripcion` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `regi_iva` float unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `con_regimenes`
--

INSERT INTO `con_regimenes` (`regi_id`, `regi_nombre`, `regi_descripcion`, `regi_iva`) VALUES
(1, 'común', '', 16),
(2, 'simplificado', '', 8),
(3, 'exento', 'No se realiza descuento para el calculo de las estampillas.', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_tiposcontratistas`
--

CREATE TABLE IF NOT EXISTS `con_tiposcontratistas` (
  `tpco_id` int(11) NOT NULL,
  `tpco_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `con_tiposcontratistas`
--

INSERT INTO `con_tiposcontratistas` (`tpco_id`, `tpco_nombre`) VALUES
(1, 'Natural'),
(2, 'Jurídica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_tiposcontratos`
--

CREATE TABLE IF NOT EXISTS `con_tiposcontratos` (
  `tico_id` int(11) NOT NULL,
  `tico_nombre` varchar(200) NOT NULL DEFAULT '',
  `tico_descripcion` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `con_tiposcontratos`
--

INSERT INTO `con_tiposcontratos` (`tico_id`, `tico_nombre`, `tico_descripcion`) VALUES
(1, 'Servicios', ''),
(2, 'Suministros', ''),
(3, 'Mantenimiento y/o reparación', ''),
(4, 'Obra', ''),
(5, 'Fiduciario', ''),
(6, 'Comodato', ''),
(7, 'Concesión', ''),
(9, 'Consultoría', ''),
(16, 'Comercialización de licores', ''),
(17, 'Cesion', ''),
(18, 'Arrendamiento', ''),
(19, 'Otros', ''),
(20, 'Interventoría', ''),
(21, 'Compraventa', ''),
(25, 'Seguros', ''),
(33, 'Apoyo Logistico', ''),
(34, 'Convenio Interadministrativo', ''),
(35, 'Convenio Interinstitucional', ''),
(36, 'Contrato Interadministrativo', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `con_tributarios`
--

CREATE TABLE IF NOT EXISTS `con_tributarios` (
  `trib_id` int(11) NOT NULL,
  `trib_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `trib_descripcion` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_estampillas`
--

CREATE TABLE IF NOT EXISTS `est_estampillas` (
  `estm_id` int(11) NOT NULL,
  `estm_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `estm_cuenta` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `estm_bancoid` int(11) NOT NULL,
  `estm_rutaimagen` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `estm_descripcion` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_estampillas_tiposcontratos`
--

CREATE TABLE IF NOT EXISTS `est_estampillas_tiposcontratos` (
  `esti_id` int(11) unsigned NOT NULL,
  `esti_estampillaid` int(11) unsigned NOT NULL,
  `esti_tipocontratoid` int(11) unsigned NOT NULL,
  `esti_porcentaje` float NOT NULL,
  `esti_ordenanzaid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_estampillas_tramites`
--

CREATE TABLE IF NOT EXISTS `est_estampillas_tramites` (
  `estr_id` int(11) NOT NULL,
  `estr_estampillaid` int(11) NOT NULL,
  `estr_tramiteid` int(11) NOT NULL,
  `estr_porcentaje` float NOT NULL,
  `estr_ordenanzaid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_facturas`
--

CREATE TABLE IF NOT EXISTS `est_facturas` (
  `fact_id` int(10) unsigned NOT NULL,
  `fact_codigo` bigint(20) unsigned NOT NULL,
  `fact_nombre` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fact_porcentaje` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `fact_valor` double NOT NULL,
  `fact_banco` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fact_cuenta` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fact_rutacomprobante` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `fact_fechacomprobante` datetime NOT NULL,
  `fact_liquidacionid` int(10) unsigned NOT NULL,
  `fact_rutaimagen` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fact_estampillaid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=508 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_impresiones`
--

CREATE TABLE IF NOT EXISTS `est_impresiones` (
  `impr_id` int(11) NOT NULL,
  `impr_estampillaid` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Guarda los codigos generados para cada impresión de estampilla',
  `impr_codigopapel` int(11) NOT NULL,
  `impr_papelid` int(11) NOT NULL,
  `impr_facturaid` int(11) NOT NULL,
  `impr_estado` int(11) NOT NULL,
  `impr_fecha` datetime NOT NULL,
  `impr_observaciones` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `impr_codigo` int(11) NOT NULL,
  `impr_tipoanulacionid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_legalizaciones`
--

CREATE TABLE IF NOT EXISTS `est_legalizaciones` (
  `lega_id` int(11) NOT NULL,
  `lega_codigo` bigint(20) NOT NULL,
  `lega_comentarios` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `lega_fecha` date NOT NULL,
  `lega_contratoid` bigint(20) NOT NULL,
  `lega_liquidacionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_liquidaciones`
--

CREATE TABLE IF NOT EXISTS `est_liquidaciones` (
  `liqu_id` int(11) NOT NULL,
  `liqu_codigo` bigint(20) NOT NULL,
  `liqu_nombreestampilla` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_nombrecontratista` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_tipocontratista` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_nit` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_numero` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_vigencia` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_valorsiniva` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_valorconiva` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_tipocontrato` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_regimen` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_cuentas` varchar(600) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_porcentajes` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_totalestampilla` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_valortotal` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_comentarios` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `liqu_valor` double NOT NULL,
  `liqu_fecha` date NOT NULL,
  `liqu_contratoid` bigint(20) NOT NULL,
  `liqu_tramiteid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_liquidartramites`
--

CREATE TABLE IF NOT EXISTS `est_liquidartramites` (
  `litr_id` int(10) unsigned NOT NULL,
  `litr_tramiteid` int(11) NOT NULL,
  `litr_tramitadorid` bigint(20) NOT NULL,
  `litr_tramitadornombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `litr_estadolocalid` int(11) NOT NULL,
  `litr_observaciones` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `litr_fechaliquidacion` datetime NOT NULL,
  `litr_fechalegalizacion` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_ordenanzas`
--

CREATE TABLE IF NOT EXISTS `est_ordenanzas` (
  `orde_id` int(11) NOT NULL,
  `orde_numero` int(11) NOT NULL,
  `orde_fecha` date NOT NULL,
  `orde_estado` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_pagos`
--

CREATE TABLE IF NOT EXISTS `est_pagos` (
  `pago_id` int(10) unsigned NOT NULL,
  `pago_facturaid` int(10) unsigned NOT NULL,
  `pago_fecha` date NOT NULL,
  `pago_valor` double NOT NULL,
  `pago_metodo` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=456 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_papeles`
--

CREATE TABLE IF NOT EXISTS `est_papeles` (
  `pape_id` int(11) NOT NULL,
  `pape_fecha` datetime NOT NULL,
  `pape_codigoinicial` int(11) NOT NULL,
  `pape_codigofinal` int(11) NOT NULL,
  `pape_cantidad` int(11) NOT NULL,
  `pape_imprimidos` int(11) NOT NULL,
  `pape_observaciones` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `pape_estado` int(11) NOT NULL,
  `pape_usuario` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_recibosdepago`
--

CREATE TABLE IF NOT EXISTS `est_recibosdepago` (
  `reci_id` int(10) unsigned NOT NULL,
  `reci_fecha` date NOT NULL,
  `reci_valor` double NOT NULL,
  `reci_estampillaid` int(11) NOT NULL,
  `reci_contratoid` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_tiposanulaciones`
--

CREATE TABLE IF NOT EXISTS `est_tiposanulaciones` (
  `tisa_id` int(11) NOT NULL,
  `tisa_nombre` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_tiposestampillas`
--

CREATE TABLE IF NOT EXISTS `est_tiposestampillas` (
  `ties_id` int(11) NOT NULL,
  `ties_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ties_descripcion` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `est_tramites`
--

CREATE TABLE IF NOT EXISTS `est_tramites` (
  `tram_id` int(10) unsigned NOT NULL,
  `tram_nombre` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `tram_observaciones` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) unsigned NOT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `par_bancos`
--

CREATE TABLE IF NOT EXISTS `par_bancos` (
  `banc_id` int(11) NOT NULL,
  `banc_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `banc_descripcion` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `par_departamentos`
--

CREATE TABLE IF NOT EXISTS `par_departamentos` (
  `depa_id` int(11) NOT NULL,
  `depa_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `par_departamentos`
--

INSERT INTO `par_departamentos` (`depa_id`, `depa_nombre`) VALUES
(1, 'Amazonas'),
(2, 'Antioquia'),
(3, 'Arauca'),
(4, 'Atlántico'),
(5, 'Bolívar'),
(6, 'Boyacá'),
(7, 'Caldas'),
(8, 'Caquetá'),
(9, 'Casanare'),
(10, 'Cauca'),
(11, 'Cesar'),
(12, 'Chocó'),
(13, 'Córdoba'),
(14, 'Cundinamarca'),
(15, 'Güainia'),
(16, 'Guaviare'),
(17, 'Huila'),
(18, 'La Guajira'),
(19, 'Magdalena'),
(20, 'Meta'),
(21, 'Nariño'),
(22, 'Norte de Santander'),
(23, 'Putumayo'),
(24, 'Quindo'),
(25, 'Risaralda'),
(26, 'San Andrés y Providencia'),
(27, 'Santander'),
(28, 'Sucre'),
(29, 'Tolima'),
(30, 'Valle del Cauca'),
(31, 'Vaupés'),
(32, 'Vichada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `par_municipios`
--

CREATE TABLE IF NOT EXISTS `par_municipios` (
  `muni_id` int(11) NOT NULL,
  `muni_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `muni_departamentoid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `par_municipios`
--

INSERT INTO `par_municipios` (`muni_id`, `muni_nombre`, `muni_departamentoid`) VALUES
(1, 'Leticia', 1),
(2, 'Puerto Nariño', 1),
(3, 'Abejorral', 2),
(4, 'Abriaquí', 2),
(5, 'Alejandria', 2),
(6, 'Amagá', 2),
(7, 'Amalfi', 2),
(8, 'Andes', 2),
(9, 'Angelópolis', 2),
(10, 'Angostura', 2),
(11, 'Anorí', 2),
(12, 'Anzá', 2),
(13, 'Apartadó', 2),
(14, 'Arboletes', 2),
(15, 'Argelia', 2),
(16, 'Armenia', 2),
(17, 'Barbosa', 2),
(18, 'Bello', 2),
(19, 'Belmira', 2),
(20, 'Betania', 2),
(21, 'Betulia', 2),
(22, 'Bolívar', 2),
(23, 'Briceño', 2),
(24, 'Burítica', 2),
(25, 'Caicedo', 2),
(26, 'Caldas', 2),
(27, 'Campamento', 2),
(28, 'Caracolí', 2),
(29, 'Caramanta', 2),
(30, 'Carepa', 2),
(31, 'Carmen de Viboral', 2),
(32, 'Carolina', 2),
(33, 'Caucasia', 2),
(34, 'Cañasgordas', 2),
(35, 'Chigorodó', 2),
(36, 'Cisneros', 2),
(37, 'Cocorná', 2),
(38, 'Concepción', 2),
(39, 'Concordia', 2),
(40, 'Copacabana', 2),
(41, 'Cáceres', 2),
(42, 'Dabeiba', 2),
(43, 'Don Matías', 2),
(44, 'Ebéjico', 2),
(45, 'El Bagre', 2),
(46, 'Entrerríos', 2),
(47, 'Envigado', 2),
(48, 'Fredonia', 2),
(49, 'Frontino', 2),
(50, 'Giraldo', 2),
(51, 'Girardota', 2),
(52, 'Granada', 2),
(53, 'Guadalupe', 2),
(54, 'Guarne', 2),
(55, 'Guatapé', 2),
(56, 'Gómez Plata', 2),
(57, 'Heliconia', 2),
(58, 'Hispania', 2),
(59, 'Itagüí', 2),
(60, 'Ituango', 2),
(61, 'Jardín', 2),
(62, 'Jericó', 2),
(63, 'La Ceja', 2),
(64, 'La Estrella', 2),
(65, 'La Pintada', 2),
(66, 'La Unión', 2),
(67, 'Liborina', 2),
(68, 'Maceo', 2),
(69, 'Marinilla', 2),
(70, 'Medellín', 2),
(71, 'Montebello', 2),
(72, 'Murindó', 2),
(73, 'Mutatá', 2),
(74, 'Nariño', 2),
(75, 'Nechí', 2),
(76, 'Necoclí', 2),
(77, 'Olaya', 2),
(78, 'Peque', 2),
(79, 'Peñol', 2),
(80, 'Pueblorrico', 2),
(81, 'Puerto Berrío', 2),
(82, 'Puerto Nare', 2),
(83, 'Puerto Triunfo', 2),
(84, 'Remedios', 2),
(85, 'Retiro', 2),
(86, 'Ríonegro', 2),
(87, 'Sabanalarga', 2),
(88, 'Sabaneta', 2),
(89, 'Salgar', 2),
(90, 'San Andrés de Cuerquía', 2),
(91, 'San Carlos', 2),
(92, 'San Francisco', 2),
(93, 'San Jerónimo', 2),
(94, 'San José de Montaña', 2),
(95, 'San Juan de Urabá', 2),
(96, 'San Luís', 2),
(97, 'San Pedro', 2),
(98, 'San Pedro de Urabá', 2),
(99, 'San Rafael', 2),
(100, 'San Roque', 2),
(101, 'San Vicente', 2),
(102, 'Santa Bárbara', 2),
(103, 'Santa Fé de Antioquia', 2),
(104, 'Santa Rosa de Osos', 2),
(105, 'Santo Domingo', 2),
(106, 'Santuario', 2),
(107, 'Segovia', 2),
(108, 'Sonsón', 2),
(109, 'Sopetrán', 2),
(110, 'Tarazá', 2),
(111, 'Tarso', 2),
(112, 'Titiribí', 2),
(113, 'Toledo', 2),
(114, 'Turbo', 2),
(115, 'Támesis', 2),
(116, 'Uramita', 2),
(117, 'Urrao', 2),
(118, 'Valdivia', 2),
(119, 'Valparaiso', 2),
(120, 'Vegachí', 2),
(121, 'Venecia', 2),
(122, 'Vigía del Fuerte', 2),
(123, 'Yalí', 2),
(124, 'Yarumal', 2),
(125, 'Yolombó', 2),
(126, 'Yondó (Casabe)', 2),
(127, 'Zaragoza', 2),
(128, 'Arauca', 3),
(129, 'Arauquita', 3),
(130, 'Cravo Norte', 3),
(131, 'Fortúl', 3),
(132, 'Puerto Rondón', 3),
(133, 'Saravena', 3),
(134, 'Tame', 3),
(135, 'Baranoa', 4),
(136, 'Barranquilla', 4),
(137, 'Campo de la Cruz', 4),
(138, 'Candelaria', 4),
(139, 'Galapa', 4),
(140, 'Juan de Acosta', 4),
(141, 'Luruaco', 4),
(142, 'Malambo', 4),
(143, 'Manatí', 4),
(144, 'Palmar de Varela', 4),
(145, 'Piojo', 4),
(146, 'Polonuevo', 4),
(147, 'Ponedera', 4),
(148, 'Puerto Colombia', 4),
(149, 'Repelón', 4),
(150, 'Sabanagrande', 4),
(151, 'Sabanalarga', 4),
(152, 'Santa Lucía', 4),
(153, 'Santo Tomás', 4),
(154, 'Soledad', 4),
(155, 'Suan', 4),
(156, 'Tubará', 4),
(157, 'Usiacuri', 4),
(158, 'Achí', 5),
(159, 'Altos del Rosario', 5),
(160, 'Arenal', 5),
(161, 'Arjona', 5),
(162, 'Arroyohondo', 5),
(163, 'Barranco de Loba', 5),
(164, 'Calamar', 5),
(165, 'Cantagallo', 5),
(166, 'Cartagena', 5),
(167, 'Cicuco', 5),
(168, 'Clemencia', 5),
(169, 'Córdoba', 5),
(170, 'El Carmen de Bolívar', 5),
(171, 'El Guamo', 5),
(172, 'El Peñon', 5),
(173, 'Hatillo de Loba', 5),
(174, 'Magangué', 5),
(175, 'Mahates', 5),
(176, 'Margarita', 5),
(177, 'María la Baja', 5),
(178, 'Mompós', 5),
(179, 'Montecristo', 5),
(180, 'Morales', 5),
(181, 'Norosí', 5),
(182, 'Pinillos', 5),
(183, 'Regidor', 5),
(184, 'Río Viejo', 5),
(185, 'San Cristobal', 5),
(186, 'San Estanislao', 5),
(187, 'San Fernando', 5),
(188, 'San Jacinto', 5),
(189, 'San Jacinto del Cauca', 5),
(190, 'San Juan de Nepomuceno', 5),
(191, 'San Martín de Loba', 5),
(192, 'San Pablo', 5),
(193, 'Santa Catalina', 5),
(194, 'Santa Rosa ', 5),
(195, 'Santa Rosa del Sur', 5),
(196, 'Simití', 5),
(197, 'Soplaviento', 5),
(198, 'Talaigua Nuevo', 5),
(199, 'Tiquisio (Puerto Rico)', 5),
(200, 'Turbaco', 5),
(201, 'Turbaná', 5),
(202, 'Villanueva', 5),
(203, 'Zambrano', 5),
(204, 'Almeida', 6),
(205, 'Aquitania', 6),
(206, 'Arcabuco', 6),
(207, 'Belén', 6),
(208, 'Berbeo', 6),
(209, 'Beteitiva', 6),
(210, 'Boavita', 6),
(211, 'Boyacá', 6),
(212, 'Briceño', 6),
(213, 'Buenavista', 6),
(214, 'Busbanza', 6),
(215, 'Caldas', 6),
(216, 'Campohermoso', 6),
(217, 'Cerinza', 6),
(218, 'Chinavita', 6),
(219, 'Chiquinquirá', 6),
(220, 'Chiscas', 6),
(221, 'Chita', 6),
(222, 'Chitaraque', 6),
(223, 'Chivatá', 6),
(224, 'Chíquiza', 6),
(225, 'Chívor', 6),
(226, 'Ciénaga', 6),
(227, 'Coper', 6),
(228, 'Corrales', 6),
(229, 'Covarachía', 6),
(230, 'Cubará', 6),
(231, 'Cucaita', 6),
(232, 'Cuitiva', 6),
(233, 'Cómbita', 6),
(234, 'Duitama', 6),
(235, 'El Cocuy', 6),
(236, 'El Espino', 6),
(237, 'Firavitoba', 6),
(238, 'Floresta', 6),
(239, 'Gachantivá', 6),
(240, 'Garagoa', 6),
(241, 'Guacamayas', 6),
(242, 'Guateque', 6),
(243, 'Guayatá', 6),
(244, 'Guicán', 6),
(245, 'Gámeza', 6),
(246, 'Izá', 6),
(247, 'Jenesano', 6),
(248, 'Jericó', 6),
(249, 'La Capilla', 6),
(250, 'La Uvita', 6),
(251, 'La Victoria', 6),
(252, 'Labranzagrande', 6),
(253, 'Macanal', 6),
(254, 'Maripí', 6),
(255, 'Miraflores', 6),
(256, 'Mongua', 6),
(257, 'Monguí', 6),
(258, 'Moniquirá', 6),
(259, 'Motavita', 6),
(260, 'Muzo', 6),
(261, 'Nobsa', 6),
(262, 'Nuevo Colón', 6),
(263, 'Oicatá', 6),
(264, 'Otanche', 6),
(265, 'Pachavita', 6),
(266, 'Paipa', 6),
(267, 'Pajarito', 6),
(268, 'Panqueba', 6),
(269, 'Pauna', 6),
(270, 'Paya', 6),
(271, 'Paz de Río', 6),
(272, 'Pesca', 6),
(273, 'Pisva', 6),
(274, 'Puerto Boyacá', 6),
(275, 'Páez', 6),
(276, 'Quipama', 6),
(277, 'Ramiriquí', 6),
(278, 'Rondón', 6),
(279, 'Ráquira', 6),
(280, 'Saboyá', 6),
(281, 'Samacá', 6),
(282, 'San Eduardo', 6),
(283, 'San José de Pare', 6),
(284, 'San Luís de Gaceno', 6),
(285, 'San Mateo', 6),
(286, 'San Miguel de Sema', 6),
(287, 'San Pablo de Borbur', 6),
(288, 'Santa María', 6),
(289, 'Santa Rosa de Viterbo', 6),
(290, 'Santa Sofía', 6),
(291, 'Santana', 6),
(292, 'Sativanorte', 6),
(293, 'Sativasur', 6),
(294, 'Siachoque', 6),
(295, 'Soatá', 6),
(296, 'Socha', 6),
(297, 'Socotá', 6),
(298, 'Sogamoso', 6),
(299, 'Somondoco', 6),
(300, 'Sora', 6),
(301, 'Soracá', 6),
(302, 'Sotaquirá', 6),
(303, 'Susacón', 6),
(304, 'Sutamarchán', 6),
(305, 'Sutatenza', 6),
(306, 'Sáchica', 6),
(307, 'Tasco', 6),
(308, 'Tenza', 6),
(309, 'Tibaná', 6),
(310, 'Tibasosa', 6),
(311, 'Tinjacá', 6),
(312, 'Tipacoque', 6),
(313, 'Toca', 6),
(314, 'Toguí', 6),
(315, 'Topagá', 6),
(316, 'Tota', 6),
(317, 'Tunja', 6),
(318, 'Tunungua', 6),
(319, 'Turmequé', 6),
(320, 'Tuta', 6),
(321, 'Tutasá', 6),
(322, 'Ventaquemada', 6),
(323, 'Villa de Leiva', 6),
(324, 'Viracachá', 6),
(325, 'Zetaquirá', 6),
(326, 'Úmbita', 6),
(327, 'Aguadas', 7),
(328, 'Anserma', 7),
(329, 'Aranzazu', 7),
(330, 'Belalcázar', 7),
(331, 'Chinchiná', 7),
(332, 'Filadelfia', 7),
(333, 'La Dorada', 7),
(334, 'La Merced', 7),
(335, 'La Victoria', 7),
(336, 'Manizales', 7),
(337, 'Manzanares', 7),
(338, 'Marmato', 7),
(339, 'Marquetalia', 7),
(340, 'Marulanda', 7),
(341, 'Neira', 7),
(342, 'Norcasia', 7),
(343, 'Palestina', 7),
(344, 'Pensilvania', 7),
(345, 'Pácora', 7),
(346, 'Risaralda', 7),
(347, 'Río Sucio', 7),
(348, 'Salamina', 7),
(349, 'Samaná', 7),
(350, 'San José', 7),
(351, 'Supía', 7),
(352, 'Villamaría', 7),
(353, 'Viterbo', 7),
(354, 'Albania', 8),
(355, 'Belén de los Andaquíes', 8),
(356, 'Cartagena del Chairá', 8),
(357, 'Curillo', 8),
(358, 'El Doncello', 8),
(359, 'El Paujil', 8),
(360, 'Florencia', 8),
(361, 'La Montañita', 8),
(362, 'Milán', 8),
(363, 'Morelia', 8),
(364, 'Puerto Rico', 8),
(365, 'San José del Fragua', 8),
(366, 'San Vicente del Caguán', 8),
(367, 'Solano', 8),
(368, 'Solita', 8),
(369, 'Valparaiso', 8),
(370, 'Aguazul', 9),
(371, 'Chámeza', 9),
(372, 'Hato Corozal', 9),
(373, 'La Salina', 9),
(374, 'Maní', 9),
(375, 'Monterrey', 9),
(376, 'Nunchía', 9),
(377, 'Orocué', 9),
(378, 'Paz de Ariporo', 9),
(379, 'Pore', 9),
(380, 'Recetor', 9),
(381, 'Sabanalarga', 9),
(382, 'San Luís de Palenque', 9),
(383, 'Sácama', 9),
(384, 'Tauramena', 9),
(385, 'Trinidad', 9),
(386, 'Támara', 9),
(387, 'Villanueva', 9),
(388, 'Yopal', 9),
(389, 'Almaguer', 10),
(390, 'Argelia', 10),
(391, 'Balboa', 10),
(392, 'Bolívar', 10),
(393, 'Buenos Aires', 10),
(394, 'Cajibío', 10),
(395, 'Caldono', 10),
(396, 'Caloto', 10),
(397, 'Corinto', 10),
(398, 'El Tambo', 10),
(399, 'Florencia', 10),
(400, 'Guachené', 10),
(401, 'Guapí', 10),
(402, 'Inzá', 10),
(403, 'Jambaló', 10),
(404, 'La Sierra', 10),
(405, 'La Vega', 10),
(406, 'López (Micay)', 10),
(407, 'Mercaderes', 10),
(408, 'Miranda', 10),
(409, 'Morales', 10),
(410, 'Padilla', 10),
(411, 'Patía (El Bordo)', 10),
(412, 'Piamonte', 10),
(413, 'Piendamó', 10),
(414, 'Popayán', 10),
(415, 'Puerto Tejada', 10),
(416, 'Puracé (Coconuco)', 10),
(417, 'Páez (Belalcazar)', 10),
(418, 'Rosas', 10),
(419, 'San Sebastián', 10),
(420, 'Santa Rosa', 10),
(421, 'Santander de Quilichao', 10),
(422, 'Silvia', 10),
(423, 'Sotara (Paispamba)', 10),
(424, 'Sucre', 10),
(425, 'Suárez', 10),
(426, 'Timbiquí', 10),
(427, 'Timbío', 10),
(428, 'Toribío', 10),
(429, 'Totoró', 10),
(430, 'Villa Rica', 10),
(431, 'Aguachica', 11),
(432, 'Agustín Codazzi', 11),
(433, 'Astrea', 11),
(434, 'Becerríl', 11),
(435, 'Bosconia', 11),
(436, 'Chimichagua', 11),
(437, 'Chiriguaná', 11),
(438, 'Curumaní', 11),
(439, 'El Copey', 11),
(440, 'El Paso', 11),
(441, 'Gamarra', 11),
(442, 'Gonzalez', 11),
(443, 'La Gloria', 11),
(444, 'La Jagua de Ibirico', 11),
(445, 'La Paz (Robles)', 11),
(446, 'Manaure Balcón del Cesar', 11),
(447, 'Pailitas', 11),
(448, 'Pelaya', 11),
(449, 'Pueblo Bello', 11),
(450, 'Río de oro', 11),
(451, 'San Alberto', 11),
(452, 'San Diego', 11),
(453, 'San Martín', 11),
(454, 'Tamalameque', 11),
(455, 'Valledupar', 11),
(456, 'Acandí', 12),
(457, 'Alto Baudó (Pie de Pato)', 12),
(458, 'Atrato (Yuto)', 12),
(459, 'Bagadó', 12),
(460, 'Bahía Solano (Mútis)', 12),
(461, 'Bajo Baudó (Pizarro)', 12),
(462, 'Belén de Bajirá', 12),
(463, 'Bojayá (Bellavista)', 12),
(464, 'Cantón de San Pablo', 12),
(465, 'Carmen del Darién (CURBARADÓ)', 12),
(466, 'Condoto', 12),
(467, 'Cértegui', 12),
(468, 'El Carmen de Atrato', 12),
(469, 'Istmina', 12),
(470, 'Juradó', 12),
(471, 'Lloró', 12),
(472, 'Medio Atrato', 12),
(473, 'Medio Baudó', 12),
(474, 'Medio San Juan (ANDAGOYA)', 12),
(475, 'Novita', 12),
(476, 'Nuquí', 12),
(477, 'Quibdó', 12),
(478, 'Río Iró', 12),
(479, 'Río Quito', 12),
(480, 'Ríosucio', 12),
(481, 'San José del Palmar', 12),
(482, 'Santa Genoveva de Docorodó', 12),
(483, 'Sipí', 12),
(484, 'Tadó', 12),
(485, 'Unguía', 12),
(486, 'Unión Panamericana (ÁNIMAS)', 12),
(487, 'Ayapel', 13),
(488, 'Buenavista', 13),
(489, 'Canalete', 13),
(490, 'Cereté', 13),
(491, 'Chimá', 13),
(492, 'Chinú', 13),
(493, 'Ciénaga de Oro', 13),
(494, 'Cotorra', 13),
(495, 'La Apartada y La Frontera', 13),
(496, 'Lorica', 13),
(497, 'Los Córdobas', 13),
(498, 'Momil', 13),
(499, 'Montelíbano', 13),
(500, 'Monteria', 13),
(501, 'Moñitos', 13),
(502, 'Planeta Rica', 13),
(503, 'Pueblo Nuevo', 13),
(504, 'Puerto Escondido', 13),
(505, 'Puerto Libertador', 13),
(506, 'Purísima', 13),
(507, 'Sahagún', 13),
(508, 'San Andrés Sotavento', 13),
(509, 'San Antero', 13),
(510, 'San Bernardo del Viento', 13),
(511, 'San Carlos', 13),
(512, 'San José de Uré', 13),
(513, 'San Pelayo', 13),
(514, 'Tierralta', 13),
(515, 'Tuchín', 13),
(516, 'Valencia', 13),
(517, 'Agua de Dios', 14),
(518, 'Albán', 14),
(519, 'Anapoima', 14),
(520, 'Anolaima', 14),
(521, 'Apulo', 14),
(522, 'Arbeláez', 14),
(523, 'Beltrán', 14),
(524, 'Bituima', 14),
(525, 'Bogotá D.C.', 14),
(526, 'Bojacá', 14),
(527, 'Cabrera', 14),
(528, 'Cachipay', 14),
(529, 'Cajicá', 14),
(530, 'Caparrapí', 14),
(531, 'Carmen de Carupa', 14),
(532, 'Chaguaní', 14),
(533, 'Chipaque', 14),
(534, 'Choachí', 14),
(535, 'Chocontá', 14),
(536, 'Chía', 14),
(537, 'Cogua', 14),
(538, 'Cota', 14),
(539, 'Cucunubá', 14),
(540, 'Cáqueza', 14),
(541, 'El Colegio', 14),
(542, 'El Peñón', 14),
(543, 'El Rosal', 14),
(544, 'Facatativá', 14),
(545, 'Fosca', 14),
(546, 'Funza', 14),
(547, 'Fusagasugá', 14),
(548, 'Fómeque', 14),
(549, 'Fúquene', 14),
(550, 'Gachalá', 14),
(551, 'Gachancipá', 14),
(552, 'Gachetá', 14),
(553, 'Gama', 14),
(554, 'Girardot', 14),
(555, 'Granada', 14),
(556, 'Guachetá', 14),
(557, 'Guaduas', 14),
(558, 'Guasca', 14),
(559, 'Guataquí', 14),
(560, 'Guatavita', 14),
(561, 'Guayabal de Siquima', 14),
(562, 'Guayabetal', 14),
(563, 'Gutiérrez', 14),
(564, 'Jerusalén', 14),
(565, 'Junín', 14),
(566, 'La Calera', 14),
(567, 'La Mesa', 14),
(568, 'La Palma', 14),
(569, 'La Peña', 14),
(570, 'La Vega', 14),
(571, 'Lenguazaque', 14),
(572, 'Machetá', 14),
(573, 'Madrid', 14),
(574, 'Manta', 14),
(575, 'Medina', 14),
(576, 'Mosquera', 14),
(577, 'Nariño', 14),
(578, 'Nemocón', 14),
(579, 'Nilo', 14),
(580, 'Nimaima', 14),
(581, 'Nocaima', 14),
(582, 'Pacho', 14),
(583, 'Paime', 14),
(584, 'Pandi', 14),
(585, 'Paratebueno', 14),
(586, 'Pasca', 14),
(587, 'Puerto Salgar', 14),
(588, 'Pulí', 14),
(589, 'Quebradanegra', 14),
(590, 'Quetame', 14),
(591, 'Quipile', 14),
(592, 'Ricaurte', 14),
(593, 'San Antonio de Tequendama', 14),
(594, 'San Bernardo', 14),
(595, 'San Cayetano', 14),
(596, 'San Francisco', 14),
(597, 'San Juan de Río Seco', 14),
(598, 'Sasaima', 14),
(599, 'Sesquilé', 14),
(600, 'Sibaté', 14),
(601, 'Silvania', 14),
(602, 'Simijaca', 14),
(603, 'Soacha', 14),
(604, 'Sopó', 14),
(605, 'Subachoque', 14),
(606, 'Suesca', 14),
(607, 'Supatá', 14),
(608, 'Susa', 14),
(609, 'Sutatausa', 14),
(610, 'Tabio', 14),
(611, 'Tausa', 14),
(612, 'Tena', 14),
(613, 'Tenjo', 14),
(614, 'Tibacuy', 14),
(615, 'Tibirita', 14),
(616, 'Tocaima', 14),
(617, 'Tocancipá', 14),
(618, 'Topaipí', 14),
(619, 'Ubalá', 14),
(620, 'Ubaque', 14),
(621, 'Ubaté', 14),
(622, 'Une', 14),
(623, 'Venecia (Ospina Pérez)', 14),
(624, 'Vergara', 14),
(625, 'Viani', 14),
(626, 'Villagómez', 14),
(627, 'Villapinzón', 14),
(628, 'Villeta', 14),
(629, 'Viotá', 14),
(630, 'Yacopí', 14),
(631, 'Zipacón', 14),
(632, 'Zipaquirá', 14),
(633, 'Útica', 14),
(634, 'Inírida', 15),
(635, 'Calamar', 16),
(636, 'El Retorno', 16),
(637, 'Miraflores', 16),
(638, 'San José del Guaviare', 16),
(639, 'Acevedo', 17),
(640, 'Agrado', 17),
(641, 'Aipe', 17),
(642, 'Algeciras', 17),
(643, 'Altamira', 17),
(644, 'Baraya', 17),
(645, 'Campoalegre', 17),
(646, 'Colombia', 17),
(647, 'Elías', 17),
(648, 'Garzón', 17),
(649, 'Gigante', 17),
(650, 'Guadalupe', 17),
(651, 'Hobo', 17),
(652, 'Isnos', 17),
(653, 'La Argentina', 17),
(654, 'La Plata', 17),
(655, 'Neiva', 17),
(656, 'Nátaga', 17),
(657, 'Oporapa', 17),
(658, 'Paicol', 17),
(659, 'Palermo', 17),
(660, 'Palestina', 17),
(661, 'Pital', 17),
(662, 'Pitalito', 17),
(663, 'Rivera', 17),
(664, 'Saladoblanco', 17),
(665, 'San Agustín', 17),
(666, 'Santa María', 17),
(667, 'Suaza', 17),
(668, 'Tarqui', 17),
(669, 'Tello', 17),
(670, 'Teruel', 17),
(671, 'Tesalia', 17),
(672, 'Timaná', 17),
(673, 'Villavieja', 17),
(674, 'Yaguará', 17),
(675, 'Íquira', 17),
(676, 'Albania', 18),
(677, 'Barrancas', 18),
(678, 'Dibulla', 18),
(679, 'Distracción', 18),
(680, 'El Molino', 18),
(681, 'Fonseca', 18),
(682, 'Hatonuevo', 18),
(683, 'La Jagua del Pilar', 18),
(684, 'Maicao', 18),
(685, 'Manaure', 18),
(686, 'Riohacha', 18),
(687, 'San Juan del Cesar', 18),
(688, 'Uribia', 18),
(689, 'Urumita', 18),
(690, 'Villanueva', 18),
(691, 'Algarrobo', 19),
(692, 'Aracataca', 19),
(693, 'Ariguaní (El Difícil)', 19),
(694, 'Cerro San Antonio', 19),
(695, 'Chivolo', 19),
(696, 'Ciénaga', 19),
(697, 'Concordia', 19),
(698, 'El Banco', 19),
(699, 'El Piñon', 19),
(700, 'El Retén', 19),
(701, 'Fundación', 19),
(702, 'Guamal', 19),
(703, 'Nueva Granada', 19),
(704, 'Pedraza', 19),
(705, 'Pijiño', 19),
(706, 'Pivijay', 19),
(707, 'Plato', 19),
(708, 'Puebloviejo', 19),
(709, 'Remolino', 19),
(710, 'Sabanas de San Angel (SAN ANGEL)', 19),
(711, 'Salamina', 19),
(712, 'San Sebastián de Buenavista', 19),
(713, 'San Zenón', 19),
(714, 'Santa Ana', 19),
(715, 'Santa Bárbara de Pinto', 19),
(716, 'Santa Marta', 19),
(717, 'Sitionuevo', 19),
(718, 'Tenerife', 19),
(719, 'Zapayán (PUNTA DE PIEDRAS)', 19),
(720, 'Zona Bananera (PRADO - SEVILLA)', 19),
(721, 'Acacías', 20),
(722, 'Barranca de Upía', 20),
(723, 'Cabuyaro', 20),
(724, 'Castilla la Nueva', 20),
(725, 'Cubarral', 20),
(726, 'Cumaral', 20),
(727, 'El Calvario', 20),
(728, 'El Castillo', 20),
(729, 'El Dorado', 20),
(730, 'Fuente de Oro', 20),
(731, 'Granada', 20),
(732, 'Guamal', 20),
(733, 'La Macarena', 20),
(734, 'Lejanías', 20),
(735, 'Mapiripan', 20),
(736, 'Mesetas', 20),
(737, 'Puerto Concordia', 20),
(738, 'Puerto Gaitán', 20),
(739, 'Puerto Lleras', 20),
(740, 'Puerto López', 20),
(741, 'Puerto Rico', 20),
(742, 'Restrepo', 20),
(743, 'San Carlos de Guaroa', 20),
(744, 'San Juan de Arama', 20),
(745, 'San Juanito', 20),
(746, 'San Martín', 20),
(747, 'Uribe', 20),
(748, 'Villavicencio', 20),
(749, 'Vista Hermosa', 20),
(750, 'Albán (San José)', 21),
(751, 'Aldana', 21),
(752, 'Ancuya', 21),
(753, 'Arboleda (Berruecos)', 21),
(754, 'Barbacoas', 21),
(755, 'Belén', 21),
(756, 'Buesaco', 21),
(757, 'Chachaguí', 21),
(758, 'Colón (Génova)', 21),
(759, 'Consaca', 21),
(760, 'Contadero', 21),
(761, 'Cuaspud (Carlosama)', 21),
(762, 'Cumbal', 21),
(763, 'Cumbitara', 21),
(764, 'Córdoba', 21),
(765, 'El Charco', 21),
(766, 'El Peñol', 21),
(767, 'El Rosario', 21),
(768, 'El Tablón de Gómez', 21),
(769, 'El Tambo', 21),
(770, 'Francisco Pizarro', 21),
(771, 'Funes', 21),
(772, 'Guachavés', 21),
(773, 'Guachucal', 21),
(774, 'Guaitarilla', 21),
(775, 'Gualmatán', 21),
(776, 'Iles', 21),
(777, 'Imúes', 21),
(778, 'Ipiales', 21),
(779, 'La Cruz', 21),
(780, 'La Florida', 21),
(781, 'La Llanada', 21),
(782, 'La Tola', 21),
(783, 'La Unión', 21),
(784, 'Leiva', 21),
(785, 'Linares', 21),
(786, 'Magüi (Payán)', 21),
(787, 'Mallama (Piedrancha)', 21),
(788, 'Mosquera', 21),
(789, 'Nariño', 21),
(790, 'Olaya Herrera', 21),
(791, 'Ospina', 21),
(792, 'Policarpa', 21),
(793, 'Potosí', 21),
(794, 'Providencia', 21),
(795, 'Puerres', 21),
(796, 'Pupiales', 21),
(797, 'Ricaurte', 21),
(798, 'Roberto Payán (San José)', 21),
(799, 'Samaniego', 21),
(800, 'San Bernardo', 21),
(801, 'San Juan de Pasto', 21),
(802, 'San Lorenzo', 21),
(803, 'San Pablo', 21),
(804, 'San Pedro de Cartago', 21),
(805, 'Sandoná', 21),
(806, 'Santa Bárbara (Iscuandé)', 21),
(807, 'Sapuyes', 21),
(808, 'Sotomayor (Los Andes)', 21),
(809, 'Taminango', 21),
(810, 'Tangua', 21),
(811, 'Tumaco', 21),
(812, 'Túquerres', 21),
(813, 'Yacuanquer', 21),
(814, 'Arboledas', 22),
(815, 'Bochalema', 22),
(816, 'Bucarasica', 22),
(817, 'Chinácota', 22),
(818, 'Chitagá', 22),
(819, 'Convención', 22),
(820, 'Cucutilla', 22),
(821, 'Cáchira', 22),
(822, 'Cácota', 22),
(823, 'Cúcuta', 22),
(824, 'Durania', 22),
(825, 'El Carmen', 22),
(826, 'El Tarra', 22),
(827, 'El Zulia', 22),
(828, 'Gramalote', 22),
(829, 'Hacarí', 22),
(830, 'Herrán', 22),
(831, 'La Esperanza', 22),
(832, 'La Playa', 22),
(833, 'Labateca', 22),
(834, 'Los Patios', 22),
(835, 'Lourdes', 22),
(836, 'Mutiscua', 22),
(837, 'Ocaña', 22),
(838, 'Pamplona', 22),
(839, 'Pamplonita', 22),
(840, 'Puerto Santander', 22),
(841, 'Ragonvalia', 22),
(842, 'Salazar', 22),
(843, 'San Calixto', 22),
(844, 'San Cayetano', 22),
(845, 'Santiago', 22),
(846, 'Sardinata', 22),
(847, 'Silos', 22),
(848, 'Teorama', 22),
(849, 'Tibú', 22),
(850, 'Toledo', 22),
(851, 'Villa Caro', 22),
(852, 'Villa del Rosario', 22),
(853, 'Ábrego', 22),
(854, 'Colón', 23),
(855, 'Mocoa', 23),
(856, 'Orito', 23),
(857, 'Puerto Asís', 23),
(858, 'Puerto Caicedo', 23),
(859, 'Puerto Guzmán', 23),
(860, 'Puerto Leguízamo', 23),
(861, 'San Francisco', 23),
(862, 'San Miguel', 23),
(863, 'Santiago', 23),
(864, 'Sibundoy', 23),
(865, 'Valle del Guamuez', 23),
(866, 'Villagarzón', 23),
(867, 'Armenia', 24),
(868, 'Buenavista', 24),
(869, 'Calarcá', 24),
(870, 'Circasia', 24),
(871, 'Cordobá', 24),
(872, 'Filandia', 24),
(873, 'Génova', 24),
(874, 'La Tebaida', 24),
(875, 'Montenegro', 24),
(876, 'Pijao', 24),
(877, 'Quimbaya', 24),
(878, 'Salento', 24),
(879, 'Apía', 25),
(880, 'Balboa', 25),
(881, 'Belén de Umbría', 25),
(882, 'Dos Quebradas', 25),
(883, 'Guática', 25),
(884, 'La Celia', 25),
(885, 'La Virginia', 25),
(886, 'Marsella', 25),
(887, 'Mistrató', 25),
(888, 'Pereira', 25),
(889, 'Pueblo Rico', 25),
(890, 'Quinchía', 25),
(891, 'Santa Rosa de Cabal', 25),
(892, 'Santuario', 25),
(893, 'Providencia', 26),
(894, 'Aguada', 27),
(895, 'Albania', 27),
(896, 'Aratoca', 27),
(897, 'Barbosa', 27),
(898, 'Barichara', 27),
(899, 'Barrancabermeja', 27),
(900, 'Betulia', 27),
(901, 'Bolívar', 27),
(902, 'Bucaramanga', 27),
(903, 'Cabrera', 27),
(904, 'California', 27),
(905, 'Capitanejo', 27),
(906, 'Carcasí', 27),
(907, 'Cepita', 27),
(908, 'Cerrito', 27),
(909, 'Charalá', 27),
(910, 'Charta', 27),
(911, 'Chima', 27),
(912, 'Chipatá', 27),
(913, 'Cimitarra', 27),
(914, 'Concepción', 27),
(915, 'Confines', 27),
(916, 'Contratación', 27),
(917, 'Coromoro', 27),
(918, 'Curití', 27),
(919, 'El Carmen', 27),
(920, 'El Guacamayo', 27),
(921, 'El Peñon', 27),
(922, 'El Playón', 27),
(923, 'Encino', 27),
(924, 'Enciso', 27),
(925, 'Floridablanca', 27),
(926, 'Florián', 27),
(927, 'Galán', 27),
(928, 'Girón', 27),
(929, 'Guaca', 27),
(930, 'Guadalupe', 27),
(931, 'Guapota', 27),
(932, 'Guavatá', 27),
(933, 'Guepsa', 27),
(934, 'Gámbita', 27),
(935, 'Hato', 27),
(936, 'Jesús María', 27),
(937, 'Jordán', 27),
(938, 'La Belleza', 27),
(939, 'La Paz', 27),
(940, 'Landázuri', 27),
(941, 'Lebrija', 27),
(942, 'Los Santos', 27),
(943, 'Macaravita', 27),
(944, 'Matanza', 27),
(945, 'Mogotes', 27),
(946, 'Molagavita', 27),
(947, 'Málaga', 27),
(948, 'Ocamonte', 27),
(949, 'Oiba', 27),
(950, 'Onzaga', 27),
(951, 'Palmar', 27),
(952, 'Palmas del Socorro', 27),
(953, 'Pie de Cuesta', 27),
(954, 'Pinchote', 27),
(955, 'Puente Nacional', 27),
(956, 'Puerto Parra', 27),
(957, 'Puerto Wilches', 27),
(958, 'Páramo', 27),
(959, 'Rio Negro', 27),
(960, 'Sabana de Torres', 27),
(961, 'San Andrés', 27),
(962, 'San Benito', 27),
(963, 'San Gíl', 27),
(964, 'San Joaquín', 27),
(965, 'San José de Miranda', 27),
(966, 'San Miguel', 27),
(967, 'San Vicente del Chucurí', 27),
(968, 'Santa Bárbara', 27),
(969, 'Santa Helena del Opón', 27),
(970, 'Simacota', 27),
(971, 'Socorro', 27),
(972, 'Suaita', 27),
(973, 'Sucre', 27),
(974, 'Suratá', 27),
(975, 'Tona', 27),
(976, 'Valle de San José', 27),
(977, 'Vetas', 27),
(978, 'Villanueva', 27),
(979, 'Vélez', 27),
(980, 'Zapatoca', 27),
(981, 'Buenavista', 28),
(982, 'Caimito', 28),
(983, 'Chalán', 28),
(984, 'Colosó (Ricaurte)', 28),
(985, 'Corozal', 28),
(986, 'Coveñas', 28),
(987, 'El Roble', 28),
(988, 'Galeras (Nueva Granada)', 28),
(989, 'Guaranda', 28),
(990, 'La Unión', 28),
(991, 'Los Palmitos', 28),
(992, 'Majagual', 28),
(993, 'Morroa', 28),
(994, 'Ovejas', 28),
(995, 'Palmito', 28),
(996, 'Sampués', 28),
(997, 'San Benito Abad', 28),
(998, 'San Juan de Betulia', 28),
(999, 'San Marcos', 28),
(1000, 'San Onofre', 28);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `perfilid` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2222222223 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `perfilid`) VALUES
(1, 0x2e2e2e, 'administrator', 'f197b1d88d859e3f1191cf247e6b45a414b5a1a0', '9462e8eee0', 'admin@admin.com', NULL, '084ec27737c64215aefe4c4f3ec46f120a123bd5', 1406747138, 'cac9db1e437076d7a633faaa6d11b41b0fdf611e', 1268889823, 1426014588, 1, 'Iván', 'Viña', 'Bitbahía', '300700000', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(4, 1, 1),
(5, 2, 1),
(6, 3, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adm_aplicaciones`
--
ALTER TABLE `adm_aplicaciones`
  ADD PRIMARY KEY (`apli_id`), ADD KEY `fk_adm_aplicaciones_1_idx` (`apli_procesoid`);

--
-- Indices de la tabla `adm_estados`
--
ALTER TABLE `adm_estados`
  ADD PRIMARY KEY (`esta_id`), ADD UNIQUE KEY `esta_nombre` (`esta_nombre`);

--
-- Indices de la tabla `adm_logactividades`
--
ALTER TABLE `adm_logactividades`
  ADD PRIMARY KEY (`loga_id`), ADD KEY `fk_adm_logactividades_1_idx` (`loga_usuarioid`);

--
-- Indices de la tabla `adm_menus`
--
ALTER TABLE `adm_menus`
  ADD PRIMARY KEY (`menu_id`), ADD KEY `fk_adm_menus_1_idx` (`menu_moduloid`), ADD KEY `fk_adm_menus_2_idx` (`menu_estadoid`);

--
-- Indices de la tabla `adm_modulos`
--
ALTER TABLE `adm_modulos`
  ADD PRIMARY KEY (`modu_id`), ADD KEY `fk_adm_modulos_1_idx` (`modu_aplicacionid`), ADD KEY `fk_adm_modulos_2_idx` (`modu_estadoid`);

--
-- Indices de la tabla `adm_parametros`
--
ALTER TABLE `adm_parametros`
  ADD PRIMARY KEY (`para_id`);

--
-- Indices de la tabla `adm_perfiles`
--
ALTER TABLE `adm_perfiles`
  ADD PRIMARY KEY (`perf_id`), ADD UNIQUE KEY `nombreperfil` (`perf_nombre`), ADD KEY `fk_adm_perfiles_1_idx` (`perf_estado`);

--
-- Indices de la tabla `adm_perfiles_menus`
--
ALTER TABLE `adm_perfiles_menus`
  ADD PRIMARY KEY (`peme_id`), ADD KEY `peme_id` (`peme_id`), ADD KEY `fk_adm_perfiles_menus_1_idx` (`peme_perfilid`), ADD KEY `fk_adm_perfiles_menus_1_idx1` (`peme_menuid`);

--
-- Indices de la tabla `adm_procesos`
--
ALTER TABLE `adm_procesos`
  ADD PRIMARY KEY (`proc_id`), ADD KEY `proc_id` (`proc_id`);

--
-- Indices de la tabla `adm_usuarios_menus`
--
ALTER TABLE `adm_usuarios_menus`
  ADD PRIMARY KEY (`usme_id`), ADD KEY `fk_adm_usuarios_menus_1_idx` (`usme_usuarioid`), ADD KEY `fk_adm_usuarios_menus_2_idx` (`usme_menuid`);

--
-- Indices de la tabla `con_contratistas`
--
ALTER TABLE `con_contratistas`
  ADD PRIMARY KEY (`cont_id`), ADD KEY `fk_con_contratistas_1_idx` (`cont_regimenid`), ADD KEY `fk_con_contratistas_2_idx` (`cont_tributarioid`), ADD KEY `fk_con_contratistas_3_idx` (`cont_municipioid`), ADD KEY `fk_con_contratistas_4_idx` (`cont_tipocontratistaid`);

--
-- Indices de la tabla `con_contratos`
--
ALTER TABLE `con_contratos`
  ADD PRIMARY KEY (`cntr_id`), ADD KEY `fk_con_contratos_1_idx` (`cntr_tipocontratoid`), ADD KEY `fk_con_contratos_2_idx` (`cntr_contratistaid`);

--
-- Indices de la tabla `con_cuantias`
--
ALTER TABLE `con_cuantias`
  ADD PRIMARY KEY (`cuan_id`), ADD KEY `cuan_id` (`cuan_id`);

--
-- Indices de la tabla `con_estados`
--
ALTER TABLE `con_estados`
  ADD PRIMARY KEY (`esta_id`);

--
-- Indices de la tabla `con_estadoslocales`
--
ALTER TABLE `con_estadoslocales`
  ADD PRIMARY KEY (`eslo_id`);

--
-- Indices de la tabla `con_regimenes`
--
ALTER TABLE `con_regimenes`
  ADD PRIMARY KEY (`regi_id`), ADD UNIQUE KEY `nombre` (`regi_nombre`);

--
-- Indices de la tabla `con_tiposcontratistas`
--
ALTER TABLE `con_tiposcontratistas`
  ADD PRIMARY KEY (`tpco_id`);

--
-- Indices de la tabla `con_tiposcontratos`
--
ALTER TABLE `con_tiposcontratos`
  ADD PRIMARY KEY (`tico_id`);

--
-- Indices de la tabla `con_tributarios`
--
ALTER TABLE `con_tributarios`
  ADD PRIMARY KEY (`trib_id`), ADD UNIQUE KEY `nombre` (`trib_nombre`);

--
-- Indices de la tabla `est_estampillas`
--
ALTER TABLE `est_estampillas`
  ADD PRIMARY KEY (`estm_id`);

--
-- Indices de la tabla `est_estampillas_tiposcontratos`
--
ALTER TABLE `est_estampillas_tiposcontratos`
  ADD PRIMARY KEY (`esti_id`);

--
-- Indices de la tabla `est_estampillas_tramites`
--
ALTER TABLE `est_estampillas_tramites`
  ADD PRIMARY KEY (`estr_id`);

--
-- Indices de la tabla `est_facturas`
--
ALTER TABLE `est_facturas`
  ADD PRIMARY KEY (`fact_id`), ADD KEY `fk_est_facturas_estampilla_idx` (`fact_estampillaid`);

--
-- Indices de la tabla `est_impresiones`
--
ALTER TABLE `est_impresiones`
  ADD PRIMARY KEY (`impr_id`), ADD UNIQUE KEY `impr_codigopapel` (`impr_codigopapel`);

--
-- Indices de la tabla `est_legalizaciones`
--
ALTER TABLE `est_legalizaciones`
  ADD PRIMARY KEY (`lega_id`);

--
-- Indices de la tabla `est_liquidaciones`
--
ALTER TABLE `est_liquidaciones`
  ADD PRIMARY KEY (`liqu_id`);

--
-- Indices de la tabla `est_liquidartramites`
--
ALTER TABLE `est_liquidartramites`
  ADD PRIMARY KEY (`litr_id`);

--
-- Indices de la tabla `est_ordenanzas`
--
ALTER TABLE `est_ordenanzas`
  ADD PRIMARY KEY (`orde_id`);

--
-- Indices de la tabla `est_pagos`
--
ALTER TABLE `est_pagos`
  ADD PRIMARY KEY (`pago_id`), ADD UNIQUE KEY `pago_facturaid` (`pago_facturaid`);

--
-- Indices de la tabla `est_papeles`
--
ALTER TABLE `est_papeles`
  ADD PRIMARY KEY (`pape_id`), ADD KEY `pape_usuario` (`pape_usuario`);

--
-- Indices de la tabla `est_recibosdepago`
--
ALTER TABLE `est_recibosdepago`
  ADD PRIMARY KEY (`reci_id`);

--
-- Indices de la tabla `est_tiposanulaciones`
--
ALTER TABLE `est_tiposanulaciones`
  ADD PRIMARY KEY (`tisa_id`);

--
-- Indices de la tabla `est_tiposestampillas`
--
ALTER TABLE `est_tiposestampillas`
  ADD PRIMARY KEY (`ties_id`);

--
-- Indices de la tabla `est_tramites`
--
ALTER TABLE `est_tramites`
  ADD PRIMARY KEY (`tram_id`);

--
-- Indices de la tabla `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `par_bancos`
--
ALTER TABLE `par_bancos`
  ADD PRIMARY KEY (`banc_id`);

--
-- Indices de la tabla `par_departamentos`
--
ALTER TABLE `par_departamentos`
  ADD PRIMARY KEY (`depa_id`), ADD KEY `depa_id` (`depa_id`);

--
-- Indices de la tabla `par_municipios`
--
ALTER TABLE `par_municipios`
  ADD PRIMARY KEY (`muni_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `ruta` (`email`), ADD KEY `fk_users_perfil_idx` (`perfilid`);

--
-- Indices de la tabla `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`), ADD KEY `fk_users_groups_users1_idx` (`user_id`), ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adm_aplicaciones`
--
ALTER TABLE `adm_aplicaciones`
  MODIFY `apli_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `adm_estados`
--
ALTER TABLE `adm_estados`
  MODIFY `esta_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `adm_logactividades`
--
ALTER TABLE `adm_logactividades`
  MODIFY `loga_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10923;
--
-- AUTO_INCREMENT de la tabla `adm_menus`
--
ALTER TABLE `adm_menus`
  MODIFY `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT de la tabla `adm_modulos`
--
ALTER TABLE `adm_modulos`
  MODIFY `modu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `adm_parametros`
--
ALTER TABLE `adm_parametros`
  MODIFY `para_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `adm_perfiles`
--
ALTER TABLE `adm_perfiles`
  MODIFY `perf_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `adm_perfiles_menus`
--
ALTER TABLE `adm_perfiles_menus`
  MODIFY `peme_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `adm_procesos`
--
ALTER TABLE `adm_procesos`
  MODIFY `proc_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `adm_usuarios_menus`
--
ALTER TABLE `adm_usuarios_menus`
  MODIFY `usme_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT de la tabla `con_contratistas`
--
ALTER TABLE `con_contratistas`
  MODIFY `cont_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=913;
--
-- AUTO_INCREMENT de la tabla `con_contratos`
--
ALTER TABLE `con_contratos`
  MODIFY `cntr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1518;
--
-- AUTO_INCREMENT de la tabla `con_cuantias`
--
ALTER TABLE `con_cuantias`
  MODIFY `cuan_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `con_estados`
--
ALTER TABLE `con_estados`
  MODIFY `esta_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `con_estadoslocales`
--
ALTER TABLE `con_estadoslocales`
  MODIFY `eslo_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `con_regimenes`
--
ALTER TABLE `con_regimenes`
  MODIFY `regi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `con_tiposcontratistas`
--
ALTER TABLE `con_tiposcontratistas`
  MODIFY `tpco_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `con_tiposcontratos`
--
ALTER TABLE `con_tiposcontratos`
  MODIFY `tico_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT de la tabla `con_tributarios`
--
ALTER TABLE `con_tributarios`
  MODIFY `trib_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `est_estampillas`
--
ALTER TABLE `est_estampillas`
  MODIFY `estm_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `est_estampillas_tiposcontratos`
--
ALTER TABLE `est_estampillas_tiposcontratos`
  MODIFY `esti_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT de la tabla `est_estampillas_tramites`
--
ALTER TABLE `est_estampillas_tramites`
  MODIFY `estr_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `est_facturas`
--
ALTER TABLE `est_facturas`
  MODIFY `fact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=508;
--
-- AUTO_INCREMENT de la tabla `est_impresiones`
--
ALTER TABLE `est_impresiones`
  MODIFY `impr_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=398;
--
-- AUTO_INCREMENT de la tabla `est_legalizaciones`
--
ALTER TABLE `est_legalizaciones`
  MODIFY `lega_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `est_liquidaciones`
--
ALTER TABLE `est_liquidaciones`
  MODIFY `liqu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=262;
--
-- AUTO_INCREMENT de la tabla `est_liquidartramites`
--
ALTER TABLE `est_liquidartramites`
  MODIFY `litr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT de la tabla `est_ordenanzas`
--
ALTER TABLE `est_ordenanzas`
  MODIFY `orde_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `est_pagos`
--
ALTER TABLE `est_pagos`
  MODIFY `pago_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=456;
--
-- AUTO_INCREMENT de la tabla `est_papeles`
--
ALTER TABLE `est_papeles`
  MODIFY `pape_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT de la tabla `est_recibosdepago`
--
ALTER TABLE `est_recibosdepago`
  MODIFY `reci_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `est_tiposanulaciones`
--
ALTER TABLE `est_tiposanulaciones`
  MODIFY `tisa_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `est_tiposestampillas`
--
ALTER TABLE `est_tiposestampillas`
  MODIFY `ties_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `est_tramites`
--
ALTER TABLE `est_tramites`
  MODIFY `tram_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `par_bancos`
--
ALTER TABLE `par_bancos`
  MODIFY `banc_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `par_departamentos`
--
ALTER TABLE `par_departamentos`
  MODIFY `depa_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT de la tabla `par_municipios`
--
ALTER TABLE `par_municipios`
  MODIFY `muni_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1103;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2222222223;
--
-- AUTO_INCREMENT de la tabla `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adm_aplicaciones`
--
ALTER TABLE `adm_aplicaciones`
ADD CONSTRAINT `fk_adm_aplicaciones_1` FOREIGN KEY (`apli_procesoid`) REFERENCES `adm_procesos` (`proc_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `adm_logactividades`
--
ALTER TABLE `adm_logactividades`
ADD CONSTRAINT `fk_adm_logactividades_1` FOREIGN KEY (`loga_usuarioid`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `adm_menus`
--
ALTER TABLE `adm_menus`
ADD CONSTRAINT `fk_adm_menus_1` FOREIGN KEY (`menu_moduloid`) REFERENCES `adm_modulos` (`modu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_adm_menus_2` FOREIGN KEY (`menu_estadoid`) REFERENCES `adm_estados` (`esta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `adm_modulos`
--
ALTER TABLE `adm_modulos`
ADD CONSTRAINT `fk_adm_modulos_1` FOREIGN KEY (`modu_aplicacionid`) REFERENCES `adm_aplicaciones` (`apli_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `adm_perfiles`
--
ALTER TABLE `adm_perfiles`
ADD CONSTRAINT `fk_adm_perfiles_1` FOREIGN KEY (`perf_estado`) REFERENCES `adm_estados` (`esta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `adm_perfiles_menus`
--
ALTER TABLE `adm_perfiles_menus`
ADD CONSTRAINT `fk_adm_perfiles_menus_1` FOREIGN KEY (`peme_perfilid`) REFERENCES `adm_perfiles` (`perf_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `fk_users_perfil` FOREIGN KEY (`perfilid`) REFERENCES `adm_perfiles` (`perf_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users_groups`
--
ALTER TABLE `users_groups`
ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
