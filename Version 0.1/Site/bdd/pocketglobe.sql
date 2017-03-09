-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 01 Mars 2017 à 22:36
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `pocketglobe`
--

-- --------------------------------------------------------

--
-- Structure de la table `fd_text`
--

CREATE TABLE `fd_text` (
  `id_fd_text` bigint(20) UNSIGNED NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fd_title`
--

CREATE TABLE `fd_title` (
  `id_fd_title` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `fiche`
--

CREATE TABLE `fiche` (
  `id_fiche` bigint(20) UNSIGNED NOT NULL,
  `id_folder` bigint(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `visited` tinyint(1) NOT NULL,
  `latitude` decimal(7,5) DEFAULT NULL,
  `longitude` decimal(8,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `field`
--

CREATE TABLE `field` (
  `id_fiche` bigint(20) NOT NULL,
  `rank` int(11) NOT NULL,
  `id_fd_title` bigint(20) DEFAULT NULL,
  `id_fd_text` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `folder`
--

CREATE TABLE `folder` (
  `id_folder` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) NOT NULL,
  `name_folder` varchar(50) NOT NULL,
  `defaut` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nickname` varchar(25) CHARACTER SET utf8 NOT NULL,
  `mail` varchar(100) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `fd_text`
--
ALTER TABLE `fd_text`
  ADD PRIMARY KEY (`id_fd_text`),
  ADD UNIQUE KEY `id_fd_text` (`id_fd_text`);

--
-- Index pour la table `fd_title`
--
ALTER TABLE `fd_title`
  ADD PRIMARY KEY (`id_fd_title`),
  ADD UNIQUE KEY `id_fd_title` (`id_fd_title`);

--
-- Index pour la table `fiche`
--
ALTER TABLE `fiche`
  ADD PRIMARY KEY (`id_fiche`),
  ADD UNIQUE KEY `id_fiche` (`id_fiche`);

--
-- Index pour la table `field`
--
ALTER TABLE `field`
  ADD PRIMARY KEY (`id_fiche`,`rank`);

--
-- Index pour la table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`id_folder`),
  ADD UNIQUE KEY `id_folder` (`id_folder`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `nickname` (`nickname`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `fd_text`
--
ALTER TABLE `fd_text`
  MODIFY `id_fd_text` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `fd_title`
--
ALTER TABLE `fd_title`
  MODIFY `id_fd_title` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `fiche`
--
ALTER TABLE `fiche`
  MODIFY `id_fiche` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT pour la table `folder`
--
ALTER TABLE `folder`
  MODIFY `id_folder` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
