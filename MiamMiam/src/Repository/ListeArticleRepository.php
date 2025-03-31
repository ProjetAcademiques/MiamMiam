<?php

namespace App\Repository;

use App\Entity\ListeArticle;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeArticle>
 */
class ListeArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeArticle::class);
    }

    /**
     * Récupère tous les articles de toutes les listes d'un utilisateur
     * 
     * @param User|int $user L'utilisateur ou son ID
     * @return array Les articles avec leurs informations associées
     */
    public function findAllArticlesByUser($user): array
    {
        $userId = $user instanceof User ? $user->getId() : $user;
        
        return $this->createQueryBuilder('la')
            ->select('la', 'a', 'm', 't')
            ->join('la.article', 'a')
            ->join('la.liste', 'l')
            ->leftJoin('a.magasin', 'm')
            ->leftJoin('a.type', 't')
            ->join('l.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('la.date_ajout', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return ListeArticle[] Returns an array of ListeArticle objects
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

    //    public function findOneBySomeField($value): ?ListeArticle
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
