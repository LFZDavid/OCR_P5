<?php

$db_creation_query = "
DROP DATABASE IF EXISTS `blog_p5`;
--
-- Base de données :  `blog_p5`
--
CREATE DATABASE `blog_p5` CHARACTER SET 'utf8';
-- --------------------------------------------------------
--
-- Structure de la table `posts`
--
CREATE TABLE `blog_p5`.`posts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    `title` varchar(50) NOT NULL,
    `chapo` varchar(150),
    `content` text NOT NULL,
    `id_author` int(11) NOT NULL,
    `active` tinyint(4) DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
-- --------------------------------------------------------
--
-- Structure de la table `users`
--
CREATE TABLE `blog_p5`.`users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    `name` varchar(50) NOT NULL,
    `email` varchar(50) NOT NULL,
    `pwd` varchar(255) NOT NULL,
    `role` varchar(50) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
-- --------------------------------------------------------
--
-- Structure de la table `comments`
--
CREATE TABLE `blog_p5`.`comments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    `content` text NOT NULL,
    `id_author` int(11) NOT NULL,
    `id_post` int(11) NOT NULL,
    `active` tinyint(4) DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
--
-- --------------------------------------------------------
--
-- Structure de la table `categories`
--
CREATE TABLE `blog_p5`.`categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    `name` varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
-- --------------------------------------------------------
--
-- Structure de la table `category_post`
--
CREATE TABLE `blog_p5`.`category_post` (
    `id_post` int(11) NOT NULL,
    `id_category` int(11) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
    
-- --------------------------------------------------------
--
-- Insert categories
--
INSERT INTO `blog_p5`.`categories`(
    `name`
) VALUES('News'),('Tips'),('Languages'),('Framework'),('Divers');

-- --------------------------------------------------------
";

// -- --------------------------------------------------------
// --
// -- Contraintes pour la table `comments`
// --
// ALTER TABLE `blog_p5`.`comments`
// ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `users` (`id`);
// ALTER TABLE `blog_p5`.`comments`
// ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`);
