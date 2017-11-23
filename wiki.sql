-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 23, 2017 at 02:21 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wiki`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
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

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `cat_id` int(10) UNSIGNED NOT NULL,
  `cat_sNom` varchar(255) NOT NULL,
  `cat_sResume` text,
  `cat_bActif` tinyint(4) DEFAULT '0',
  `cat_sSlug` varchar(45) NOT NULL,
  `cat_sCodeHexa` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `join_article_categorie`
--

CREATE TABLE `join_article_categorie` (
  `art_id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `usr_id` int(10) UNSIGNED NOT NULL,
  `usr_sNom` varchar(255) DEFAULT NULL,
  `usr_sPrenom` varchar(255) DEFAULT NULL,
  `usr_sMail` varchar(255) NOT NULL,
  `usr_sPwd` varchar(255) NOT NULL,
  `usr_sToken` varchar(255) DEFAULT NULL,
  `usr_bActif` tinyint(4) DEFAULT '0',
  `usr_bAdmin` tinyint(4) DEFAULT '0',
  `usr_sAvatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`art_id`,`usr_id`),
  ADD UNIQUE KEY `idx_art_sSlug` (`art_sSlug`),
  ADD KEY `fk_article_user1_idx` (`usr_id`),
  ADD KEY `idx_art_sTitre` (`art_sTitre`),
  ADD KEY `idx_art_dDateCreation` (`art_dDateCreation`);

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `idx_cat_sSlug` (`cat_sSlug`),
  ADD UNIQUE KEY `idx_cat_sNom` (`cat_sNom`);

--
-- Indexes for table `join_article_categorie`
--
ALTER TABLE `join_article_categorie`
  ADD PRIMARY KEY (`art_id`,`cat_id`),
  ADD KEY `fk_article_has_categorie_categorie1_idx` (`cat_id`),
  ADD KEY `fk_article_has_categorie_article_idx` (`art_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`),
  ADD UNIQUE KEY `idx_usr_sMail` (`usr_sMail`),
  ADD UNIQUE KEY `idx_usr_sToken` (`usr_sToken`),
  ADD KEY `idx_usr_sNom_sPrenom` (`usr_sNom`,`usr_sPrenom`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `art_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_user1` FOREIGN KEY (`usr_id`) REFERENCES `user` (`usr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `join_article_categorie`
--
ALTER TABLE `join_article_categorie`
  ADD CONSTRAINT `fk_article_has_categorie_article` FOREIGN KEY (`art_id`) REFERENCES `article` (`art_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_article_has_categorie_categorie1` FOREIGN KEY (`cat_id`) REFERENCES `categorie` (`cat_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
