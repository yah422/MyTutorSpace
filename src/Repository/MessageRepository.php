<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Récupère tous les correspondants avec lesquels un utilisateur a échangé des messages.
     *
     * @param User $user L'utilisateur dont on souhaite récupérer les correspondants.
     * @return array Une liste d'utilisateurs distincts qui ont échangé des messages avec cet utilisateur.
     */
    public function findCorrespondents(User $user): array
    {
        $em = $this->getEntityManager(); // Récupère le gestionnaire d'entités
        $qb = $em->createQueryBuilder(); // Crée une instance de QueryBuilder pour construire la requête DQL

        // Construction de la requête
        $qb->select('u AS correspondent, MAX(m.messageDate) AS lastMessageDate') // Correspondant et date du dernier message
            ->from('App\Entity\User', 'u') // Définit la table source (entité User) avec l'alias 'u'
            ->join(
                'App\Entity\Message', // Table des messages
                'm',                  // Alias pour la table des messages
                'WITH',               // Clause de jointure avec condition
                'm.sender = u OR m.receiver = u' // Condition de jointure : utilisateur est expéditeur ou destinataire
            )
            ->where('m.sender = :user OR m.receiver = :user') // Filtre les messages liés à l'utilisateur
            ->andWhere('u.id != :user') // Exclut l'utilisateur lui-même des résultats
            ->setParameter('user', $user) // Définit le paramètre sécurisé pour éviter les injections SQL
            ->groupBy('u') // Groupe les résultats par correspondant
            ->orderBy('lastMessageDate', 'DESC'); // Trie par date de dernier message

        // Exécution de la requête et récupération des résultats
        $query = $qb->getQuery();
        return $query->getResult();
    }


    /**
     * Récupère tous les messages échangés entre deux utilisateurs.
     *
     * @param User $user L'utilisateur qui effectue la requête.
     * @param User $receiver L'utilisateur avec lequel des messages ont été échangés.
     * @return array Une liste de messages échangés entre les deux utilisateurs, triés par date.
     */
    public function findAllMessages(User $user, User $receiver): array
    {
        $em = $this->getEntityManager(); // Récupère le gestionnaire d'entités
        $qb = $em->createQueryBuilder(); // Crée une instance de QueryBuilder pour construire la requête DQL

        // Construction de la requête
        $qb->select('m') // Sélectionne tous les messages
            ->from('App\Entity\Message', 'm') // Définit la table source (entité Message) avec l'alias 'm'
            ->innerJoin('m.sender', 'sender') // Jointure avec l'expéditeur des messages
            ->innerJoin('m.receiver', 'receiver') // Jointure avec le destinataire des messages
            ->where(
                '(m.sender = :user AND m.receiver = :receiver) OR (m.sender = :receiver AND m.receiver = :user)'
                // Condition pour filtrer les messages échangés dans les deux directions
            )
            ->setParameter('user', $user) // Définit le paramètre pour l'utilisateur
            ->setParameter('receiver', $receiver) // Définit le paramètre pour le destinataire
            ->orderBy('m.messageDate', 'ASC'); // Trie les messages par date croissante (les plus récents en bas)

        // Exécution de la requête et récupération des résultats
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
