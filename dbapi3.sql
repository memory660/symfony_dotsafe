-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Hôte : mariadb
-- Généré le : dim. 20 mars 2022 à 07:06
-- Version du serveur : 10.7.3-MariaDB-1:10.7.3+maria~focal
-- Version de PHP : 8.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dbapi3`
--

-- --------------------------------------------------------

--
-- Structure de la table `flashcard`
--

CREATE TABLE `flashcard` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lesson_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `flashcard`
--

INSERT INTO `flashcard` (`id`, `lesson_id`, `question`, `answer`) VALUES
('18ac5654-a7b3-11ec-8d11-0242ac150002', 'f23e2e91-a7b2-11ec-8d11-0242ac150002', 'qflash1', 'aflash1'),
('2149b5d2-a7b3-11ec-8d11-0242ac150002', 'f23e3316-a7b2-11ec-8d11-0242ac150002', 'qflash2', 'aflash2');

-- --------------------------------------------------------

--
-- Structure de la table `lesson`
--

CREATE TABLE `lesson` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lesson`
--

INSERT INTO `lesson` (`id`, `subject_id`, `name`) VALUES
('03ab1c44-a7b3-11ec-8d11-0242ac150002', 'c932e71d-a7b2-11ec-8d11-0242ac150002', 'lesson13'),
('f23e2e91-a7b2-11ec-8d11-0242ac150002', 'be970dab-a7b2-11ec-8d11-0242ac150002', 'lesson1'),
('f23e3316-a7b2-11ec-8d11-0242ac150002', 'be970dab-a7b2-11ec-8d11-0242ac150002', 'lesson2'),
('ffa419a2-a7b2-11ec-8d11-0242ac150002', 'be9717a0-a7b2-11ec-8d11-0242ac150002', 'lesson12');

-- --------------------------------------------------------

--
-- Structure de la table `subject`
--

CREATE TABLE `subject` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `subject`
--

INSERT INTO `subject` (`id`, `user_id`, `name`) VALUES
('be970dab-a7b2-11ec-8d11-0242ac150002', 1, 'sub1'),
('be9717a0-a7b2-11ec-8d11-0242ac150002', 1, 'sub2'),
('c932e71d-a7b2-11ec-8d11-0242ac150002', 1, 'sub3'),
('d0395699-a7b2-11ec-8d11-0242ac150002', 2, 'sub1 toto2');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'toto@gmail.com', '[\"ROLE_USER\"]', '$2y$13$5MfxjmmZKsj3dfgahzcz6ep4d6.jH7c3bxpfX.7b5JIGQoCL0sOH.'),
(2, 'toto2@gmail.com', '[\"ROLE_USER\"]', '$2y$13$3Ci352Zr6YPtcR.HDUb98eCbYrs.ZF1HMGCZDgdcjTMr1MadX7ZCS');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `flashcard`
--
ALTER TABLE `flashcard`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_70511A09CDF80196` (`lesson_id`);

--
-- Index pour la table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F87474F323EDC87` (`subject_id`);

--
-- Index pour la table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FBCE3E7AA76ED395` (`user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `flashcard`
--
ALTER TABLE `flashcard`
  ADD CONSTRAINT `FK_70511A09CDF80196` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`);

--
-- Contraintes pour la table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `FK_F87474F323EDC87` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);

--
-- Contraintes pour la table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `FK_FBCE3E7AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
