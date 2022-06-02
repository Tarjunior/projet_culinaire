<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Search\SearchItem;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    // /**
    //  * @return QueryBuilder
    //  */
    // public function findAllQuery(): QueryBuilder
    // {
    //     return $this->createQueryBuilder('r');
    // }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

     // Find/search recipes by image/name
     public function findRecipesByName(string $query)
     {
         $qb = $this->createQueryBuilder('p');
         $qb
             ->where(
                 $qb->expr()->andX(
                     $qb->expr()->orX(
                        //  $qb->expr()->like('p.image', ':query'),
                         $qb->expr()->like('p.name', ':query'),
                         $qb->expr()->like('p.ingredient', ':query'),
                         $qb->expr()->like('p.difficulty', ':query')

                     )
                     // $qb->expr()->isNotNull('p.created_at')
                 )
             )
            
             ->setParameter('query', '%' . $query . '%')
         ;
         return $qb
             ->getQuery()
             ->getResult();
     }
}
