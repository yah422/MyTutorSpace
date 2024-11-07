<?php

namespace App\Repository;

use App\Entity\Lecon;
use App\Entity\Niveau; // Importation de l'entité Niveau
use App\Entity\Matiere; // Importation de l'entité Matiere
use Doctrine\Persistence\ManagerRegistry; // Importation de ManagerRegistry pour la gestion des entités
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe de base pour les repositories d'entités

/**
 * @extends ServiceEntityRepository<Lecon>
 */
class LeconRepository extends ServiceEntityRepository // Définition de la classe LeconRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lecon::class); // Appel du constructeur parent avec le registre et l'entité Lecon
    }
    
    /**
     * Find exercises by lesson.
     * Cette méthode permet de trouver les exercices associés à une leçon via la matière.
     * @param Lecon $lecon La leçon dont on cherche les exercices
     * @return array Les exercices trouvés
     */
    public function findExercicesByLecon(Lecon $lecon)
    {
        // Récupération du gestionnaire d'entités
        $em = $this->getEntityManager();
        // Récupération du repository des exercices
        $exerciceRepository = $em->getRepository('App\Entity\Exercice');
        // Construction d'une requête pour récupérer les exercices
        $queryBuilder = $exerciceRepository->createQueryBuilder('e');
        // Tri des résultats par titre d'exercice
        $queryBuilder->orderBy('e.titre', 'ASC');
        // Obtention de la requête
        $query = $queryBuilder->getQuery();
        // Exécution de la requête et obtention des résultats
        return $query->getResult();
    }

    /**
     * Find resources by lesson.
     * Cette méthode permet de trouver les ressources associées aux exercices d'une leçon.
     * @param Lecon $lecon La leçon dont on cherche les ressources
     * @return array Les ressources trouvées
     */
    public function findRessourcesByLecon(Lecon $lecon)
    {
        // Récupération du gestionnaire d'entités
        $em = $this->getEntityManager();
        // Récupération du repository des ressources
        $ressourceRepository = $em->getRepository('App\Entity\Ressource');
        // Construction d'une requête pour récupérer les ressources
        $queryBuilder = $ressourceRepository->createQueryBuilder('r');
        // Ajout d'une condition pour filtrer par exercice associé à la leçon
        $queryBuilder->where('r.exercice = :exercice')
            ->setParameter('exercice', $lecon->getExercice());
        // Tri des résultats par titre de ressource
        $queryBuilder->orderBy('r.titre', 'ASC');
        // Obtention de la requête
        $query = $queryBuilder->getQuery();
        // Exécution de la requête et obtention des résultats
        return $query->getResult();
    }

    /**
     * Find lessons by level.
     * Cette méthode permet de trouver les leçons associées à un niveau donné.
     */
    public function findLeconsByNiveau(Niveau $niveau)
    {
        return $this->createQueryBuilder('l') // Création d'un query builder pour l'entité Lecon
            ->innerJoin('l.niveaux', 'n') // Jointure avec l'entité Niveau
            ->where('n.id = :niveauId') // Condition pour filtrer par identifiant de niveau
            ->setParameter('niveauId', $niveau->getId()) // Définition du paramètre pour l'identifiant de niveau
            ->getQuery() // Obtention de la requête
            ->getResult(); // Exécution de la requête et obtention des résultats
    }

    /**
     * Find lessons by subject and level filters.
     * Cette méthode permet de trouver les leçons en fonction de la matière et du niveau, si fournis.
     */
    public function findLeconsByFilters(?Matiere $matiere = null, ?Niveau $niveau = null)
    {
        $qb = $this->createQueryBuilder('l') // Création d'un query builder pour l'entité Lecon
            ->leftJoin('l.matiere', 'm') // Jointure optionnelle avec l'entité Matiere
            ->leftJoin('l.niveaux', 'n') // Jointure optionnelle avec l'entité Niveau
            ->orderBy('l.titre', 'ASC'); // Tri des résultats par titre de leçon

        if ($matiere) { // Vérification si une matière est fournie
            $qb->andWhere('l.matiere = :matiere') // Condition pour filtrer par matière
            ->setParameter('matiere', $matiere); // Définition du paramètre pour la matière
        }

        if ($niveau) { // Vérification si un niveau est fourni
            $qb->andWhere(':niveau MEMBER OF l.niveaux') // Condition pour filtrer par niveau
            ->setParameter('niveau', $niveau); // Définition du paramètre pour le niveau
        }

        return $qb->getQuery()->getResult(); // Exécution de la requête et obtention des résultats
    }

    /**
     * Find lessons by both level and subject.
     * Cette méthode permet de trouver les leçons en fonction d'un niveau et d'une matière spécifiques.
     */
    public function findLeconsByNiveauAndMatiere(Niveau $niveau, Matiere $matiere)
    {
        return $this->createQueryBuilder('l') // Création d'un query builder pour l'entité Lecon
            ->innerJoin('l.niveaux', 'n') // Jointure avec l'entité Niveau
            ->innerJoin('l.matiere', 'm') // Jointure avec l'entité Matiere
            ->where('n.id = :niveauId') // Condition pour filtrer par identifiant de niveau
            ->andWhere('m.id = :matiereId') // Condition pour filtrer par identifiant de matière
            ->setParameter('niveauId', $niveau->getId()) // Définition du paramètre pour l'identifiant de niveau
            ->setParameter('matiereId', $matiere->getId()) // Définition du paramètre pour l'identifiant de matière
            ->orderBy('l.titre', 'ASC') // Tri des résultats par titre de leçon
            ->getQuery() // Obtention de la requête
            ->getResult(); // Exécution de la requête et obtention des résultats
    }
}
