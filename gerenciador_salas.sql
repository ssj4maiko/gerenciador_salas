-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2018 at 03:59 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gerenciador_salas`
--
DROP DATABASE IF EXISTS `gerenciador_salas`;
CREATE DATABASE IF NOT EXISTS `gerenciador_salas` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gerenciador_salas`;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2018_05_18_012139_create_table_usuarios', 1),
(2, '2018_05_18_013041_create_table_salas', 1),
(3, '2018_05_18_013058_create_table_reservas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_sala` int(10) UNSIGNED NOT NULL,
  `dt_start` datetime NOT NULL,
  `dt_end` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_usuario`, `id_sala`, `dt_start`, `dt_end`, `created_at`, `updated_at`) VALUES
(2, 1, 2, '2018-05-19 22:00:00', '2018-05-19 23:00:00', '2018-05-20 01:01:14', '2018-05-20 01:01:14'),
(3, 1, 2, '2018-05-19 23:00:00', '2018-05-20 00:00:00', '2018-05-20 02:20:17', '2018-05-20 02:20:17'),
(4, 1, 3, '2018-05-19 10:00:00', '2018-05-19 11:00:00', '2018-05-20 02:20:55', '2018-05-20 02:20:55'),
(5, 1, 3, '2018-05-19 00:00:00', '2018-05-19 01:00:00', '2018-05-20 03:09:38', '2018-05-20 03:09:38'),
(9, 2, 2, '2018-05-19 00:00:00', '2018-05-19 01:00:00', '2018-05-20 03:36:06', '2018-05-20 03:36:06'),
(10, 2, 3, '2018-05-19 03:00:00', '2018-05-19 04:00:00', '2018-05-20 03:38:58', '2018-05-20 03:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `salas`
--

CREATE TABLE `salas` (
  `id_sala` int(10) UNSIGNED NOT NULL,
  `descricao` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salas`
--

INSERT INTO `salas` (`id_sala`, `descricao`) VALUES
(2, 'Sala 1'),
(3, 'Sala 2');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `realname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `realname`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Usuário 1 A', 'user1', '$2y$10$qaFp29nbMFaISy5YhtMphO1xOP4kPxyIjqY.VUAgaEOdcTx4wpMi6', '$2y$10$2vcB2RmARlhs7ryJwzjdDegzMo9uKaxH0ZxDh.p0dlUjXa5pT7I76', '2018-05-19 02:18:09', '2018-05-20 04:08:50'),
(2, 'User 2', 'user2', '$2y$10$mGHlm.n/B/1y81Q.d5RINOKY/R0symuASOMJ1.RxaakS8Yz6kY7Bm', '$2y$10$ys3JRItaZptvhO1rwN9ai.mLDzdvbrSRBozasFTYOV8ktI3n42V.e', '2018-05-20 03:30:46', '2018-05-20 03:30:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `reservas_id_usuario_foreign` (`id_usuario`),
  ADD KEY `reservas_id_sala_foreign` (`id_sala`);

--
-- Indexes for table `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuarios_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_id_sala_foreign` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`),
  ADD CONSTRAINT `reservas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
