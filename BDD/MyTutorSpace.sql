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

-- Listage des données de la table mytutorspace2.category : ~4 rows (environ)
INSERT INTO `category` (`id`, `name`, `description`) VALUES
	(1, 'Comment accompagné son enfant ?', 'lorem ipsum ahloiahfnaqjqo,fe qefhoiaenf aehfoiaehf '),
	(2, 'Général', 'akugqefukgqligqehbvfqlgbveelihfbqevqilvb'),
	(3, 'Discussions', 'rzoeihfbqufbzqlvlqjbvfqlbfqllkq'),
	(4, 'Actualités', 'hzefouhbfeqgfiuevfsrfukkfbsrkukgshlfiljrfsuflsj');

-- Listage des données de la table mytutorspace2.contact : ~8 rows (environ)
INSERT INTO `contact` (`id`, `user_id`, `nom`, `email`, `message`) VALUES
	(1, NULL, 'Dolore accusantium c', 'bomymasusi@mailinator.com', 'Fuga Pariatur Et i'),
	(2, NULL, 'Quo est quam lorem', 'tewikydu@mailinator.com', 'Ipsum in dolor error'),
	(3, NULL, 'Voluptatem dignissim', 'xipon@mailinator.com', 'Voluptatem soluta si'),
	(4, NULL, 'Exercitation est sit', 'reguzu@mailinator.com', 'Voluptas qui archite'),
	(5, NULL, 'Molestiae esse dolor', 'saguhyjyte@mailinator.com', 'Cupidatat dolores ac'),
	(6, NULL, 'Beatae eum ipsa omn', 'jofuw@mailinator.com', 'Et sit ex commodi qu'),
	(7, NULL, 'Est mollit dolores a', 'jowomocafa@mailinator.com', 'Necessitatibus at ut'),
	(8, 29, 'Nisi adipisci sit se', 'sogyn@mailinator.com', 'Est quasi illum no');

-- Listage des données de la table mytutorspace2.doctrine_migration_versions : ~0 rows (environ)

-- Listage des données de la table mytutorspace2.exercice : ~6 rows (environ)
INSERT INTO `exercice` (`id`, `type_id`, `lecon_id`, `titre`, `description`, `date_creation`) VALUES
	(1, 1, 12, 'équation polynôme', 'Cet exercice vise à développer des compétences en résolution d\'équations polynomiales de degré 3, en utilisant des techniques telles que le théorème de la racine évidente, la division polynomiale, et la résolution d\'équations quadratiques.', '2024-10-16 15:55:24'),
	(2, 1, 12, 'Résolution d\'équations différentielle', 'Résoudre l’équation différentielle suivante :', '2024-10-16 16:10:27'),
	(3, 1, 12, 'Mouvement rectiligne uniformèment accélérer', 'Un objet est lancé verticalement avec une vitesse initiale de 15 m/s. En négligeant la résistance de l’air, calculez la hauteur maximale atteinte par l’objet et le temps qu’il mettra à retomber au sol. Accélération de la gravité : \\( g = 9,8 \\, m/s^2 \\).', '2024-10-16 16:11:34'),
	(4, 2, 12, 'Analyse de texte littéraire', 'Analysez le passage suivant extrait de *Madame Bovary* de Gustave Flaubert. Discutez de l\'attitude de l\'auteur face à la société de son époque et des techniques littéraires employées pour critiquer les mœurs bourgeoises.', '2024-10-16 16:12:19'),
	(5, 2, 12, 'Calcul de concentration molaire', 'Un chimiste dissout 58,5 g de NaCl dans 2 litres d’eau. Calculez la concentration molaire de la solution ainsi obtenue. Donnez votre réponse en mol/L. Masse molaire de NaCl : 58,5 g/mol.', '2024-10-16 16:13:00'),
	(6, 3, 12, 'Tri par insertion', 'Implémentez l’algorithme de tri par insertion en langage Python. Donnez ensuite un exemple d’utilisation de cet algorithme pour trier la liste suivante : [29, 10, 14, 37, 14].', '2024-10-16 16:19:38');

-- Listage des données de la table mytutorspace2.lecon : ~15 rows (environ)
INSERT INTO `lecon` (`id`, `matiere_id`, `user_id`, `titre`, `description`, `pdf_path`, `date_creation`) VALUES
	(1, 2, 24, 'Emission et perception d\'un son', '', NULL, '2025-01-21 22:02:20'),
	(2, 1, 24, 'Les suites', '', NULL, '2025-01-21 22:02:20'),
	(3, 1, 9, 'Propriétés des angles et triangles', '', NULL, '2025-01-21 22:02:20'),
	(4, 1, 9, 'Calcul des probabilités', '', NULL, '2025-01-21 22:02:20'),
	(5, 2, 11, 'Réactions acido-basiques', '', NULL, '2025-01-21 22:02:20'),
	(6, 5, 11, 'Analyse du genre narratif', 'az', '672bea922732c.pdf', '2025-01-21 22:02:20'),
	(7, 5, 1, 'L’argumentation et les figures de style', 'az', NULL, '2025-01-21 22:02:20'),
	(8, 6, 1, 'La révolution française et ses conséquences', 'az', NULL, '2025-01-21 22:02:20'),
	(9, 6, 24, 'La Seconde Guerre mondiale', 'az', NULL, '2025-01-21 22:02:20'),
	(11, 4, 9, 'Officia sunt delenit', 'az', NULL, '2025-01-21 22:02:20'),
	(12, 8, 11, 'Fugit aliquam nostr', 'Dignissimos necessit', NULL, '2025-01-21 22:02:20'),
	(13, 2, 1, 'Hic sequi quo dolore', 'Maiores deleniti ull', NULL, '2025-01-21 22:02:20'),
	(14, 1, 24, 'Les suites', 'ZIEFHPBQGVQOUFHIPnlc<hbfuqeogpqhigisrhlgnlsurbjvlxehgvsmqwhnvdnsbrglflvhxbligeshbr vlsdwhbvmkqhgvbmkq', '6769ec6f8970f.pdf', '2025-01-21 22:02:20'),
	(15, 6, 24, 'Verdun', 'hfejqafhqmhgqhgqihgvipqrhiphdqvhfqiphv&qhdpgiqhdpiv', '6769ef7498c44.pdf', '2025-01-21 22:02:20'),
	(16, 1, 1, 'la symétrie axiale', 'Nisi deserunt qui lizaefhuizgfzyuvzubvuyazbuyifbyzbvouaefbuvbaoybvruyevyueyuvbyuervybqeryvuyeqbvyubqyurvbeviqerivf', '676dd922b8202.pdf', '2025-01-21 22:02:20');

-- Listage des données de la table mytutorspace2.lecon_user : ~9 rows (environ)
INSERT INTO `lecon_user` (`lecon_id`, `user_id`) VALUES
	(14, 2),
	(15, 2),
	(15, 3),
	(15, 4),
	(15, 5),
	(15, 6),
	(16, 2),
	(16, 3),
	(16, 4);

-- Listage des données de la table mytutorspace2.lien : ~3 rows (environ)
INSERT INTO `lien` (`id`, `valeur`) VALUES
	(1, 'https://www.youtube.com/watch?v=3wuPvNDLNjY'),
	(2, 'https://www.youtube.com/@dave-hollingworth'),
	(3, 'https://symfony.com/doc/current/best_practices.html');

-- Listage des données de la table mytutorspace2.matiere : ~6 rows (environ)
INSERT INTO `matiere` (`id`, `nom`, `description`, `hourly_rate`) VALUES
	(1, 'Mathématiques', 'Étude des nombres, des structures et des relations entre les quantités.', 0),
	(2, 'Physique', 'Exploration des lois régissant la matière, l\'énergie et l\'univers.', 0),
	(4, 'Informatique', 'Conception et manipulation des algorithmes, logiciels et systèmes.', 0),
	(5, 'Français', 'Apprentissage de la langue, de la littérature et de la culture française.', 0),
	(6, 'Histoire', 'Compréhension des événements passés pour mieux appréhender le présent.', 0),
	(8, 'Philosophie', 'La philosophie est la discipline qui cherche à comprendre les principes fondamentaux de l\'existence, de la connaissance, de la morale et de la réalité à travers la réflexion et le raisonnement. Elle encourage l\'exploration des questions profondes sur la vie, la vérité et la justice, tout en développant la pensée critique.', 0);

-- Listage des données de la table mytutorspace2.message : ~5 rows (environ)
INSERT INTO `message` (`id`, `message_date`, `sender_id`, `receiver_id`, `message_content`) VALUES
	(30, '2025-01-18 16:56:58', 24, 6, 'fgx vhbigbui kvblyuvuyhj'),
	(31, '2025-01-18 18:29:48', 24, 14, 'akfcqsc'),
	(32, '2025-01-18 18:31:42', 24, 6, 'qjbvoLD'),
	(33, '2025-01-19 18:32:34', 24, 1, 'srrbsf'),
	(34, '2025-01-20 00:35:34', 31, 31, 'xckvkckhvkhvkhvkhvkvh');

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

-- Listage des données de la table mytutorspace2.niveau_lecon : ~15 rows (environ)
INSERT INTO `niveau_lecon` (`niveau_id`, `lecon_id`) VALUES
	(1, 1),
	(2, 2),
	(2, 6),
	(2, 12),
	(2, 13),
	(2, 16),
	(3, 6),
	(3, 12),
	(3, 13),
	(3, 14),
	(4, 6),
	(7, 7),
	(7, 15),
	(8, 12),
	(9, 12);

-- Listage des données de la table mytutorspace2.niveau_users : ~0 rows (environ)
INSERT INTO `niveau_users` (`niveau_id`, `user_id`) VALUES
	(9, 8);

-- Listage des données de la table mytutorspace2.post : ~2 rows (environ)
INSERT INTO `post` (`id`, `author_id`, `topic_id`, `content`, `created_at`) VALUES
	(1, 24, 2, 'bonjour tout le monde', '2025-01-21 16:53:08'),
	(2, 24, 1, 's,dw cvkqshvkqdvhqd', '2025-01-21 20:30:35');

-- Listage des données de la table mytutorspace2.reservation : ~0 rows (environ)

-- Listage des données de la table mytutorspace2.ressource : ~2 rows (environ)
INSERT INTO `ressource` (`id`, `exercice_id`, `titre`, `contenu`) VALUES
	(1, 4, 'Introduction à la logique philosophique  ', 'Ce document offre une introduction aux principes de base de la logique philosophique, un domaine essentiel pour structurer les arguments rationnels.'),
	(2, 6, 'Introduction à la programmation orientée objet (POO)', 'Un tutoriel sur les concepts clés de la programmation orientée objet, y compris les classes, les objets, l\'héritage et l\'encapsulation, avec des exemples en PHP et en Java. Des exercices pratiques permettent de renforcer l\'apprentissage.');

-- Listage des données de la table mytutorspace2.ressource_lien : ~3 rows (environ)
INSERT INTO `ressource_lien` (`ressource_id`, `lien_id`) VALUES
	(1, 1),
	(2, 2),
	(2, 3);

-- Listage des données de la table mytutorspace2.sauvegarde_profil : ~4 rows (environ)
INSERT INTO `sauvegarde_profil` (`id`, `date_sauvegarde`, `contenu`, `user_id`, `tuteur_id`) VALUES
	(5, '2025-01-15', '[]', 24, 9),
	(7, '2025-01-20', '[]', 31, 1),
	(8, '2025-01-20', '[]', 27, 1),
	(15, '2025-01-27', '[]', 24, 1);

-- Listage des données de la table mytutorspace2.suivi : ~0 rows (environ)

-- Listage des données de la table mytutorspace2.topic : ~2 rows (environ)
INSERT INTO `topic` (`id`, `category_id`, `author_id`, `title`, `content`, `created_at`, `locked`) VALUES
	(1, 1, 24, 'Quis maiores quod qu', 'Ipsa Nam quasi et vtudikvkkjbhv', '2025-01-02 22:39:17', 0),
	(2, 2, 24, 'Sapiente repudiandae', 'Anim quam volupta', '2025-01-21 16:52:32', 0);

-- Listage des données de la table mytutorspace2.tutoring_booking : ~19 rows (environ)
INSERT INTO `tutoring_booking` (`id`, `matiere_id`, `student_name`, `student_email`, `message`, `preferred_date`, `preferred_time_slot`, `status`, `created_at`, `tuteur_id`, `availability_id`) VALUES
	(3, 8, 'Jelani Bridges', 'cezav@mailinator.com', 'Qui ut incididunt ne', '2025-01-15 00:00:00', 'afternoon', 'pending', '2024-12-14 00:10:28', 24, NULL),
	(4, 1, 'Plato Parks', 'bygu@mailinator.com', 'Odit beatae voluptat', '2025-02-22 00:00:00', 'afternoon', 'pending', '2024-12-14 00:11:24', 24, NULL),
	(5, 4, 'Tanishuy', 'pydywi@mailinator.com', 'Et aut libero quia i', '2025-09-22 00:00:00', 'evening', 'pending', '2024-12-14 19:12:18', 24, NULL),
	(6, 8, 'Jaden Jacobs', 'dicejymo@mailinator.com', 'Quidem iusto explica', '2025-11-25 00:00:00', 'afternoon', 'pending', '2024-12-16 22:26:19', 24, NULL),
	(7, 4, 'Britanni Mercer', 'rypur@mailinator.com', 'Dolor eius rerum mol', '2025-02-12 00:00:00', 'morning', 'pending', '2024-12-17 23:08:46', 24, NULL),
	(8, 6, 'Amber Osborn', 'javaqawasi@mailinator.com', 'Aliqua Molestiae doiuqgfigbk<jcl<b', '2025-06-13 00:00:00', 'evening', 'pending', '2024-12-23 20:08:48', 25, NULL),
	(9, 8, 'Harlan Santiago', 'cucajy@mailinator.com', 'Ut lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi coUt lorem eligendi covvvUt lorem eligendi co', '2025-02-27 00:00:00', 'afternoon', 'pending', '2024-12-26 23:47:52', 24, NULL),
	(10, 4, 'Herman Santiago', 'bopy@mailinator.com', 'Eos dolorum volupta', '2025-06-12 00:00:00', 'morning', 'pending', '2024-12-27 02:32:59', 24, NULL),
	(11, 4, 'Maia Carr', 'vogufevixe@mailinator.com', 'Nesciunt minima lab', '2025-04-12 00:00:00', 'morning', 'pending', '2025-01-13 14:21:53', 24, NULL),
	(12, 6, 'Zia Mcpherson', 'nepirapo@mailinator.com', 'Cupiditate exercitatVHVLJLJVJL.JLLJVLJ', '2025-07-02 00:00:00', 'afternoon', 'pending', '2025-01-18 18:34:24', 11, NULL),
	(13, 2, 'Duncan Gould', 'fypytowe@mailinator.com', 'Maxime quasi beatae', '2025-10-21 00:00:00', 'morning', 'pending', '2025-01-21 16:02:01', 1, NULL),
	(14, 4, 'Tanek Mccall', 'xarot@mailinator.com', 'Aut qui architecto i', '2025-11-24 00:00:00', 'afternoon', 'pending', '2025-01-21 22:40:09', 11, NULL),
	(15, 5, 'Justina Barlow', 'leresilamo@mailinator.com', 'Unde vel ex labore r', '2025-10-05 00:00:00', 'evening', 'pending', '2025-01-23 18:32:02', 25, NULL),
	(16, 5, 'Justina Barlow', 'leresilamo@mailinator.com', 'Unde vel ex labore r', '2025-10-05 00:00:00', 'evening', 'pending', '2025-01-23 18:34:15', 25, NULL),
	(17, 1, 'Mechelle Simmons', 'poqat@mailinator.com', 'Chaima abore dolore magna', '2025-12-24 00:00:00', 'morning', 'pending', '2025-01-23 18:36:46', 9, NULL),
	(18, 4, 'Kyle Vega', 'qipo@mailinator.com', 'Consequatur neque iu', '2025-09-30 00:00:00', 'evening', 'pending', '2025-01-23 19:19:16', 11, NULL),
	(19, 5, 'Marianne', 'marianne@gmail.com', 'wdhngstjfgjn', '2025-01-31 00:00:00', 'evening', 'pending', '2025-01-24 08:51:08', 11, NULL),
	(20, 5, 'Marianne', 'marianne@gmail.com', 'wdhngstjfgjn', '2025-01-31 00:00:00', 'evening', 'pending', '2025-01-24 08:52:42', 11, NULL),
	(21, 4, 'Marianne', 'famubugu@mailinator.com', 'Qui et animi quia q', '2025-09-21 00:00:00', 'evening', 'pending', '2025-01-24 08:54:03', 30, NULL),
	(22, 4, 'Marianne', 'famubugu@mailinator.com', 'Qui et animi quia q', '2025-09-21 00:00:00', 'evening', 'pending', '2025-01-24 08:55:42', 30, NULL),
	(23, 4, 'Marianne', 'famubugu@mailinator.com', 'Qui et animi quia q', '2025-09-21 00:00:00', 'evening', 'pending', '2025-01-24 09:01:15', 30, NULL),
	(24, 4, 'Violet Turner', 'zypepi@mailinator.com', 'Quisquam sapiente pe chaima', '2025-06-29 00:00:00', 'afternoon', 'pending', '2025-01-27 08:00:41', 31, NULL),
	(25, 4, 'Violet Turner', 'zypepi@mailinator.com', 'Quisquam sapiente pe chaima', '2025-06-29 00:00:00', 'afternoon', 'pending', '2025-01-27 08:01:18', 31, NULL);

-- Listage des données de la table mytutorspace2.tutor_availability : ~1 rows (environ)
INSERT INTO `tutor_availability` (`id`, `tutor_id`, `start`, `end`, `is_booked`, `is_recurring`, `recurrence_pattern`) VALUES
	(4, 24, '2025-01-27 11:38:00', '2025-01-27 13:40:00', 0, 1, NULL);

-- Listage des données de la table mytutorspace2.type : ~3 rows (environ)
INSERT INTO `type` (`id`, `nom`) VALUES
	(1, 'Problème'),
	(2, 'Question ouverte'),
	(3, 'QCM');

-- Listage des données de la table mytutorspace2.user : ~26 rows (environ)
INSERT INTO `user` (`id`, `email`, `nom`, `prenom`, `is_verified`, `roles`, `password`, `phone`, `niveau_id`, `updated_at`, `profile_picture`, `about_me`, `plain_password`, `parent_id`, `tuteur_id`, `hourly_rate`, `banned`) VALUES
	(1, 'boreki@mailinator.com', 'Elit minim eu nisi', 'Occaecat aut ipsa u', 0, '["ROLE_TUTEUR"]', '$2y$13$oN31yrC5RxdHdaOSy9anN.a1DMqaAPKpq2dnWs1WmFec.AzkdL2EC', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 20.00, 0),
	(2, 'bubodosu@mailinator.com', 'eleve2', 'Dolore enim quisquam', 0, '["ROLE_ELEVE"]', '$2y$13$dI3E7lJx2ThmDLR4OOYqgug2bEM2wA8a6xbdEKaKqHu6rd0ms.OZG', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 0.00, 0),
	(3, 'nujuc@mailinator.com', 'Libero esse in ut o', 'Quo quis mollitia ei', 0, '["ROLE_PARENT"]', '$2y$13$mDdX/KtiOhPzXTdTvyGXd.MnNxIX4ApSSSMvHrcSWAP1vnDAVjSKG', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(4, 'asmi@mailinator.com', 'Velit sit et delen', 'Minima ut aliquid ad', 0, '["ROLE_USER"]', '$2y$13$FDqceW80TQQDBWVWQTGiZe9BR79maMxeOaoFzO84BM7cfYccyN4HG', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(5, 'vulyl@mailinator.com', 'Amet in voluptatibu', 'Autem consequatur N', 0, '["ROLE_USER"]', '$2y$13$Ii/YLXynBLA4iuDh0Q5QKuiO3h4Wak8Pb5T3XEWUcLU0B0p6baS6O', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(6, 'casorucaja@mailinator.com', 'Voluptas dolore elig', 'Ut doloremque volupt', 0, '["ROLE_USER"]', '$2y$13$GTlZYDnRlI5Y9nGNf6dTxeDyLmGqU3Mf8beRjV2Ua1LgSg4pgA5hi', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(7, 'mynip@mailinator.com', 'Qui non ipsam odit n', 'Eiusmod reprehenderi', 0, '["ROLE_ADMIN"]', '$2y$13$yxhaVICHFRtBZD5RXIPY1.b6WFNCvOm5n14y2IE9goL3Te0l0dG86', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(8, 'puxy@mailinator.com', 'eleve1', 'Porro aut fugiat se', 0, '["ROLE_ELEVE"]', '$2y$13$RZOgfjxThSpFiCYPcH6GkOwOMz6knYt5rfil8o5V8m5ohuzelY9xe', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(9, 'zacorer@mailinator.com', 'tuteur1', 'Asperiores quo ut do', 0, '["ROLE_TUTEUR"]', '$2y$13$sqmRr9NZmr9t1sqj9YKSg.Q0jFZlomhZMkYKa1POgrilwmtYQAlNG', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(10, 'movujawasa@mailinator.com', 'Quia illo error sequ', 'Eaque qui dolor offi', 0, '["ROLE_PARENT"]', '$2y$13$EzlX22zrJE5gc4tlRzBzU.JXkSEGvvMkctG0qf1ijtiRDoK90qUE.', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(11, 'rykybu@mailinator.com', 'tuteur2', 'Architecto quia quis', 0, '["ROLE_TUTEUR"]', '$2y$13$Ub7F.E7xJkTI4pspjIx36uajm3JlDqlW/zwu61oRB9w1d1ZL4vxY.', '', NULL, NULL, NULL, '', NULL, NULL, NULL, 30.00, 0),
	(12, 'yami@mailinator.com', 'Vel excepteur velit', 'Optio eos perspici', 0, '["ROLE_ELEVE"]', '$2y$13$oSRquj/ATE5o4gRI5wYPWuM7Wz/0.FJrBZOj0Yj80Vrtez/aV0r1u', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(13, 'pefev@mailinator.com', 'Voluptatum similique', 'Non a ipsum dolorib', 0, '["ROLE_PARENT"]', '$2y$13$P.ihuRkfZOYqaqIo9olmO.jljjWfGcqZgMWtbfWvjChIXyz2RUOsO', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(14, 'dezaben@mailinator.com', 'Non voluptates atque', 'malak', 0, '["ROLE_ELEVE"]', '$2y$13$jcjp99kNgYjSlEWUTASwCO17ifE8ld1PzVG9ga8QttzqjRsozS0qy', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(21, 'jciv@gmail.com', 'jvkhb jl', 'vkhvblbl', 0, '["ROLE_USER"]', 'hvkh', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 0),
	(24, 'asmo@gmail.com', 'Nulla dicta veniam', 'Magnam dolore volupt', 0, '["ROLE_ADMIN"]', '$2y$13$lOoMzmLVFGQkmDWFdPoDludJFSIlPBdKFtACxGJWZm6mhmYi3yyTi', '', NULL, NULL, 'composer1-6769cbb11fa8d.png', 'zigubcziumgevbcqirz cyehlycb qylrgqi gfzqbcgfgiuzegbhouefkbjcqbdbvjqvbjqvkjqdgfiuegbcegfuqdb asmi', NULL, NULL, NULL, NULL, 0),
	(25, 'zynu@mailinator.com', 'Sit assumenda labori', 'Dolor facere dolorem', 0, '["ROLE_TUTEUR"]', '$2y$13$yKBt4BiBZFNQFLo8fm9KVOZebwypMLYPAxpuhJjqhbe6l3O3RG3Oq', '0625352545', NULL, NULL, 'default-profile.png', '', NULL, NULL, NULL, 50.00, 0),
	(26, 'vehafel@mailinator.com', 'In repudiandae quo u', 'Quae et adipisicing', 0, '["ROLE_PARENT"]', '$2y$13$3FBcV3p7BRx6.KMEvrS3WOnSo1UWGIxAe9BVpf.yAv.cK4MUcE36e', '0623484721', NULL, NULL, 'default-profile.png', '', NULL, NULL, NULL, NULL, 0),
	(27, 'vehafel@mailr.com', 'In repudiandae quo u', 'Quae et adipisicing', 0, '["ROLE_PARENT"]', '$2y$13$.1YH7smVgdt187CaA/bM2OiToHoqVd9DIsGMW6OVJalNDH2EHe2vq', '0623484721', NULL, NULL, 'default-profile.png', '', NULL, NULL, NULL, NULL, 0),
	(28, 'myfyhipy@mailinator.com', 'Exercitationem delec', 'Qui id tempor dolor', 0, '["ROLE_PARENT"]', '$2y$13$.3LH.YHmR2aU4gItC6E3nu4vs3Oenw659UYrSsQNpKGfrUt.kt3Ue', '0650549367', NULL, NULL, 'default-profile.png', '', NULL, NULL, NULL, NULL, 0),
	(29, 'myfyhipy@mator.com', 'Exercitationem delec', 'Qui id tempor dolor', 0, '["ROLE_PARENT"]', '$2y$13$Gea5atGYlHunobveRmg0TuxoMe48gbtExNPe9j9NNxWwiL0KFOTa6', '0650549367', NULL, NULL, 'default-profile.png', '', NULL, NULL, NULL, NULL, 0),
	(30, 'vilycefi@mailinator.com', 'Id corrupti animi', 'Cillum et qui veniam', 1, '["ROLE_TUTEUR"]', '$2y$13$NwQOZXG/d0tjudIbCdIN9OSeYzjdOoY6LWZr0oqF7vav.qU0/Ccom', '0657157881', NULL, NULL, 'default-profile.png', 'Id quaerat duis parsefbjckqkjfcbaqkgvblqjljq', 'Azertyuiop@12', NULL, NULL, 20.00, 0),
	(31, 'wida@mailinator.com', 'Nemo culpa reiciend', 'Voluptatem ut aliqu', 1, '["ROLE_TUTEUR"]', '$2y$13$KFp7bRSEv8henR.ZnCoc5.hlF0OKBMDVLWTWvDu05SOuavd34r6r.', '0625657509', NULL, NULL, 'default-profile.png', 'Ipsa velit velit si', 'Azertyuiop@12', NULL, NULL, 50.00, 0),
	(32, 'wutad@mailinator.com', 'Qui voluptate conseq', 'Voluptas praesentium', 0, '["ROLE_PARENT"]', '$2y$13$iuYu/jBRpewRRGaSM/Gude3qo5S8fT8p3b/cgIjTJDjcZKYhOTMlq', '0652457565', NULL, NULL, 'default-profile.png', 'Molestiae occaecat lasma', 'Azertyuiop@12', NULL, NULL, NULL, 0),
	(33, 'xizam@mailinator.com', 'Et nisi sint nemo si', 'Iusto molestias dolo', 1, '["ROLE_PARENT"]', '$2y$13$tOEPcJvd1.S1wG16lBcznOaZJZGRcNRNfaNhu9p0VjMw0nm95gP8K', '02154459336', NULL, NULL, 'default-profile.png', 'Enim dignissimos mai haj', 'Azertyuiop@12', NULL, NULL, NULL, 0),
	(34, 'zanaq@mailinator.com', 'Voluptatem beatae qu', 'Suscipit unde consec', 1, '["ROLE_ELEVE"]', '$2y$13$FU7nuAFNR.fObh/kDLiY/.HqJSqglXCG8k4THaNx/cbmtIF1P8XWS', '06548475834', NULL, NULL, 'default-profile.png', 'A aspernatur vero op', 'Azertyuiop@12', NULL, NULL, NULL, 0);

-- Listage des données de la table mytutorspace2.user_matiere : ~7 rows (environ)
INSERT INTO `user_matiere` (`user_id`, `matiere_id`) VALUES
	(11, 8),
	(21, 1),
	(24, 1),
	(24, 2),
	(24, 4),
	(24, 5),
	(24, 6);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
