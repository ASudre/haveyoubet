-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2014 at 03:47 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `symfony`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipes`
--

CREATE TABLE IF NOT EXISTS `equipes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pays` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `groupe` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

--
-- Dumping data for table `equipes`
--

INSERT INTO `equipes` (`id`, `nom`, `pays`, `groupe`) VALUES
(1, 'Brésil', '', 'A'),
(2, 'Croatie', '', 'A'),
(3, 'Mexique', '', 'A'),
(4, 'Cameroun', '', 'A'),
(5, 'Espagne', '', 'B'),
(6, 'Pays-Bas', '', 'B'),
(7, 'Chili', '', 'B'),
(8, 'Australie', '', 'B'),
(9, 'Colombie', '', 'C'),
(10, 'Grèce', '', 'C'),
(11, 'Côte d''Ivoire', '', 'C'),
(12, 'Japon', '', 'C'),
(13, 'Uruguay', '', 'D'),
(14, 'Costa Rica', '', 'D'),
(15, 'Angleterre', '', 'D'),
(16, 'Italie', '', 'D'),
(17, 'Suisse', '', 'E'),
(18, 'Ecuateur', '', 'E'),
(19, 'France', '', 'E'),
(20, 'Honduras', '', 'E'),
(21, 'Argentine', '', 'F'),
(22, 'Bosnie-Herzegovine', '', 'F'),
(23, 'Iran', '', 'F'),
(24, 'Nigeria', '', 'F'),
(25, 'Allemagne', '', 'G'),
(26, 'Portugal', '', 'G'),
(27, 'Ghana', '', 'G'),
(28, 'USA', '', 'G'),
(29, 'Belgique', '', 'H'),
(30, 'Algérie', '', 'H'),
(31, 'Russie', '', 'H'),
(32, 'République de Corée', '', 'H'),
(33, 'Nul', 'Nul', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
