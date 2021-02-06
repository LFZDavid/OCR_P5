-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 01 fév. 2021 à 21:00
-- Version du serveur :  5.7.24
-- Version de PHP : 7.4.8
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Base de données : `blog_p5`
--
CREATE DATABASE IF NOT EXISTS `blog_p5` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `blog_p5`;
-- --------------------------------------------------------
--
-- Structure de la table `categories`
--
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
--
-- Déchargement des données de la table `categories`
--
INSERT INTO `categories` (`id`, `name`)
VALUES (1, 'HTML/CSS'),
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
-- Structure de la table `category_post`
--
CREATE TABLE `category_post` (
  `id_post` int(11) NOT NULL,
  `id_category` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
--
-- Déchargement des données de la table `category_post`
--
INSERT INTO `category_post` (`id_post`, `id_category`)
VALUES (1, 1),
  (1, 2),
  (2, 1),
  (3, 2),
  (4, 3),
  (4, 5),
  (4, 6),
  (7, 2),
  (7, 7),
  (8, 7),
  (8, 8),
  (8, 9),
  (9, 6),
  (9, 8),
  (9, 9),
  (10, 1),
  (10, 3),
  (10, 6),
  (11, 2),
  (11, 3),
  (11, 7),
  (12, 6),
  (12, 8),
  (12, 9),
  (13, 2),
  (13, 3),
  (13, 6),
  (13, 7),
  (13, 10),
  (14, 2),
  (14, 3),
  (14, 4),
  (14, 6),
  (15, 5),
  (16, 4),
  (16, 6);
-- --------------------------------------------------------
--
-- Structure de la table `comments`
--
CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `id_author` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
--
-- Déchargement des données de la table `comments`
--
INSERT INTO `comments` (
    `id`,
    `content`,
    `id_author`,
    `id_post`,
    `active`,
    `created_at`
  )
VALUES (
    1,
    'Loin, très loin, au delà des monts Mots, à mille lieues des pays Voyellie et Consonnia, demeurent les Bolos Bolos. ',
    2,
    1,
    1,
    '2001-01-23 23:00:00'
  ),
  (
    2,
    'Un jour pourtant, une petite ligne de Bolo Bolo du nom de Lorem Ipsum décida de s\'aventurer dans la vaste Grammaire. ',
    5,
    2,
    1,
    '2011-12-02 23:00:00'
  ),
  (
    3,
    'Le grand Oxymore voulut l\'en dissuader, le prevenant que là-bas cela fourmillait de vils Virgulos, de sauvages ',
    3,
    3,
    1,
    '2016-05-27 22:00:00'
  ),
  (
    4,
    'Super, vraiment intéressant',
    4,
    4,
    1,
    '2004-07-01 22:00:00'
  ),
  (
    5,
    'Pas même la toute puissante Ponctuation ne régit les Bolos Bolos - une vie on ne peut moins orthodoxographique. \r\n',
    5,
    5,
    1,
    '2012-04-20 22:00:00'
  ),
  (
    6,
    'Pointdexclamators et de sournois Semicolons qui l\'attendraient pour sûr au prochain paragraphe',
    6,
    6,
    1,
    '2012-04-20 22:00:00'
  ),
  (
    7,
    'Super projet, Bravo!',
    2,
    7,
    1,
    '2021-02-01 16:34:56'
  ),
  (
    8,
    'Super, vraiment intéressant',
    3,
    8,
    1,
    '2021-02-01 16:35:12'
  ),
  (
    9,
    'Loin, très loin, au delà des monts Mots, à mille lieues des pays Voyellie et Consonnia, demeurent les Bolos Bolos. ',
    4,
    9,
    1,
    '2021-02-01 16:38:38'
  ),
  (
    10,
    'Ils vivent en retrait, à Bourg-en-Lettres, sur les côtes de la Sémantique, un vaste océan de langues. ',
    5,
    10,
    1,
    '2021-02-01 16:38:50'
  ),
  (
    11,
    'Un petit ruisseau, du nom de Larousse, coule en leur lieu et les approvisionne en règlalades nécessaires en tout genre; ',
    6,
    1,
    1,
    '2021-02-01 16:39:01'
  ),
  (
    12,
    ' un pays paradisiagmatique, dans lequel des pans entiers de phrases prémâchées vous volent litéralement tout cuit dans la bouche. ',
    2,
    2,
    1,
    '2021-02-01 16:39:10'
  ),
  (
    13,
    'Pas même la toute puissante Ponctuation ne régit les Bolos Bolos - une vie on ne peut moins orthodoxographique. \r\n',
    3,
    3,
    1,
    '2021-02-01 16:39:27'
  ),
  (
    14,
    'Un jour pourtant, une petite ligne de Bolo Bolo du nom de Lorem Ipsum décida de s\'aventurer dans la vaste Grammaire. ',
    4,
    4,
    1,
    '2021-02-01 16:39:29'
  ),
  (
    15,
    'Le grand Oxymore',
    5,
    5,
    1,
    '2021-02-01 16:39:38'
  ),
  (
    16,
    'Le grand Oxymore voulut l\'en dissuader, le prevenant que là-bas cela fourmillait de vils Virgulos, de sauvages ',
    6,
    6,
    1,
    '2021-02-01 16:39:47'
  ),
  (
    17,
    'Pointdexclamators et de sournois Semicolons qui l\'attendraient pour sûr au prochain paragraphe',
    2,
    7,
    1,
    '2021-02-01 16:39:56'
  ),
  (
    18,
    'En chemin, il rencontra un Copy. Le Copy prévint le petit Bolo que là d\'où il venait, il avait déjà maintes et maintes fois été ressaisi, et que tout ce qui désormais restait de leurs origines était le mot \"et\", et que le petit Bolo',
    3,
    8,
    1,
    '2021-02-01 16:40:07'
  ),
  (
    19,
    'Le grand Oxymore voulut l\'en dissuader, le prevenant que là-bas cela fourmillait de vils Virgulos, de sauvages Pointdexclamators et de sournois Semicolons ',
    4,
    9,
    1,
    '2021-02-01 16:40:17'
  ),
  (
    20,
    'Loin, très loin, au delà des monts Mots, à mille lieues des pays Voyellie et Consonnia, demeurent les Bolos Bolos. ',
    5,
    10,
    1,
    '2001-01-23 23:00:00'
  ),
  (
    21,
    'Un jour pourtant, une petite ligne de Bolo Bolo du nom de Lorem Ipsum décida de s\'aventurer dans la vaste Grammaire. ',
    2,
    1,
    1,
    '2011-12-02 23:00:00'
  ),
  (
    22,
    'Le grand Oxymore voulut l\'en dissuader, le prevenant que là-bas cela fourmillait de vils Virgulos, de sauvages ',
    3,
    2,
    1,
    '2016-05-27 22:00:00'
  ),
  (
    23,
    'Super, vraiment intéressant',
    4,
    3,
    1,
    '2004-07-01 22:00:00'
  ),
  (
    24,
    'Pas même la toute puissante Ponctuation ne régit les Bolos Bolos - une vie on ne peut moins orthodoxographique. \r\n',
    5,
    4,
    1,
    '2012-04-20 22:00:00'
  ),
  (
    25,
    'Pointdexclamators et de sournois Semicolons qui l\'attendraient pour sûr au prochain paragraphe',
    2,
    5,
    1,
    '2012-04-20 22:00:00'
  ),
  (
    26,
    'Super projet, Bravo!',
    3,
    6,
    1,
    '2021-02-01 16:34:56'
  ),
  (
    27,
    'Super, vraiment intéressant',
    4,
    7,
    1,
    '2021-02-01 16:35:12'
  ),
  (
    28,
    'Loin, très loin, au delà des monts Mots, à mille lieues des pays Voyellie et Consonnia, demeurent les Bolos Bolos. ',
    5,
    8,
    1,
    '2021-02-01 16:38:38'
  ),
  (
    29,
    'Ils vivent en retrait, à Bourg-en-Lettres, sur les côtes de la Sémantique, un vaste océan de langues. ',
    6,
    9,
    1,
    '2021-02-01 16:38:50'
  ),
  (
    30,
    'Un petit ruisseau, du nom de Larousse, coule en leur lieu et les approvisionne en règlalades nécessaires en tout genre; ',
    2,
    10,
    1,
    '2021-02-01 16:39:01'
  ),
  (
    31,
    ' un pays paradisiagmatique, dans lequel des pans entiers de phrases prémâchées vous volent litéralement tout cuit dans la bouche. ',
    3,
    1,
    1,
    '2021-02-01 16:39:10'
  ),
  (
    32,
    'Pas même la toute puissante Ponctuation ne régit les Bolos Bolos - une vie on ne peut moins orthodoxographique. \r\n',
    4,
    2,
    1,
    '2021-02-01 16:39:27'
  ),
  (
    33,
    'Un jour pourtant, une petite ligne de Bolo Bolo du nom de Lorem Ipsum décida de s\'aventurer dans la vaste Grammaire. ',
    5,
    3,
    1,
    '2021-02-01 16:39:29'
  ),
  (
    34,
    'Le grand Oxymore',
    6,
    4,
    1,
    '2021-02-01 16:39:38'
  ),
  (
    35,
    'Le grand Oxymore voulut l\'en dissuader, le prevenant que là-bas cela fourmillait de vils Virgulos, de sauvages ',
    2,
    5,
    1,
    '2021-02-01 16:39:47'
  ),
  (
    36,
    'Pointdexclamators et de sournois Semicolons qui l\'attendraient pour sûr au prochain paragraphe',
    3,
    6,
    1,
    '2021-02-01 16:39:56'
  ),
  (
    37,
    'En chemin, il rencontra un Copy. Le Copy prévint le petit Bolo que là d\'où il venait, il avait déjà maintes et maintes fois été ressaisi, et que tout ce qui désormais restait de leurs origines était le mot \"et\", et que le petit Bolo',
    4,
    7,
    1,
    '2021-02-01 16:40:07'
  ),
  (
    38,
    'Le grand Oxymore voulut l\'en dissuader, le prevenant que là-bas cela fourmillait de vils Virgulos, de sauvages Pointdexclamators et de sournois Semicolons ',
    5,
    8,
    1,
    '2021-02-01 16:40:17'
  );
-- --------------------------------------------------------
--
-- Structure de la table `posts`
--
CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chapo` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `id_author` int(11) NOT NULL,
  `active` tinyint(4) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
--
-- Déchargement des données de la table `posts`
--
INSERT INTO `posts` (
    `id`,
    `title`,
    `chapo`,
    `content`,
    `id_author`,
    `active`,
    `created_at`
  )
VALUES (
    1,
    'La FragZone',
    'Site communautaire associatif dans le domaine du jeux vidéos',
    'Installation et paramétrage :\r\n<ul>\r\n<li>\r\n    Elementor\r\n</li>\r\n<li>\r\n    EventManager\r\n</li>\r\n<li>\r\n    Yoast SEO\r\n</li>\r\n<li>\r\n    Contact Form 7\r\n</li>\r\n<li>\r\n    Akismet Spam Protection\r\n</li>\r\n<li>\r\n    Redirection\r\n</li>\r\n<li>\r\n    W3 Total Cache\r\n</li>\r\n<li>\r\n    File Manager\r\n</li>\r\n<li>\r\n    Captcha\r\n</li>\r\n<li>\r\n    Broken link checker\r\n</li>\r\n<li>\r\n    ShortPixel\r\n</li>\r\n</ul>\r\n\r\n\r\n',
    1,
    1,
    '2017-11-02 12:32:40'
  ),
  (
    2,
    'Burger Code',
    'Site vitrine pour Snack',
    'Création d\'un site dynamique pour sandwicherie.\r\n<ul>\r\n    <li>\r\n        Affichage des produits classés par catégories\r\n    </li>\r\n    <li>\r\n        Création d\'un interface d\'administration sécurisé.\r\n        <ul>\r\n            <li>\r\n                PHP\r\n            </li>\r\n            <li>\r\n                MySql\r\n            </li>\r\n            <li>\r\n                HTML5 / CSS3\r\n            </li>\r\n            <li>\r\n                Bootstrap\r\n            </li>\r\n        </ul>\r\n    </li>\r\n</ul>\r\n\r\n<a href=\"https://github.com/LFZDavid/BurgerCode\" target=\"_blank\"><em>lien vers le projet</em></a>',
    1,
    1,
    '2019-08-12 11:24:19'
  ),
  (
    3,
    'Chalets et caviar',
    'Vitrine agence immobilière Courchevel',
    '<ul>\r\n    <li>\r\n        Adapter un thème Wordpress pour respecter les exigences du client\r\n    </li>\r\n    <li>\r\n        Rédiger une documentation à l\'intention d\'utilisateurs non spécialistes\r\n    </li>\r\n    <li>\r\n        Sélectionner un thème Wordpress adapté aux besoins du client\r\n    </li>\r\n    <li>\r\n        Mise en ligne du site\r\n    </li>\r\n</ul>\r\n\r\n<a href=\"https://www.chalets-et-caviar.lafragzone.com/\" target=\"_blank\"><em>lien vers le projet</em></a>',
    1,
    1,
    '2020-01-06 12:20:06'
  ),
  (
    4,
    'GBAF',
    'Extranet pour un groupe bancaire',
    'Description du projet FRONTEND\r\n<ul>\r\n    <li>\r\n        Structure en HTML5 (Template et vues)\r\n    </li>\r\n    <li>\r\n        Mise en forme et mise en page CSS3\r\n    </li>\r\n    <li>\r\n        Bootstrap\r\n    </li>\r\n    <li>\r\n        FontAwesome (icones)\r\n    </li>\r\n    <li>\r\n        JavaScript (Alertes)\r\n    </li>\r\n    <li>\r\n        Design Responsive Breackpoints : <576px ; <768px ; >=768px\r\n    </li>\r\n</ul>\r\n\r\nBACKEND\r\n<ul>\r\n    <li>\r\n        Architecture MVC\r\n    </li>\r\n    <li>\r\n        Programmation Orienté Objet.\r\n    </li>\r\n    <li>\r\n        Connexion à la base de données via PDO en PHP 7.4.0\r\n    </li>\r\n    <li>\r\n        Modelisation de la base de données via phpMyAdmin\r\n    </li>\r\n    <li>\r\n        Gestion de la base de données en SQL (MariaDB 10.4.10)\r\n    </li>\r\n</ul>\r\n\r\nFONCTIONNALITÉS\r\n<ul>\r\n    <li>\r\n        Connexion via UserName et Password\r\n    </li>\r\n    <li>\r\n        Création et modification de compte\r\n    </li>\r\n    <li>\r\n        Mot de passe oublié (création de nouveau mot de passe avec Username et question secrète)\r\n    </li>\r\n    <li>\r\n        Affichage le la liste des acteurs/partenaires\r\n    </li>\r\n    <li>\r\n        Affichage de la liste des commentaires pour chacun\r\n    </li>\r\n    <li>\r\n        Ajouter un nouveau commentaire (prénom et date remplis automatiquement)\r\n    </li>\r\n    <li>\r\n        Afficher le compteur de like/dislike\r\n    </li>\r\n    <li>\r\n        Ajouter un like/dislike\r\n    </li>\r\n    <li>\r\n        Lien de téléchargement des logos des acteurs/partenaires\r\n    </li>\r\n</ul>\r\n\r\n<a href=\"https://github.com/LFZDavid/gbaf\" target=\"_blank\"><em>lien vers de projet</em></a>\r\n\r\n',
    1,
    1,
    '2020-03-02 07:18:38'
  ),
  (
    5,
    'Refonte : La FragZone',
    'Refonte total du site de La FragZone sous Laravel',
    'Projet abandonné',
    1,
    0,
    '2020-04-12 11:47:08'
  ),
  (
    6,
    'Refonte : La FragZone',
    'Refonte total du site de La FragZone sous Symfony',
    '<strong>\r\n    Projet en cours\r\n</strong> \r\n\r\nRefonte total du site de La FragZone sous Symfony\r\n\r\nPlateforme communautaire proposant à chaque utilisateur de partager la liste de ses jeux et ainsi de pouvoir interagir avec les autres membres de la plateforme \r\nFeatures prévus : \r\n<ul>\r\n    <li>\r\n        Messagerie privé inter-user\r\n    </li>\r\n    <li>\r\n        Espace blog\r\n    </li>\r\n    <li>\r\n        Organisation d\'événements vidéoludique en ligne et IRL\r\n    </li>\r\n</ul>',
    1,
    1,
    '2020-07-01 11:51:17'
  ),
  (
    7,
    'M\'Com',
    'Menu dynamique pour restaurant',
    '<ul>\r\n    <li>\r\n        Affichage des produits classés par catégorie\r\n    </li>\r\n    <li>\r\n        Accès au Back-Office sécurisé par processus de connexion\r\n    </li>\r\n    <li>\r\n        Entité produit : Nom, id, description, prix, catégorie, image, vidéo\r\n    </li>\r\n    <li>\r\n        Entité catégorie: Nom, id, produits\r\n    </li>\r\n    <li>\r\n        Back-Office pour ajouter, modifier, supprimer les articles et catégories\r\n    </li>\r\n</ul>\r\n\r\n\r\n',
    1,
    1,
    '2020-08-30 11:42:48'
  ),
  (
    8,
    'Hydrokit',
    'Site e-commerce de matériel et fourniture hydrographie',
    'Installation et paramétrage :\r\n\r\n<ul>\r\n<li>\r\n    Divi (theme & builder)\r\n</li>\r\n<li>\r\n    Woocommerce\r\n</li>\r\n<li>\r\n    Sales Countdown\r\n</li>\r\n<li>\r\n    Composite products\r\n</li>\r\n<li>\r\n    Smart Quick View\r\n</li>\r\n<li>\r\n    Contact Form 7\r\n</li>\r\n</ul>\r\n',
    1,
    1,
    '2020-09-03 11:22:31'
  ),
  (
    9,
    'Sitez-vous',
    'Vitrine Développeur Web Freelance',
    'Site vitrine :\r\n<ul>\r\n    <li>\r\n        Design Responsive\r\n    </li>\r\n    <li>\r\n        HTML5\r\n    </li>\r\n    <li>\r\n        CSS3\r\n    </li>\r\n    <li>\r\n        JavaScript\r\n    </li>\r\n    <li>\r\n        jQuery\r\n    </li>\r\n    <li>\r\n        Bootstrap\r\n    </li>\r\n    <li>\r\n        PHP (formulaire de contact avec envoi de mail)\r\n    </li>\r\n</ul>\r\n\r\n<a href:=\"http://sitez-vous.com/\" target=\"_blank\"><em>lien vers le projet</em></a>\r\n ',
    1,
    1,
    '2020-09-23 11:23:38'
  ),
  (
    10,
    'Snake game',
    'Version JavaScript du Snake',
    '<p>\r\n    Amélioration d\'un projet de formation JavaScript : <strong>pour utilisation Smartphone</strong> \r\n</p>\r\n<ul>\r\n    <li>\r\n    Jeu du Snake classic\r\n    </li>\r\n    <li>\r\n     Ajout de boutons de commande (rotation)\r\n    </li>\r\n    <li>\r\n    Enregistrement du score\r\n    </li>\r\n    <li>\r\n    Affichage du tableau des scores (SQLite3)\r\n    </li>\r\n</ul>\r\n<br>\r\n<a href=\"http://lfz-snake.sitez-vous.com/\" target=\"_blank\"><em>lien vers le projet :</em></a> ',
    1,
    1,
    '2020-11-10 12:37:34'
  );
-- --------------------------------------------------------
--
-- Structure de la table `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pwd` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
--
-- Déchargement des données de la table `users`
--
INSERT INTO `users` (
    `id`,
    `name`,
    `email`,
    `pwd`,
    `role`,
    `created_at`
  )
VALUES (
    1,
    'admin',
    'admin@admin.com',
    '$2y$10$oiNmLZMeZwUKDAU6H0VFIedFx6uBp3d4kulH75XEAf6Zup9flINXW',
    'admin',
    '2001-01-23 23:00:00'
  ),
  (
    2,
    'Tony',
    'stark@gmail.com',
    '$2y$10$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO',
    'user',
    '2001-01-31 23:00:00'
  ),
  (
    3,
    'Steve',
    'rodgers@gmail.com',
    '$2y$10$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO',
    'user',
    '2001-01-31 23:00:00'
  ),
  (
    4,
    'Bruce',
    'banner@gmail.com',
    '$2y$10$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO',
    'user',
    '2017-09-03 22:00:00'
  ),
  (
    5,
    'Natasha',
    'romanoff@gmail.com',
    '$2y$10$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO',
    'user',
    '2004-07-01 22:00:00'
  ),
  (
    6,
    'Thor',
    'odinsson@gmail.com',
    '$2y$10$OMo8C72.I0iyyHZr.WvXhOgY/55zMqMYVf3ESVuGWFmBmWWPSw8DO',
    'user',
    '2018-05-24 22:00:00'
  ),
  (
    7,
    'David',
    'contact@sitez-vous.com',
    '$2y$10$oiNmLZMeZwUKDAU6H0VFIedFx6uBp3d4kulH75XEAf6Zup9flINXW',
    'admin',
    '2021-02-01 16:33:29'
  );
--
-- Index pour les tables déchargées
--
--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
ADD PRIMARY KEY (`id`);
--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
ADD PRIMARY KEY (`id`);
--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
ADD PRIMARY KEY (`id`);
--
-- Index pour la table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT pour les tables déchargées
--
--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 12;
--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 39;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 17;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 8;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;