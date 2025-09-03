-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 31 août 2025 à 08:37
-- Version du serveur : 10.11.11-MariaDB-0+deb12u1
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `media_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `author` varchar(100) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `pages_nb` int(11) NOT NULL,
  `summary` text NOT NULL,
  `publication_year` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `stock` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `genre_id`, `pages_nb`, `summary`, `publication_year`, `image`, `stock`, `deleted_at`) VALUES
(2, 'bilbo', 'tolkien', '1234567891234', 1, 255, 'livre de fantasy', 1985, 'default.jpg', 1, NULL),
(4, 'livre1', 'author1', '1234568521035', 1, 255, 'resumer du livre', 1992, 'default.jpg', 1, NULL),
(55, 'livre1', 'author1', '1234568521735', 1, 255, 'resumer du livre', 1992, 'default.jpg', 1, NULL),
(56, 'livre2', 'author2', '1235214562989', 1, 300, 'resume du livre 2', 2005, 'default.jpg', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(3, 'fantasy'),
(1, 'heroic-fantasy'),
(2, 'plateforme');

-- --------------------------------------------------------

--
-- Structure de la table `loan`
--

CREATE TABLE `loan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `media_type` enum('books','movies','video_games') NOT NULL,
  `media_id` int(11) NOT NULL,
  `loan_date` date NOT NULL,
  `expected_return_date` date NOT NULL,
  `actual_return_date` date DEFAULT NULL,
  `loan_status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `genre_id` int(11) NOT NULL,
  `director` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `synopsis` text NOT NULL,
  `classification` enum('tous publics','12','16','18') NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `stock` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `movies`
--

INSERT INTO `movies` (`id`, `title`, `genre_id`, `director`, `year`, `duration`, `synopsis`, `classification`, `image`, `stock`, `deleted_at`) VALUES
(1, 'Inception', 3, 'Christopher Nolan', 2010, 148, 'A mind-bending thriller about dreams within dreams.', '16', 'inception.jpg', 50, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('standard','admin') DEFAULT 'standard',
  `creation_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `password`, `role`, `creation_date`) VALUES
(1, 'julien', 'juju', 'julien@mail.com', '$2y$10$nsBVsFqqkF8pWoqZmZMkhe6rIAeSQvLK1tcNuhKNG4w2w4oGFMLFe', 'admin', '2025-08-27 16:16:13'),
(2, 'roge', 'santos', 'roge@mail.com', '$2y$10$Q8bXZPdPUywMNyQb7Dfnc.BzmjXSPE8KOR9O76icN491ilEtJlomm', 'standard', '2025-08-27 16:17:23'),
(3, 'alain', 'rob', 'rob@mail.com', '$2y$10$EA66gq51.ehNrMv2BmaVjuUUeD0fFFjSgRVxd.dXy5qa8BlicIK4K', 'standard', '2025-08-27 16:43:29'),
(4, 'karim', 'sebih', 'karim@mail.com', '$2y$10$0HGp292DlEUQLZLNyMDCr.G0p2Qa1q9fbl3dTPjJuL6C8.AxgOO1e', 'standard', '2025-08-28 11:44:38'),
(5, 'test', 'test', 'test@mail.com', '$2y$10$l1liVQY1bZ3spHNpHQR90.oFLtOxFhk0yvk.J/jzN64Pmx9UmG9zm', 'standard', '2025-08-28 13:30:30');

-- --------------------------------------------------------

--
-- Structure de la table `video_games`
--

CREATE TABLE `video_games` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `platform` enum('PC','Playstation','Xbox','Nintendo','Mobile') NOT NULL,
  `genre_id` int(11) NOT NULL,
  `minimum_age` enum('3','7','12','16','18') NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `stock` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `video_games`
--

INSERT INTO `video_games` (`id`, `title`, `publisher`, `platform`, `genre_id`, `minimum_age`, `description`, `image`, `stock`, `deleted_at`) VALUES
(3, 'mario bros', 'nintendo', 'Nintendo', 2, '12', 'jeu de plateforme', 'default.jpg', 100, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Index pour la table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `video_games`
--
ALTER TABLE `video_games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `loan`
--
ALTER TABLE `loan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `video_games`
--
ALTER TABLE `video_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

--
-- Contraintes pour la table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

--
-- Contraintes pour la table `video_games`
--
ALTER TABLE `video_games`
  ADD CONSTRAINT `video_games_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
