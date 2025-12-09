-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 09, 2025 at 12:54 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tienda_videojuegos`
--

-- --------------------------------------------------------

--
-- Table structure for table `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int NOT NULL,
  `id_usuario` int NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carrito_detalle`
--

CREATE TABLE `carrito_detalle` (
  `id_detalle` int NOT NULL,
  `id_carrito` int NOT NULL,
  `id_videojuego` int NOT NULL,
  `cantidad` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compras`
--

CREATE TABLE `compras` (
  `id_compra` int NOT NULL,
  `id_usuario` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_compra` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compras_detalle`
--

CREATE TABLE `compras_detalle` (
  `id_detalle` int NOT NULL,
  `id_compra` int NOT NULL,
  `id_videojuego` int NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detalles_venta`
--

CREATE TABLE `detalles_venta` (
  `id_detalle` int NOT NULL,
  `id_venta` int DEFAULT NULL,
  `id_videojuego` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `oculto` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detalles_venta`
--

INSERT INTO `detalles_venta` (`id_detalle`, `id_venta`, `id_videojuego`, `cantidad`, `oculto`) VALUES
(3, 7, 1, 1, 0),
(4, 8, 1, 1, 0),
(5, 9, 2, 1, 0),
(6, 10, 2, 1, 0),
(7, 11, 1, 1, 0),
(8, 12, 1, 1, 0),
(9, 13, 3, 1, 0),
(10, 14, 1, 1, 0),
(11, 15, 3, 1, 0),
(12, 16, 2, 1, 0),
(13, 17, 2, 1, 0),
(14, 18, 2, 1, 0),
(15, 19, 2, NULL, 0),
(16, 20, 1, 1, 0),
(17, 21, 2, 1, 0),
(18, 22, 3, 1, 0),
(19, 23, 5, 1, 0),
(20, 24, 1, NULL, 0),
(21, 24, 5, NULL, 0),
(22, 25, 1, 1, 0),
(23, 26, 2, 1, 0),
(24, 27, 3, NULL, 0),
(25, 28, 5, NULL, 0),
(26, 28, 6, NULL, 0),
(27, 28, 7, NULL, 0),
(28, 29, 1, NULL, 0),
(29, 30, 2, NULL, 0),
(30, 30, 3, NULL, 0),
(31, 31, 7, NULL, 1),
(32, 32, 5, NULL, 0),
(33, 32, 7, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `juegos_comprados`
--

CREATE TABLE `juegos_comprados` (
  `id` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_videojuego` int DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `devuelto` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regalos`
--

CREATE TABLE `regalos` (
  `id_regalo` int NOT NULL,
  `id_emisor` int NOT NULL,
  `id_receptor` int NOT NULL,
  `id_videojuego` int NOT NULL,
  `fecha_regalo` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `regalos`
--

INSERT INTO `regalos` (`id_regalo`, `id_emisor`, `id_receptor`, `id_videojuego`, `fecha_regalo`) VALUES
(1, 1, 2, 1, '2025-12-03 19:33:56'),
(3, 1, 2, 1, '2025-12-03 19:36:57'),
(4, 1, 4, 3, '2025-12-04 16:25:35'),
(5, 1, 4, 3, '2025-12-04 16:47:37');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `password`, `fecha_registro`) VALUES
(1, 'marcos duran', 'marquizmejia@gmail.com', '$2y$10$zXS11zJBw82tglMnydEaHeSDGeIyWbGvdYZp8h8PPG1PCwLKTPze.', '2025-11-27 23:51:24'),
(2, 'dieso', 'abner@gmail.com', '$2y$10$Xy0qJX8bR/cV/IlZ4/OCouvYGSCFp4ZpyIzNkDNdbTKKj/qIS52Du', '2025-11-28 01:10:38'),
(3, 'paracetamol', 'cheto@gmail.com', '$2y$10$fFNFzQc2HoYZrSfmqtyQHOp/rUdqVF3vV4Lzgf.gNl2Ei5Nq8VYoK', '2025-12-03 00:13:13'),
(4, 'manuel', 'manuel@gmail.com', '$2y$10$iYT4ABiv/l6UWeHi5CUTxOSir.EKezq8yBfP3viNb7loW2gmwV836', '2025-12-04 01:42:19'),
(5, 'xd', 'xd@gmail.com', '$2y$10$lXzIa0UU9NLGuVGwzEk2keINdueSYsCtXviULFCz3bniseYGbqE8q', '2025-12-04 16:55:59'),
(6, 'panela', 'admin@tienda.com', 'xd', '2025-12-04 22:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_usuario`, `total`, `fecha`) VALUES
(1, 1, '0.00', '2025-11-27 18:38:47'),
(2, 1, '0.00', '2025-11-27 18:41:49'),
(3, 1, '0.00', '2025-11-27 18:42:15'),
(4, 1, '0.00', '2025-11-27 18:43:55'),
(7, 1, '179.00', '2025-11-27 18:49:29'),
(8, 1, '179.00', '2025-11-27 18:49:33'),
(9, 1, '180.00', '2025-11-27 18:49:42'),
(10, 1, '180.00', '2025-11-27 18:49:45'),
(11, 1, '179.00', '2025-11-27 18:50:38'),
(12, 1, '179.00', '2025-11-27 18:53:21'),
(13, 1, '300.00', '2025-11-27 18:56:07'),
(14, 2, '179.00', '2025-11-27 19:12:46'),
(15, 2, '300.00', '2025-11-27 19:23:28'),
(16, 1, '180.00', '2025-12-02 17:46:33'),
(17, 1, '180.00', '2025-12-02 17:46:37'),
(18, 1, '180.00', '2025-12-02 17:46:39'),
(19, 2, '180.00', '2025-12-02 18:05:11'),
(20, 3, '179.00', '2025-12-02 18:16:54'),
(21, 3, '180.00', '2025-12-02 18:17:30'),
(22, 3, '300.00', '2025-12-02 18:20:20'),
(23, 3, '520.00', '2025-12-02 18:27:08'),
(24, 2, '699.00', '2025-12-03 18:47:56'),
(25, 4, '179.00', '2025-12-03 19:44:17'),
(26, 4, '180.00', '2025-12-03 19:44:56'),
(27, 4, '300.00', '2025-12-03 19:46:04'),
(28, 4, '1457609.00', '2025-12-03 19:46:46'),
(29, 5, '179.00', '2025-12-04 10:56:20'),
(30, 5, '480.00', '2025-12-04 10:56:29'),
(31, 2, '300.00', '2025-12-04 16:32:39'),
(32, 1, '820.00', '2025-12-04 16:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `videojuegos`
--

CREATE TABLE `videojuegos` (
  `id_videojuego` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `id_usuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `videojuegos`
--

INSERT INTO `videojuegos` (`id_videojuego`, `titulo`, `descripcion`, `precio`, `genero`, `fecha_lanzamiento`, `imagen`, `id_usuario`) VALUES
(1, 'cuphed', NULL, '179.00', 'Aventura', NULL, 'download.jpg', 0),
(2, 'Hollow Knight', NULL, '180.00', 'Acción', NULL, 'hollow.jpeg', 0),
(3, 'red dead redemption', NULL, '300.00', 'Disparos', NULL, 'read.jpeg', 0),
(5, 'mortal kombat X', NULL, '520.00', 'Acción', NULL, 'mortal.jpeg', 0),
(6, 'xbox', NULL, '1456789.00', 'Acción', NULL, 'OIP.webp', 1),
(7, 'Mega Man X', NULL, '300.00', 'Acci�n', NULL, 'images.jpg', 1),
(8, 'Furry Love ', NULL, '0.00', 'Disparos', NULL, 'Captura de pantalla 2025-12-04 164049.png', 1),
(9, 'Resident Evil 2 Remake', NULL, '699.00', 'Disparos', NULL, 'Captura de pantalla 2025-12-04 164406.png', 1),
(10, 'Resident Evil 3 Remake', NULL, '699.00', 'Disparos', NULL, 'Captura de pantalla 2025-12-04 164555.png', 1),
(11, 'Nier Automata', NULL, '890.00', 'RPG', NULL, 'Captura de pantalla 2025-12-04 164953.png', 1),
(12, 'Nier Replicant', NULL, '900.00', 'Simulaci�n', NULL, 'Captura de pantalla 2025-12-04 165121.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `videojuegos_categorias`
--

CREATE TABLE `videojuegos_categorias` (
  `id_videojuego` int NOT NULL,
  `id_categoria` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_videojuego` (`id_videojuego`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `compras_detalle`
--
ALTER TABLE `compras_detalle`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_videojuego` (`id_videojuego`);

--
-- Indexes for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_videojuego` (`id_videojuego`);

--
-- Indexes for table `juegos_comprados`
--
ALTER TABLE `juegos_comprados`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regalos`
--
ALTER TABLE `regalos`
  ADD PRIMARY KEY (`id_regalo`),
  ADD KEY `fk_regalo_emisor` (`id_emisor`),
  ADD KEY `fk_regalo_receptor` (`id_receptor`),
  ADD KEY `fk_regalo_videojuego` (`id_videojuego`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `videojuegos`
--
ALTER TABLE `videojuegos`
  ADD PRIMARY KEY (`id_videojuego`);

--
-- Indexes for table `videojuegos_categorias`
--
ALTER TABLE `videojuegos_categorias`
  ADD PRIMARY KEY (`id_videojuego`,`id_categoria`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compras_detalle`
--
ALTER TABLE `compras_detalle`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id_detalle` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `juegos_comprados`
--
ALTER TABLE `juegos_comprados`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regalos`
--
ALTER TABLE `regalos`
  MODIFY `id_regalo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `videojuegos`
--
ALTER TABLE `videojuegos`
  MODIFY `id_videojuego` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `carrito_detalle`
--
ALTER TABLE `carrito_detalle`
  ADD CONSTRAINT `carrito_detalle_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrito_detalle_ibfk_2` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuegos` (`id_videojuego`) ON DELETE CASCADE;

--
-- Constraints for table `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Constraints for table `compras_detalle`
--
ALTER TABLE `compras_detalle`
  ADD CONSTRAINT `compras_detalle_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE,
  ADD CONSTRAINT `compras_detalle_ibfk_2` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuegos` (`id_videojuego`) ON DELETE CASCADE;

--
-- Constraints for table `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuegos` (`id_videojuego`);

--
-- Constraints for table `regalos`
--
ALTER TABLE `regalos`
  ADD CONSTRAINT `fk_regalo_emisor` FOREIGN KEY (`id_emisor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_regalo_receptor` FOREIGN KEY (`id_receptor`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_regalo_videojuego` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuegos` (`id_videojuego`) ON DELETE CASCADE;

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `videojuegos_categorias`
--
ALTER TABLE `videojuegos_categorias`
  ADD CONSTRAINT `videojuegos_categorias_ibfk_1` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuegos` (`id_videojuego`) ON DELETE CASCADE,
  ADD CONSTRAINT `videojuegos_categorias_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
