-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 01 déc. 2024 à 18:22
-- Version du serveur : 8.0.30
-- Version de PHP : 8.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `saruscan`
--

-- --------------------------------------------------------

--
-- Structure de la table `chapter`
--

CREATE TABLE `chapter` (
  `id_chapter` int NOT NULL,
  `id_comics` int NOT NULL,
  `title_chapter` varchar(255) NOT NULL,
  `view_count` int DEFAULT '0',
  `comics_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `page_number` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `chapter`
--

INSERT INTO `chapter` (`id_chapter`, `id_comics`, `title_chapter`, `view_count`, `comics_path`, `created_at`, `page_number`) VALUES
(1, 1, 'Chapter01', 0, 'comics/Dr.Stone/Chapter01', '2024-11-25 19:28:21', 173),
(2, 1, 'Chapter02', 0, 'comics/Dr.Stone/Chapter02', '2024-11-25 19:28:21', 170),
(3, 2, 'Chapter01', 0, 'comics/OnePiece/Chapter01', '2024-11-25 21:14:04', 5),
(5, 2, 'Chapter02', 0, 'bh', '2024-11-26 00:00:00', 2),
(6, 2, 'Chapter02', 0, 'bh', '2024-11-26 00:00:00', 2),
(7, 2, 'Chapter02', 0, 'bh', '2024-11-26 00:00:00', 2),
(8, 2, 'Chapter02', 0, 'bh', '2024-11-26 00:00:00', 2),
(9, 2, 'Chapter02', 0, 'bh', '2024-11-26 00:00:00', 2),
(10, 2, 'Chapter02', 0, 'bh', '2024-11-26 00:00:00', 2);

-- --------------------------------------------------------

--
-- Structure de la table `comics`
--

CREATE TABLE `comics` (
  `id_comics` int NOT NULL,
  `title_comics` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comics`
--

INSERT INTO `comics` (`id_comics`, `title_comics`, `image_path`, `author`, `created_at`, `category`) VALUES
(1, 'Dr. Stone', '/SaruScan/src/imgs/DrStone.jpg', 'Riichiro Inagaki', '2024-11-25 19:26:42', 'Science Fiction'),
(2, 'One Piece', '/SaruScan/src/imgs/op.jpg', 'Eiichiro Oda', '2024-11-25 21:12:49', 'Adventure'),
(4, 'Attack on Titan', '/SaruScan/src/imgs/snk.jpg', 'Hajime Isayama', '2024-11-26 13:43:40', 'Dark Fantasy'),
(5, 'My Hero Academia', '/SaruScan/src/imgs/mha.jpg', 'Kohei Horikoshi', '2024-11-26 13:43:40', 'Superhero'),
(6, 'Demon Slayer', '/SaruScan/src/imgs/kny.jpeg', 'Koyoharu Gotouge', '2024-11-26 13:43:40', 'Action'),
(7, 'Fullmetal Alchemist', '/SaruScan/src/imgs/fma.jpg', 'Hiromu Arakawa', '2024-11-26 13:43:40', 'Adventure'),
(8, 'Dragon Ball', '/SaruScan/src/imgs/db.jpg', 'Akira Toriyama', '2024-11-26 13:43:40', 'Martial Arts'),
(9, 'Death Note', '/SaruScan/src/imgs/deathnote.jpg', 'Tsugumi Ohba', '2024-11-26 13:43:40', 'Psychological'),
(10, 'Hunter x Hunter', '/SaruScan/src/imgs/hxh.jpg', 'Yoshihiro Togashi', '2024-11-26 13:43:40', 'Adventure'),
(11, 'Tokyo Ghoul', '/SaruScan/src/imgs/tokyoghoul.jpg', 'Sui Ishida', '2024-11-26 13:43:40', 'Horror');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comics_id` int NOT NULL,
  `chapter_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorite`
--

CREATE TABLE `favorite` (
  `id_user` int NOT NULL,
  `id_comics` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rate`
--

CREATE TABLE `rate` (
  `id_user` int NOT NULL,
  `id_comics` int NOT NULL,
  `rate` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `is_admin`) VALUES
(1, 'oui', 'oui@gmail.com', '$2y$10$AzwmpQcqrrGU2hhpyKcpOOW/jJHEyG9QBXuT7EZ6FPfqw0sip8oF2', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`id_chapter`),
  ADD KEY `id_comics` (`id_comics`);

--
-- Index pour la table `comics`
--
ALTER TABLE `comics`
  ADD PRIMARY KEY (`id_comics`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comics_id` (`comics_id`),
  ADD KEY `chapter_id` (`chapter_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Index pour la table `favorite`
--
ALTER TABLE `favorite`
  ADD PRIMARY KEY (`id_user`,`id_comics`),
  ADD KEY `id_comics` (`id_comics`);

--
-- Index pour la table `rate`
--
ALTER TABLE `rate`
  ADD PRIMARY KEY (`id_user`,`id_comics`),
  ADD KEY `id_comics` (`id_comics`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chapter`
--
ALTER TABLE `chapter`
  MODIFY `id_chapter` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `comics`
--
ALTER TABLE `comics`
  MODIFY `id_comics` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chapter`
--
ALTER TABLE `chapter`
  ADD CONSTRAINT `chapter_ibfk_1` FOREIGN KEY (`id_comics`) REFERENCES `comics` (`id_comics`) ON DELETE CASCADE;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`comics_id`) REFERENCES `comics` (`id_comics`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`chapter_id`) REFERENCES `chapter` (`id_chapter`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_4` FOREIGN KEY (`parent_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`id_comics`) REFERENCES `comics` (`id_comics`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `rate_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `rate_ibfk_2` FOREIGN KEY (`id_comics`) REFERENCES `comics` (`id_comics`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
