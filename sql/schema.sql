-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-01-2026 a las 23:41:25
-- Versión del servidor: 10.11.15-MariaDB-cll-lve
-- Versión de PHP: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zqgikadc_clientesinforcom`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_ultima_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas_renovacion`
--

DROP TABLE IF EXISTS `alertas_renovacion`;
CREATE TABLE IF NOT EXISTS `alertas_renovacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `servicio_tipo` enum('Hosting','Dominio','Correo','Diseño Web','Diseño Gráfico') NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `dias_aviso` int(11) DEFAULT 30,
  `fecha_renovacion` date DEFAULT NULL,
  `alerta_enviada` tinyint(1) DEFAULT 0,
  `fecha_alerta_enviada` timestamp NULL DEFAULT NULL,
  `renovado` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cliente_id` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_cliente` varchar(150) NOT NULL,
  `contacto` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `empresa` varchar(150) DEFAULT NULL,
  `ruc_dni` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `notas_generales` text DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Suspendido','En Revisión') DEFAULT 'Activo',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `correo` varchar(190) NOT NULL,
  `dominio` varchar(255) NOT NULL,
  `notas_adicionales` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_clientes_nombre_cliente` (`nombre_cliente`),
  UNIQUE KEY `ux_clientes_dominio` (`dominio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formas_pago`
--

DROP TABLE IF EXISTS `formas_pago`;
CREATE TABLE IF NOT EXISTS `formas_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_cambios`
--

DROP TABLE IF EXISTS `historial_cambios`;
CREATE TABLE IF NOT EXISTS `historial_cambios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabla_afectada` varchar(100) NOT NULL,
  `registro_id` int(11) NOT NULL,
  `tipo_cambio` enum('Creación','Actualización','Eliminación') NOT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `usuario` varchar(50) DEFAULT 'admin',
  `fecha_cambio` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

DROP TABLE IF EXISTS `pagos`;
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `servicio_tipo` enum('Hosting','Dominio','Correo','Diseño Web','Diseño Gráfico') NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `forma_pago_id` int(11) DEFAULT NULL,
  `fecha_pago` date NOT NULL,
  `numero_comprobante` varchar(100) DEFAULT NULL,
  `comprobante_archivo` varchar(255) DEFAULT NULL,
  `detalles_pago` text DEFAULT NULL,
  `notas_adicionales` text DEFAULT NULL,
  `estado_pago` enum('Pagado','Pendiente','Rechazado','Cancelado') DEFAULT 'Pendiente',
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `forma_pago_id` (`forma_pago_id`),
  KEY `idx_cliente_id` (`cliente_id`),
  KEY `idx_estado_pagos` (`estado_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_hosting`
--

DROP TABLE IF EXISTS `planes_hosting`;
CREATE TABLE IF NOT EXISTS `planes_hosting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrantes`
--

DROP TABLE IF EXISTS `registrantes`;
CREATE TABLE IF NOT EXISTS `registrantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_correo`
--

DROP TABLE IF EXISTS `servicios_correo`;
CREATE TABLE IF NOT EXISTS `servicios_correo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `fecha_renovacion` date DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` enum('Activo','Suspendido','Vencido','Cancelado') DEFAULT 'Activo',
  `dias_aviso_renovacion` int(11) DEFAULT 30,
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tipo_id` (`tipo_id`),
  KEY `idx_cliente_id` (`cliente_id`),
  KEY `idx_estado_correo` (`estado`),
  KEY `idx_fecha_renovacion_correo` (`fecha_renovacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_diseno_grafico`
--

DROP TABLE IF EXISTS `servicios_diseno_grafico`;
CREATE TABLE IF NOT EXISTS `servicios_diseno_grafico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `fecha_entrega_estimada` date DEFAULT NULL,
  `fecha_entrega_real` date DEFAULT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `precio_adelanto` decimal(10,2) NOT NULL,
  `precio_saldo` decimal(10,2) NOT NULL,
  `estado` enum('En Desarrollo','En Revisión','Completado','Cancelado') DEFAULT 'En Desarrollo',
  `porcentaje_avance` int(11) DEFAULT 0,
  `notas_adicionales` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tipo_id` (`tipo_id`),
  KEY `idx_cliente_id` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_diseno_web`
--

DROP TABLE IF EXISTS `servicios_diseno_web`;
CREATE TABLE IF NOT EXISTS `servicios_diseno_web` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `fecha_entrega_estimada` date DEFAULT NULL,
  `fecha_entrega_real` date DEFAULT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `precio_adelanto` decimal(10,2) NOT NULL,
  `precio_saldo` decimal(10,2) NOT NULL,
  `estado` enum('En Desarrollo','En Revisión','Completado','Cancelado') DEFAULT 'En Desarrollo',
  `porcentaje_avance` int(11) DEFAULT 0,
  `notas_adicionales` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tipo_id` (`tipo_id`),
  KEY `idx_cliente_id` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_dominios`
--

DROP TABLE IF EXISTS `servicios_dominios`;
CREATE TABLE IF NOT EXISTS `servicios_dominios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `dominio_nombre` varchar(150) NOT NULL,
  `tld_id` int(11) NOT NULL,
  `registrante_id` int(11) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `fecha_renovacion` date DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` enum('Activo','Suspendido','Vencido','Cancelado') DEFAULT 'Activo',
  `dias_aviso_renovacion` int(11) DEFAULT 30,
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tld_id` (`tld_id`),
  KEY `registrante_id` (`registrante_id`),
  KEY `idx_cliente_id` (`cliente_id`),
  KEY `idx_estado_dominios` (`estado`),
  KEY `idx_fecha_renovacion_dominios` (`fecha_renovacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_hosting`
--

DROP TABLE IF EXISTS `servicios_hosting`;
CREATE TABLE IF NOT EXISTS `servicios_hosting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `fecha_renovacion` date DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` enum('Activo','Suspendido','Vencido','Cancelado') DEFAULT 'Activo',
  `dias_aviso_renovacion` int(11) DEFAULT 30,
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `plan_id` (`plan_id`),
  KEY `idx_cliente_id` (`cliente_id`),
  KEY `idx_estado_hosting` (`estado`),
  KEY `idx_fecha_renovacion_hosting` (`fecha_renovacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_otro`
--

DROP TABLE IF EXISTS `servicios_otro`;
CREATE TABLE IF NOT EXISTS `servicios_otro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tipo_id` (`tipo_id`),
  KEY `idx_cliente_id` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_correo`
--

DROP TABLE IF EXISTS `tipos_correo`;
CREATE TABLE IF NOT EXISTS `tipos_correo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_diseno_grafico`
--

DROP TABLE IF EXISTS `tipos_diseno_grafico`;
CREATE TABLE IF NOT EXISTS `tipos_diseno_grafico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_diseno_web`
--

DROP TABLE IF EXISTS `tipos_diseno_web`;
CREATE TABLE IF NOT EXISTS `tipos_diseno_web` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_otro`
--

DROP TABLE IF EXISTS `tipos_otro`;
CREATE TABLE IF NOT EXISTS `tipos_otro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tld_dominios`
--

DROP TABLE IF EXISTS `tld_dominios`;
CREATE TABLE IF NOT EXISTS `tld_dominios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tld` varchar(20) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `tld` (`tld`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas_renovacion`
--
ALTER TABLE `alertas_renovacion`
  ADD CONSTRAINT `alertas_renovacion_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`forma_pago_id`) REFERENCES `formas_pago` (`id`);

--
-- Filtros para la tabla `servicios_correo`
--
ALTER TABLE `servicios_correo`
  ADD CONSTRAINT `servicios_correo_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_correo_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_correo` (`id`);

--
-- Filtros para la tabla `servicios_diseno_grafico`
--
ALTER TABLE `servicios_diseno_grafico`
  ADD CONSTRAINT `servicios_diseno_grafico_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_diseno_grafico_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_diseno_grafico` (`id`);

--
-- Filtros para la tabla `servicios_diseno_web`
--
ALTER TABLE `servicios_diseno_web`
  ADD CONSTRAINT `servicios_diseno_web_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_diseno_web_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_diseno_web` (`id`);

--
-- Filtros para la tabla `servicios_dominios`
--
ALTER TABLE `servicios_dominios`
  ADD CONSTRAINT `servicios_dominios_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_dominios_ibfk_2` FOREIGN KEY (`tld_id`) REFERENCES `tld_dominios` (`id`),
  ADD CONSTRAINT `servicios_dominios_ibfk_3` FOREIGN KEY (`registrante_id`) REFERENCES `registrantes` (`id`);

--
-- Filtros para la tabla `servicios_hosting`
--
ALTER TABLE `servicios_hosting`
  ADD CONSTRAINT `servicios_hosting_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_hosting_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `planes_hosting` (`id`);

--
-- Filtros para la tabla `servicios_otro`
--
ALTER TABLE `servicios_otro`
  ADD CONSTRAINT `servicios_otro_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_otro_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_otro` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
