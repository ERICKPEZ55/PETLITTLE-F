-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-06-2025 a las 00:56:09
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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Eliminar_Usuario` (IN `p_id_usuario` INT)   BEGIN
    DELETE FROM usuarios
    WHERE id_usuario = p_id_usuario;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Insertar_Usuario` (IN `p_nombre` VARCHAR(50), IN `p_apellido` VARCHAR(50), IN `p_telefono` VARCHAR(15), IN `p_correo` VARCHAR(100), IN `p_contrasena` VARCHAR(255), IN `p_rol` VARCHAR(20))   BEGIN
    INSERT INTO usuarios (
        nombre, 
        apellido, 
        telefono, 
        correo, 
        contrasena, 
        rol
    )
    VALUES (
        p_nombre, 
        p_apellido, 
        p_telefono, 
        p_correo, 
        p_contrasena, 
        p_rol
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Select_Empleado_ById` (IN `IdEmpleado` INT)   BEGIN
    SELECT * FROM empleados WHERE id_empleado = IdEmpleado;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Select_Mascotas_ById` (IN `IdMascotas` INT)   BEGIN
    SELECT * FROM mascotas WHERE id_mascota = IdMascotas;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Select_Usuario_ById` (IN `IdUsuario` INT)   BEGIN
    SELECT 
        id_usuario,
        nombre,
        apellido,
        telefono,
        correo,
        rol
    FROM usuarios
    WHERE id_usuario = IdUsuario;
END$$

DELIMITER ;

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
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `contrasena` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre`, `apellido`, `rol`, `usuario`, `telefono`, `contrasena`) VALUES
(1, 'Paula', 'Torres', 'Empleado', 'paula@gmail.com', '3123758703', 'aP0z9J'),
(2, 'Erick', 'Romero', 'Empleado', 'erick@gmail.com', '3456765432', 'dB$HKx'),
(3, 'Juan', 'Garzon', 'Empleado', 'juan@gmail.com', '3456789876', '1yTPKz'),
(4, 'Deiner', 'suarez', 'Empleado', 'deiner@gmail.com', '3233044072', '9bnk2a');

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
(1, 'Cardiología'),
(2, 'Nutrición'),
(3, 'Dermatología'),
(4, 'Odontología'),
(5, 'Neurología'),
(6, 'Endocrinología'),
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
  `id_usuario` int(11) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id_mascota`, `nombre`, `raza`, `genero`, `id_usuario`, `imagen`) VALUES
(5, 'Rocky', 'Golden', 'Macho', 25, 'mascota_6852ffdc65cd1.jfif'),
(6, 'Max', 'Pitbull', 'Macho', 20, 'mascota_68533ca2ec0ef.jfif');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'cliente',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `telefono`, `correo`, `contrasena`, `rol`, `fecha_registro`, `fecha_actualizacion`) VALUES
(20, 'Erick', 'Romero', '3123758705', 'santiromerito30@gmail.com', '$2y$10$nIhLd96vycj5CWxwbLNC.O92M8fzzgcziCeMFa4WIvuJGxNQ7PXhO', 'cliente', '2025-06-10 15:34:03', '2025-06-18 17:16:37'),
(24, 'Derly', 'Villalobos', '3456765432', 'derlyvillalobos0702z@gmail.com', '$2y$10$RBf5dh/yjl3kehHhmCqbSOBPqHh7kSbLRG0rgd48pJdbQR0M9dH26', 'cliente', '2025-06-10 15:34:03', '2025-06-10 15:56:13'),
(25, 'Paula', 'Torres', '3123758703', 'mpautorresb.06@gmail.com', '$2y$10$/XNEhjEIJxHifXS4xDy9n.L4iOMROnuePc9ZlKeoKZ68QFgGoy.UC', 'cliente', '2025-06-10 15:34:03', '2025-06-10 15:34:03'),
(29, 'Lucia', 'Riaño', '4153231454', 'luciar@gmail.com', '$2y$10$OF1YcHgiyE4XWxqEQRUUjeYlK8H9/CUGceJAiDxfoi8872MHdzkda', 'cliente', '2025-06-10 15:54:34', '2025-06-10 15:54:34');

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
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
