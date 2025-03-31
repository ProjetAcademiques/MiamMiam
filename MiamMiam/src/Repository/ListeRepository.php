<?php

namespace App\Repository;

use App\Entity\Liste;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Liste>
 */
class ListeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Liste::class);
    }

    /**
     * Récupère toutes les listes d'un utilisateur
     * 
     * @param User|int $user L'utilisateur ou son ID
     * @return array Les listes de l'utilisateur
     */
    public function findListesByUser($user): array
    {
        $userId = $user instanceof User ? $user->getId() : $user;
        
        return $this->createQueryBuilder('l')
            ->join('l.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findArticlesByListeId(int $listeId): array
    {
        return $this->createQueryBuilder('l')
            ->select('a.id, a.nom, a.prix, la.quantite, la.date_ajout, la.acheter as achete, la.id as liste_article_id')
            ->join('l.liste_article', 'la')
            ->join('la.article', 'a')
            ->where('l.id = :listeId')
            ->setParameter('listeId', $listeId)
            ->getQuery()
            ->getResult();
    }

    public function countArticlesInListe(int $listeId): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(la.id)')
            ->join('l.liste_article', 'la')
            ->where('l.id = :listeId')
            ->setParameter('listeId', $listeId)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Liste[] Returns an array of Liste objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Liste
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
