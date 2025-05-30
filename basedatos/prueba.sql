-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-04-2025 a las 21:36:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Base de datos: `prueba`
DROP DATABASE IF EXISTS `prueba`;
CREATE DATABASE `prueba`;
USE `prueba`;

-- Tabla: `citas`
CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  PRIMARY KEY (`id_cita`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_mascota` (`id_mascota`),
  KEY `id_especialidad` (`id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: `datos`
CREATE TABLE `datos` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo_UNIQUE` (`correo`),
  UNIQUE KEY `telefono_UNIQUE` (`telefono`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos de ejemplo para la tabla `datos`
INSERT INTO `datos` (`id_usuario`, `nombre`, `apellido`, `telefono`, `correo`, `contrasena`) VALUES
(12, 'luis', 'perez', '34468776645', 'luisp@gmail.com', '$2y$10$EjemploHashContraseña1'),
(13, 'derly', 'villalobos', '3208796548', 'derlyv@gmail.co', '$2y$10$EjemploHashContraseña2'),
(15, 'Paula', 'Torres', '3123758703', 'mpautorresb.06@gmail.com', '$2y$10$EjemploHashContraseña3'),
(16, 'Erick', 'Romero', '3115420367', 'erickr4@gmail.com', '$2y$10$EjemploHashContraseña4');

-- Tabla: `especialidades`
CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos de ejemplo para `especialidades`
INSERT INTO `especialidades` (`id_especialidad`, `nombre`) VALUES
(1, 'Cardiología'),
(2, 'Nutrición'),
(3, 'Dermatología'),
(4, 'Odontología'),
(5, 'Neurología'),
(6, 'Endocrinología'),
(7, 'Medicina general');

-- Tabla: `mascotas`
CREATE TABLE `mascotas` (
  `id_mascota` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `raza` varchar(50) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_mascota`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Claves foráneas
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `datos` (`id_usuario`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `datos` (`id_usuario`);

COMMIT;
