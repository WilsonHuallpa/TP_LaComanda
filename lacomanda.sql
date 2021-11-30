-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2021 a las 15:21:45
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_mesas`
--

CREATE TABLE `estado_mesas` (
  `id` int(10) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado_mesas`
--

INSERT INTO `estado_mesas` (`id`, `descripcion`) VALUES
(1, 'clientes esperando pedido'),
(2, 'clientes comiendo'),
(3, 'clientes pagando'),
(4, 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedidos`
--

CREATE TABLE `estado_pedidos` (
  `id` int(10) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado_pedidos`
--

INSERT INTO `estado_pedidos` (`id`, `estado`) VALUES
(1, 'pendiente'),
(2, 'en preparacion'),
(3, 'listo para servir');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(10) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `id_estado` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `id_estado`) VALUES
(1, '1zneg', 3),
(2, '4dao1', 1),
(3, 'knr5t', 2),
(5, 'h8cs3', 0),
(6, 'wil213e', 2),
(7, 'ertye', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) NOT NULL,
  `id_mesa` int(10) NOT NULL,
  `id_producto` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `cliente` varchar(50) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `id_estado_pedido` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `id_empleado` int(10) NOT NULL,
  `id_estado_mesa` int(10) NOT NULL,
  `nombre_foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_mesa`, `id_producto`, `cantidad`, `cliente`, `codigo`, `id_estado_pedido`, `fecha`, `id_empleado`, `id_estado_mesa`, `nombre_foto`) VALUES
(1, 1, 1, 3, 'Jorge', '06lak', 1, '2020-07-23', 5, 3, ''),
(2, 2, 15, 1, 'Marcos', 'u36ql', 1, '2020-07-23', 11, 1, ''),
(3, 5, 10, 2, 'Jacinto', 'oyvzf', 1, '2020-07-24', 11, 2, ''),
(7, 5, 4, 33, 'wilson', 'wil003', 1, '2021-05-17', 25, 1, ''),
(15, 7, 9, 1, 'silvina', '4dfew', 1, '2021-11-13', 6, 1, 'fotos/PEDIDOS4dfew-7.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) NOT NULL,
  `id_sector` int(10) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `precio` float NOT NULL,
  `stock` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `id_sector`, `nombre`, `precio`, `stock`) VALUES
(1, 5, 'Miller', 150, 20),
(2, 5, 'Corona', 150, 30),
(3, 5, 'Quilmes', 150, 20),
(4, 5, 'Stella', 150, 10),
(5, 5, 'Imperial', 150, 23),
(6, 5, 'Brahma', 150, 56),
(7, 4, 'Daiquiri', 250, 32),
(8, 4, 'Fernet', 250, 9),
(9, 4, 'Campari', 250, 32),
(10, 4, 'Satanas', 250, 2),
(11, 4, 'Termidor Tinto', 250, 3),
(12, 4, 'Gancia', 250, 20),
(13, 4, 'Espumante', 250, 20),
(14, 3, 'Empanadas', 320, 23),
(15, 3, 'Ravioles', 320, 30),
(16, 4, 'Asado', 250, 20),
(17, 4, 'Ensalada', 250, 20),
(18, 4, 'Carre de Cerdo', 250, 10),
(19, 4, 'Lasagna', 250, 25),
(20, 6, 'Alfajor', 90, 30),
(21, 5, 'ipa', 250, 22),
(23, 5, 'heineken', 200, 24),
(24, 5, 'miller', 230, 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

CREATE TABLE `sectores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id`, `nombre`) VALUES
(3, 'Cocina'),
(4, 'Barra de Tragos y Vinos'),
(5, 'Barra de Cervezas'),
(6, 'Candy Bar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_empleados`
--

CREATE TABLE `tipo_empleados` (
  `id` int(11) NOT NULL,
  `tipo_empleado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_empleados`
--

INSERT INTO `tipo_empleados` (`id`, `tipo_empleado`) VALUES
(1, 'socio'),
(2, 'mozo'),
(3, 'cocinero'),
(4, 'bartender'),
(5, 'cervecero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `clave` text NOT NULL,
  `id_tipo_empleado` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `mail`, `clave`, `id_tipo_empleado`) VALUES
(1, 'wilson', 'wilson@gmail.com', '$2y$10$HhfHdatTENHJ/TkqCOGveurwySaKBYxWsJSJmASiftgiMq0YiFqW6', 1),
(2, 'priscila', 'vida@gmail.com', '$2y$10$HVg5Q46HHJowGHYYpeShz.zgUYEj6KOhzflZPtZSPWdjyAfcjH3gu', 2),
(3, 'pety', 'lagata@gmail.com', '$2y$10$35n75wNEz6mvEiIGj5smO.TEmtjJ1NL5USLylXhQikvpxO5RaNAv2', 3),
(4, 'maxi', 'cheve@gmail.com', '$2y$10$29BSQL8LgBSN84ihvEijDuDerwMQd2Veet9r0dwnXJMcYK9zxTiTS', 4),
(5, 'tavo', 'birra@gmail.com', '$2y$10$xDlK6WdECLjE1XLRY3/WluR7icz.JP4Bpi.CpZFRXjaoAKSWlFHFW', 5),
(9, 'niko', 'kiko@gmail.com', '$2y$10$elbp6AQgvlfBeAzfiU0cBuhkdlyRFa7qlVUlreDKS5fchHtOklk6i', 5),
(10, 'miguel', 'ale@gmail.com', '$2y$10$iOO0F78ohwHKa2U8nDQPRuewoXLnvpFoqnhO1GV62kvsvwsVLKibK', 4),
(11, 'niko', 'kiko@gmail.com', '$2y$10$X6xwQS4.A92GdahfpbMyP.4PIO0IkjmTCMcKQ/.PplQ76cL69JP.W', 5),
(12, 'miguel', 'ale@gmail.com', '$2y$10$WOu18sg.WJyu9GhGaJgn6enYKCZK3hjy38EqYob81Ue4DFgZzae52', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estado_mesas`
--
ALTER TABLE `estado_mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_empleados`
--
ALTER TABLE `tipo_empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_rol_id` (`id_tipo_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estado_mesas`
--
ALTER TABLE `estado_mesas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `tipo_empleados`
--
ALTER TABLE `tipo_empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuario_rol_id` FOREIGN KEY (`id_tipo_empleado`) REFERENCES `tipo_empleados` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
