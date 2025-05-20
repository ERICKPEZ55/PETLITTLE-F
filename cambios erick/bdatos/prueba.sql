-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-04-2025 a las 21:36:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`id_usuario`, `nombre`, `telefono`, `correo`, `contraseña`) VALUES
(12, 'luis perez', '34468776645', 'luisp@gmail.com', '567890'),
(13, 'derly villalobos', '3208796548', 'derlyv@gmail.co', '98765432'),
(15, 'Paula Torres', '3123758703', 'mpautorresb.06@gmail.com', '12345678'),
(16, 'Erick Romero', '34468776645', 'erickr4@gmail.com', '098554332');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`) VALUES
(1, 'Cardialolgia'),
(2, 'Nutricion'),
(3, 'Dermatologia'),
(4, 'Odontologia'),
(5, 'Neurologia'),
(6, 'Endocrinologia'),
(7, 'Medicina general');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id_mascota` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `raza` varchar(50) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_mascota` (`id_mascota`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `datos`
--
ALTER TABLE `datos`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id_mascota`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `datos`
--
ALTER TABLE `datos`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `datos` (`id_usuario`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `datos` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
