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
    `role` varchar(50) DEFAULT 'user',
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
    `active` tinyint(1) DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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

INSERT INTO `blog_p5`.`categories` (`id`, `name`) VALUES
(1, 'HTML/CSS'),
(2, 'PHP/MySql'),
(3, 'JavaScript'),
(4, 'Symfony'),
(5, 'Laravel'),
(6, 'Projet perso'),
(7, 'Projet formation'),
(8, 'Projet pro'),
(11, 'CMS'),
(12, 'Wordpress'),
(15, 'Divers');
-- --------------------------------------------------------
--
-- Déchargement des données de la table `category_post`
--

INSERT INTO `category_post` (`id_post`, `id_category`) VALUES
(1, 1),(1, 2),(2, 1),(3, 2),(4, 3),(4, 5),(4, 6),(7, 2),(7, 7),(8, 7),(8, 8),(8, 9),(9, 6),(9, 8),(9, 9),(10, 1),(10, 3),(10, 6),(11, 2),(11, 3),(11, 7),(12, 6),(12, 8),(12, 9),(13, 2),(13, 3),(13, 6),(13, 7),(13, 10),(14, 2),(14, 3),(14, 4),(14, 6),(15, 5),(16, 4),(16, 6);

-- --------------------------------------------------------


";
