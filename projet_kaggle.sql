-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 01 Décembre 2015 à 11:29
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `projet_kaggle`
--

-- --------------------------------------------------------

--
-- Structure de la table `candidature`
--

CREATE TABLE IF NOT EXISTS `candidature` (
  `id_candidature` int(11) NOT NULL AUTO_INCREMENT,
  `motivations` varchar(255) NOT NULL,
  `candidature_acceptée` varchar(6) DEFAULT NULL,
  `id_inscrit_candidature` int(11) NOT NULL,
  `id_equipe_candidature` int(11) NOT NULL,
  PRIMARY KEY (`id_candidature`),
  KEY `id_inscrit_candidature` (`id_inscrit_candidature`),
  KEY `id_equipe_candidature` (`id_equipe_candidature`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `equipe`
--

CREATE TABLE IF NOT EXISTS `equipe` (
  `id_equipe` int(11) NOT NULL AUTO_INCREMENT,
  `nom_equipe` varchar(100) NOT NULL,
  `nombre_equipier` int(10) NOT NULL,
  PRIMARY KEY (`id_equipe`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `equipe`
--

INSERT INTO `equipe` (`id_equipe`, `nom_equipe`, `nombre_equipier`) VALUES
(1, 'bulls', 0),
(2, 'bills', 0);

-- --------------------------------------------------------

--
-- Structure de la table `inscrit`
--

CREATE TABLE IF NOT EXISTS `inscrit` (
  `id_inscrit` int(11) NOT NULL AUTO_INCREMENT,
  `mdp_inscrit` varchar(100) NOT NULL,
  `nom_inscrit` varchar(100) NOT NULL,
  `telephone_inscrit` varchar(10) NOT NULL,
  `statut_inscrit` varchar(30) NOT NULL,
  `id_equipe_inscrit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_inscrit`),
  KEY `id_equipe_inscrit` (`id_equipe_inscrit`),
  KEY `id_equipe_inscrit_2` (`id_equipe_inscrit`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

--
-- Contenu de la table `inscrit`
--

INSERT INTO `inscrit` (`id_inscrit`, `mdp_inscrit`, `nom_inscrit`, `telephone_inscrit`, `statut_inscrit`, `id_equipe_inscrit`) VALUES
(33, 'b3be8415a5758616aace50af173194365c9bda7c', 'vaboufferbonsang', '0611463985', 'INSCRIT', NULL),
(34, 'a8008a0779a8d5abc1c33bb136889c1e09792070', 'benDover', '0611463985', 'INSCRIT', NULL),
(35, 'a8008a0779a8d5abc1c33bb136889c1e09792070', 'er', '0611463985', 'INSCRIT', NULL),
(36, 'e38f37c91310d9d88d25347bd6738fe686dd5d32', 'benLyes', '0611463985', 'INSCRIT', NULL),
(37, 'd285ec97b614756b6c02f0c6ad028b581f672508', 'ty', '0611463985', 'INSCRIT', NULL),
(38, '9ae2562abb9fdbddb7970ccb0c3b92c4851c5fa9', 'gh', '0147852369', 'INSCRIT', NULL),
(39, 'e058b868de88e12448f296dc613354dd60fb2e2c', 'lyes', '0611463985', 'INSCRIT', NULL),
(40, 'a8008a0779a8d5abc1c33bb136889c1e09792070', 'a', '0611463985', 'EQUIPIER', NULL),
(41, 'a8008a0779a8d5abc1c33bb136889c1e09792070', 'b', '0611463985', 'CHEF_EQUIPE', NULL),
(42, '67b8f3fd64db59c22b239b0876e5e9a621c9d8e1', 'z', '0611463985', 'INSCRIT', NULL),
(43, '655bd2cae78bd001555e34061dc9c493b6717d32', 'lies', '0123456789', 'INSCRIT', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reunion`
--

CREATE TABLE IF NOT EXISTS `reunion` (
  `id_reunion` int(11) NOT NULL AUTO_INCREMENT,
  `motif_reunion` varchar(255) NOT NULL,
  `date_reunion` date NOT NULL,
  `heure_reunion` int(2) NOT NULL,
  `minute_reunion` int(2) NOT NULL,
  `id_reunion_equipe` int(11) NOT NULL,
  PRIMARY KEY (`id_reunion`),
  KEY `id_reunion_equipe` (`id_reunion_equipe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `fk_candidature_inscrit` FOREIGN KEY (`id_inscrit_candidature`) REFERENCES `inscrit` (`id_inscrit`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_equipe_candidature` FOREIGN KEY (`id_equipe_candidature`) REFERENCES `equipe` (`id_equipe`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `inscrit`
--
ALTER TABLE `inscrit`
  ADD CONSTRAINT `fk_equipe_inscrit` FOREIGN KEY (`id_equipe_inscrit`) REFERENCES `equipe` (`id_equipe`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reunion`
--
ALTER TABLE `reunion`
  ADD CONSTRAINT `fk_reunion_equipe` FOREIGN KEY (`id_reunion_equipe`) REFERENCES `equipe` (`id_equipe`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
