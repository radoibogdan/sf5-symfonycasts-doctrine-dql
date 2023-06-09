<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function findAllOrdered()
    {
        # $dql = 'SELECT cat FROM AppBundle\Entity\Category cat ORDER BY cat.name DESC';
        # $query = $this->getEntityManager()->createQuery($dql);

        # Récupère le sql
        # var_dump($query->getSQL());

        $qb = $this->createQueryBuilder('cat')
            ->addOrderBy('cat.name', 'DESC');
        $this->addFortuneCookieJoinAndSelect($qb);
        $query = $qb->getQuery();

        # Récupère le dql créé par le query builder
        # var_dump($query->getDQL());

        return $query->execute();
    }

    public function search($term)
    {
        # DO NOT USE orWhere
        $qb = $this->createQueryBuilder('cat')
            ->andWhere('cat.name LIKE :searchTerm 
                OR cat.iconKey LIKE :searchTerm
                OR fc.fortune LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$term.'%');
        $this->addFortuneCookieJoinAndSelect($qb);
        return $qb
            ->getQuery()
            ->execute();
    }

    public function findWithFortunesJoin(int $id)
    {
        $qb = $this->createQueryBuilder('cat')
            ->andWhere('cat.id = :id')
            ->setParameter('id', $id);
        $this->addFortuneCookieJoinAndSelect($qb);
        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Réutiliser le join sur la table fortune_cookies
     *
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    private function addFortuneCookieJoinAndSelect(QueryBuilder $qb)
    {
        $qb
            ->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc');
    }
}
