-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour mytutorspace2
CREATE DATABASE IF NOT EXISTS `mytutorspace2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `mytutorspace2`;

-- Listage de la structure de table mytutorspace2. exercice
CREATE TABLE IF NOT EXISTS `exercice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lecon_id` int DEFAULT NULL,
  `type_id` int DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E418C74DEC1308A5` (`lecon_id`),
  KEY `IDX_E418C74DC54C8C93` (`type_id`),
  CONSTRAINT `FK_E418C74DC54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`),
  CONSTRAINT `FK_E418C74DEC1308A5` FOREIGN KEY (`lecon_id`) REFERENCES `lecon` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.exercice : ~6 rows (environ)
DELETE FROM `exercice`;
INSERT INTO `exercice` (`id`, `lecon_id`, `type_id`, `titre`, `description`, `date_creation`) VALUES
	(1, 12, 1, 'équation polynôme', 'Cet exercice vise à développer des compétences en résolution d\'équations polynomiales de degré 3, en utilisant des techniques telles que le théorème de la racine évidente, la division polynomiale, et la résolution d\'équations quadratiques.', '2024-10-16 15:55:24'),
	(2, 12, 1, 'Résolution d\'équations différentielle', 'Résoudre l’équation différentielle suivante :', '2024-10-16 16:10:27'),
	(3, 12, 1, 'Mouvement rectiligne uniformèment accélérer', 'Un objet est lancé verticalement avec une vitesse initiale de 15 m/s. En négligeant la résistance de l’air, calculez la hauteur maximale atteinte par l’objet et le temps qu’il mettra à retomber au sol. Accélération de la gravité : \\( g = 9,8 \\, m/s^2 \\).', '2024-10-16 16:11:34'),
	(4, 12, 2, 'Analyse de texte littéraire', 'Analysez le passage suivant extrait de *Madame Bovary* de Gustave Flaubert. Discutez de l\'attitude de l\'auteur face à la société de son époque et des techniques littéraires employées pour critiquer les mœurs bourgeoises.', '2024-10-16 16:12:19'),
	(5, 12, 2, 'Calcul de concentration molaire', 'Un chimiste dissout 58,5 g de NaCl dans 2 litres d’eau. Calculez la concentration molaire de la solution ainsi obtenue. Donnez votre réponse en mol/L. Masse molaire de NaCl : 58,5 g/mol.', '2024-10-16 16:13:00'),
	(6, 12, 3, 'Tri par insertion', 'Implémentez l’algorithme de tri par insertion en langage Python. Donnez ensuite un exemple d’utilisation de cet algorithme pour trier la liste suivante : [29, 10, 14, 37, 14].', '2024-10-16 16:19:38');

-- Listage de la structure de table mytutorspace2. lecon
CREATE TABLE IF NOT EXISTS `lecon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matiere_id` int NOT NULL,
  `user_id` int NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_94E6242EF46CD258` (`matiere_id`),
  KEY `IDX_94E6242EA76ED395` (`user_id`),
  CONSTRAINT `FK_94E6242EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_94E6242EF46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.lecon : ~12 rows (environ)
DELETE FROM `lecon`;
INSERT INTO `lecon` (`id`, `matiere_id`, `user_id`, `titre`, `description`, `contenu`) VALUES
	(1, 2, 21, 'Emission et perception d\'un son', '', ''),
	(2, 1, 23, 'Les suites', '', ''),
	(3, 1, 12, 'Propriétés des angles et triangles', '', ''),
	(4, 1, 21, 'Calcul des probabilités', '', ''),
	(5, 2, 23, 'Réactions acido-basiques', '', ''),
	(6, 5, 21, 'Analyse du genre narratif', 'az', ''),
	(7, 5, 23, 'L’argumentation et les figures de style', 'az', ''),
	(8, 6, 21, 'La révolution française et ses conséquences', 'az', ''),
	(9, 6, 23, 'La Seconde Guerre mondiale', 'az', ''),
	(11, 4, 23, 'Officia sunt delenit', 'az', ''),
	(12, 8, 15, 'Fugit aliquam nostr', 'Dignissimos necessit', ''),
	(13, 2, 17, 'Hic sequi quo dolore', 'Maiores deleniti ull', '');

-- Listage de la structure de table mytutorspace2. lien
CREATE TABLE IF NOT EXISTS `lien` (
  `id` int NOT NULL AUTO_INCREMENT,
  `valeur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.lien : ~3 rows (environ)
DELETE FROM `lien`;
INSERT INTO `lien` (`id`, `valeur`) VALUES
	(1, 'https://www.youtube.com/watch?v=3wuPvNDLNjY'),
	(2, 'https://www.youtube.com/@dave-hollingworth'),
	(3, 'https://symfony.com/doc/current/best_practices.html');

-- Listage de la structure de table mytutorspace2. matiere
CREATE TABLE IF NOT EXISTS `matiere` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.matiere : ~6 rows (environ)
DELETE FROM `matiere`;
INSERT INTO `matiere` (`id`, `nom`, `description`) VALUES
	(1, 'Mathématiques', 'Étude des nombres, des structures et des relations entre les quantités.'),
	(2, 'Physique', 'Exploration des lois régissant la matière, l\'énergie et l\'univers.'),
	(4, 'Informatique', 'Conception et manipulation des algorithmes, logiciels et systèmes.'),
	(5, 'Français', 'Apprentissage de la langue, de la littérature et de la culture française.'),
	(6, 'Histoire', 'Compréhension des événements passés pour mieux appréhender le présent.'),
	(8, 'Philosophie', 'La philosophie est la discipline qui cherche à comprendre les principes fondamentaux de l\'existence, de la connaissance, de la morale et de la réalité à travers la réflexion et le raisonnement. Elle encourage l\'exploration des questions profondes sur la vie, la vérité et la justice, tout en développant la pensée critique.');

-- Listage de la structure de table mytutorspace2. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.messenger_messages : ~0 rows (environ)
DELETE FROM `messenger_messages`;

-- Listage de la structure de table mytutorspace2. niveau
CREATE TABLE IF NOT EXISTS `niveau` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.niveau : ~7 rows (environ)
DELETE FROM `niveau`;
INSERT INTO `niveau` (`id`, `titre`) VALUES
	(1, 'Première'),
	(2, 'Seconde'),
	(3, 'Terminale'),
	(4, 'Sixième'),
	(7, 'Quatrième'),
	(8, 'Troisième'),
	(9, 'Cinquième');

-- Listage de la structure de table mytutorspace2. niveau_lecon
CREATE TABLE IF NOT EXISTS `niveau_lecon` (
  `niveau_id` int NOT NULL,
  `lecon_id` int NOT NULL,
  PRIMARY KEY (`niveau_id`,`lecon_id`),
  KEY `IDX_7181D648B3E9C81` (`niveau_id`),
  KEY `IDX_7181D648EC1308A5` (`lecon_id`),
  CONSTRAINT `FK_7181D648B3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_7181D648EC1308A5` FOREIGN KEY (`lecon_id`) REFERENCES `lecon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.niveau_lecon : ~10 rows (environ)
DELETE FROM `niveau_lecon`;
INSERT INTO `niveau_lecon` (`niveau_id`, `lecon_id`) VALUES
	(1, 1),
	(2, 2),
	(2, 12),
	(2, 13),
	(3, 12),
	(3, 13),
	(4, 6),
	(7, 7),
	(8, 12),
	(9, 12);

-- Listage de la structure de table mytutorspace2. ressource
CREATE TABLE IF NOT EXISTS `ressource` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exercice_id` int DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_939F454489D40298` (`exercice_id`),
  CONSTRAINT `FK_939F454489D40298` FOREIGN KEY (`exercice_id`) REFERENCES `exercice` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.ressource : ~2 rows (environ)
DELETE FROM `ressource`;
INSERT INTO `ressource` (`id`, `exercice_id`, `titre`, `contenu`) VALUES
	(1, 4, 'Introduction à la logique philosophique  ', 'Ce document offre une introduction aux principes de base de la logique philosophique, un domaine essentiel pour structurer les arguments rationnels.'),
	(2, 6, 'Introduction à la programmation orientée objet (POO)', 'Un tutoriel sur les concepts clés de la programmation orientée objet, y compris les classes, les objets, l\'héritage et l\'encapsulation, avec des exemples en PHP et en Java. Des exercices pratiques permettent de renforcer l\'apprentissage.');

-- Listage de la structure de table mytutorspace2. ressource_lien
CREATE TABLE IF NOT EXISTS `ressource_lien` (
  `ressource_id` int NOT NULL,
  `lien_id` int NOT NULL,
  PRIMARY KEY (`ressource_id`,`lien_id`),
  KEY `IDX_558DB43CFC6CD52A` (`ressource_id`),
  KEY `IDX_558DB43CEDAAC352` (`lien_id`),
  CONSTRAINT `FK_558DB43CEDAAC352` FOREIGN KEY (`lien_id`) REFERENCES `lien` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_558DB43CFC6CD52A` FOREIGN KEY (`ressource_id`) REFERENCES `ressource` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.ressource_lien : ~3 rows (environ)
DELETE FROM `ressource_lien`;
INSERT INTO `ressource_lien` (`ressource_id`, `lien_id`) VALUES
	(1, 1),
	(2, 2),
	(2, 3);

-- Listage de la structure de table mytutorspace2. type
CREATE TABLE IF NOT EXISTS `type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.type : ~3 rows (environ)
DELETE FROM `type`;
INSERT INTO `type` (`id`, `nom`) VALUES
	(1, 'Problème'),
	(2, 'Question ouverte'),
	(3, 'QCM');

-- Listage de la structure de table mytutorspace2. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `about_me` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.user : ~14 rows (environ)
DELETE FROM `user`;
INSERT INTO `user` (`id`, `email`, `nom`, `prenom`, `is_verified`, `roles`, `password`, `about_me`) VALUES
	(1, 'boreki@mailinator.com', 'Elit minim eu nisi', 'Occaecat aut ipsa u', 0, '["ROLE_TUTEUR"]', '$2y$13$oN31yrC5RxdHdaOSy9anN.a1DMqaAPKpq2dnWs1WmFec.AzkdL2EC', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(2, 'bubodosu@mailinator.com', 'eleve2', 'Dolore enim quisquam', 0, '["ROLE_ELEVE"]', '$2y$13$dI3E7lJx2ThmDLR4OOYqgug2bEM2wA8a6xbdEKaKqHu6rd0ms.OZG', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(3, 'nujuc@mailinator.com', 'Libero esse in ut o', 'Quo quis mollitia ei', 0, '["ROLE_PARENT"]', '$2y$13$mDdX/KtiOhPzXTdTvyGXd.MnNxIX4ApSSSMvHrcSWAP1vnDAVjSKG', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(4, 'asmi@mailinator.com', 'Velit sit et delen', 'Minima ut aliquid ad', 0, '["ROLE_USER"]', '$2y$13$FDqceW80TQQDBWVWQTGiZe9BR79maMxeOaoFzO84BM7cfYccyN4HG', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(5, 'vulyl@mailinator.com', 'Amet in voluptatibu', 'Autem consequatur N', 0, '["ROLE_USER"]', '$2y$13$Ii/YLXynBLA4iuDh0Q5QKuiO3h4Wak8Pb5T3XEWUcLU0B0p6baS6O', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(6, 'casorucaja@mailinator.com', 'Voluptas dolore elig', 'Ut doloremque volupt', 0, '["ROLE_USER"]', '$2y$13$GTlZYDnRlI5Y9nGNf6dTxeDyLmGqU3Mf8beRjV2Ua1LgSg4pgA5hi', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(7, 'mynip@mailinator.com', 'Qui non ipsam odit n', 'Eiusmod reprehenderi', 0, '["ROLE_ADMIN"]', '$2y$13$yxhaVICHFRtBZD5RXIPY1.b6WFNCvOm5n14y2IE9goL3Te0l0dG86', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(8, 'puxy@mailinator.com', 'eleve1', 'Porro aut fugiat se', 0, '["ROLE_ELEVE"]', '$2y$13$RZOgfjxThSpFiCYPcH6GkOwOMz6knYt5rfil8o5V8m5ohuzelY9xe', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(9, 'zacorer@mailinator.com', 'tuteur1', 'Asperiores quo ut do', 0, '["ROLE_TUTEUR"]', '$2y$13$sqmRr9NZmr9t1sqj9YKSg.Q0jFZlomhZMkYKa1POgrilwmtYQAlNG', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(10, 'movujawasa@mailinator.com', 'Quia illo error sequ', 'Eaque qui dolor offi', 0, '["ROLE_PARENT"]', '$2y$13$EzlX22zrJE5gc4tlRzBzU.JXkSEGvvMkctG0qf1ijtiRDoK90qUE.', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(11, 'rykybu@mailinator.com', 'tuteur2', 'Architecto quia quis', 0, '["ROLE_TUTEUR"]', '$2y$13$Ub7F.E7xJkTI4pspjIx36uajm3JlDqlW/zwu61oRB9w1d1ZL4vxY.', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(12, 'yami@mailinator.com', 'Vel excepteur velit', 'Optio eos perspici', 0, '["ROLE_ELEVE"]', '$2y$13$oSRquj/ATE5o4gRI5wYPWuM7Wz/0.FJrBZOj0Yj80Vrtez/aV0r1u', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(13, 'pefev@mailinator.com', 'Voluptatum similique', 'Non a ipsum dolorib', 0, '["ROLE_PARENT"]', '$2y$13$P.ihuRkfZOYqaqIo9olmO.jljjWfGcqZgMWtbfWvjChIXyz2RUOsO', 'un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.'),
	(14, 'dezaben@mailinator.com', 'Non voluptates atque', 'malak', 0, '["ROLE_ELEVE"]', '$2y$13$jcjp99kNgYjSlEWUTASwCO17ifE8ld1PzVG9ga8QttzqjRsozS0qy', 'Bonjour ! Je suis Malak, un tuteur passionné par l\'éducation et l\'accompagnement des élèves dans leur parcours scolaire. Avec une expérience de plus de 5 ans dans l\'enseignement, j\'ai eu l\'occasion d\'aider des élèves de tous niveaux à développer leurs compétences en mathématiques et en sciences.\n\nJe crois fermement que chaque élève est unique et mérite une attention personnalisée. Mon approche consiste à créer un environnement d\'apprentissage positif et stimulant, où chacun peut s\'épanouir. J\'aime utiliser des méthodes interactives, comme des jeux éducatifs et des projets pratiques, tout en intégrant des outils numériques pour rendre l\'apprentissage plus dynamique.\n\nEn dehors de l\'enseignement, je suis passionnée par la musique et le jardinage, ce qui me permet de rester équilibrée et inspirée. J\'ai hâte de travailler avec vous et de vous aider à atteindre vos objectifs académiques !');

-- Listage de la structure de table mytutorspace2. user_lecon
CREATE TABLE IF NOT EXISTS `user_lecon` (
  `user_id` int NOT NULL,
  `lecon_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`lecon_id`),
  KEY `IDX_7624DF76A76ED395` (`user_id`),
  KEY `IDX_7624DF76EC1308A5` (`lecon_id`),
  CONSTRAINT `FK_7624DF76A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_7624DF76EC1308A5` FOREIGN KEY (`lecon_id`) REFERENCES `lecon` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.user_lecon : ~3 rows (environ)
DELETE FROM `user_lecon`;
INSERT INTO `user_lecon` (`user_id`, `lecon_id`) VALUES
	(1, 7),
	(21, 12),
	(23, 12);

-- Listage de la structure de table mytutorspace2. user_matiere
CREATE TABLE IF NOT EXISTS `user_matiere` (
  `user_id` int NOT NULL,
  `matiere_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`matiere_id`),
  KEY `IDX_C8194940A76ED395` (`user_id`),
  KEY `IDX_C8194940F46CD258` (`matiere_id`),
  CONSTRAINT `FK_C8194940A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C8194940F46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table mytutorspace2.user_matiere : ~8 rows (environ)
DELETE FROM `user_matiere`;
INSERT INTO `user_matiere` (`user_id`, `matiere_id`) VALUES
	(1, 1),
	(1, 2),
	(1, 4),
	(1, 5),
	(1, 6),
	(1, 8),
	(21, 5),
	(23, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
