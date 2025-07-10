-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-07-2025 a las 06:41:42
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Listar_Usuarios_Y_Mascotas` ()   BEGIN
    SELECT 
        u.id_usuario,
        CONCAT(u.nombre, ' ', u.apellido) AS nombre_completo,
        u.correo,
        u.telefono,
        COUNT(m.id_mascota) AS cantidad_mascotas,
        GROUP_CONCAT(m.nombre SEPARATOR ', ') AS nombres_mascotas
    FROM usuarios u
    LEFT JOIN mascotas m ON u.id_usuario = m.id_usuario
    GROUP BY u.id_usuario, u.nombre, u.apellido, u.correo, u.telefono
    ORDER BY u.id_usuario;
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
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admins`
--

INSERT INTO `admins` (`id_admin`, `nombre`, `apellido`, `correo`, `telefono`, `contrasena`, `rol`) VALUES
(1, 'Admin', 'Veterinario', 'petlittle.soporte@gmail.com', '3454765879', '12345', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('Asistió','No asistió','Cancelada') DEFAULT 'No asistió'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_usuario`, `id_mascota`, `id_especialidad`, `fecha_hora`, `estado`) VALUES
(5, 20, 12, 5, '2025-06-27 09:30:00', 'Cancelada'),
(6, 20, 12, 3, '2025-06-10 08:00:00', 'No asistió'),
(7, 25, 5, 2, '2025-07-16 08:00:00', 'No asistió'),
(46, 25, 5, 1, '2025-07-10 08:00:00', 'Asistió'),
(47, 25, 5, 5, '2025-07-24 10:00:00', 'No asistió');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `id_especialidad` int(11) DEFAULT NULL,
  `usuario` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `contrasena` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `nombre`, `apellido`, `rol`, `id_especialidad`, `usuario`, `telefono`, `contrasena`) VALUES
(9, 'Luis', 'Castro', 'Empleado', 3, 'luisc@gmail.com', '3456765256', 'rjazJY');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `duracion` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`, `imagen`, `duracion`) VALUES
(1, 'Medicina General', 'icomedicinageneral.png', 20),
(2, 'Nutrición', 'icoiconutricion.png', 20),
(3, 'Dermatología', 'icoicodermatologia.png', 30),
(4, 'Odontología', 'icoicoodontologia.png', 50),
(5, 'Neurología', 'icoiconeurologia.png', 30),
(6, 'Endocrinología', 'icoicoendocri.png', 30),
(9, 'Oncología', 'icoicocardiologia.png', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id_mascota` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `raza` varchar(50) DEFAULT NULL,
  `sexo` varchar(50) DEFAULT NULL,
  `especie` varchar(50) DEFAULT NULL,
  `edad` varchar(50) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `microchip` varchar(50) DEFAULT NULL,
  `collar` varchar(100) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL,
  `veterinario_habitual` varchar(100) DEFAULT NULL,
  `clinica_emergencia` varchar(100) DEFAULT NULL,
  `seguro_mascotas` varchar(100) DEFAULT NULL,
  `vacunas` text DEFAULT NULL,
  `esterilizacion` varchar(50) DEFAULT NULL,
  `condiciones_medicas` text DEFAULT NULL,
  `alergias` text DEFAULT NULL,
  `medicamentos_preventivos` text DEFAULT NULL,
  `cirugias` text DEFAULT NULL,
  `hospitalizaciones` text DEFAULT NULL,
  `comportamiento` text DEFAULT NULL,
  `ejercicio` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `alimento` varchar(100) DEFAULT NULL,
  `horario_alimentacion` varchar(100) DEFAULT NULL,
  `premios` varchar(100) DEFAULT NULL,
  `suplementos` varchar(100) DEFAULT NULL,
  `agua` varchar(100) DEFAULT NULL,
  `alergias_alimentarias` text DEFAULT NULL,
  `aseo` text DEFAULT NULL,
  `restricciones_ejercicio` text DEFAULT NULL,
  `temperamento` varchar(100) DEFAULT NULL,
  `miedos` text DEFAULT NULL,
  `ubicacion_microchip` varchar(100) DEFAULT NULL,
  `grupo_sanguineo` varchar(50) DEFAULT NULL,
  `reacciones_anestesia` text DEFAULT NULL,
  `deseos_final_vida` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id_mascota`, `nombre`, `raza`, `sexo`, `especie`, `edad`, `color`, `microchip`, `collar`, `id_usuario`, `imagen`, `veterinario_habitual`, `clinica_emergencia`, `seguro_mascotas`, `vacunas`, `esterilizacion`, `condiciones_medicas`, `alergias`, `medicamentos_preventivos`, `cirugias`, `hospitalizaciones`, `comportamiento`, `ejercicio`, `alimento`, `horario_alimentacion`, `premios`, `suplementos`, `agua`, `alergias_alimentarias`, `aseo`, `restricciones_ejercicio`, `temperamento`, `miedos`, `ubicacion_microchip`, `grupo_sanguineo`, `reacciones_anestesia`, `deseos_final_vida`) VALUES
(5, 'Rocky', 'Golden', 'Macho', 'd', 'd', 'u', 'u', 'u', 25, 'mascota_6852ffdc65cd1.jfif', 'u', 'ujytui', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', NULL, NULL, NULL, 'u', NULL, NULL, NULL, 'u', NULL, 'u', NULL, NULL, 'u', NULL, 'u', 'u'),
(12, 'Lucas', 'Pitbull', 'Macho', '', '7', 'x', 'xx', 'x', 20, 'mascota_685b78d51e203.jfif', 'x', 'x', 'xx', 'x', 'x', 'x', 'x', 'x', 'x', 'x', NULL, NULL, NULL, 'x', NULL, NULL, NULL, '', NULL, 'x', NULL, NULL, 'x', NULL, 'x', 'x');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes_laboratorio`
--

CREATE TABLE `ordenes_laboratorio` (
  `id_orden` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `tipo_muestra` varchar(100) DEFAULT NULL,
  `pruebas` text DEFAULT NULL,
  `laboratorio_destino` varchar(100) DEFAULT NULL,
  `urgencia` enum('Normal','Urgente') DEFAULT 'Normal',
  `notas` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ordenes_laboratorio`
--

INSERT INTO `ordenes_laboratorio` (`id_orden`, `id_mascota`, `tipo_muestra`, `pruebas`, `laboratorio_destino`, `urgencia`, `notas`, `fecha_creacion`) VALUES
(2, 5, 'orina', 'Muestra', NULL, 'Normal', 'Ninguna', '2025-06-26 17:04:41'),
(3, 12, 'sangre', 'Prueba', 'labB', 'Urgente', 'Ninguna', '2025-06-26 17:05:10'),
(4, 12, 'orina', 'jbhjvjbjbjkb', 'labA', 'Urgente', 'bhjvjhvjvj', '2025-06-26 17:06:29'),
(5, 5, 'orina', 'Muestra', 'labB', 'Normal', 'Ninguna', '2025-07-09 21:48:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_medicos`
--

CREATE TABLE `reportes_medicos` (
  `id_reporte` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `fecha_reporte` date NOT NULL,
  `sintomas` text DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamiento` text DEFAULT NULL,
  `medicamentos_recetados` text DEFAULT NULL,
  `recomendaciones` text DEFAULT NULL,
  `archivo_adjunto_ruta` varchar(255) DEFAULT NULL COMMENT 'Ruta al archivo PDF adjunto, si aplica',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reportes_medicos`
--

INSERT INTO `reportes_medicos` (`id_reporte`, `id_mascota`, `fecha_reporte`, `sintomas`, `diagnostico`, `tratamiento`, `medicamentos_recetados`, `recomendaciones`, `archivo_adjunto_ruta`, `fecha_registro`) VALUES
(1, 5, '2025-06-20', 'x', 'x', 'x', 'x', 'x', NULL, '2025-06-20 07:05:40'),
(12, 12, '2025-06-19', 'x', 'x', 'x', 'xxx', 'x', NULL, '2025-06-25 19:11:51'),
(13, 5, '2025-06-12', 'u', 'u', 'u', 'u', 'u', NULL, '2025-06-25 19:26:47');

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
  `direccion` varchar(255) DEFAULT NULL,
  `contacto_emergencia_nombre` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'cliente',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `telefono`, `correo`, `direccion`, `contacto_emergencia_nombre`, `contrasena`, `rol`, `fecha_registro`, `fecha_actualizacion`) VALUES
(20, 'Erick', 'Romero', '3123758705', 'santiromerito30@gmail.com', NULL, NULL, '$2y$10$nIhLd96vycj5CWxwbLNC.O92M8fzzgcziCeMFa4WIvuJGxNQ7PXhO', 'cliente', '2025-06-10 15:34:03', '2025-06-18 17:16:37'),
(25, 'Paula', 'Torres', '3123758703', 'mpautorresb.06@gmail.com', NULL, NULL, '$2y$10$/XNEhjEIJxHifXS4xDy9n.L4iOMROnuePc9ZlKeoKZ68QFgGoy.UC', 'cliente', '2025-06-10 15:34:03', '2025-06-10 15:34:03'),
(31, 'Derly', 'Villalobos', '3456765432', 'derlyvillalobos0702z@gmail.com', NULL, NULL, '$2y$10$gtqX5ntmoydLTD3XwXxJZ.5vfBndmmi7gJx6WgS5LvUTgVVl9SriO', 'cliente', '2025-06-25 22:36:15', '2025-06-25 22:36:15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `correo` (`correo`);

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
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `fk_empleados_especialidad` (`id_especialidad`);

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
-- Indices de la tabla `ordenes_laboratorio`
--
ALTER TABLE `ordenes_laboratorio`
  ADD PRIMARY KEY (`id_orden`),
  ADD KEY `id_mascota` (`id_mascota`);

--
-- Indices de la tabla `reportes_medicos`
--
ALTER TABLE `reportes_medicos`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `fk_reportes_medicos_mascotas` (`id_mascota`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ordenes_laboratorio`
--
ALTER TABLE `ordenes_laboratorio`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `reportes_medicos`
--
ALTER TABLE `reportes_medicos`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_empleados_especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `ordenes_laboratorio`
--
ALTER TABLE `ordenes_laboratorio`
  ADD CONSTRAINT `ordenes_laboratorio_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`);

--
-- Filtros para la tabla `reportes_medicos`
--
ALTER TABLE `reportes_medicos`
  ADD CONSTRAINT `fk_reportes_medicos_mascotas` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
