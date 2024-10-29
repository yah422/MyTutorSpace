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

-- Listage des données de la table mytutorspace2.exercice : ~6 rows (environ)
INSERT INTO `exercice` (`id`, `lecon_id`, `type_id`, `titre`, `description`, `date_creation`) VALUES
	(1, 12, 1, 'équation polynôme', 'Cet exercice vise à développer des compétences en résolution d\'équations polynomiales de degré 3, en utilisant des techniques telles que le théorème de la racine évidente, la division polynomiale, et la résolution d\'équations quadratiques.', '2024-10-16 15:55:24'),
	(2, 12, 1, 'Résolution d\'équations différentielle', 'Résoudre l’équation différentielle suivante :', '2024-10-16 16:10:27'),
	(3, 12, 1, 'Mouvement rectiligne uniformèment accélérer', 'Un objet est lancé verticalement avec une vitesse initiale de 15 m/s. En négligeant la résistance de l’air, calculez la hauteur maximale atteinte par l’objet et le temps qu’il mettra à retomber au sol. Accélération de la gravité : \\( g = 9,8 \\, m/s^2 \\).', '2024-10-16 16:11:34'),
	(4, 12, 2, 'Analyse de texte littéraire', 'Analysez le passage suivant extrait de *Madame Bovary* de Gustave Flaubert. Discutez de l\'attitude de l\'auteur face à la société de son époque et des techniques littéraires employées pour critiquer les mœurs bourgeoises.', '2024-10-16 16:12:19'),
	(5, 12, 2, 'Calcul de concentration molaire', 'Un chimiste dissout 58,5 g de NaCl dans 2 litres d’eau. Calculez la concentration molaire de la solution ainsi obtenue. Donnez votre réponse en mol/L. Masse molaire de NaCl : 58,5 g/mol.', '2024-10-16 16:13:00'),
	(6, 12, 3, 'Tri par insertion', 'Implémentez l’algorithme de tri par insertion en langage Python. Donnez ensuite un exemple d’utilisation de cet algorithme pour trier la liste suivante : [29, 10, 14, 37, 14].', '2024-10-16 16:19:38');

-- Listage des données de la table mytutorspace2.lecon : ~12 rows (environ)
INSERT INTO `lecon` (`id`, `matiere_id`, `user_id`, `titre`, `description`) VALUES
	(1, 2, 21, 'Emission et perception d\'un son', ''),
	(2, 1, 23, 'Les suites', ''),
	(3, 1, 12, 'Propriétés des angles et triangles', ''),
	(4, 1, 21, 'Calcul des probabilités', ''),
	(5, 2, 23, 'Réactions acido-basiques', ''),
	(6, 5, 21, 'Analyse du genre narratif', 'az'),
	(7, 5, 23, 'L’argumentation et les figures de style', 'az'),
	(8, 6, 21, 'La révolution française et ses conséquences', 'az'),
	(9, 6, 23, 'La Seconde Guerre mondiale', 'az'),
	(11, 4, 23, 'Officia sunt delenit', 'az'),
	(12, 8, 15, 'Fugit aliquam nostr', 'Dignissimos necessit'),
	(13, 2, 17, 'Hic sequi quo dolore', 'Maiores deleniti ull');

-- Listage des données de la table mytutorspace2.lien : ~3 rows (environ)
INSERT INTO `lien` (`id`, `valeur`) VALUES
	(1, 'https://www.youtube.com/watch?v=3wuPvNDLNjY'),
	(2, 'https://www.youtube.com/@dave-hollingworth'),
	(3, 'https://symfony.com/doc/current/best_practices.html');

-- Listage des données de la table mytutorspace2.matiere : ~6 rows (environ)
INSERT INTO `matiere` (`id`, `nom`, `description`) VALUES
	(1, 'Mathématiques', 'Étude des nombres, des structures et des relations entre les quantités.'),
	(2, 'Physique', 'Exploration des lois régissant la matière, l\'énergie et l\'univers.'),
	(4, 'Informatique', 'Conception et manipulation des algorithmes, logiciels et systèmes.'),
	(5, 'Français', 'Apprentissage de la langue, de la littérature et de la culture française.'),
	(6, 'Histoire', 'Compréhension des événements passés pour mieux appréhender le présent.'),
	(8, 'Philosophie', 'La philosophie est la discipline qui cherche à comprendre les principes fondamentaux de l\'existence, de la connaissance, de la morale et de la réalité à travers la réflexion et le raisonnement. Elle encourage l\'exploration des questions profondes sur la vie, la vérité et la justice, tout en développant la pensée critique.');

-- Listage des données de la table mytutorspace2.messenger_messages : ~0 rows (environ)

-- Listage des données de la table mytutorspace2.niveau : ~7 rows (environ)
INSERT INTO `niveau` (`id`, `titre`) VALUES
	(1, 'Première'),
	(2, 'Seconde'),
	(3, 'Terminale'),
	(4, 'Sixième'),
	(7, 'Quatrième'),
	(8, 'Troisième'),
	(9, 'Cinquième');

-- Listage des données de la table mytutorspace2.niveau_lecon : ~6 rows (environ)
INSERT INTO `niveau_lecon` (`niveau_id`, `lecon_id`) VALUES
	(2, 12),
	(2, 13),
	(3, 12),
	(3, 13),
	(8, 12),
	(9, 12);

-- Listage des données de la table mytutorspace2.ressource : ~2 rows (environ)
INSERT INTO `ressource` (`id`, `exercice_id`, `titre`, `contenu`) VALUES
	(1, 4, 'Introduction à la logique philosophique  ', 'Ce document offre une introduction aux principes de base de la logique philosophique, un domaine essentiel pour structurer les arguments rationnels.'),
	(2, 6, 'Introduction à la programmation orientée objet (POO)', 'Un tutoriel sur les concepts clés de la programmation orientée objet, y compris les classes, les objets, l\'héritage et l\'encapsulation, avec des exemples en PHP et en Java. Des exercices pratiques permettent de renforcer l\'apprentissage.');

-- Listage des données de la table mytutorspace2.ressource_lien : ~3 rows (environ)
INSERT INTO `ressource_lien` (`ressource_id`, `lien_id`) VALUES
	(1, 1),
	(2, 2),
	(2, 3);

-- Listage des données de la table mytutorspace2.type : ~3 rows (environ)
INSERT INTO `type` (`id`, `nom`) VALUES
	(1, 'Problème'),
	(2, 'Question ouverte'),
	(3, 'QCM');

-- Listage des données de la table mytutorspace2.user : ~13 rows (environ)
INSERT INTO `user` (`id`, `email`, `nom`, `prenom`, `is_verified`, `roles`, `password`) VALUES
	(12, 'boreki@mailinator.com', 'Elit minim eu nisi', 'Occaecat aut ipsa u', 0, '["ROLE_USER"]', '$2y$13$oN31yrC5RxdHdaOSy9anN.a1DMqaAPKpq2dnWs1WmFec.AzkdL2EC'),
	(13, 'bubodosu@mailinator.com', 'eleve2', 'Dolore enim quisquam', 0, '["ROLE_ELEVE"]', '$2y$13$dI3E7lJx2ThmDLR4OOYqgug2bEM2wA8a6xbdEKaKqHu6rd0ms.OZG'),
	(15, 'nujuc@mailinator.com', 'Libero esse in ut o', 'Quo quis mollitia ei', 0, '["ROLE_PARENT"]', '$2y$13$mDdX/KtiOhPzXTdTvyGXd.MnNxIX4ApSSSMvHrcSWAP1vnDAVjSKG'),
	(16, 'asmi@mailinator.com', 'Velit sit et delen', 'Minima ut aliquid ad', 0, '["ROLE_USER"]', '$2y$13$FDqceW80TQQDBWVWQTGiZe9BR79maMxeOaoFzO84BM7cfYccyN4HG'),
	(17, 'vulyl@mailinator.com', 'Amet in voluptatibu', 'Autem consequatur N', 0, '["ROLE_USER"]', '$2y$13$Ii/YLXynBLA4iuDh0Q5QKuiO3h4Wak8Pb5T3XEWUcLU0B0p6baS6O'),
	(18, 'casorucaja@mailinator.com', 'Voluptas dolore elig', 'Ut doloremque volupt', 0, '["ROLE_USER"]', '$2y$13$GTlZYDnRlI5Y9nGNf6dTxeDyLmGqU3Mf8beRjV2Ua1LgSg4pgA5hi'),
	(19, 'mynip@mailinator.com', 'Qui non ipsam odit n', 'Eiusmod reprehenderi', 0, '["ROLE_ADMIN"]', '$2y$13$yxhaVICHFRtBZD5RXIPY1.b6WFNCvOm5n14y2IE9goL3Te0l0dG86'),
	(20, 'puxy@mailinator.com', 'eleve1', 'Porro aut fugiat se', 0, '["ROLE_ELEVE"]', '$2y$13$RZOgfjxThSpFiCYPcH6GkOwOMz6knYt5rfil8o5V8m5ohuzelY9xe'),
	(21, 'zacorer@mailinator.com', 'tuteur1', 'Asperiores quo ut do', 0, '["ROLE_TUTEUR"]', '$2y$13$sqmRr9NZmr9t1sqj9YKSg.Q0jFZlomhZMkYKa1POgrilwmtYQAlNG'),
	(22, 'movujawasa@mailinator.com', 'Quia illo error sequ', 'Eaque qui dolor offi', 0, '["ROLE_PARENT"]', '$2y$13$EzlX22zrJE5gc4tlRzBzU.JXkSEGvvMkctG0qf1ijtiRDoK90qUE.'),
	(23, 'rykybu@mailinator.com', 'tuteur2', 'Architecto quia quis', 0, '["ROLE_TUTEUR"]', '$2y$13$Ub7F.E7xJkTI4pspjIx36uajm3JlDqlW/zwu61oRB9w1d1ZL4vxY.'),
	(24, 'yami@mailinator.com', 'Vel excepteur velit', 'Optio eos perspici', 0, '["ROLE_ELEVE"]', '$2y$13$oSRquj/ATE5o4gRI5wYPWuM7Wz/0.FJrBZOj0Yj80Vrtez/aV0r1u'),
	(25, 'pefev@mailinator.com', 'Voluptatum similique', 'Non a ipsum dolorib', 0, '["ROLE_PARENT"]', '$2y$13$P.ihuRkfZOYqaqIo9olmO.jljjWfGcqZgMWtbfWvjChIXyz2RUOsO');

-- Listage des données de la table mytutorspace2.user_lecon : ~2 rows (environ)
INSERT INTO `user_lecon` (`user_id`, `lecon_id`) VALUES
	(21, 12),
	(23, 12);

-- Listage des données de la table mytutorspace2.user_matiere : ~2 rows (environ)
INSERT INTO `user_matiere` (`user_id`, `matiere_id`) VALUES
	(21, 5),
	(23, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
