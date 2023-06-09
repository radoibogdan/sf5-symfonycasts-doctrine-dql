<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * FortuneCookieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FortuneCookieRepository extends EntityRepository
{
    public function countNumberPrintedForCategory(Category $category)
    {
        return $this->createQueryBuilder('fc')
            ->andWhere('fc.category = :category')
            ->setParameter('category', $category)
            ->select("SUM(fc.numberPrinted) as fortunesPrinted")
            ->getQuery()
            ->getSingleScalarResult(); # Récupère que la valeur de la somme
    }

    public function getDetailedNumberPrintedForCategory(Category $category)
    {

        return $this->createQueryBuilder('fc')
            ->innerJoin('fc.category', 'cat')
            ->andWhere('fc.category = :category')
            ->setParameter('category', $category)
            ->select("SUM(fc.numberPrinted) as fortunesPrinted, AVG(fc.numberPrinted) as fortunesAverage, cat.name")
            ->getQuery()
            ->getOneOrNullResult(); # Récupère un array associatif "clé => valeur"
    }

    public function getDetailsRawSql($category)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT SUM(fc.numberPrinted) as fortunesPrinted, AVG(fc.numberPrinted) as fortunesAverage, cat.name 
            FROM fortune_cookie fc
            INNER JOIN category cat ON fc.category_id = cat.id
            WHERE fc.category_id = :category
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('category' => $category->getId()));
        return $stmt->fetch();
    }
}
