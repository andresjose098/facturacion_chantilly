-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-12-2024 a las 16:03:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adiciones`
--

CREATE TABLE `adiciones` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre_adicion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `adiciones`
--

INSERT INTO `adiciones` (`id`, `producto_id`, `nombre_adicion`) VALUES
(4, 32, 'Lechera'),
(5, 33, 'Chocolate'),
(6, 33, 'Chocolate'),
(7, 34, 'Arequipe'),
(8, 35, 'Chispas de chocolate'),
(9, 35, 'Fresa'),
(10, 35, 'Fresa'),
(11, 35, 'Fresa'),
(12, 36, 'Crema Chantilly'),
(13, 37, 'Chispas de chocolate'),
(14, 37, 'Fresa'),
(15, 38, 'Arequipe'),
(16, 39, 'Chispas de chocolate'),
(17, 39, 'Crema Chantilly'),
(18, 39, 'Lechera'),
(19, 39, 'Lechera'),
(20, 40, 'Arequipe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `metodopago` varchar(100) NOT NULL,
  `domicilio` varchar(100) NOT NULL,
  `valortotal` varchar(100) NOT NULL,
  `cambio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `direccion`, `telefono`, `producto`, `metodopago`, `domicilio`, `valortotal`, `cambio`) VALUES
(37, 'jorge alban', 'carrera 18 sur #8-16', '3185212067', '', 'Nequi', '$10.000', '150000', ''),
(38, 'jorge alban', 'carrera 18 sur #8-16', '3185212067', '', 'DaviPlata', '$4.000', '150000', ''),
(39, 'jorge alban', 'carrera 18 sur #8-16', '3185212067', '', 'Nequi', '$4.000', '30000', ''),
(40, 'jorge alban', 'carrera 18 sur #8-16', '3185212067', '', 'Nequi', '$4.000', '30000', ''),
(41, 'jorge alban', 'carrera 18 sur #8-16', '3185212067', '', 'Nequi', '$4.000', '150000', '200000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `usuario_id`, `nombre_producto`) VALUES
(32, 37, 'Merengon Guanabana'),
(33, 37, 'Merengon Guanabana'),
(34, 38, 'Merengon mixto'),
(35, 38, 'Merengon fresa y durazno'),
(36, 39, 'Merengon Guanabana'),
(37, 39, 'Merengon Guanabana'),
(38, 40, 'Merengon fresa'),
(39, 40, 'Merengon Oreo'),
(40, 41, 'Merengon mixto');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adiciones`
--
ALTER TABLE `adiciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adiciones`
--
ALTER TABLE `adiciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adiciones`
--
ALTER TABLE `adiciones`
  ADD CONSTRAINT `adiciones_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
