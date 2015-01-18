-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 12 Janvier 2015 à 22:25
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `vgbt`
--

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_albums`
--

CREATE TABLE IF NOT EXISTS `vgbt_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_composers`
--

CREATE TABLE IF NOT EXISTS `vgbt_composers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_extracts`
--

CREATE TABLE IF NOT EXISTS `vgbt_extracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size` int(11) NOT NULL,
  `md5` varchar(32) COLLATE utf8_bin NOT NULL,
  `bitrate` int(11) NOT NULL,
  `sample_rate` int(11) NOT NULL,
  `encoding` varchar(128) COLLATE utf8_bin NOT NULL,
  `play_time` varchar(16) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_extract_album_links`
--

CREATE TABLE IF NOT EXISTS `vgbt_extract_album_links` (
  `id_extract` int(11) NOT NULL,
  `id_album` int(11) NOT NULL,
  `track_number` int(11) NOT NULL,
  PRIMARY KEY (`id_extract`,`id_album`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_extract_composer_links`
--

CREATE TABLE IF NOT EXISTS `vgbt_extract_composer_links` (
  `id_extract` int(11) NOT NULL,
  `id_composer` int(11) NOT NULL,
  PRIMARY KEY (`id_extract`,`id_composer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_extract_game_links`
--

CREATE TABLE IF NOT EXISTS `vgbt_extract_game_links` (
  `id_extract` int(11) NOT NULL,
  `id_game` int(11) NOT NULL,
  PRIMARY KEY (`id_extract`,`id_game`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_extract_name_links`
--

CREATE TABLE IF NOT EXISTS `vgbt_extract_name_links` (
  `id_extract` int(11) NOT NULL,
  `id_name` int(11) NOT NULL,
  PRIMARY KEY (`id_extract`,`id_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_games`
--

CREATE TABLE IF NOT EXISTS `vgbt_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `spin_off` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_games_alternatives`
--

CREATE TABLE IF NOT EXISTS `vgbt_games_alternatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_game_game_serie_links`
--

CREATE TABLE IF NOT EXISTS `vgbt_game_game_serie_links` (
  `id_game` int(11) NOT NULL,
  `id_game_serie` int(11) NOT NULL,
  PRIMARY KEY (`id_game`,`id_game_serie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_game_series`
--

CREATE TABLE IF NOT EXISTS `vgbt_game_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_names`
--

CREATE TABLE IF NOT EXISTS `vgbt_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_names_alternatives`
--

CREATE TABLE IF NOT EXISTS `vgbt_names_alternatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
