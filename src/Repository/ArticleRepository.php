<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param $id
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getArticle($id)
    {
        return $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ;
    }

    /**
     * Liste des video concernées par les carousel
     *
     * @return int|mixed|string
     */
    public function findCarousel()
    {
        return $this->createQueryBuilder('a')
            ->where('a.IsSlide = 1')
            ->orderBy('a.publieLe', 'DESC')
            ->getQuery()->getResult()
            ;
    }

    /**
     * Liste des articles selon la rubrique
     *
     * @param $rubrique
     * @return int|mixed|string
     */
    public function findByRubriques($rubrique, $article)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.rubrique', 'r')
            ->where('r.id = :id')
            ->andWhere('a.id <> :article')
            ->orderBy('a.publieLe', 'DESC')
            ->setParameters([
                'id' => $rubrique,
                'article' => $article
            ])
            ->getQuery()->getResult()
            ;
    }

    /**
     * Liste des articles selon la rubrique
     *
     * @param $rubrique
     * @return int|mixed|string
     */
    public function findListByRubrique($rubrique)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.rubrique', 'r')
            ->where('r.libelle LIKE :rubrique')
            ->orderBy('a.publieLe', 'DESC')
            ->setParameter('rubrique', '%'.$rubrique."%")
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
