-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mer. 29 nov. 2017 à 15:04
-- Version du serveur :  10.1.28-MariaDB
-- Version de PHP :  7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `wiki`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `art_id` int(10) UNSIGNED NOT NULL,
  `usr_id` int(10) UNSIGNED NOT NULL,
  `art_sTitre` varchar(255) NOT NULL,
  `art_sContenu` text,
  `art_dDateCreation` datetime NOT NULL,
  `art_dDateLastModif` datetime DEFAULT NULL,
  `art_bActif` tinyint(4) NOT NULL DEFAULT '0',
  `art_sSlug` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`art_id`, `usr_id`, `art_sTitre`, `art_sContenu`, `art_dDateCreation`, `art_dDateLastModif`, `art_bActif`, `art_sSlug`) VALUES
(4, 1, 'My article', 'Lorem ipsumSed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur', '2017-11-06 00:00:00', NULL, 0, 'my-article'),
(6, 4, 'My article 2', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur', '2017-11-17 00:00:00', NULL, 0, 'my-article-2'),
(52, 1, 'aaaa', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur', '2017-11-27 12:08:59', NULL, 0, 'aaaa'),
(53, 1, 'Un nouvel article', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur', '2017-11-27 12:10:53', NULL, 0, 'un-nouvel-article'),
(54, 13, 'Mon belle article', 'sd,dkjhsv sdhdhsfoii fiosdfiosdfhdfhsdifksdqhqfsdufsdfsqdf', '2017-11-27 16:11:17', NULL, 0, 'mon-belle-article'),
(55, 13, 'Mon titre', 'Lorem ipsum blakjbsdjbjdbvd', '2017-11-27 16:20:33', NULL, 0, 'mon-titre'),
(57, 1, 'Mon nouvel article', 'Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab Lorem Ipsum blab ', '2017-11-29 12:45:09', NULL, 0, 'mon-nouvel-article');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `cat_id` int(10) UNSIGNED NOT NULL,
  `cat_sNom` varchar(255) NOT NULL,
  `cat_sResume` text,
  `cat_bActif` tinyint(4) DEFAULT '0',
  `cat_sSlug` varchar(45) NOT NULL,
  `cat_sCodeHexa` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`cat_id`, `cat_sNom`, `cat_sResume`, `cat_bActif`, `cat_sSlug`, `cat_sCodeHexa`) VALUES
(3, 'World', 'Relatif au monde', 1, 'world', '#0011A5'),
(4, 'Voyage', 'Relatif au voyage', 1, 'voyage', '#A50000'),
(5, 'Finance', 'Relatif à la finance', 1, 'finance', '#16A500');

-- --------------------------------------------------------

--
-- Structure de la table `join_article_categorie`
--

CREATE TABLE `join_article_categorie` (
  `art_id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `join_article_categorie`
--

INSERT INTO `join_article_categorie` (`art_id`, `cat_id`) VALUES
(4, 3),
(4, 4),
(6, 3),
(6, 5),
(52, 3),
(52, 4),
(52, 5),
(53, 3),
(53, 4),
(54, 3),
(54, 4),
(54, 5),
(55, 3),
(55, 4),
(55, 5),
(57, 3),
(57, 4);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `usr_id` int(10) UNSIGNED NOT NULL,
  `usr_sNom` varchar(255) DEFAULT NULL,
  `usr_sPrenom` varchar(255) DEFAULT NULL,
  `usr_sMail` varchar(255) NOT NULL,
  `usr_sPwd` varchar(255) NOT NULL,
  `usr_sToken` varchar(255) NOT NULL,
  `usr_bActif` tinyint(4) DEFAULT '0',
  `usr_bAdmin` tinyint(4) DEFAULT '0',
  `usr_sAvatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`usr_id`, `usr_sNom`, `usr_sPrenom`, `usr_sMail`, `usr_sPwd`, `usr_sToken`, `usr_bActif`, `usr_bAdmin`, `usr_sAvatar`) VALUES
(1, 'marguet', 'Alex', 'okok@yah.fr', '$2y$10$xsaunkCcxSAfluSJeKdy9eZqRg9MG4OZWO1pKl.3yRDNF.tf270Aq', 'dsfsdfdsf', 0, 0, NULL),
(4, 'marguet', 'Alex', 'dslksssssd@yah.fr', '$2y$10$xsaunkCcxSAfluSJeKdy9eZqRg9MG4OZWO1pKl.3yRDNF.tf270Aq', 'dsfsdfdsfsssss', 0, 0, NULL),
(13, 'Marguet', 'Alex', 'ok@yah.fr', '$2y$10$xsaunkCcxSAfluSJeKdy9eZqRg9MG4OZWO1pKl.3yRDNF.tf270Aq', 'db36f65997db4580a3984e69b60f872378ab0532493e1255eb59de83d2d2bf6d5a1c270fae4d0', 0, 1, NULL),
(14, 'moh', 'sto', 'oki@yah.fr', '$2y$10$u8JmIr8vRI76Qy0uH//1Z.JrYVgb5NYEU4mNBVFktZiOPDpg8rPv.', 'fab5ea373aa91a3731cede30bf878e128df8bed728a0f162feedb4dea1a53c985a1ebd534476f', 0, 0, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`art_id`,`usr_id`),
  ADD UNIQUE KEY `idx_art_sSlug` (`art_sSlug`),
  ADD KEY `fk_article_user1_idx` (`usr_id`),
  ADD KEY `idx_art_sTitre` (`art_sTitre`),
  ADD KEY `idx_art_dDateCreation` (`art_dDateCreation`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `idx_cat_sSlug` (`cat_sSlug`),
  ADD UNIQUE KEY `idx_cat_sNom` (`cat_sNom`);

--
-- Index pour la table `join_article_categorie`
--
ALTER TABLE `join_article_categorie`
  ADD PRIMARY KEY (`art_id`,`cat_id`),
  ADD KEY `fk_article_has_categorie_categorie1_idx` (`cat_id`),
  ADD KEY `fk_article_has_categorie_article_idx` (`art_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`),
  ADD UNIQUE KEY `idx_usr_sMail` (`usr_sMail`),
  ADD UNIQUE KEY `idx_usr_sToken` (`usr_sToken`),
  ADD KEY `idx_usr_sNom_sPrenom` (`usr_sNom`,`usr_sPrenom`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `art_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_user1` FOREIGN KEY (`usr_id`) REFERENCES `user` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `join_article_categorie`
--
ALTER TABLE `join_article_categorie`
  ADD CONSTRAINT `fk_article_has_categorie_article` FOREIGN KEY (`art_id`) REFERENCES `article` (`art_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_article_has_categorie_categorie1` FOREIGN KEY (`cat_id`) REFERENCES `categorie` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
