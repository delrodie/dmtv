<?php

namespace App\Repository;

use App\Entity\Rubrique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rubrique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rubrique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rubrique[]    findAll()
 * @method Rubrique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RubriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rubrique::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function liste()
    {
        return $this->createQueryBuilder('r')->orderBy('r.libelle', 'ASC');
    }

    /**
     * Liste des rubriques selon les articles
     *
     * @param $article
     * @return int|mixed|string
     */
    public function findByArticle($article)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.articles', 'a')
            ->where('a.id = :id')
            ->setParameter('id',$article)
            ->getQuery()->getResult()
            ;
    }

    /**
     * La recherche de la rubrique
     *
     * @param $libelle
     * @return int|mixed|string
     */
    public function findByLibelle($libelle)
    {
        return $this->createQueryBuilder('r')
            ->where('r.libelle LIKE :libelle')
            ->setParameter('libelle', '%'.$libelle.'%')
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Rubrique[] Returns an array of Rubrique objects
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
    public function findOneBySomeField($value): ?Rubrique
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
